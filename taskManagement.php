<?php

session_start();

require_once('var/constants.php');
require_once('var/protect.php');
require_once ('classes/TaskManager.php');
require_once ('classes/UserRequestsManager.php');

$user_id = $_SESSION['user_id'] ?? $_COOKIE['user_id'];


if (isset($_POST['read-all-submit'])) {
    $userRequestsManager = new UserRequestsManager();
    $all_tasks = getAllTasks($user_id);
    $ur_id = $userRequestsManager->isExistsForUser($user_id);
    $userRequestsManager->update($ur_id, 'readAll_request', $user_id);


}

function getAllTasks($user_id): array {
    $taskManager = new TaskManager();
    return $taskManager->readAll($user_id);
}


if (isset($_POST['create-submit'])) {
    if (isset($_POST['create-description']) && strlen(trim($_POST['create-description'])) > 0) {
        $description = $_POST['create-description'];
        $taskManager = new TaskManager();
        $taskManager->create($description, $user_id);
        $userRequestsManager = new UserRequestsManager();
        $id = $userRequestsManager->isExistsForUser($user_id);
        $userRequestsManager->update($id, 'create_request', $user_id);
        $_POST = array();
        $confirm_msg = 'New task has been created.';
    } else {
        $error_msg = 'Description field has not to be empty.';
    }
}

if (isset($_POST['read-submit'])) {
    if (isset($_POST['read-id']) && strlen(trim($_POST['read-id'])) > 0) {
        $id = $_POST['read-id'];
        $taskManager = new TaskManager();
        $read_task = $taskManager->read($id, $user_id);
        if (!$read_task) {
            $error_msg = 'Task with id #'.$id.' not found.';
        }
        $userRequestsManager = new UserRequestsManager();
        $ur_id = $userRequestsManager->isExistsForUser($user_id);
        $userRequestsManager->update($ur_id, 'read_request', $user_id);
        $_POST = array();
    }
    else {
        $error_msg = 'Id field has not to be empty.';
    }
}

if (isset($_POST['update-submit'])) {
    if (isset($_POST['update-id']) && isset($_POST['update-description']) && strlen(trim($_POST['update-description'])) > 0) {
        $description = $_POST['update-description'];
        $id = $_POST['update-id'];
        $taskManager = new TaskManager();
        $updated_tasks = $taskManager->update($id, $description, $user_id);
        $_POST = array();
        $confirm_msg = 'The task has been updated.';
        $userRequestsManager = new UserRequestsManager();
        $ur_id = $userRequestsManager->isExistsForUser($user_id);
        $userRequestsManager->update($ur_id, 'update_request', $user_id);
    } else {
        $error_msg = 'Description and Id fields have not to be empty.';
    }
}

if (isset($_POST['delete-submit'])) {
    if (isset($_POST['delete-id'])) {
        $id = $_POST['delete-id'];
        $taskManager = new TaskManager();
        $deleted_tasks = $taskManager->delete($id, $user_id);
        $_POST = array();
        $confirm_msg = 'The task has been deleted.';
        $userRequestsManager = new UserRequestsManager();
        $ur_id = $userRequestsManager->isExistsForUser($user_id);
        $userRequestsManager->update($ur_id, 'delete_request', $user_id);
    } else {
        $error_msg = 'Id field has not to be empty.';
    }
}

?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <?php require_once('html/head.html'); ?>
    <title>Project 4 - Task Management</title>
