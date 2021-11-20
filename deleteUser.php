<?php
// Loading all constants
require_once('var/constants.php');
require_once('services/user_service.php');

// If the user is logged in, delete the session vars to log them out
session_start();

$deleted_rows = deleteAccount();
if ($deleted_rows == 1) {
    header('Location: ' . PAGE_LOGOUT);
} else {
    header('Location: ' . PAGE_HOME . '?error_msg="Account has not been deleted.');
}
?>