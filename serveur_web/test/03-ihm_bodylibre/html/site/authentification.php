<?php
session_set_cookie_params([
	'lifetime' => 0,
	'path' => '/',
	'domain' => '',
	'secure' => false,
	'httponly' => true,
	'samesite' => 'Strict'
]);
session_start();

if (!isset($_SESSION['init'])) {
	session_regenerate_id();
	$_SESSION['init'] = true;
	$_SESSION['ip'] = $_SERVER['REMOTE_ADDR'];
	$_SESSION['ua'] = $_SERVER['HTTP_USER_AGENT'];
}
?>