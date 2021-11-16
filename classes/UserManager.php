<?php
    require_once('ITaskManager.php');
    require_once('User.php');
    require_once('var/constants.php');

    class UserManager {

        public function create($username, $password): string {
            $api_key = $this->generate_api_key(API_KEY_LENGTH);
            $created = date('Y-m-d H:i:s');
            $user_id = null;
            $db = new PDO(sprintf("mysql:host=%s;dbname=%s", DB_DOMAIN, DB_NAME),
                DB_USER, DB_PASSWORD);

            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            try {
                $db->beginTransaction();

                $sqlOne = "INSERT INTO user(`username`, `password`, `api_key`, `created`) VALUES(:username, SHA(:password), :api_key, :created)";
                $queryOne = $db->prepare($sqlOne);
                $queryOne->bindParam(':username', $username);
                $queryOne->bindParam(':api_key', $api_key);
                $queryOne->bindParam(':password', $password);
                $queryOne->bindParam(':created', $created);
                $queryOne->execute();
                $user_id = $db->lastInsertId();

                $sqlTwo = "INSERT INTO user_requests (`user_id`) VALUES (:user_id)";
                $query2 = $db->prepare($sqlTwo);
                $query2->bindParam(':user_id', $user_id);
                $query2->execute();

                $db->commit();

            } catch (Exception $ex) {
                echo "{$ex->getMessage()}<br/>";
                $db->rollBack();
            }
            return $user_id;
        }

        public function read($id): User | null {
            $db = new PDO(sprintf("mysql:host=%s;dbname=%s", DB_DOMAIN, DB_NAME),
                DB_USER, DB_PASSWORD);

            $sql = "SELECT * FROM user WHERE id=:id LIMIT 1";

            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $result = new User();

            try {
                $query = $db->prepare($sql);
                $query->bindParam(':id', $id);
                $query->execute();
                $result_arr = $query->fetchAll(PDO::FETCH_CLASS, 'User');
                $result = $result_arr[0];
            } catch (Exception $ex) {
                echo "{$ex->getMessage()}<br/>";
            }
            return $result ?? null;
        }

        public function getUserByUsername($username) {
            $db = new PDO(sprintf("mysql:host=%s;dbname=%s", DB_DOMAIN, DB_NAME),
                DB_USER, DB_PASSWORD);

            $sql = "SELECT * FROM user WHERE username=:username LIMIT 1";

            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            try {
                $query = $db->prepare($sql);
                $query->bindParam(':username', $username);
                $query->execute();
                $result_arr = $query->fetchAll(PDO::FETCH_CLASS, 'User');
                if (isset($result_arr[0])) {
                    return $result_arr[0];
                }
            } catch (Exception $ex) {
                echo "{$ex->getMessage()}<br/>";
            }
            return null;
        }

        public function changePassword($id, $new_password): int {

            $db = new PDO(sprintf("mysql:host=%s;dbname=%s", DB_DOMAIN, DB_NAME),
                DB_USER, DB_PASSWORD);

            $sql = "UPDATE user SET `password`=SHA(:password) WHERE `id`=:id";

            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $rows_affected = 0;

            try {
                $query = $db->prepare($sql);
                $query->bindParam(':id', $id);
                $query->bindParam(':password', $new_password);
                $query->execute();
                $rows_affected = $query->rowCount();
            } catch (Exception $ex) {
                echo "{$ex->getMessage()}<br/>";
            }

            return  $rows_affected;
        }

        public function changeApiKey($id): string | null {

            $api_key = $this->generate_api_key(API_KEY_LENGTH);
            $db = new PDO(sprintf("mysql:host=%s;dbname=%s", DB_DOMAIN, DB_NAME),
                DB_USER, DB_PASSWORD);

            $sql = "UPDATE user SET `api_key`=:api_key WHERE `id`=:id";

            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $rows_affected = 0;

            try {
                $query = $db->prepare($sql);
                $query->bindParam(':id', $id);
                $query->bindParam(':api_key', $api_key);
                $query->execute();
                $rows_affected = $query->rowCount();
            } catch (Exception $ex) {
                echo "{$ex->getMessage()}<br/>";
            }

            if ($rows_affected > 0) { return $api_key; }
            return null;
        }

        public function checkPassword($password, $id): bool {
            $user_by_id = $this->read($id);
            if ($user_by_id) {
                $user_by_id->__get('password') == $password;
                return true;
            }
            return false;
        }

        public function checkApiKey($id, $api_key): bool {
            $db = new PDO(sprintf("mysql:host=%s;dbname=%s", DB_DOMAIN, DB_NAME),
                DB_USER, DB_PASSWORD);

            $sql = "SELECT * FROM user WHERE id=:id AND api_key=:api_key LIMIT 1";

            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            try {
                $query = $db->prepare($sql);
                $query->bindParam(':id', $id);
                $query->bindParam(':api_key', $api_key);
                $query->execute();
                $result_arr = $query->fetchAll(PDO::FETCH_CLASS, 'User');
                if (count($result_arr) == 1) {
                    return true;
                }
            } catch (Exception $ex) {
                echo "{$ex->getMessage()}<br/>";
            }
            return false;
        }

        public function delete($id): int {
            $db = new PDO(sprintf("mysql:host=%s;dbname=%s", DB_DOMAIN, DB_NAME),
                DB_USER, DB_PASSWORD);
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $rows_affected = 0;
            try {
                $db->beginTransaction();

                $sqlOne = "DELETE FROM task WHERE `user_id`=:id";
                $queryOne = $db->prepare($sqlOne);
                $queryOne->bindParam(':id', $id);
                $queryOne->execute();

                $sqlTwo = "DELETE FROM user_requests WHERE `user_id`=:user_id";
                $queryTwo = $db->prepare($sqlTwo);
                $queryTwo->bindParam(':user_id', $id);
                $queryTwo->execute();

                $sqlThree = "DELETE FROM user WHERE id=:id";
                $queryThree = $db->prepare($sqlThree);
                $queryThree->bindParam(':id', $id);
                $queryThree->execute();
                $rows_affected = $queryThree->rowCount();

                $db->commit();
            } catch (Exception $ex) {
                echo "{$ex->getMessage()}<br/>";
                $db->rollBack();
            }
            return  $rows_affected;
        }

        function generate_api_key($length): string {
            $random = '';
            for ($i = 0; $i < $length; $i++) {
                $random_number = rand(1, 2);
                if ($random_number == 1) {
                    $symbol = chr(mt_rand(48, 57));
                } else $symbol = chr(mt_rand(97, 122));
                $random .= $symbol;
            }
            return $random;
        }
    }
?>