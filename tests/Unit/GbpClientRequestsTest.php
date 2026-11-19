<?php

/**
 * Every request the Business Profile API client (includes/class.fad-gbp-client.php) sends, pinned whole: the
 * token exchange and when it is repeated, the headers, each endpoint's query, the bodies, and the messages of
 * the errors it raises. The behavior of each call is in tests/Unit/GbpClientTest.php.
 */

declare(strict_types=1);

/**
 * A client whose transport records every call and answers it from a queue of [status, raw body] pairs, the
 * token exchange included.
 *
 * @param  array<int, array{0: int, 1: string}>  $responses
 * @return array{0: UAMSWP_FAD_GBP_Client, 1: ArrayObject<int, array{0: string, 1: string, 2: array<string, string>, 3: ?string}>}
 */
function gbp_requests_client(array $responses): array
{
    /** @var ArrayObject<int, array{0: string, 1: string, 2: array<string, string>, 3: ?string}> $calls */
    $calls = new ArrayObject;

    $client = new UAMSWP_FAD_GBP_Client(
        ['client_id' => 'id-1', 'client_secret' => 'secret-1', 'refresh_token' => 'refresh-1'],
        static function (string $method, string $url, array $headers, ?string $body) use (&$responses, $calls): array {
            $calls[] = [$method, $url, $headers, $body];
            $next = array_shift($responses);

            if ($next === null) {
                throw new LogicException('An unexpected call: '.$method.' '.$url);
            }

            return $next;
        }
    );

    return [$client, $calls];
}

/**
 * A token exchange's answer.
 *
 * @return array{0: int, 1: string}
 */
function gbp_requests_token(int $expires_in = 3600): array
{
    return [200, (string) json_encode(['access_token' => 'tok-1', 'expires_in' => $expires_in])];
}

/**
 * A JSON answer.
 *
 * @param  array<string, mixed>  $body
 * @return array{0: int, 1: string}
 */
function gbp_requests_json(array $body, int $status = 200): array
{
    return [$status, (string) json_encode($body)];
}

/** The base of the Business Information API. */
const GBP_INFO = 'https://mybusinessbusinessinformation.googleapis.com/v1';

test('the token exchange posts the three credentials and the refresh grant as a form', function (): void {
    [$client, $calls] = gbp_requests_client([gbp_requests_token()]);

    expect($client->access_token())->toBe('tok-1')
        ->and($calls->getArrayCopy())->toBe([[
            'POST',
            'https://oauth2.googleapis.com/token',
            ['Content-Type' => 'application/x-www-form-urlencoded'],
            'client_id=id-1&client_secret=secret-1&refresh_token=refresh-1&grant_type=refresh_token',
        ]]);
});

test('a token is reused until a minute before it expires, and exchanged again from then', function (int $expires_in, int $exchanges): void {
    // Repeated until the clock did not tick during the two calls, so the margin is read in one second.
    do {
        [$client, $calls] = gbp_requests_client([gbp_requests_token($expires_in), gbp_requests_token($expires_in)]);
        $now = time();
        $client->access_token();
        $client->access_token();
    } while (time() !== $now);

    expect($calls->count())->toBe($exchanges);
})->with([
    'an hour' => [3600, 1],
    'a minute and a second' => [61, 1],
    'exactly a minute' => [60, 2],
    'less than a minute' => [10, 2],
]);

test('a token with no expiry is kept for an hour', function (): void {
    // Repeated until the clock did not tick, so the margin is read in one second.
    do {
        [$client, $calls] = gbp_requests_client([[200, (string) json_encode(['access_token' => 'tok-1'])]]);
        $now = time();
        $client->access_token();
        $reflection = new ReflectionProperty(UAMSWP_FAD_GBP_Client::class, 'token_expires');
        $expires = $reflection->getValue($client);
    } while (time() !== $now);

    expect($expires)->toBe($now + 3600)
        ->and($calls->count())->toBe(1);
});

