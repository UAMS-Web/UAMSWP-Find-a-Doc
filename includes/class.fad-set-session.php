<?php 
session_start();

if(isset($_REQUEST['variable']) && isset($_REQUEST['value'])) {
    $variable = $_REQUEST['variable'];
    $value = $_REQUEST['value'];
    $_SESSION[$variable] = $value;
}