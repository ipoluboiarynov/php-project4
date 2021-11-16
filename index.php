<?php
session_start();
require_once('var/protect.php');
require_once ('classes/UserManager.php');

if (isset($_POST['submit'])) {
    $userManager = new UserManager();
    $id = $_SESSION['user_id'] ?? $_COOKIE['user_id'];
    $new_api_key = $userManager->changeApiKey($id);
    if ($new_api_key != 0) {
        $_SESSION['api_key'] = $new_api_key;
        setcookie('api_key', $new_api_key);
    } else {
        $error_msg = 'Api Key was not changed!';
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
        <p><?php echo $_SESSION['api_key']; ?></p>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
            <button class="btn p4-btn bg-gradient mb-3" type="submit" name="submit">Generate New API Key</button>
        </form>
        <p><?php echo $_SESSION['username'] ?? $_COOKIE['username']; ?>, if you are no longer using the <span class="fw-bold">Student Service API</span>, consider <a href="#">deleting</a> your account.</p>
    </div>
</main>
<?php require_once('html/footer.html'); ?>
</body>
</html>