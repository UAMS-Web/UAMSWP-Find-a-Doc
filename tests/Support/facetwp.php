<?php

/**
 * Stand-in for the FacetWP base class the bundled Alpha add-on extends.
 *
 * FacetWP is not installed in the unit process, so the class the add-on extends is declared here with only
 * the property the add-on sets.
 */

declare(strict_types=1);

if (! class_exists('FacetWP_Facet')) {
    class FacetWP_Facet
    {
        public string $label = '';
    }
}
