<?php
    require_once('classes/UserManager.php');
require_once('classes/User.php');

    function login($username, $password): User | string {
        $userManager = new UserManager();
        $message = '';
        $user = $userManager->getUserByUsername($username);
        if (!empty($user) && gettype($user) == 'object') {
            $user_password = $user->__get('password');
            if ($user_password == sha1($password)) {
                $user->__set('password', '');
                return $user;
            } else {
                $message = 'Wrong password';
            }
        } else {
            $message = "Wrong username";
        }
        return $message;
    }

    function register($username, $password): bool {
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

?>