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
            $category = new Category($row["name"], $row["id_account"], $row["id_parent"]);
            $category->setId($row["id"]);
            $level = $this->getLevelOfCategory($row["id"]);
            $category->setLevel($level);
            $childs = $this->getChilds($row["id"]);
            $category->setChilds($childs);

            $categories[] = $category;
        }

        return $categories;
    }

    public function addChild($id_cat_parent, $id_cat_child) : void {
        if (!($id_cat_parent == "" || $id_cat_child == "")) {
            $sql = "INSERT INTO cat_hierarchy(id_cat_parent, id_cat_child) VALUES (:id_cat_parent, :id_cat_child)";
            $query1 = $this->execRequest($sql, ["id_cat_parent" => $id_cat_parent, "id_cat_child" => $id_cat_child]);

            $sql = "UPDATE categories SET id_parent=:id_cat_parent WHERE id=:id_cat_child";
            $query2 = $this->execRequest($sql, ["id_cat_parent" => $id_cat_parent, "id_cat_child" => $id_cat_child]);

            if (!$query1 || !$query2) {
                throw new Exception("Erreur lors de l'ajout d'un enfant à une catégorie parent en base de donnée !");
            }
        } 
    }

    public function removeChild(string $id_cat_parent, string $id_cat_child) : void {
        $sql = "DELETE FROM cat_hierarchy WHERE id_cat_parent=:id_cat_parent AND id_cat_child=:id_cat_child";
        $query1 = $this->execRequest($sql, ["id_cat_parent" => $id_cat_parent, "id_cat_child" => $id_cat_child]);

        $sql = "UPDATE categories SET id_parent=NULL WHERE id=:id_cat_child";
        $query2 = $this->execRequest($sql, ["id_cat_child" => $id_cat_child]);

        if(!$query1 || !$query2) {
            throw new Exception("Erreur lors de la supression d'une catégorie enfant d'une catégorie parent !");
        }
    }

    public function removeChilds(string $id_cat_parent) : void {
        $childs = $this->getChilds($id_cat_parent);
        foreach($childs as $child) {
            $this->removeChild($id_cat_parent, $child->getId());
        }
    }

    protected function deleteChilds(string $id_cat_parent) : void {
        $childs = $this->getChilds($id_cat_parent);
        foreach($childs as $child) {
            $this->delete($child->getId());
        }
    }

    public function delete(string $id) : void {
        $this->deleteChilds($id);

        $sql = "DELETE FROM categories WHERE id=:id";
        $query = $this->execRequest($sql, ["id" => $id]);

        if ($query == false) {
            throw new Exception("Erreur lors de la suppression d'une catégorie en base de donnée.");
        }
    }

}

?>