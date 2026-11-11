<?php

/**
 * Pins uamswp_provider_region_in_rule(), the region check behind the remove-ontology rule
 * on the provider profile (templates/single-physicians.php).
 *
 * A provider matches when at least one of their regions is listed, even if they also have a
 * region that is not listed (decided in UAMS-Web/UAMSWP-Find-a-Doc#1132).
 */

declare(strict_types=1);

require_once __DIR__.'/../Support/acf-functions.php';

beforeEach(function (): void {
    uamswp_test_acf_functions();
});

test('a provider with one region matches a rule that lists it', function (): void {
    expect(uamswp_provider_region_in_rule([65], [65]))->toBeTrue();
});

test('a provider whose locations share one region matches a rule that lists it', function (): void {
    expect(uamswp_provider_region_in_rule([65, 65], [65, 66]))->toBeTrue();
});

test('a region stored as a string matches the same region listed as a number', function (): void {
    expect(uamswp_provider_region_in_rule(['65', '65'], [65]))->toBeTrue();
});

test('a provider in one region does not match a rule that lists other regions', function (): void {
    expect(uamswp_provider_region_in_rule([65, 65], [66]))->toBeFalse();
});

test('a provider in two regions matches a rule that lists either one or both', function (): void {
    expect(uamswp_provider_region_in_rule([65, 66], [65]))->toBeTrue()
        ->and(uamswp_provider_region_in_rule([65, 66], [66]))->toBeTrue()
        ->and(uamswp_provider_region_in_rule([65, 66], [65, 66]))->toBeTrue();
});

test('a provider in two regions does not match a rule that lists neither', function (): void {
    expect(uamswp_provider_region_in_rule([65, 66], [67]))->toBeFalse()
        ->and(uamswp_provider_region_in_rule([65, 66], [67, 68]))->toBeFalse();
});

test('a listed region later in the provider list still matches', function (): void {
    expect(uamswp_provider_region_in_rule([64, 65, 66], [66]))->toBeTrue();
});

test('a provider with no region, or a rule with no regions, does not match', function (): void {
    expect(uamswp_provider_region_in_rule([], [65]))->toBeFalse()
        ->and(uamswp_provider_region_in_rule(null, [65]))->toBeFalse()
        ->and(uamswp_provider_region_in_rule([65], []))->toBeFalse()
        ->and(uamswp_provider_region_in_rule([65], null))->toBeFalse();
});
