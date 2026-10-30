<?php

/**
 * Business Profile sync: the Google API client.
 *
 * Every HTTP call goes through an injected transport, so the client runs the same way
 * under WordPress (wp_remote_request), from the command line, and in a unit test with a
 * fake. The client never logs or returns a credential, and an API failure is reported by
 * status and Google's own message only.
 */

declare(strict_types=1);

final class UAMSWP_FAD_GBP_Client
{
    private const TOKEN_URL = 'https://oauth2.googleapis.com/token';

    private const INFO_URL = 'https://mybusinessbusinessinformation.googleapis.com/v1';

    private const ACCOUNTS_URL = 'https://mybusinessaccountmanagement.googleapis.com/v1/accounts';

    /** The fields read back from a listing for the diff. */
    private const READ_MASK = 'name,storeCode,title,storefrontAddress,latlng,phoneNumbers,websiteUri,categories,profile';

    /** @var array{client_id: string, client_secret: string, refresh_token: string} */
    private array $credentials;

    /** @var callable(string, string, array<string, string>, ?string): array{0: int, 1: string} */
    private $transport;

    private string $token = '';

    private int $token_expires = 0;

    /**
     * @param  array{client_id: string, client_secret: string, refresh_token: string}  $credentials
     * @param  callable(string, string, array<string, string>, ?string): array{0: int, 1: string}  $transport  Receives method, URL, headers and body; returns the HTTP status and the raw body.
     */
    public function __construct(array $credentials, callable $transport)
    {
        $this->credentials = $credentials;
        $this->transport = $transport;
    }

    /**
     * An access token, exchanged from the refresh token and reused until shortly before it expires.
     */
    public function access_token(): string
    {
        if ($this->token !== '' && time() < $this->token_expires - 60) {
            return $this->token;
        }

        [$status, $raw] = ($this->transport)('POST', self::TOKEN_URL, ['Content-Type' => 'application/x-www-form-urlencoded'], http_build_query([
            'client_id' => $this->credentials['client_id'],
            'client_secret' => $this->credentials['client_secret'],
            'refresh_token' => $this->credentials['refresh_token'],
            'grant_type' => 'refresh_token',
        ]));

        $data = json_decode($raw, true);

        if ($status !== 200 || ! is_array($data) || empty($data['access_token'])) {
            $reason = is_array($data) && isset($data['error']) ? (string) $data['error'] : 'no token returned';

            throw new RuntimeException('Google token exchange failed ('.$status.'): '.$reason);
        }

        $this->token = (string) $data['access_token'];
        $this->token_expires = time() + (int) ($data['expires_in'] ?? 3600);

        return $this->token;
    }

    /**
     * One authenticated call. Returns the decoded body, or an empty array for an empty body.
     *
     * @param  array<string, scalar>  $query
     * @param  array<string, mixed>|null  $body
     * @return array<string, mixed>
     *
     * @throws RuntimeException On a non-2xx response, carrying the status and Google's message.
     */
    public function request(string $method, string $url, array $query = [], ?array $body = null): array
    {
        if ($query !== []) {
            $url .= (strpos($url, '?') === false ? '?' : '&').http_build_query($query);
        }

        $headers = ['Authorization' => 'Bearer '.$this->access_token()];
        if ($body !== null) {
            $headers['Content-Type'] = 'application/json';
        }

        [$status, $raw] = ($this->transport)($method, $url, $headers, $body === null ? null : json_encode($body));

        $data = $raw === '' ? [] : json_decode($raw, true);
        $data = is_array($data) ? $data : [];

        if ($status < 200 || $status > 299) {
            $error = isset($data['error']) && is_array($data['error']) ? $data['error'] : [];
            $message = isset($error['message']) ? (string) $error['message'] : 'no message';

            throw new RuntimeException('Google API '.$method.' failed ('.$status.'): '.$message);
        }

        return $data;
    }

    /**
     * The location-group accounts the credentials manage, keyed by account name.
     *
     * @return array<string, string> Account name (`accounts/123`) => display name.
     */
    public function location_groups(): array
    {
        $groups = [];
        $page = '';

        do {
            $data = $this->request('GET', self::ACCOUNTS_URL, array_filter(['pageSize' => 20, 'pageToken' => $page]));

            foreach ((array) ($data['accounts'] ?? []) as $account) {
                if (($account['type'] ?? '') === 'LOCATION_GROUP') {
                    $groups[(string) $account['name']] = (string) ($account['accountName'] ?? '');
                }
            }

            $page = (string) ($data['nextPageToken'] ?? '');
        } while ($page !== '');

        return $groups;
    }

    /**
     * Every listing in one account group, with the fields the sync compares.
     *
     * @return array<int, array<string, mixed>>
     */
    public function locations(string $account): array
    {
        $locations = [];
        $page = '';

        do {
            $data = $this->request('GET', self::INFO_URL.'/'.$account.'/locations', array_filter([
                'readMask' => self::READ_MASK,
                'pageSize' => 100,
                'pageToken' => $page,
            ]));

            foreach ((array) ($data['locations'] ?? []) as $location) {
                $locations[] = $location;
            }

            $page = (string) ($data['nextPageToken'] ?? '');
        } while ($page !== '');

        return $locations;
    }

    /**
     * Google's category names, for turning a display name into a resource name.
     *
     * @return array<string, string> Display name => `categories/gcid:...`.
     */
    public function category_ids(): array
    {
        $ids = [];
        $page = '';

        do {
            $data = $this->request('GET', self::INFO_URL.'/categories', array_filter([
                'regionCode' => 'US',
                'languageCode' => 'en',
                'view' => 'BASIC',
                'pageSize' => 100,
                'pageToken' => $page,
            ]));

            foreach ((array) ($data['categories'] ?? []) as $category) {
                if (isset($category['displayName'], $category['name'])) {
                    $ids[(string) $category['displayName']] = (string) $category['name'];
                }
            }

            $page = (string) ($data['nextPageToken'] ?? '');
        } while ($page !== '');

        return $ids;
    }

    /**
     * Update the named fields of one listing.
     *
     * @param  string  $location  The listing's resource name, `locations/123`.
     * @param  array<string, mixed>  $fields  Field values keyed by Business Profile field name.
     * @param  string  $mask  The `updateMask`, naming exactly the fields in $fields.
     * @return array<string, mixed>
     */
    public function patch(string $location, array $fields, string $mask, bool $validate_only = false): array
    {
        return $this->request('PATCH', self::INFO_URL.'/'.$location, array_filter([
            'updateMask' => $mask,
            'validateOnly' => $validate_only ? 'true' : '',
        ]), $fields);
    }

    /**
     * Create a listing in an account group.
     *
     * The request id is derived from the store code, so a retry of the same creation is
     * recognized by Google as the same request rather than a second listing.
     *
     * @param  string  $account  The account group, `accounts/123`.
     * @param  array<string, mixed>  $fields  Field values keyed by Business Profile field name.
     * @return array<string, mixed>
     */
    public function create(string $account, array $fields, string $store_code, bool $validate_only = false): array
    {
        return $this->request('POST', self::INFO_URL.'/'.$account.'/locations', array_filter([
            'requestId' => 'uamswp-'.substr(sha1($store_code), 0, 32),
            'validateOnly' => $validate_only ? 'true' : '',
        ]), $fields + ['storeCode' => $store_code, 'languageCode' => 'en']);
    }
}
