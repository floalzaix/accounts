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

    public function __toString() : string {
        echo "<div class='account'>";
            echo "<a class='name' href='index.php?action=inputs&id={$this->getId()}'>";
                echo "<img alt='Icone compte' src='/public/images/icones/doubles_cartes.png' />";
                echo "<div id='name'>".$this->getName()."</div>";
            echo "</a>";
            echo "<a class='button' href='index.php?action=del-account&id={$this->getId()}'>";
                echo "<img alt='Bouton supprimer' src='/public/images/icones/supprimer.png' />";
            echo "</a>";
        echo "</div>";
        return "This is the display of the accont intitled : ".$this->getName();
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