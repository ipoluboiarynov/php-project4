<?php
require_once('managers/AuthManager.php');

$http_verb = $_SERVER['REQUEST_METHOD'];
$auth_manager = new AuthManager();

switch ($http_verb) {
    case "POST":
        $body = json_decode(file_get_contents("php://input"));
        if (isset($body->username) && isset($body->password) && isset($body->method)) {
            if ($body->method == 'register') {
                echo $result = $auth_manager->register($body->username, $body->password);
            } else if ($body->method == 'login') {
                $result = $auth_manager->login($body->username, $body->password);
                if (is_string($result)) {
                    echo $result;
                } else {
                    echo json_encode($result);
                }
            } else {
                echo 'No method added to POST';
            }
        } else {
            echo 'No username or password';
        }
        break;
    default:
        echo 'Wrong method';
        break;
}
?>