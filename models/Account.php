<?php

namespace Models;

class Account {
    private string $id;

    private string $id_user;
    private string $name;
    private int $nb_of_categories;

    public function __construct(string $name, string $id_user, int $nb_of_categories) {
        $this->createId();
        $this->id_user = $id_user;
        $this->name = $name;
        $this->nb_of_categories = $nb_of_categories;
    }

    /**
     * Summary of createId
     * Create a unique id for the account
     * @return void
     */
    private function createId() : void {
        $this->id = uniqid("account_");
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
    public function getNbOfCategories() : int {
        return $this->nb_of_categories;
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
    public function setNbOfCategories(int $nb_of_categories) : void {
        $this->nb_of_categories = $nb_of_categories;
    }
}

?>