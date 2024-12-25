<?php

namespace Models;

class Transaction {
    private string $id;
    private string $id_account;
    private string $name;
    private string $date;
    private string $title;
    private string $bank_date;
    private array $categories;
    private string $amount;

    public function __construct(string $id_account, string $name, string $date, string $title, string $bank_date, string $amount) {
        $this->createId();
        $this->id_account = $id_account;
        $this->name = $name;
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
    public function getDate() : string {
        return $this->date;
    }
    public function getTitle() : string {
        return $this->title;
    }
    public function getBankDate() : string {
        return $this->bank_date;
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
    public function setName(string $name) : void {
        $this->name = $name;
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
    public function setAmount(string $amount) : void {
        $this->amount = $amount;
    }
}

?>