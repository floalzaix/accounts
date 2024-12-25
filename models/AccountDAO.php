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

        $account = null;

        if ($query->rowCount() == 1) {
            $row = $query->fetch();
            $account = new Account($row["name"], $row["id_user"], $row["nb_of_categories"]);
            $account->setId($row["id"]);
        }

        return $account;
    }

    public function create(Account $account) : void {
        $sql = "INSERT INTO accounts(id, id_user, name, nb_of_categories) VALUES(:id, :id_user, :name, :nb_of_categories)";
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
}

?>