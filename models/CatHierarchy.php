<?php

namespace Models;

use Models\CategoryLevelDAO;
use Exception;

class CatHierarchy extends CategoryLevelDAO {
    protected function getChilds(string $id_cat_parent) : array {
        $categories = [];
        $sql = "
            SELECT * 
            FROM cat_hierarchy ch 
            INNER JOIN categories c ON c.id=ch.id_cat_child
            WHERE ch.id_cat_parent=:id_cat_parent
            ORDER BY c.name
        ";
        $query = $this->execRequest($sql, ["id_cat_parent" => $id_cat_parent]);

        if (!$query) {
            throw new Exception("Erreur lors de la récupération des catégories filles d'une catégorie en base de donnée !");
        }

        foreach($query as $row) {
            $category = new Category($row["name"], $row["id_account"]);
            $category->setId($row["id"]);
            $level = $this->getLevelOfCategory($row["id"]);
            $category->setLevel($level);
            $childs = $this->getChilds($row["id"]);
            $category->setChilds($childs);

            $categories[] = $category;
        }

        return $categories;
    }

    protected function addChild($id_cat_parent, $id_cat_child) : void {
        $sql = "INSERT INTO cat_hierarchy(id_cat_parent, id_cat_child) VALUES (:id_cat_parent, :id_cat_child)";
        $query = $this->execRequest($sql, ["id_cat_parent" => $id_cat_parent, "id_cat_child" => $id_cat_child]);

        if (!$query) {
            throw new Exception("Erreur lors de l'ajout d'un enfant à une catégorie parent en base de donnée !");
        }
    }

    protected function removeChilds(string $id_cat_parent) : void {
        $childs = $this->getChilds($id_cat_parent);
        foreach($childs as $child) {
            $this->delete($child->getId());
        }
    }

    public function delete(string $id) : void {
        $this->removeChilds($id);

        $sql = "DELETE FROM categories WHERE id=:id";
        $query = $this->execRequest($sql, ["id" => $id]);

        if ($query == false) {
            throw new Exception("Erreur lors de la suppression d'une catégorie en base de donnée.");
        }
    }

}

?>