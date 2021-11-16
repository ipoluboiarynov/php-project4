<?php
    require_once('var/constants.php');
?>

<header>
    <div class="container-fluid">
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark bg-gradient p-0">
            <div class="container-fluid">

                <a class="navbar-brand py-3" href="#">Student Service</a>

                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainMenuContent"
                        aria-controls="mainMenuContent" aria-expanded="false" aria-label="Main Menu">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="mainMenuContent">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item py-2 <?php echo (basename($_SERVER['PHP_SELF']) == 'index.php') ? "p4-active" : "" ?>">
                            <a class="nav-link" aria-current="page" href="index.php">API Key Management</a>
                        </li>
                        <li class="nav-item py-2 <?php echo (basename($_SERVER['PHP_SELF']) == 'password.php') ? "p4-active" : "" ?>">
                            <a class="nav-link" href="password.php">Change Password</a>
                        </li>
                        <li class="nav-item py-2 <?php echo (basename($_SERVER['PHP_SELF']) == 'taskManagement.php') ? "p4-active" : "" ?>">
                            <a class="nav-link" href="taskManagement.php">Task Management</a>
                        </li>
                        <li class="nav-item py-2 <?php echo (basename($_SERVER['PHP_SELF']) == 'taskStatistics.php') ? "p4-active" : "" ?>">
                            <a class="nav-link" href="taskStatistics.php">Task Statistics</a>
                        </li>
                        <li class="nav-item py-2">
                            <a class="nav-link" href="<?php echo PAGE_LOGOUT; ?>">Log Out (<?php echo $_SESSION['username'] ?? $_COOKIE['username']; ?>)</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </div>
</header>