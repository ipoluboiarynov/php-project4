<?php
session_start();
require_once('var/protect.php');
require_once('services/user_service.php');

if (isset($_GET['error_msg'])) {
    $error_msg = $_GET['error_msg'];
}
// On submit change api_key button
if (isset($_POST['submit'])) {
    $api_key = $_COOKIE['api_key'];
    $new_api_key = changeApiKey($api_key);
    if ($new_api_key == 'Access denied') {
        $error_msg = 'Access denied!';
    } else {
        setcookie('api_key', $new_api_key);
    }
}
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <?php require_once('html/head.html'); ?>
    <title>Project 4 - API Key Management</title>
</head>
<body>
<?php require_once('html/header.php'); ?>
<main>
    <div class="container-fluid">
        <h3 class="py-3">API Key</h3>
        <?php if (!empty($error_msg)) : ?>
            <div class="alert alert-danger d-flex align-items-center" role="alert">
                <i class="fas fa-exclamation-triangle text-danger me-3"></i>
                <div>
                    <?php echo $error_msg; ?>
                </div>
            </div>
        <?php endif; ?>
        <p><?php echo $new_api_key ?? $_COOKIE['api_key']; ?></p>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
            <button class="btn p4-btn bg-gradient mb-3" type="submit" name="submit">Generate New API Key</button>
        </form>
        <p><?php echo $_COOKIE['username'] ?? ''; ?>, if you are no longer using the <span class="fw-bold">
                Student Service API</span>, consider <a href="deleteUser.php">deleting</a> your account.
        </p>
    </div>
</main>
<?php require_once('html/footer.html'); ?>
</body>
</html>