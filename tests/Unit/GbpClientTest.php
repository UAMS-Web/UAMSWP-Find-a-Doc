<?php

/**
 * Unit tests for the Business Profile API client, against a fake transport.
 */

declare(strict_types=1);

namespace UamswpFindADoc\Tests\Unit;

use RuntimeException;
use UAMSWP_FAD_GBP_Client;
use UamswpFindADoc\Tests\Support\UnitTestCase;

final class GbpClientTest extends UnitTestCase
{
    private const CREDENTIALS = [
        'client_id' => 'the-client-id',
        'client_secret' => 'the-client-secret',
        'refresh_token' => 'the-refresh-token',
    ];

    /** @var array<int, array{method: string, url: string, headers: array<string, string>, body: ?string}> */
    private $calls = [];

    /**
     * A client whose transport answers from a queue of [status, body] pairs, after the token exchange.
     *
     * @param  array<int, array{0: int, 1: array<string, mixed>|string}>  $responses
     */
    private function client(array $responses): UAMSWP_FAD_GBP_Client
    {
        $this->calls = [];

        return new UAMSWP_FAD_GBP_Client(self::CREDENTIALS, function (string $method, string $url, array $headers, ?string $body) use (&$responses): array {
            $this->calls[] = compact('method', 'url', 'headers', 'body');

            if (strpos($url, 'oauth2.googleapis.com') !== false) {
                return [200, json_encode(['access_token' => 'tok-1', 'expires_in' => 3600])];
            }

            $next = array_shift($responses);
            $this->assertNotNull($next, 'The client made a call the test did not expect: '.$method.' '.$url);

            return [$next[0], is_string($next[1]) ? $next[1] : json_encode($next[1])];
        });
    }

    /**
     * @return array<string, string>
     */
    private function query(string $url): array
    {
        parse_str((string) parse_url($url, PHP_URL_QUERY), $query);

        return $query;
    }

    public function test_the_token_is_exchanged_once_and_reused(): void
    {
        $client = $this->client([[200, ['accounts' => []]], [200, ['accounts' => []]]]);

        $client->location_groups();
        $client->location_groups();

        $exchanges = array_filter($this->calls, function (array $call): bool {
            return strpos($call['url'], 'oauth2.googleapis.com') !== false;
        });
        $this->assertCount(1, $exchanges);
    }

    public function test_the_token_exchange_sends_the_refresh_grant_and_calls_carry_the_bearer(): void
    {
        $client = $this->client([[200, ['accounts' => []]]]);
        $client->location_groups();

        parse_str((string) $this->calls[0]['body'], $form);
        $this->assertSame('refresh_token', $form['grant_type']);
        $this->assertSame('the-refresh-token', $form['refresh_token']);
        $this->assertSame('Bearer tok-1', $this->calls[1]['headers']['Authorization']);
    }

    public function test_a_failed_token_exchange_names_the_reason_and_leaks_no_credential(): void
    {
        $client = new UAMSWP_FAD_GBP_Client(self::CREDENTIALS, function (): array {
            return [400, json_encode(['error' => 'invalid_grant'])];
        });

        try {
            $client->access_token();
            $this->fail('Expected the exchange to fail.');
        } catch (RuntimeException $e) {
            $this->assertStringContainsString('invalid_grant', $e->getMessage());
            foreach (self::CREDENTIALS as $secret) {
                $this->assertStringNotContainsString($secret, $e->getMessage());
            }
        }
    }

    public function test_an_api_failure_carries_the_status_and_googles_message(): void
    {
        $client = $this->client([[403, ['error' => ['message' => 'API not enabled']]]]);

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('(403): API not enabled');

        $client->location_groups();
    }

    public function test_only_429_and_503_are_rate_limited(): void
    {
        foreach ([429, 503] as $status) {
            try {
                $this->client([[$status, ['error' => ['message' => 'slow down']]]])->location_groups();
                $this->fail('Expected a failure for '.$status);
            } catch (\UAMSWP_FAD_GBP_Rate_Limited $e) {
                $this->assertStringContainsString('('.$status.'): slow down', $e->getMessage());
            }
        }

        foreach ([400, 403, 500] as $status) {
            try {
                $this->client([[$status, ['error' => ['message' => 'no']]]])->location_groups();
                $this->fail('Expected a failure for '.$status);
            } catch (RuntimeException $e) {
                $this->assertNotInstanceOf(\UAMSWP_FAD_GBP_Rate_Limited::class, $e, (string) $status);
            }
        }
    }