test('a token exchange that does not return a token says why', function (array $response, string $message): void {
    [$client] = gbp_requests_client([$response]);

    expect(fn () => $client->access_token())->toThrow(RuntimeException::class, $message);
})->with([
    'an error from Google' => [[400, (string) json_encode(['error' => 'invalid_grant'])], 'Google token exchange failed (400): invalid_grant'],
    'a body that is not JSON' => [[200, 'not json'], 'Google token exchange failed (200): no token returned'],
    'a success with no token' => [[200, (string) json_encode(['expires_in' => 3600])], 'Google token exchange failed (200): no token returned'],
    'a token with an error status' => [[500, (string) json_encode(['access_token' => 'tok-1'])], 'Google token exchange failed (500): no token returned'],
    'a request that was not made' => [[0, 'cURL error 6'], 'Google token exchange failed (0): cURL error 6'],
    'a request that was not made, with no reason' => [[0, ''], 'Google token exchange failed (0): no token returned'],
    'an error status with a raw body' => [[502, 'Bad Gateway'], 'Google token exchange failed (502): no token returned'],
]);

test('a call without a body sends only the bearer, and one with a body sends JSON', function (): void {
    [$client, $calls] = gbp_requests_client([gbp_requests_token(), gbp_requests_json([]), gbp_requests_json([])]);

    $client->request('GET', GBP_INFO.'/x');
    $client->request('PATCH', GBP_INFO.'/y', [], ['title' => 'A']);

    expect($calls[1])->toBe(['GET', GBP_INFO.'/x', ['Authorization' => 'Bearer tok-1'], null])
        ->and($calls[2])->toBe(['PATCH', GBP_INFO.'/y', ['Authorization' => 'Bearer tok-1', 'Content-Type' => 'application/json'], '{"title":"A"}']);
});

test('a query is added after a question mark, or after an ampersand when the URL has one', function (): void {
    [$client, $calls] = gbp_requests_client([gbp_requests_token(), gbp_requests_json([]), gbp_requests_json([])]);

    $client->request('GET', GBP_INFO.'/x', ['a' => 1, 'b' => 'two']);
    $client->request('GET', GBP_INFO.'/x?z=1', ['a' => 1]);

    expect($calls[1][1])->toBe(GBP_INFO.'/x?a=1&b=two')
        ->and($calls[2][1])->toBe(GBP_INFO.'/x?z=1&a=1');
});

test('a body that cannot be encoded is sent as an empty string', function (): void {
    [$client, $calls] = gbp_requests_client([gbp_requests_token(), gbp_requests_json([])]);

    $client->request('PATCH', GBP_INFO.'/y', [], ['title' => "\xB1"]);

    expect($calls[1][3])->toBe('');
});

test('an empty or non-JSON answer reads as an empty array', function (string $raw): void {
    [$client] = gbp_requests_client([gbp_requests_token(), [200, $raw]]);

    expect($client->request('GET', GBP_INFO.'/x'))->toBe([]);
})->with(['empty' => [''], 'not JSON' => ['not json'], 'a JSON string' => ['"text"']]);

test('every 2xx status is a success and every other status a failure', function (int $status, bool $ok): void {
    [$client] = gbp_requests_client([gbp_requests_token(), gbp_requests_json(['ok' => true], $status)]);

    if ($ok) {
        expect($client->request('GET', GBP_INFO.'/x'))->toBe(['ok' => true]);
    } else {
        expect(fn () => $client->request('GET', GBP_INFO.'/x'))->toThrow(RuntimeException::class, 'Google API GET failed ('.$status.'): no message');
    }
})->with([
    '199' => [199, false],
    '200' => [200, true],
    '299' => [299, true],
    '300' => [300, false],
]);

test("a failure names the method, the status and Google's message, or why the request was not made", function (array $response, string $message): void {
    [$client] = gbp_requests_client([gbp_requests_token(), $response]);

    expect(fn () => $client->request('DELETE', GBP_INFO.'/x'))->toThrow(RuntimeException::class, $message);
})->with([
    "Google's message" => [gbp_requests_json(['error' => ['message' => 'Not found']], 404), 'Google API DELETE failed (404): Not found'],
    'an error that is not an object' => [gbp_requests_json(['error' => 'flat'], 400), 'Google API DELETE failed (400): no message'],
    'a raw body on a failure' => [[502, 'Bad Gateway'], 'Google API DELETE failed (502): no message'],
    'a request that was not made' => [[0, 'cURL error 6'], 'Google API DELETE failed (0): cURL error 6'],
    'a request that was not made, with no reason' => [[0, ''], 'Google API DELETE failed (0): no message'],
]);

test('a rate limit created with no message has an empty message and is not daily', function (): void {
    $limited = new UAMSWP_FAD_GBP_Rate_Limited;

    expect($limited->getMessage())->toBe('')
        ->and($limited->daily)->toBeFalse();
});

