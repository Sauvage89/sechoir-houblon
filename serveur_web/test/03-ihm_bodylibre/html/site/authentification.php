<?php
session_set_cookie_params([
	'lifetime' => 0,
	'path' => '/',
	'domain' => '',
	'secure' => false,
	'httponly' => true,
	'samesite' => 'Strict'
]);

if (session_status() === PHP_SESSION_NONE)
	session_start();

if (!isset($_SESSION['init'])) {
	session_regenerate_id();
	$_SESSION['init'] = true;
	$_SESSION['ip'] = $_SERVER['REMOTE_ADDR'];
	$_SESSION['ua'] = $_SERVER['HTTP_USER_AGENT'];
	$_SESSION['access'] = $_SERVER['HTTP_USER_AGENT'];
}
?>