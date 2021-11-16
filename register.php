<?php
// Loading all constants
require_once('var/constants.php');
require_once('classes/Auth.php');

// Clear the error message
$error_msg = "";

// Try to signup
if (isset($_POST['submit'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirm = $_POST['confirm'];
    // Trying to set up the data
    if (!empty($username) && !empty($password) && !empty($confirm) && ($password == $confirm)) {
        $result = register($username, $password);
        if ($result) {
            $confirm_msg = 'New account has been created. Now just log in.';
            header('Location: ' . PAGE_LOGIN . '?confirm_msg=' . $confirm_msg);
        }
    } else {
        $error_msg = "Enter all of the sign-up data.";
    }
}
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <?php require_once('html/head.html'); ?>
    <title>Project 4 - Sign Up</title>
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
                                <h3 class="mb-5">Sign Up</h3>
                                <?php if (!empty($error_msg)) : ?>
                                    <div class="alert alert-danger d-flex align-items-center" role="alert">
                                        <i class="fas fa-exclamation-triangle text-danger me-3"></i>
                                        <div>
                                            <?php echo $error_msg; ?>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                                    <div class="form-outline mb-4">
                                        <input type="text" id="username" class="form-control form-control-lg" name="username"
                                               value="<?php if (!empty($username)) echo $username; ?>"/>
                                        <label class="form-label" for="username">Username</label>
                                    </div>

                                    <div class="form-outline mb-4">
                                        <input type="password" id="password" class="form-control form-control-lg" name="password"/>
                                        <label class="form-label" for="password">Password</label>
                                    </div>

                                    <div class="form-outline mb-4">
                                        <input type="password" id="confirm" class="form-control form-control-lg" name="confirm"/>
                                        <label class="form-label" for="confirm">Confirm</label>
                                    </div>

                                    <button class="btn btn-primary btn-lg btn-block" type="submit" name="submit">Sign Up</button>
                                    <div class="mt-3">or <a href="login.php">Log In<a/></div>
                                </form>
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