test('the location groups are read 20 at a time, from the first page without a page token, keeping only location groups', function (): void {
    [$client, $calls] = gbp_requests_client([
        gbp_requests_token(),
        gbp_requests_json(['accounts' => [['type' => 'LOCATION_GROUP', 'name' => 'accounts/1', 'accountName' => 'UAMS'], ['type' => 'PERSONAL', 'name' => 'accounts/2'], ['name' => 'accounts/3']], 'nextPageToken' => 'p2']),
        gbp_requests_json(['accounts' => [['type' => 'LOCATION_GROUP', 'name' => 'accounts/4']]]),
    ]);

    expect($client->location_groups())->toBe(['accounts/1' => 'UAMS', 'accounts/4' => ''])
        ->and($calls[1][1])->toBe('https://mybusinessaccountmanagement.googleapis.com/v1/accounts?pageSize=20')
        ->and($calls[2][1])->toBe('https://mybusinessaccountmanagement.googleapis.com/v1/accounts?pageSize=20&pageToken=p2')
        ->and($calls->count())->toBe(3);
});

test('a page whose list is not a list is read as a list of that one value', function (string $method, string $key, array $expected): void {
    [$client] = gbp_requests_client([gbp_requests_token(), gbp_requests_json([$key => 'not a list'])]);

    expect($client->{$method}(...($method === 'locations' ? ['accounts/1'] : [])))->toBe($expected);
})->with([
    // The value is not an account or a category, so nothing is kept.
    'location groups' => ['location_groups', 'accounts', []],
    // A listing that is not an object is kept as an empty one.
    'locations' => ['locations', 'locations', [[]]],
    'categories' => ['category_ids', 'categories', []],
]);

test('the locations of a group are read 100 at a time with the compared fields, following every page', function (): void {
    [$client, $calls] = gbp_requests_client([
        gbp_requests_token(),
        gbp_requests_json(['locations' => [['name' => 'locations/1']], 'nextPageToken' => 'p2']),
        gbp_requests_json(['locations' => [['name' => 'locations/2'], 'not an object']]),
    ]);

    $mask = 'name,storeCode,title,storefrontAddress,latlng,phoneNumbers,websiteUri,categories,profile,metadata,openInfo';

    expect($client->locations('accounts/9'))->toBe([['name' => 'locations/1'], ['name' => 'locations/2'], []])
        ->and($calls[1][1])->toBe(GBP_INFO.'/accounts/9/locations?'.http_build_query(['readMask' => $mask, 'pageSize' => 100]))
        ->and($calls[2][1])->toBe(GBP_INFO.'/accounts/9/locations?'.http_build_query(['readMask' => $mask, 'pageSize' => 100, 'pageToken' => 'p2']));
});

test('the categories are read in English for the US, 100 at a time, keeping those with both names', function (): void {
    [$client, $calls] = gbp_requests_client([
        gbp_requests_token(),
        gbp_requests_json(['categories' => [['displayName' => 'Doctor', 'name' => 'categories/gcid:doctor'], ['displayName' => 'No id'], ['name' => 'categories/gcid:no_name']], 'nextPageToken' => 'p2']),
        gbp_requests_json(['categories' => [['displayName' => 'Clinic', 'name' => 'categories/gcid:clinic']]]),
    ]);

    $query = ['regionCode' => 'US', 'languageCode' => 'en', 'view' => 'BASIC', 'pageSize' => 100];

    expect($client->category_ids())->toBe(['Doctor' => 'categories/gcid:doctor', 'Clinic' => 'categories/gcid:clinic'])
        ->and($calls[1][1])->toBe(GBP_INFO.'/categories?'.http_build_query($query))
        ->and($calls[2][1])->toBe(GBP_INFO.'/categories?'.http_build_query($query + ['pageToken' => 'p2']));
});

test('a patch sends its mask, and validateOnly only when asked', function (): void {
    [$client, $calls] = gbp_requests_client([gbp_requests_token(), gbp_requests_json(['name' => 'locations/1']), gbp_requests_json([])]);

    expect($client->patch('locations/1', ['title' => 'A'], 'title'))->toBe(['name' => 'locations/1']);
    $client->patch('locations/1', ['title' => 'A'], 'title', true);

    expect(array_slice($calls[1], 0, 2))->toBe(['PATCH', GBP_INFO.'/locations/1?updateMask=title'])
        ->and($calls[1][3])->toBe('{"title":"A"}')
        ->and($calls[2][1])->toBe(GBP_INFO.'/locations/1?updateMask=title&validateOnly=true');
});

