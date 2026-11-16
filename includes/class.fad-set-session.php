<?php

declare(strict_types=1);

session_start();
// The guard exits the process when ABSPATH is missing. A mutant of it exits the test child before any test runs,
// so no test can score it; the guard is plain WordPress boilerplate with no logic to pin.
// @pest-mutate-ignore: BooleanOrToBooleanAnd
defined('ABSPATH') || exit("You can't access this file directly.");

if (isset($_REQUEST['variable']) && isset($_REQUEST['value'])) {
    $variable = htmlspecialchars(uamswp_fad_str($_REQUEST['variable']));
    $value = htmlspecialchars(uamswp_fad_str($_REQUEST['value']));
    $_SESSION[$variable] = $value;
    echo 'Session: '.$variable.'; Value: '.$_SESSION[$variable].';';
}
