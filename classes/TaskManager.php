<?php
    require_once('ITaskManager.php');
    require_once('Task.php');
    require_once('var/constants.php');

    class TaskManager implements ITaskManager {

        // add a record to the database and return the id of the newly inserted record
        public function create($description, $user_id): int {

            $db = new PDO(sprintf("mysql:host=%s;dbname=%s", DB_DOMAIN, DB_NAME),
                DB_USER, DB_PASSWORD);

            $sql = "INSERT INTO task (`description`, `user_id`) VALUES (:description, :user_id)";

            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            try {
                $query = $db->prepare($sql);
                $query->bindParam(':description', $description);
                $query->bindParam(':user_id', $user_id);
                $query->execute();
            } catch (Exception $ex) {
                echo "{$ex->getMessage()}<br/>";
            }

            return $db->lastInsertId();
        }

        // return the Task record that corresponds to the passed in $id parameter.
        // If no record exists, throw an Exception.
        // The Task record returns as a Class object
        public function read($id, $user_id): Task | null {
            $db = new PDO(sprintf("mysql:host=%s;dbname=%s", DB_DOMAIN, DB_NAME),
                DB_USER, DB_PASSWORD);

            $sql = "SELECT * FROM task WHERE id=:id AND user_id=:user_id LIMIT 1";

            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            try {
                $query = $db->prepare($sql);
                $query->bindParam(':id', $id);
                $query->bindParam(':user_id', $user_id);
                $query->execute();
                $result_arr = $query->fetchAll(PDO::FETCH_CLASS, 'Task');
                if (count($result_arr) > 0) {
                    $result = $result_arr[0];
                }

            } catch (Exception $ex) {
                echo "{$ex->getMessage()}<br/>";
            }
            return $result ?? null;
        }

        // return all of the Task records from the Task table.
        // The Task records returns as an array of Class objects
        public function readAll($user_id): array {
            $db = new PDO(sprintf("mysql:host=%s;dbname=%s", DB_DOMAIN, DB_NAME),
                DB_USER, DB_PASSWORD);

            $sql = "SELECT * FROM task WHERE `user_id`=:user_id";

            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $results = array();

            try {
                $query = $db->prepare($sql);
                $query->bindParam(':user_id', $user_id);
                $query->execute();
                $results = $query->fetchAll(PDO::FETCH_CLASS, 'Task');
            } catch (Exception $ex) {
                echo "{$ex->getMessage()}<br/>";
            }

            return $results;
        }

        // change the description of the Task with id = $id to the $newDesc.
        // Return the number of rows affected by the update
        public function update($id, $description, $user_id): int {

            $db = new PDO(sprintf("mysql:host=%s;dbname=%s", DB_DOMAIN, DB_NAME),
                DB_USER, DB_PASSWORD);

            $sql = "UPDATE task SET `description`=:description WHERE `id`=:id AND `user_id`=:user_id";

            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $rows_affected = 0;

            try {
                $query = $db->prepare($sql);
                $query->bindParam(':id', $id);
                $query->bindParam(':user_id', $user_id);
                $query->bindParam(':description', $description);
                $query->execute();
                $rows_affected = $query->rowCount();
            } catch (Exception $ex) {
                echo "{$ex->getMessage()}<br/>";
            }

            return  $rows_affected;
        }

        // remove the Task record with the id = $id.
        // Return the number of rows affected by the update
        public function delete($id, $user_id): int {

            $db = new PDO(sprintf("mysql:host=%s;dbname=%s", DB_DOMAIN, DB_NAME),
                DB_USER, DB_PASSWORD);

            $sql = "DELETE FROM task WHERE `id`=:id AND `user_id`=:user_id";

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
