<?php
session_start();

if (!isset($_SESSION['cyber']))
    exit;

echo "hey";

?>