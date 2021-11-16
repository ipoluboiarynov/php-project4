<?php
// If user_id and username are not set, try to get them with cookies or redirect to login page
if (!isset($_SESSION['user_id'])) {
    if (isset($_COOKIE['user_id']) && isset($_COOKIE['username']) && isset($_COOKIE['api_key'])) {
        $_SESSION['user_id'] = $_COOKIE['user_id'];
        $_SESSION['username'] = $_COOKIE['username'];
        $_SESSION['api_key'] = $_COOKIE['api_key'];
    } else {
        header("Location: " . PAGE_LOGIN);
    }
}
?>