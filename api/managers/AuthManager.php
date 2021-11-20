<?php
require_once('./managers/UserManager.php');
require_once('./classes/User.php');

class AuthManager {
    public function login($username, $password) {
        $userManager = new UserManager();
        $message = '';
        $user = $userManager->getUserByUsername($username);
        if (!empty($user) && gettype($user) == 'object') {
            $user_password = $user->__get('password');
            if ($user_password == sha1($password)) {
                $js = ["username" => $user->__get('username'), 'api_key' => $user->__get('api_key')];
                return json_encode($js);
            } else {
                $message = 'Wrong password';
            }
        } else {
            $message = "Wrong username";
        }
        return $message;
    }

    public function register($username, $password) {
        $userManager = new UserManager();
        $data = $userManager->getUserByUsername($username);
        if (empty($data)) {
            $id = $userManager->create($username, $password);
            if (!empty($id)) {
                return true;
            }
        }
        return false;
    }

}
?>