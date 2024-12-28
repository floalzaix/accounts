<?php

namespace Models;

use Models\BasePDODAO;
use Exception;

class CategoryLevelDAO extends BasePDODAO {
    protected function getLevelOfCategory(string $id_cat) : int {
        $sql = "SELECT * FROM categories_level WHERE id_cat=:id_cat";
        $query = $this->execRequest($sql, ["id_cat" => $id_cat]);

        if ($query == false) {
            throw new Exception("Erreur lors de la récupération du niveau d'une catégorie en base de donnée.");
        } elseif ($query->rowCount() != 1) {
            throw new Exception("Une catégorie n'a pas de niveau en base de donnée !");
        }

        $row = $query->fetch();
        $level = $row["level"];

        return $level;
    }

    protected function setLevelOfCategory(string $id_cat, int $level) : void {
        $sql = "INSERT INTO categories_level(id_cat, level) VALUES (:id_cat, :level)";
        $query = $this->execRequest($sql, ["id_cat" => $id_cat, "level" => $level]);

        if ($query == false) {
            throw new Exception("Erreur lors de l'ajout du niveau d'une catégorie en base de donnée !");
        }
    }
}

?>