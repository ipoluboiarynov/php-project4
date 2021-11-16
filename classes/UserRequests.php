<?php
class UserRequests {
    private int $id;
    private string $user_id;
    private string $create_request;
    private string $read_request;
    private string $read_all_request;
    private string $update_request;
    private string $delete_request;

    // Magic Get/Set
    public function __get($ivar) {
        return $this->$ivar;
    }

    public function __set($ivar, $value) {
        $this->$ivar = $value;
    }
}
?>