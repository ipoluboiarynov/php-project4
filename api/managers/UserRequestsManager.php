<?php
require_once('./classes/UserRequests.php');
require_once('var/constants.php');
require_once('UserManager.php');

class UserRequestsManager {

    public function readAll() {
        $db = new PDO(sprintf("mysql:host=%s;dbname=%s", DB_DOMAIN, DB_NAME),
            DB_USER, DB_PASSWORD);
        $sql = "SELECT * FROM user_requests";
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        try {
            $user_manager = new UserManager();
            $query = $db->prepare($sql);
            $query->execute();
            $result_arr = $query->fetchAll(PDO::FETCH_CLASS, 'UserRequests');
            $new_arr = array();
            foreach ($result_arr as $result) {
                $username = $user_manager->getUsernameById($result->__get('user_id'));
                array_push($new_arr, array(
                    "username" => $username,
                    "create_request" => $result->__get('create_request'),
                    "read_request" => $result->__get('read_request'),
                    "readAll_request" => $result->__get('readAll_request'),
                    "update_request" => $result->__get('update_request'),
                    "delete_request" => $result->__get('delete_request'),
                ));
            }
            return json_encode($new_arr);
        } catch (Exception $ex) {
            echo "{$ex->getMessage()}<br/>";
        }
    }

    public function update($type, $user_id) {
        $db = new PDO(sprintf("mysql:host=%s;dbname=%s", DB_DOMAIN, DB_NAME),
            DB_USER, DB_PASSWORD);
        $sql = "UPDATE user_requests SET `{$type}`=`{$type}` + 1 WHERE `user_id`=:user_id";
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $rows_affected = 0;
        try {
            $query = $db->prepare($sql);
            $query->bindParam(':user_id', $user_id);
            $query->execute();
            $rows_affected = $query->rowCount();
        } catch (Exception $ex) {
            echo "{$ex->getMessage()}<br/>";
        }
        return  $rows_affected;
    }
}
?>
