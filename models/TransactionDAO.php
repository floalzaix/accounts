<?php

namespace Models;

use Models\BasePDODAO;
use Models\Transaction;
use Exception;

class TransactionDAO extends BasePDODAO {
    public function getAllOfAccount(string $id_account) : array {
        $transactions = [];

        $sql = "
            SELECT * FROM transactions t 
            INNER JOIN transactions_categories tc ON tc.id_transaction=t.id
            INNER JOIN categories c ON c.id=tc.id_category
            WHERE t.id_account=:id_account
            ORDER BY date;
        ";
        $query = $this->execRequest($sql, ["id_account" => $id_account]);

        if ($query == false) {
            throw new Exception("Erreur lors de la récupération de toutes les transactions en base de donnée.");
        }

        foreach($query as $row) {
            $transaction = new Transaction($row["id_account"], $row["name"], $row["date"], $row["title"], $row["bank_date"], $row["amount"]);
            $transaction->setId($row["id"]);

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
            $transaction = new Transaction($row["id_account"], $row["name"], $row["date"], $row["title"], $row["bank_date"], $row["amount"]);
            $transaction->setId($row["id"]);
        }

        return $transaction;
    }

    public function create(Transaction $transaction) : void {
        $sql = "INSERT INTO transactions(id, id_account, name, date, title, bank_date, amount) VALUES(:id, :id_account, :name, :date, :title, :bank_date, :amount)";
        if ($this->getById($transaction->getId()) != null) {
            $sql = "UPDATE transactions SET id_account=:id_account, name=:name, date=:date, title=:title, bank_date=:bank_date, amount=:amount WHERE id=:id";
        }

        $query = $this->execRequest($sql, [
            "id" => $transaction->getId(),
            "id_account" => $transaction->getIdAccount(),
            "date" => $transaction->getDate(),
            "title" => $transaction->getTitle(),
            "bank_date" => $transaction->getBankDate(),
            "amount" => $transaction->getAmount()
        ]);

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