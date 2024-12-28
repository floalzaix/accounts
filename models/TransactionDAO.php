<?php

namespace Models;

use Models\Transaction;
use Models\TransactionCategoriesDAO;
use Exception;

class TransactionDAO extends TransactionCategoriesDAO {
    public function getAllOfAccount(string $id_account) : array {
        $transactions = [];

        $sql = "
            SELECT t.id, t.id_account, t.date, t.title, t.bank_date, t.amount
            FROM transactions t 
            WHERE t.id_account=:id_account
            ORDER BY date;
        ";
        $query = $this->execRequest($sql, params: ["id_account" => $id_account]);

        if ($query == false) {
            throw new Exception("Erreur lors de la récupération de toutes les transactions en base de donnée.");
        }

        foreach($query as $row) {
            $transaction = new Transaction($row["id_account"], $row["date"], $row["title"], $row["bank_date"], $row["amount"]);
            $transaction->setId($row["id"]);
            $categories = $this->getCategoriesOfTransaction($row["id"]);
            $transaction->setCategories($categories);    

            $transactions[] = $transaction;
        }

        return $transactions;
    }

    public function getById(string $id) : ?Transaction {
        $sql = "SELECT * FROM transactions WHERE id=:id";
        $query = $this->execRequest($sql, ["id" => $id]);

        $transaction = null;

        if ($query == false) {
            throw new Exception("Erreur lors de la recupération en base de donnée d'une transaction");
        } elseif($query->rowCount() > 1) {
            throw new Exception("Il y a plus d'une transaction avec le même id");
        }

        if ($query->rowCount() == 1) {
            $row = $query->fetch();
            $transaction = new Transaction($row["id_account"], $row["date"], $row["title"], $row["bank_date"], $row["amount"]);
            $transaction->setId($row["id"]);
            $categories = $this->getCategoriesOfTransaction($id);
            $transaction->setCategories($categories);
        }

        return $transaction;
    }

    public function create(Transaction $transaction) : void {
        $sql = "INSERT INTO transactions(id, id_account, date, title, bank_date, amount) VALUES(:id, :id_account, :date, :title, :bank_date, :amount)";
        if ($this->getById($transaction->getId()) != null) {
            $sql = "UPDATE transactions SET id_account=:id_account, date=:date, title=:title, bank_date=:bank_date, amount=:amount WHERE id=:id";
        }

        $query = $this->execRequest($sql, [
            "id" => $transaction->getId(),
            "id_account" => $transaction->getIdAccount(),
            "date" => $transaction->getDate(),
            "title" => $transaction->getTitle(),
            "bank_date" => $transaction->getBankDate(),
            "amount" => $transaction->getAmount()
        ]);
        
        $this->setCategoriesOfTransaction($transaction->getId(), $transaction->getCategories());

        if ($query == false) {
            throw new Exception("Erreur lors de la création d'une transaction en base de donnée.");
        }
    }

    public function delete(string $id) : void {
        $sql = "DELETE FROM transactions WHERE id=:id";
        $query = $this->execRequest($sql, ["id" => $id]);

        if ($query == false) {
            throw new Exception("Erreur lors de la suppression d'une transaction en base de donnée.");
        }
    }
}

?>