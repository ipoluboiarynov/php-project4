<?php
interface ITaskManager {

    public function create($description, $user_id);
    public function read($id, $user_id);
    public function readAll($user_id);
    public function update($id, $description, $user_id);
    public function delete($id, $user_id);
}
?>