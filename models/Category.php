<?php

namespace Models;

class Category {
    private string $id;
    private string $id_user;
    private string $name;

    public function __construct(string $name, string $id_user) {
        $this->createId();
        $this->id_user = $id_user;
        $this->name = $name;
    }

    /**
     * Summary of createId
     * Create a unique id for the category
     * @return void
     */
    private function createId() : void {
        $this->id = uniqid("category_");
    }

    //Getters
    public function getId() : string {
        return $this->id;
    }
    public function getIdUser() : string {
        return $this->id_user;
    }
    public function getName() : string {
        return $this->name;
    }

    //Setters
    public function setId(string $id) : void {
        $this->id = $id;
    }
    public function setIdUser(string $id_user) : void {
        $this->id_user = $id_user;
    }
    public function setName(string $name) : void {
        $this->name = $name;
    }
}

?>