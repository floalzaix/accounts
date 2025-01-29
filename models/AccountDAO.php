<?php

namespace Models;

use Models\BasePDODAO;
use Exception;

class AccountDAO extends BasePDODAO {
    public function getAllOfUser(string $id_user) : array {
        $accounts = [];

        $sql = "SELECT * FROM accounts WHERE id_user=:id_user ORDER BY name";
        $query = $this->execRequest($sql, ["id_user" => $id_user]);

        foreach($query as $row) {
            $account = new Account($row["name"], $row["id_user"], $row["nb_of_categories"]);
            $account->setId($row["id"]);

            $accounts[] = $account;
        }

        return $accounts;
    }

    public function getById(string $id) : ?Account {
        $sql = "SELECT * FROM accounts WHERE id=:id";
        $query = $this->execRequest($sql, ["id" => $id]);

        if ($query == false) {
            throw new Exception("Erreur lors de la récupération d'un compte dans la base de donnée");
        }

        $account = null;

        if ($query->rowCount() == 1) {
            $row = $query->fetch();
            $account = new Account($row["name"], $row["id_user"], $row["nb_of_categories"]);
            $account->setId($row["id"]);
        }

        return $account;
    }

    public function create(Account $account) : void {
        $sql = "INSERT INTO accounts(id, id_user, name, nb_of_categories) VALUES (:id, :id_user, :name, :nb_of_categories)";
        if ($this->getById($account->getId()) != null) {
            $sql = "UPDATE accounts SET id_user=:id_user, name=:name, nb_of_categories=:nb_of_categories WHERE id=:id";
        }

        $query = $this->execRequest($sql, [
            "id" => $account->getId(),
            "id_user" => $account->getIdUser(),
            "name" => $account->getName(),
            "nb_of_categories" => $account->getNbOfCategories()
        ]);

        if ($query == false) {
            throw new Exception("Erreur lors de la création ou de la mise à jour d'un compte en base de donnée.");
        }
    }

    public function delete(string $id) : void {
        $sql = "DELETE FROM accounts WHERE id=:id";
        $query = $this->execRequest($sql, ["id" => $id]);

        if ($query == false) {
            throw new Exception("Erreur lors de la suppression d'un compte en base de donnée.");
        }
    }

    public function getExpenses(string $id) : float {
        $sql = "
            SELECT SUM(t.amount) AS expenses
            FROM transactions t
            INNER JOIN accounts a ON a.id=t.id_account
            WHERE a.id=:id AND t.amount < 0
        ";
        $query = $this->execRequest($sql, ["id" => $id]);

        if ($query == false || $query->rowCount() != 1) {
            throw new Exception("Erreur lors de la recupération de dépenses en base de donnée.");
        } 

        $row = $query->fetch();

        return $row["expenses"] ?? 0;
    }

    public function getRevenues(string $id) : float {
        $sql = "
            SELECT SUM(t.amount) AS revenues
            FROM transactions t
            INNER JOIN accounts a ON a.id=t.id_account
            WHERE a.id=:id AND t.amount >= 0
        ";
        $query = $this->execRequest($sql, ["id" => $id]);

        if ($query == false || $query->rowCount() != 1) {
            throw new Exception("Erreur lors de la recupération des recettes en base de donnée.");
        } 

        $row = $query->fetch();

        return $row["revenues"] ?? 0;
    }

    public function getMExpenses(string $id, int $month) : float {
        $sql = "
            SELECT SUM(t.amount) AS expenses
            FROM transactions t
            INNER JOIN accounts a ON a.id=t.id_account
            WHERE a.id=:id AND t.amount < 0 AND EXTRACT(MONTH FROM t.date)=:month
        ";
        $query = $this->execRequest($sql, ["id" => $id, "month" => $month]);

        if ($query == false || $query->rowCount() != 1) {
            throw new Exception("Erreur lors de la recupération de dépenses par mois en base de donnée.");
        } 

        $row = $query->fetch();

        return $row["expenses"] ?? 0;
    }

    public function getMRevenues(string $id, int $month) : float {
        $sql = "
            SELECT SUM(t.amount) AS revenues
            FROM transactions t
            INNER JOIN accounts a ON a.id=t.id_account
            WHERE a.id=:id AND t.amount >= 0 AND EXTRACT(MONTH FROM t.date)=:month
        ";
        $query = $this->execRequest($sql, ["id" => $id, "month" => $month]);
    
        if ($query == false || $query->rowCount() != 1) {
            throw new Exception("Erreur lors de la recupération des recettes par mois en base de donnée.");
        } 
    
        $row = $query->fetch();
    
        return $row["revenues"] ?? 0;
    }
}

?>