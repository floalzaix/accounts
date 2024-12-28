<?php

namespace Models;

use Exception;

class Category {
    private string $id;
    private string $id_account;
    private string $name;
    private int $level;
    private array $childs;

    public function __construct(string $name, string $id_account) {
        $this->createId();
        $this->id_account = $id_account;
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
    public function getIdAccount() : string {
        return $this->id_account;
    }
    public function getName() : string {
        return $this->name;
    }
    public function getLevel() : int {
        return $this->level;
    }
    public function getChilds() : array {
        return $this->childs;
    }

    //Setters
    public function setId(string $id) : void {
        $this->id = $id;
    }
    public function setIdAccount(string $id_account) : void {
        $this->id_account = $id_account;
    }
    public function setName(string $name) : void {
        $this->name = $name;
    }
    public function setLevel(int $level) : void {
        $this->level = $level;
    }
    public function setChilds(array $childs) : void {
        foreach($childs as $child) {
            if ($child->getLevel() != $this->getLevel()+1) {
                throw new Exception("Erreur : la catégorie ".$this->getName()." niveau ".$this->getLevel()." a une catégorie enfant ".$child->getName()." sur le niveau ".$child->getLevel());
            }
        }
        $this->childs = $childs;
    }
}

?>