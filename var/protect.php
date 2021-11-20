<?php
require_once('var/constants.php');

if (!isset($_COOKIE['api_key']) || !isset($_COOKIE['username'])) {
    session_destroy();
    setcookie('username', '', time() - 3600);
    setcookie('api_key', '', time() - 3600);
    header("Location: " . PAGE_LOGIN);
}
?>