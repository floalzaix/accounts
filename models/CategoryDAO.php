<?php 

namespace Models;

use Exception;
use PDO;
use Models\CategoryLevelDAO;

class CategoryDAO extends CategoryLevelDAO {
    public function getAllOfAccount(string $id_account) : array {
        $categories = [];

        $sql = "
            SELECT *
            FROM categories 
            WHERE id_account=:id_account 
            ORDER BY name
        ";
        $query = $this->execRequest($sql, ["id_account" => $id_account]);

        if ($query == false) {
            throw new Exception("Erreur lors de la récupération de toutes les catégories en base de donnée.");
        }

        foreach($query as $row) {
            $category = new Category($row["name"], $row["id_account"]);
            $category->setId($row["id"]);
            $level = $this->getLevelOfCategory($row["id"]);
            $category->setLevel($level);

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
            $category = new Category($row["name"], $row["id_account"]);
            $category->setId($row["id"]);
            $level = $this->getLevelOfCategory($row["id"]);
            $category->setLevel($level);
        }

        return $category;
    }

    public function create(Category $category) : void {
        $sql = "INSERT INTO categories(id, id_account, name) VALUES (:id, :id_account, :name)";
        if ($this->getById($category->getId()) != null) {
            $sql = "UPDATE categories SET name=:name, id_account=:id_account WHERE id=:id";
        }
        $query = $this->execRequest($sql, ["id" => $category->getId(), "id_account" => $category->getIdAccount(), "name" => $category->getName()]);

        $this->setLevelOfCategory($category->getId(), $category->getLevel());

        if ($query == false) {
            throw new Exception("Erreur lors de la création d'une catégorie en base de donnée.");
        }
    }
}

?>