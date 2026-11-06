<?php

/**
 * Theme phone stand-ins for metadata tests that leave phone fields absent.
 *
 * Populated values are rejected rather than pretending to format them. These
 * provide no evidence about the theme's phone formatting or links.
 */

declare(strict_types=1);

if (! function_exists('format_phone_us')) {
    /**
     * Handle only absent phone values without loading the site's theme.
     *
     * @throws LogicException When a fixture requires real phone formatting.
     */
    function format_phone_us(mixed $phone): string
    {
        if (! in_array($phone, [false, null, ''], true)) {
            throw new LogicException('Populated phone fixtures require the real theme formatter.');
        }

        return '';
    }
}

if (! function_exists('format_phone_dash')) {
    /**
     * Handle only absent phone values without loading the site's theme.
     *
     * @throws LogicException When a fixture requires real phone formatting.
     */
    function format_phone_dash(mixed $phone): string
    {
        if (! in_array($phone, [false, null, ''], true)) {
            throw new LogicException('Populated phone fixtures require the real theme formatter.');
        }

        return '';
    }
}
