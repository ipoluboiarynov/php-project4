<?php
// Loading all constants
require_once('var/constants.php');
require_once('classes/Auth.php');
require_once ('classes/UserManager.php');

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
if (!isset($_SESSION['user_id'])) {
    if (isset($_POST['submit'])) {

        $user_username = $_POST['username'];
        $user_password = $_POST['password'];

        // Trying login
        if (!empty($user_username) && !empty($user_password)) {
            // Look up the username and password in the database
            $result = login($user_username, $user_password);

            if (gettype($result) == 'object') {
                // The log-in is OK than set the user_id, username and api_key to cookies and redirect to the home page
                $_SESSION['user_id'] = $result->__get('id');
                $_SESSION['username'] = $result->__get('username');
                $_SESSION['api_key'] = $result->__get('api_key');
                setcookie('user_id', $result->__get('id'), time() + TOKENS_LIFE);
                setcookie('username', $result->__get('username'), time() + TOKENS_LIFE);
                setcookie('api_key', $result->__get('api_key'), time() + TOKENS_LIFE);
            } else {
                // The username/password are incorrect so set an error message
                $error_msg = $result . '. Enter a valid username and password.';
            }
        } else {
            // The username/password weren't entered so set an error message
            $error_msg = 'Please, enter your username and password to log in form.';
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


                                <?php if (empty($_SESSION['user_id'])):?>
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
                                <?php else:
                                    if (isset($_SESSION['current_page']) && !empty($_SESSION['current_page'])) {
                                        header('Location: ' . $_SESSION['current_page']);
                                    } else {
                                        header('Location: ' . PAGE_HOME);
                                    }
                                endif;
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