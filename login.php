<?php
// Loading all constants
require_once('var/constants.php');
require_once('services/auth_service.php');

// Start the session_start
session_start();

// Clear the error message or get it from $_GET global variable
if (isset($_GET['error_msg'])) {
    $error_msg = $_GET['error_msg'];
} else {
    $error_msg = "";
}

// Clear the confirm message or get it from $_GET global variable
if (isset($_GET['confirm_msg'])) {
    $confirm_msg = $_GET['confirm_msg'];
} else {
    $confirm_msg = "";
}

// If the user isn't logged in, try to log them in
if (!isset($_COOKIE['api_key'])) {
    if (isset($_POST['submit'])) {
        $user_username = $_POST['username'];
        $user_password = $_POST['password'];

        // Trying login
        if (!empty($user_username) && !empty($user_password)) {
            $result = login($user_username, $user_password);

            if (gettype($result) == 'object') {
                setcookie('username', $result->username, time() + 360);
                setcookie('api_key', $result->api_key, time() + 360);
                header("Location: " . PAGE_HOME);
            } else {
                $error_msg = $result . 'User not found try again.';
            }
        } else {
            $error_msg = 'Fill in all fields of the form.';
        }
    }
}

?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <?php require_once('html/head.html'); ?>
    <title>Project 4 - Log In</title>
</head>
<body>
<main>
    <div class="container-fluid bg-light">
        <section class="vh-100">
            <div class="container py-5 h-100">
                <div class="row d-flex justify-content-center align-items-center h-100">
                    <div class="col-12 col-md-8 col-lg-6 col-xl-5">
                        <div class="card shadow-2-strong" style="border-radius: 1rem;">
                            <div class="card-body p-5 text-center">
                                <h3 class="mb-5">Log in</h3>
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


<!--                                --><?php //if (empty($_COOKIE['api_key'])):?>
                                <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                                    <div class="form-outline mb-4">
                                        <input type="text" id="username" name="username"
                                               class="form-control form-control-lg"
                                               value="<?php if (!empty($user_username)) echo $user_username; ?>">
                                        <label class="form-label" for="username">Username</label>
                                    </div>
                                    <div class="form-outline mb-4">
                                        <input type="password" id="password" name="password"
                                               class="form-control form-control-lg"/>
                                        <label class="form-label" for="password">Password</label>
                                    </div>
                                    <button class="btn btn-primary btn-lg btn-block" type="submit" name="submit">Log
                                        In
                                    </button>
                                    <div class="mt-3">or <a href="<?php echo PAGE_SIGNUP ?>">Sign Up<a/></div>
                                </form>
<!--                                --><?php //else:
//                                    header('Location: ' . PAGE_HOME);
//                                endif;
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</main>
<?php require_once('html/footer.html'); ?>
</body>
</html>