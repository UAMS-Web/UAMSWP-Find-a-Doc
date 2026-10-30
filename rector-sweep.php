<?php

declare(strict_types=1);

/*
 * The sweep profile -- every rule `rector.php` enables, including the ones that
 * config holds back from the gating profile.
 *
 * Run it deliberately and review the diff; it is never the gate:
 *   composer test:refactor:sweep   # preview
 *   composer refactor:sweep        # apply
 *
 * The constant is the only difference between the two profiles. `rector.php`
 * stays the single source of truth for paths, sets, and skips, so the sweep
 * cannot drift from what the gate enforces. Copy this file unchanged.
 */

define('UAMS_RECTOR_SWEEP', true);

return require __DIR__.'/rector.php';
