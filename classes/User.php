<?php
class User {
    private int $id;
    private string $name;
    private string $password;
    private string $api_key;
    private string $created;

    // Magic Get/Set
    public function __get($ivar) {
        return $this->$ivar;
    }

    public function __set($ivar, $value) {
        $this->$ivar = $value;
    }
}
?>