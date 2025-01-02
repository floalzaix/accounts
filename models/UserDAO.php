<?php

namespace Models;

use Models\BasePDODAO;
use Models\User;
use Exception;

class UserDAO extends BasePDODAO {
    public function getAll() : array {
        $users = [];

        $sql = "SELECT * FROM users ORDER BY name";
        $query = $this->execRequest($sql);

        if ($query == false) {
            throw new Exception("Erreur lors de la récupération de tous les utilisateurs en base de donnée.");
        }

        foreach($query as $row) {
            $user = new User($row["name"], $row["hash"]);
            $user->setId($row("id"));

            $users[] = $user;
        }

        return $users;
    }

    public function getById(string $id) : ?User {
        $sql = "SELECT * FROM users WHERE id=:id";
        $query = $this->execRequest($sql, ["id" => $id]);

        $user = null;

        if ($query == false) {
            throw new Exception("Erreur lors de la recupération en base de donnée d'un utilisateur");
        } elseif($query->rowCount() > 1) {
            throw new Exception("Il y a plus d'un utilisateur avec le même id");
        }

        if ($query->rowCount() == 1) {
            $row = $query->fetch();
            $user = new User($row["name"], $row["hash"]);
            $user->setId($row["id"]);
        }

        return $user;
    }

    public function getByName(string $name) : ?User {
        $sql = "SELECT * FROM users WHERE name=:name";
        $query = $this->execRequest($sql, ["name" => $name]);

        $user = null;

        if ($query == false) {
            throw new Exception("Erreur lors de la recupération en bdd d'un utilisateur par le nom");
        } elseif ($query->rowCount() > 1) {
            throw new Exception("Il y a plus d'un utilisateur avec le même nom");
        }
        
        if ($query->rowCount() == 1) {
            $row = $query->fetch();
            $user = new User($row["name"], $row["hash"]);
            $user->setId($row["id"]);
        }

        return $user;
    }

    public function createUser(User $user) : void {
        $sql = "INSERT INTO users(id, name, hash) VALUES (:id, :name, :hash)";
        if ($this->getById($user->getId()) != null) {
            $sql = "UPDATE users SET name=:name, hash=:hash WHERE id=:id";
        }

        $query = $this->execRequest($sql, ["id" => $user->getId(), "name" => $user->getName(), "hash" => $user->getHash()]);

        if ($query == false) {
            throw new Exception("Erreur lors de la création d'un utilisateur dans la base de donnée");
        }
    }

    public function getExpensesOfAllAccounts(string $id_user) : int {
        $sql = "
            SELECT SUM(t.amount) AS expenses
            FROM users u
            INNER JOIN accounts a ON a.id_user=u.id
            INNER JOIN transactions t ON t.id_account=a.id
            WHERE u.id=:id_user
            AND t.amount < 0
        ";
        $query = $this->execRequest($sql, ["id_user" => $id_user]);

        if (!$query) {
            throw new Exception("Erreur lors de la récupération des dépenses de tous les comptes en base de donnée !");
        }

        $row = $query->fetch();

        return $row["expenses"];
    }

    public function getRevenuesOfAllAccounts(string $id_user) : int {
        $sql = "
            SELECT SUM(t.amount) AS revenues
            FROM users u
            INNER JOIN accounts a ON a.id_user=u.id
            INNER JOIN transactions t ON t.id_account=a.id
            WHERE u.id=:id_user
            AND t.amount >= 0
        ";
        $query = $this->execRequest($sql, ["id_user" => $id_user]);

        if (!$query) {
            throw new Exception("Erreur lors de la récupération des revenues de tous les comptes en base de donnée !");
        }

        $row = $query->fetch();

        return $row["revenues"];
    }
}

?>