    public function test_location_groups_keeps_only_location_groups_and_follows_pages(): void
    {
        $client = $this->client([
            [200, ['accounts' => [['name' => 'accounts/1', 'accountName' => 'Personal', 'type' => 'PERSONAL'], ['name' => 'accounts/2', 'accountName' => 'Providers', 'type' => 'LOCATION_GROUP']], 'nextPageToken' => 'p2']],
            [200, ['accounts' => [['name' => 'accounts/3', 'accountName' => 'Locations', 'type' => 'LOCATION_GROUP']]]],
        ]);

        $this->assertSame(['accounts/2' => 'Providers', 'accounts/3' => 'Locations'], $client->location_groups());
        $this->assertSame('p2', $this->query($this->calls[2]['url'])['pageToken']);
    }

    public function test_locations_reads_the_compared_fields_and_follows_pages(): void
    {
        $client = $this->client([
            [200, ['locations' => [['name' => 'locations/1', 'storeCode' => 'a_b']], 'nextPageToken' => 'next']],
            [200, ['locations' => [['name' => 'locations/2', 'storeCode' => 'c_d']]]],
        ]);

        $locations = $client->locations('accounts/9');

        $this->assertSame(['a_b', 'c_d'], array_column($locations, 'storeCode'));
        $this->assertStringContainsString('/accounts/9/locations', $this->calls[1]['url']);
        $this->assertStringContainsString('storefrontAddress', $this->query($this->calls[1]['url'])['readMask']);
    }

    public function test_category_ids_maps_display_names_to_resource_names(): void
    {
        $client = $this->client([[200, ['categories' => [['name' => 'categories/gcid:doctor', 'displayName' => 'Doctor'], ['displayName' => 'Nameless']]]]]);

        $this->assertSame(['Doctor' => 'categories/gcid:doctor'], $client->category_ids());
    }

    public function test_patch_sends_only_the_given_fields_with_the_mask(): void
    {
        $client = $this->client([[200, ['name' => 'locations/7']]]);

        $client->patch('locations/7', ['websiteUri' => 'https://x.test/'], 'websiteUri');

        $call = $this->calls[1];
        $this->assertSame('PATCH', $call['method']);
        $this->assertSame('websiteUri', $this->query($call['url'])['updateMask']);
        $this->assertArrayNotHasKey('validateOnly', $this->query($call['url']));
        $this->assertSame(['websiteUri' => 'https://x.test/'], json_decode((string) $call['body'], true));
    }

    public function test_validate_only_is_sent_when_asked(): void
    {
        $client = $this->client([[200, []]]);

        $client->patch('locations/7', ['title' => 'T'], 'title', true);

        $this->assertSame('true', $this->query($this->calls[1]['url'])['validateOnly']);
    }

    public function test_create_posts_to_the_account_with_the_store_code_and_a_stable_request_id(): void
    {
        $client = $this->client([[200, ['name' => 'locations/8']], [200, ['name' => 'locations/8']]]);

        $client->create('accounts/5', ['title' => 'T'], 'jane-doe_clinic');
        $client->create('accounts/5', ['title' => 'T'], 'jane-doe_clinic');

        $first = $this->calls[1];
        $second = $this->calls[2];
        $this->assertSame('POST', $first['method']);
        $this->assertStringContainsString('/accounts/5/locations', $first['url']);
        $this->assertSame('jane-doe_clinic', json_decode((string) $first['body'], true)['storeCode']);
        $this->assertSame($this->query($first['url'])['requestId'], $this->query($second['url'])['requestId']);
    }

    public function test_different_store_codes_get_different_request_ids(): void
    {
        $client = $this->client([[200, []], [200, []]]);

        $client->create('accounts/5', [], 'a_b');
        $client->create('accounts/5', [], 'c_d');

        $this->assertNotSame($this->query($this->calls[1]['url'])['requestId'], $this->query($this->calls[2]['url'])['requestId']);
    }
}
