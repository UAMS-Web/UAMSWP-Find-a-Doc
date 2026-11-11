<?php

/**
 * The Integration suite loads no theme. Metadata tests do not exercise image sizing.
 */

declare(strict_types=1);

if (! function_exists('image_sizer')) {
    /**
     * @param  mixed  ...$arguments
     * @return array<mixed>
     */
    function image_sizer(...$arguments): array
    {
        return [];
    }
}
