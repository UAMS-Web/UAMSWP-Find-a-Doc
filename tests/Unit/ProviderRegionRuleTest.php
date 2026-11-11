<?php

/**
 * Pins uamswp_provider_region_in_rule(), the region check behind the remove-ontology rule
 * on the provider profile (templates/single-physicians.php).
 *
 * A provider matches only when all of their locations are in one region and that region is
 * listed. A provider in two different regions does not match, as before; how a rule treats
 * that provider is decided in UAMS-Web/UAMSWP-Find-a-Doc#1132.
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

test('a provider in two different regions does not match, even when both are listed', function (): void {
    expect(uamswp_provider_region_in_rule([65, 66], [65, 66]))->toBeFalse()
        ->and(uamswp_provider_region_in_rule([65, 66], [65]))->toBeFalse();
});

test('a provider with no region, or a rule with no regions, does not match', function (): void {
    expect(uamswp_provider_region_in_rule([], [65]))->toBeFalse()
        ->and(uamswp_provider_region_in_rule(null, [65]))->toBeFalse()
        ->and(uamswp_provider_region_in_rule([65], []))->toBeFalse()
        ->and(uamswp_provider_region_in_rule([65], null))->toBeFalse();
});
