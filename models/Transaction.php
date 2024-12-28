<?php

namespace Models;

class Transaction {
    private string $id;
    private string $id_account;
    private string $date;
    private string $title;
    private string $bank_date;
    private array $categories;
    private string $amount;

    public function __construct(string $id_account, string $date, string $title, string $bank_date, string $amount) {
        $this->createId();
        $this->id_account = $id_account;
        $this->date = $date;
        $this->title = $title;
        $this->bank_date = $bank_date;
        $this->amount = $amount;
    }

    /**
     * Summary of createId
     * Create a unique id for the transaction
     * @return void
     */
    private function createId() : void {
        $this->id = uniqid("transaction_");
    }

    public function __toString() : string {
        echo "<div class='transaction'>";
            echo "<div class='date'>".$this->getDate()."</div>";
            echo "<div class='title'>".$this->getTitle()."</div>";
            echo "<div class='date'>".$this->getBankDate()."</div>";
            for ($i = 0; $i < $this->getNbOfCat(); $i++) {
                echo "<div class='cat_{$i}'>".$this->getCategories()[$i]->getName()."</div>";
            }
            echo "<div class='amount'>".$this->getAmount()."</div>";
            echo "<a href='index.php?action=del-transaction&id={$this->getIdAccount()}&id_transaction={$this->getId()}'>Supprimer</a>";
            echo "<a href='index.php?action=edit-transaction&id={$this->getIdAccount()}&id_transaction={$this->getId()}'>Edit</a>";
        echo "</div>";
        return "This the display of a transaction !";
    }

    public function getNbOfCat() : int {
        return count($this->getCategories());
    }

    //Getters
    public function getId() : string {
        return $this->id;
    }
    public function getIdAccount() : string {
        return $this->id_account;
    }
    public function getDate() : string {
        return $this->date;
    }
    public function getTitle() : string {
        return $this->title;
    }
    public function getBankDate() : string {
        return $this->bank_date;
    }
    public function getCategories() : array {
        return $this->categories;
    }
    public function getAmount() : string {
        return $this->amount;
    }

    //Setters
    public function setId(string $id) : void {
        $this->id = $id;
    }
    public function setIdAccount(string $id_account) : void {
        $this->id_account = $id_account;
    }
    public function setDate(string $date) : void {
        $this->date = $date;
    }
    public function setTitle(string $title) : void {
        $this->title = $title;
    }
    public function setBankDate(string $bank_date) : void {
        $this->bank_date = $bank_date;
    }
    public function setCategories(array $categories) : void {
        $this->categories = $categories;
    }
    public function setAmount(string $amount) : void {
        $this->amount = $amount;
    }
}

?>