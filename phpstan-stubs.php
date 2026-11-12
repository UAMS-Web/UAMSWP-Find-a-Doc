<?php

/**
 * Declarations for functions that other plugins and the theme define at run time.
 *
 * PHPStan reads this file and never runs it. Each function is declared with the parameters the
 * plugin passes and `mixed` or a broad return type, which is what ACF, FacetWP and the theme
 * give back; the declarations exist so a call is not reported as an unknown function.
 */

declare(strict_types=1);

/** Advanced Custom Fields. */
/** @phpstan-impure */
function get_field(mixed $selector, mixed $post_id = false, bool $format_value = true): mixed {}

/** @phpstan-impure */
function get_field_object(mixed $selector, mixed $post_id = false, bool $format_value = true, bool $load_value = true): mixed {}

/** @phpstan-impure */
function get_sub_field(mixed $selector = '', bool $format_value = true): mixed {}

function update_field(mixed $selector, mixed $value, mixed $post_id = false): bool {}

/** @phpstan-impure */
function have_rows(mixed $selector, mixed $post_id = false): bool {}

/**
 * @phpstan-impure
 *
 * @return array<string, mixed>|false
 */
function the_row(bool $format = false): array|false {}

function acf_update_setting(string $name, mixed $value): bool {}

/** @param array<string, mixed>|string $page */
function acf_add_options_sub_page(array|string $page): mixed {}

/**
 * @param  array<string, mixed>  $field
 * @param  array<string, mixed>  $setting
 */
function acf_render_field_setting(array $field, array $setting, bool $global = false): void {}

function acf_add_validation_error(string $input = '', string $message = ''): void {}

/** FacetWP. */
/** @phpstan-impure */
function facetwp_display(string $type, mixed $name = ''): mixed {}

/** The UAMS theme. */
function image_sizer(mixed $image, int $width, int $height, string $x = 'center', string $y = 'center', string $class = ''): mixed {}

function format_phone_us(mixed $phone): mixed {}

function format_phone_dash(mixed $phone): mixed {}
