<?php
require_once('managers/UserRequestsManager.php');
require_once('managers/UserManager.php');

$http_verb = $_SERVER['REQUEST_METHOD'];
$request_manager = new UserRequestsManager();
$user_manager =  new UserManager();

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
        try {
            $requests = $request_manager->readAll();
            echo $requests;
        } catch (Exception $e) {
            echo new Exception('Requests not found. ' .$e);
        }
        break;
    case "PUT":
        $body = json_decode(file_get_contents("php://input"));
        if (isset($body->type)) {
            try {
                echo $request_manager->update($body->type, $user_id);
            } catch (Exception $e) {
                echo new Exception("Invalid HTTP UPDATE request parameters. " . $e);
            }
        }
        break;
    default:
        echo new Exception();
}
?>