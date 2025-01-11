<?php

namespace Models;

use Exception;

class Category {
    private string $id;
    private string $id_account;
    private string $name;
    private int $level;
    private array $childs;
    private ?string $id_parent;

    private bool $displayed = false;

    public function __construct(string $name, string $id_account, ?string $id_parent) {
        $this->createId();
        $this->id_account = $id_account;
        $this->name = $name;
        $this->id_parent = $id_parent;
    }

    /**
     * Summary of createId
     * Create a unique id for the category
     * @return void
     */
    private function createId() : void {
        $this->id = uniqid("category_");
    }

    public function __toString() : string {
        if (!$this->displayed) {
            echo "<div class='category'>";
            echo "<div class='category_name'>".$this->getName()."</div>";
            echo "<a href='index.php?action=edit-category&id={$this->getIdAccount()}&id_cat={$this->getId()}'>Modifier</a>";
            echo "<a href='index.php?action=del-category&id={$this->getIdAccount()}&id_cat={$this->getId()}'>Supprimer</a>";
            foreach($this->getChilds() as $child) {
                $child->__toString();
            }
            echo "</div>";
            $this->displayed = true;
        }
        return "This is the display of a category : ".$this->getName();
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
    public function getIdParent() : ?string {
        return $this->id_parent;
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
        $this->childs = $childs;
    }
    public function setIdParent(?string $id_parent) : void {
        $this->id_parent = $id_parent;
    }
}

?>