<?php 

namespace Models;

use Exception;
use Models\BasePDODAO;

class CategoryDAO extends BasePDODAO {
    public function getAllOfUser(string $id_user) : array {
        $categories = [];

        $sql = "SELECT * FROM categories WHERE id_user=:id_user ORDER BY name";
        $query = $this->execRequest($sql, ["id_user" => $id_user]);

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
        $sql = "INSERT INTO categories(id, name) VALUES (:id, :name)";
        if ($this->getById($category->getId()) != null) {
            $sql = "UPDATE categories SET name=:name WHERE id=:id";
        }
        $query = $this->execRequest($sql, ["id" => $category->getId(), "name" => $category->getName()]);

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