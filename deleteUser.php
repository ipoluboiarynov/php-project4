<?php
// Loading all constants
require_once('var/constants.php');
require_once('classes/UserManager.php');

// If the user is logged in, delete the session vars to log them out
session_start();

$userManager = new UserManager();
$id = $_SESSION['user_id'] ?? $_COOKIE['user_id'];
$deleted_accounts = $userManager->delete($id);
if ($deleted_accounts == 1) {
    header('Location: ' . PAGE_LOGOUT);
} else {
    header('Location: ' . PAGE_HOME . '?error_msg="Account has not been deleted.');
}
?>