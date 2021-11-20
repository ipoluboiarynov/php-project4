<?php
class Task implements JsonSerializable {
    private int $id;
    private string $description;
    private int $user_id;

    // Magic Get/Set
    public function __get($ivar) {
        return $this->$ivar;
    }

    public function __set($ivar, $value) {
        $this->$ivar = $value;
    }

    public function jsonSerialize() {
        return get_object_vars($this);
    }
}
?>
