<?php
require_once('managers/UserManager.php');

$http_verb = $_SERVER['REQUEST_METHOD'];
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
    case "PUT":
        $body = json_decode(file_get_contents("php://input"));
        if (isset($body->api_key)) {
            try {
                echo $user_manager->changeApiKey($user_id);
            } catch (Exception $e) {
                echo new Exception("Invalid HTTP UPDATE request parameters.");
            }
        }

        if (isset($body->new) && isset($body->current)) {
            try {
                $confirm = $user_manager->checkPassword($user_id, $body->current);
                if ($confirm == 1) {
                    echo $user_manager->changePassword($user_id, $body->new);
                } else {
                    echo 'Wrong Password';
                }
            } catch (Exception $e) {
                echo new Exception("Invalid HTTP UPDATE request parameters.");
            }
        }
        break;
    case "DELETE":
        if (isset($user_id)) {
            try {
                echo $user_manager->delete($user_id);
            } catch (Exception $e) {
                echo new Exception("Invalid HTTP DELETE request parameters.");
            }
        }
        break;
    default:
        echo new Exception("Unsupported HTTP request.");
        break;
}
?>