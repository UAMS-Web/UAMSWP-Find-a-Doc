<?php
session_start();
defined('ABSPATH') or die("You can't access this file directly.");

if(isset($_REQUEST['variable']) && isset($_REQUEST['value'])) {
	$variable = htmlspecialchars($_REQUEST['variable']);
	$value = htmlspecialchars($_REQUEST['value']);
	$_SESSION[$variable] = $value;
	echo ( 'Session: ' .$variable .'; Value: '. $_SESSION[$variable] .';' );
}