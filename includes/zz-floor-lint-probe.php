<?php

// Throwaway probe for #885: a typed class constant is PHP 8.3 syntax and does not parse on 8.2.
final class UamswpFloorLintProbe
{
    public const string NAME = 'probe';
}