test('a create posts the fields with the store code and English, under a request id from the store code', function (): void {
    [$client, $calls] = gbp_requests_client([gbp_requests_token(), gbp_requests_json(['name' => 'locations/5']), gbp_requests_json([])]);

    expect($client->create('accounts/1', ['title' => 'A'], 'P-12'))->toBe(['name' => 'locations/5']);
    $client->create('accounts/1', ['title' => 'A', 'languageCode' => 'es'], 'P-12', true);

    $id = 'uamswp-'.substr(sha1('P-12'), 0, 32);

    expect(array_slice($calls[1], 0, 2))->toBe(['POST', GBP_INFO.'/accounts/1/locations?requestId='.$id])
        ->and(strlen($id))->toBe(39)
        ->and($calls[1][3])->toBe('{"title":"A","storeCode":"P-12","languageCode":"en"}')
        ->and($calls[2][1])->toBe(GBP_INFO.'/accounts/1/locations?requestId='.$id.'&validateOnly=true')
        ->and($calls[2][3])->toBe('{"title":"A","languageCode":"es","storeCode":"P-12"}');
});

test('a photo is added through the v4 media endpoint of the account and listing', function (): void {
    [$client, $calls] = gbp_requests_client([gbp_requests_token(), gbp_requests_json(['name' => 'media/1'])]);

    expect($client->upload_photo('accounts/1', 'locations/5', 'LOGO', 'https://example.test/logo.png'))->toBe(['name' => 'media/1'])
        ->and(array_slice($calls[1], 0, 2))->toBe(['POST', 'https://mybusiness.googleapis.com/v4/accounts/1/locations/5/media'])
        ->and($calls[1][3])->toBe('{"mediaFormat":"PHOTO","locationAssociation":{"category":"LOGO"},"sourceUrl":"https:\/\/example.test\/logo.png"}');
});

test('the attributes are read from the listing, as true or false values without their prefix', function (): void {
    [$client, $calls] = gbp_requests_client([gbp_requests_token(), gbp_requests_json(['attributes' => [
        ['name' => 'attributes/has_wheelchair_accessible_entrance', 'valueType' => 'BOOL', 'values' => [true]],
        ['name' => 'attributes/is_closed', 'valueType' => 'BOOL', 'values' => [false]],
        ['name' => 'attributes/url_menu', 'valueType' => 'URL', 'values' => ['https://example.test/']],
        ['name' => 'attributes/no_type', 'values' => [true]],
    ]])]);

    expect($client->attributes('locations/5'))->toBe(['has_wheelchair_accessible_entrance' => true, 'is_closed' => false])
        ->and(array_slice($calls[1], 0, 2))->toBe(['GET', GBP_INFO.'/locations/5/attributes']);
});

test('a listing whose attributes are not a list has none', function (): void {
    [$client] = gbp_requests_client([gbp_requests_token(), gbp_requests_json(['attributes' => 'not a list'])]);

    expect($client->attributes('locations/5'))->toBe([]);
});

test('the attributes are written with a mask naming each one, in order', function (): void {
    [$client, $calls] = gbp_requests_client([gbp_requests_token(), gbp_requests_json([])]);

    $client->update_attributes('locations/5', ['a' => true, 'b' => false]);

    expect(array_slice($calls[1], 0, 2))->toBe(['PATCH', GBP_INFO.'/locations/5/attributes?'.http_build_query(['attributeMask' => 'attributes/a,attributes/b'])])
        ->and($calls[1][3])->toBe('{"name":"locations\/5\/attributes","attributes":[{"name":"attributes\/a","values":[true]},{"name":"attributes\/b","values":[false]}]}');
});

test('writing no attributes sends an empty list under an empty mask', function (): void {
    [$client, $calls] = gbp_requests_client([gbp_requests_token(), gbp_requests_json([])]);

    $client->update_attributes('locations/5', []);

    expect($calls[1][1])->toBe(GBP_INFO.'/locations/5/attributes?attributeMask=')
        ->and($calls[1][3])->toBe('{"name":"locations\\/5\\/attributes","attributes":[]}');
});