</head>
<body>
<?php require_once('html/header.php'); ?>
<main>
    <div class="container-fluid">
        <h3 class="py-3">Task Management for <?php echo $user_id; ?></h3>
        <?php if (!empty($error_msg)) : ?>
            <div class="alert alert-danger d-flex align-items-center" role="alert" id="error_msg">
                <i class="fas fa-exclamation-triangle text-danger me-3"></i>
                <div>
                    <?php echo $error_msg; ?>
                </div>
            </div>
        <?php endif; ?>
        <?php if (!empty($confirm_msg)) : ?>
            <div class="alert alert-warning d-flex align-items-center" role="alert" id="confirm_msg">
                <i class="fas fa-check-circle text-success me-3"></i>
                <div>
                    <?php echo $confirm_msg; ?>
                </div>
            </div>
        <?php endif; ?>
        <ul class="nav nav-pills" id="pills-tab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link p4-nav-link active" id="task-create-tab" data-bs-toggle="pill" data-bs-target="#task-create"
                        type="button" role="tab" aria-controls="pills-task-create" aria-selected="true">Create Task</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link p4-nav-link" id="task-read-tab" data-bs-toggle="pill" data-bs-target="#task-read"
                        type="button" role="tab" aria-controls="pills-task-read" aria-selected="false">Read Task</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link p4-nav-link" id="task-read-all-tab" data-bs-toggle="pill" data-bs-target="#task-read-all"
                        type="button" role="tab" aria-controls="pills-task-read-all" aria-selected="false">Read All Tasks</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link p4-nav-link" id="task-update-tab" data-bs-toggle="pill" data-bs-target="#task-update"
                        type="button" role="tab" aria-controls="pills-task-update" aria-selected="false">Update Task</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link p4-nav-link" id="task-delete-tab" data-bs-toggle="pill" data-bs-target="#task-delete"
                        type="button" role="tab" aria-controls="pills-task-delete" aria-selected="false">Delete Task</button>
            </li>
        </ul>
        <hr class="border-1 border-top border-p4-accent">
        <div class="tab-content" id="pills-tabContent">
            <div class="tab-pane fade show active" id="task-create" role="tabpanel" aria-labelledby="task-create-tab">
                <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                    <div class="row mb-3 mx-2">
                        <label for="create-description" class="col-sm-2 col-form-label fw-bold">Description:</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="create-description" name="create-description">
                        </div>
                    </div>
                    <button type="submit" class="btn p4-btn" name="create-submit">Create Task</button>
                </form>
            </div>
            <div class="tab-pane fade" id="task-read" role="tabpanel" aria-labelledby="task-read-tab">
                <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                    <div class="row mb-3 mx-2">
                        <label for="read-id" class="col-sm-2 col-form-label fw-bold">Id:</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="read-id" name="read-id">
                        </div>
                    </div>
                    <button type="submit" class="btn p4-btn" name="read-submit">Read Task</button>
                </form>
                <?php if (isset($read_task)) : ?>
                <h4 class="pt-4">Task for id: <?php echo $read_task->__get('id'); ?></h4>
                <hr class="border-1 border-top border-p4-accent mt-0">
                <pre class="bg-pre"><?php echo json_encode($read_task, JSON_PRETTY_PRINT); ?></pre>
                <?php endif; ?>
            </div>
            <div class="tab-pane fade" id="task-read-all" role="tabpanel" aria-labelledby="task-read-all-tab">
                <h3 class="pt-3 mb-0">All Tasks for <?php echo $user_id; ?></h3>
                <hr class="border-1 mt-1 mb-3 border-top border-p4-accent">

                <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                    <button type="submit" class="btn p4-btn" name="read-all-submit">Read All Tasks</button>
                </form>
                <?php if (isset($all_tasks)) :?>
                <pre class="bg-pre"><?php echo json_encode($all_tasks, JSON_PRETTY_PRINT); ?></pre>
                <hr class="border-1 mt-0 mb-3 border-top border-p4-accent">
                <?php endif; ?>
            </div>
            <div class="tab-pane fade" id="task-update" role="tabpanel" aria-labelledby="task-update-tab">
                <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                    <div class="row mb-3 mx-2">
                        <label for="update-id" class="col-sm-2 col-form-label fw-bold">Id:</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="update-id" name="update-id">
                        </div>
                    </div>
                    <div class="row mb-3 mx-2">
                        <label for="update-description" class="col-sm-2 col-form-label fw-bold">Description:</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="update-description" name="update-description">
                        </div>
                    </div>
                    <button type="submit" class="btn p4-btn" name="update-submit">Update Task</button>
                </form>
                <?php if (isset($updated_tasks)) : ?>
                    <h4 class="pt-4">Number of Rows Updated:</h4>
                    <hr class="border-1 border-top border-p4-accent mt-0">
                    <pre class="bg-pre"><?php echo $updated_tasks; ?></pre>
                <?php endif; ?>
            </div>
            <div class="tab-pane fade" id="task-delete" role="tabpanel" aria-labelledby="task-delete-tab">
                <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                    <div class="row mb-3 mx-2">
                        <label for="delete-id" class="col-sm-2 col-form-label fw-bold">Id:</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="delete-id" name="delete-id">
                        </div>
                    </div>
                    <button type="submit" class="btn p4-btn" name="delete-submit">Delete Task</button>
                </form>
                <?php if (isset($deleted_tasks)) : ?>
                    <h4 class="pt-4">Number of Rows Deleted:</h4>
                    <hr class="border-1 border-top border-p4-accent mt-0">
                    <pre class="bg-pre"><?php echo $deleted_tasks; ?></pre>
                <?php endif; ?>
            </div>
        </div>


    </div>
</main>
<?php require_once('html/footer.html'); ?>

<script>
    if (window.localStorage.getItem('tab')) {
        let id = window.localStorage.getItem('tab');
        let tabElement = document.getElementById(id);
        let tabTrigger = new bootstrap.Tab(tabElement);
        tabTrigger.show();
    }
    let triggerTabList = document.querySelectorAll("button[role='tab']");
    triggerTabList.forEach((tabElement) => {
        tabElement.addEventListener('click', function (event) {
            let id = tabElement.id;
            window.localStorage.setItem('tab', id);
            let error = document.getElementById('error_msg');
            let confirm = document.getElementById('confirm_msg');
            if (error) {
                console.log(1);
            }
            if (confirm) {
                confirm.classList.add("d-none");
            }
            // document.getElementById('error_msg').classList.add('d-none');
            // document.getElementById('confirm_msg').classList.add('d-none');
        })
    })
</script>
</body>
</html>