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
            ORDER BY t.date, t.title;
        ";
        $query = $this->execRequest($sql, params: ["id_account" => $id_account]);

        if (!$query) {
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

    public function getMTopTransactions(string $id, int $limit, int $month) : array {
        $sql = "
            SELECT *
            FROM transactions 
            WHERE id_account=:id AND EXTRACT(MONTH FROM date)=:month
            ORDER BY ABS(amount) DESC
            LIMIT $limit
        ";
        $query = $this->execRequest($sql, ["id" => $id, "month" => $month]);

        if (!$query) {
            throw new Exception("Erreur lors de la recupération ne base de donnée des top transactions par mois");
        }

        $top_transactions = [];
        foreach($query as $row) {
            $transaction = new Transaction($row["id_account"], $row["date"], $row["title"], $row["bank_date"], $row["amount"]);
            $transaction->setId($row["id"]);
            $categories = $this->getCategoriesOfTransaction($row["id"]);
            $transaction->setCategories($categories);

            $top_transactions[] = $transaction;
        }

        return $top_transactions;
    } 

    public function getBalanceEndMonth(string $id, int $month) : float {
        $sql = "
            SELECT SUM(amount) as balance
            FROM transactions
            WHERE id_account=:id
            AND EXTRACT(MONTH FROM date) <= :month
        ";
        $query = $this->execRequest($sql, ["id" => $id, "month" => $month]);

        if (!$query) {
            throw new Exception("Erreur lors de la récupération du solde à la fin d'un mois en base de donnée !");
        }

        $row = $query->fetch();

        return $row["balance"] ?? 0;
    }

    public function getExpensesPerMonthOfCategory(string $id_account, string $id_category, array $months) : array {
        $sql = "
            SELECT SUM(t.amount) AS expenses, EXTRACT(MONTH FROM t.date) as month
            FROM transactions t
            INNER JOIN transactions_categories tc ON tc.id_transaction = t.id
            WHERE tc.id_category = :id_category 
            AND t.id_account = :id_account
            AND t.amount < 0
            GROUP BY EXTRACT(MONTH FROM t.date)
            ORDER BY EXTRACT(MONTH FROM t.date)
        ";
        $query = $this->execRequest($sql, [
            "id_account" => $id_account,
            "id_category" => $id_category,
        ]);

        if (!$query) {
            throw new Exception("Erreur lors de la récupération en base de donnée des dépenses par mois d'une catégorie");
        }

        $expenses_per_month = [];
        foreach($query as $row) {
            $expenses_per_month[$row["month"]] = $row["expenses"];
        }

        $S = 0;
        foreach($expenses_per_month as $expense) {
            $S+= $expense;
        }

        foreach($months as $month => $num) {
            if (!isset($expenses_per_month[$num])) {
                $expenses_per_month[$num] = 0;
            }
        }

        $expenses_per_month["sum"] = $S;

        return $expenses_per_month;
    }

    public function getRevenuesPerMonthOfCategory(string $id_account, string $id_category, array $months) : array {
        $sql = "
            SELECT SUM(t.amount) AS expenses, EXTRACT(MONTH FROM t.date) as month
            FROM transactions t
            INNER JOIN transactions_categories tc ON tc.id_transaction = t.id
            WHERE tc.id_category = :id_category 
            AND t.id_account = :id_account
            AND t.amount >= 0
            GROUP BY EXTRACT(MONTH FROM t.date)
            ORDER BY EXTRACT(MONTH FROM t.date)
        ";
        $query = $this->execRequest($sql, [
            "id_account" => $id_account,
            "id_category" => $id_category,
        ]);

        if (!$query) {
            throw new Exception("Erreur lors de la récupération en base de donnée des recettes par mois d'une catégorie");
        }

        $expenses_per_month = [];
        foreach($query as $row) {
            $expenses_per_month[$row["month"]] = $row["expenses"];
        }

        $S = 0;
        foreach($expenses_per_month as $expense) {
            $S+= $expense;
        }

        foreach($months as $month => $num) {
            if (!isset($expenses_per_month[$num])) {
                $expenses_per_month[$num] = 0;
            }
        }

        $expenses_per_month["sum"] = $S;

        return $expenses_per_month;
    }
}

?>