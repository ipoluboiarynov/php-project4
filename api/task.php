<?php
require_once('managers/TaskManager.php');
require_once('managers/UserManager.php');

$http_verb = $_SERVER['REQUEST_METHOD'];
$task_manager = new TaskManager();
$user_manager = new UserManager();

if (isset($_SERVER['HTTP_COOKIE'])) {
    $user_id = $user_manager->getUserIdByApiKey($_SERVER['HTTP_COOKIE']);
    if ($user_id == 0) {
        echo 'api key is not valid';
        return;
    }
} else {
    echo 'Access denied';
    return;
}

switch ($http_verb) {
    case "GET":
        if (isset($_GET['id'])) {
            try {
                $task = $task_manager->read($_GET['id'], $user_id);
                echo json_encode($task, JSON_PRETTY_PRINT);
            } catch (Exception $e) {
                echo new Exception('Task not found. ' .$e);
            }
        } else {
            try {
                $tasks = $task_manager->readAll($user_id);
                echo json_encode($tasks, JSON_PRETTY_PRINT);
            } catch (Exception $e) {
                echo new Exception('Tasks not found.' . $e);
            }
        }
        break;
    case "POST":
        $body = json_decode(file_get_contents("php://input"));
        if (isset($body->description)) {
            try {
                echo $task_manager->create($body->description, $user_id);
            } catch (Exception $e) {
                echo new Exception('Invalid HTTP POST request parameters.' . $e);
            }
        }
        break;
    case "PUT":
        $body = json_decode(file_get_contents("php://input"));
        if (isset($body->id) && isset($body->description)) {
            try {
                echo $task_manager->update($body->id, $body->description, $user_id);
            } catch (Exception $e) {
                echo new Exception("Invalid HTTP UPDATE request parameters. " .$e);
            }
        }
        break;
    case "DELETE":
        $body = json_decode(file_get_contents("php://input"));
        if (isset($body->id)) {
            try {
                echo $task_manager->delete($body->id, $user_id);
            } catch (Exception $e) {
                echo new Exception("Invalid HTTP DELETE request parameters. ".$e);
            }
        }
        break;
    default:
        echo new Exception("Unsupported HTTP request.");
        break;
}
?>
