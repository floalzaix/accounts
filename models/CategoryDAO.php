<?php 

namespace Models;

use Exception;
use PDO;
use Models\BasePDODAO;

class CategoryDAO extends BasePDODAO {
    public function getAllOfAccount(string $id_account) : array {
        $categories = [];

        $sql = "
            SELECT c.id, c.name, c.id_user 
            FROM categories c
            INNER JOIN accounts a ON a.id_user=c.id_user
            WHERE a.id=:id_account 
            ORDER BY c.name
        ";
        $query = $this->execRequest($sql, ["id_account" => $id_account]);

        if ($query == false) {
            throw new Exception("Erreur lors de la récupération de toutes les catégories en base de donnée.");
        }

        foreach($query as $row) {
            $category = new Category($row["name"], $row["id_user"]);
            $category->setId($row["id"]);

            $categories[] = $category;
        }

        return $categories;
    }

    public function getById(string $id) : ?Category {
        $sql = "SELECT * FROM categories WHERE id=:id";
        $query = $this->execRequest($sql, ["id" => $id]);

        $category = null;

        if ($query == false) {
            throw new Exception("Erreur lors de la recupération en base de donnée d'une catégorie");
        } elseif($query->rowCount() > 1) {
            throw new Exception("Il y a plus d'une catégorie avec le même id");
        }

        if ($query->rowCount() == 1) {
            $row = $query->fetch();
            $category = new Category($row["name"], $row["id_user"]);
            $category->setId($row["id"]);
        }

        return $category;
    }

    public function create(Category $category) : void {
        $sql = "INSERT INTO categories(id, id_user, name) VALUES (:id, :id_user, :name)";
        if ($this->getById($category->getId()) != null) {
            $sql = "UPDATE categories SET name=:name, id_user=:id_user WHERE id=:id";
        }
        $query = $this->execRequest($sql, ["id" => $category->getId(), "id_user" => $category->getIdUser(), "name" => $category->getName()]);

        if ($query == false) {
            throw new Exception("Erreur lors de la création d'une catégorie en base de donnée.");
        }
    }

    public function delete(string $id) : void {
        $sql = "DELETE FROM categories WHERE id=:id";
        $query = $this->execRequest($sql, ["id" => $id]);

        if ($query == false) {
            throw new Exception("Erreur lors de la suppression d'une catégorie en base de donnée.");
        }
    }
}

?>