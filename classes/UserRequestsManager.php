<?php
require_once('UserRequests.php');
require_once('var/constants.php');

class UserRequestsManager {

    public function create($user_id): int {

        $db = new PDO(sprintf("mysql:host=%s;dbname=%s", DB_DOMAIN, DB_NAME),
            DB_USER, DB_PASSWORD);

        $sql = "INSERT INTO user_requests (`user_id`) VALUES (:user_id)";

        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        try {
            $query = $db->prepare($sql);
            $query->bindParam(':user_id', $user_id);
            $query->execute();
        } catch (Exception $ex) {
            echo "{$ex->getMessage()}<br/>";
        }

        return $db->lastInsertId();
    }

    public function isExistsForUser($user_id): int {
        $db = new PDO(sprintf("mysql:host=%s;dbname=%s", DB_DOMAIN, DB_NAME),
            DB_USER, DB_PASSWORD);

        $sql = "SELECT * FROM user_requests WHERE user_id=:user_id LIMIT 1";

        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        try {
            $query = $db->prepare($sql);
            $query->bindParam(':user_id', $user_id);
            $query->execute();
            $result_arr = $query->fetchAll(PDO::FETCH_CLASS, 'UserRequests');
            if (count($result_arr) > 0) {
                return $result_arr[0]->__get('id');
            }
        } catch (Exception $ex) {
            echo "{$ex->getMessage()}<br/>";
        }

        return $this->create($user_id);
    }

    public function readAll(): array {
        $db = new PDO(sprintf("mysql:host=%s;dbname=%s", DB_DOMAIN, DB_NAME),
            DB_USER, DB_PASSWORD);

        $sql = "SELECT * FROM user_requests";

        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $results = array();

        try {
            $query = $db->prepare($sql);
            $query->execute();
            $results = $query->fetchAll(PDO::FETCH_CLASS, 'UserRequests');
        } catch (Exception $ex) {
            echo "{$ex->getMessage()}<br/>";
        }

        return $results;
    }

    public function update($id, $name, $user_id): int {

        $db = new PDO(sprintf("mysql:host=%s;dbname=%s", DB_DOMAIN, DB_NAME),
            DB_USER, DB_PASSWORD);


        $sql = "UPDATE user_requests SET `{$name}`=`{$name}` + 1 WHERE `id`=:id AND `user_id`=:user_id";

        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $rows_affected = 0;

        try {
            $query = $db->prepare($sql);
            $query->bindParam(':user_id', $user_id);
            $query->bindParam(':id', $id);
            $query->execute();
            $rows_affected = $query->rowCount();
        } catch (Exception $ex) {
            echo "{$ex->getMessage()}<br/>";
        }

        return  $rows_affected;
    }

    public function delete($id, $user_id): int {

        $db = new PDO(sprintf("mysql:host=%s;dbname=%s", DB_DOMAIN, DB_NAME),
            DB_USER, DB_PASSWORD);

        $sql = "DELETE FROM user_requests WHERE `id`=:id AND `user_id`=:user_id";

        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $rows_affected = 0;

        try {
            $query = $db->prepare($sql);
            $query->bindParam(':id', $id);
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
