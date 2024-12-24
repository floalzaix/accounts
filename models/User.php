<?php

namespace Models;

class User {
    private string $id;
    private string $name;
    private string $hash;

    public function __construct(string $name, string $hash) {
        $this->createId();
        $this->name = $name;
        $this->hash = $hash;
    }

    private function createId() : void {
        $this->id = uniqid("user_");
    }
    public function verifyPassword(string $pwd) : bool {
        return $this->hash == hash("sha256", $pwd);
    }

    public function setId(string $id) : void {
        $this->id = $id;
    }
    public function setName(string $name) : void {
        $this->name = $name;
    }
    public function setHash(string $hash) : void {
        $this->hash = $hash;
    }

    public function getId() : string {
        return $this->id;
    }
    public function getName() : string {
        return $this->name;
    }
    public function getHash() : string {
        return $this->hash;
    }
}

?>