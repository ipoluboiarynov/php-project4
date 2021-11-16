<?php

session_start();

require_once('var/constants.php');
require_once('var/protect.php');
require_once ('classes/UserManager.php');

if (isset($_POST['submit'])) {
    $current_password = $_POST['current'];
    $new_password = $_POST['new'];
    $confirm_password = $_POST['confirm'];
    $id = $_SESSION['user_id'] ?? $_COOKIE['user_id'];
    $userManager = new UserManager();
    $isPasswordExists = $userManager->checkPassword($current_password, $_SESSION['user_id'] ?? $_COOKIE['user_id']);
    if ($isPasswordExists) {
        if ($new_password == $confirm_password) {
            $userManager->changePassword($id, $new_password);
            $confirm_msg = 'Password changed.';
        } else {
            $error_msg = 'Wrong password confirm. Try again.';
        }
    } else {
        $error_msg = 'Wrong password. Try again.';
    }
}
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <?php require_once('html/head.html'); ?>
    <title>Project 4 - Change Password</title>
</head>
<body>
<?php require_once('html/header.php'); ?>
<main>
    <div class="container-fluid">
        <h3 class="py-3">Password Update for <?php echo $_SESSION['username'] ?? $_COOKIE['username']; ?></h3>
        <?php if (!empty($error_msg)) : ?>
            <div class="alert alert-danger d-flex align-items-center" role="alert">
                <i class="fas fa-exclamation-triangle text-danger me-3"></i>
                <div>
                    <?php echo $error_msg; ?>
                </div>
            </div>
        <?php endif; ?>
        <?php if (!empty($confirm_msg)) : ?>
            <div class="alert alert-warning d-flex align-items-center" role="alert">
                <i class="fas fa-check-circle text-success me-3"></i>
                <div>
                    <?php echo $confirm_msg; ?>
                </div>
            </div>
        <?php endif; ?>
        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <div class="row mb-3 mx-2">
                <label for="current" class="col-sm-2 col-form-label fw-bold">Current</label>
                <div class="col-sm-10">
                    <input type="password" class="form-control" id="current" name="current">
                </div>
            </div>
            <div class="row mb-3 mx-2">
                <label for="new" class="col-sm-2 col-form-label fw-bold">New</label>
                <div class="col-sm-10">
                    <input type="password" class="form-control" id="new" name="new">
                </div>
            </div>
            <div class="row mb-3 mx-2">
                <label for="confirm" class="col-sm-2 col-form-label fw-bold">Confirm</label>
                <div class="col-sm-10">
                    <input type="password" class="form-control" id="confirm" name="confirm">
                </div>
            </div>
            <button type="submit" class="btn p4-btn" name="submit">Update</button>
        </form>

    </div>
</main>
<?php require_once('html/footer.html'); ?>
</body>
</html>