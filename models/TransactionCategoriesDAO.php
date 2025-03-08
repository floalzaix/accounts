<?php

namespace Models;

use Models\Category;
use Exception;

class TransactionCategoriesDAO extends CategoryLevelDAO {
    protected function getCategoriesOfTransaction(string $id_transaction) : array {
        $categories = [];

        $sql = "
            SELECT *
            FROM categories c
            INNER JOIN transactions_categories tc ON tc.id_category=c.id
            INNER JOIN categories_level cl ON cl.id_cat=c.id
            WHERE tc.id_transaction=:id_transaction
            ORDER BY cl.level;
        ";
        $query = $this->execRequest($sql, ["id_transaction" => $id_transaction]);

        if ($query == false) {
            throw new Exception("Erreur lors de la récupération de toutes les catégories d'une transaction en base de donnée.");
        }

        foreach($query as $row) {
            $category = new Category($row["name"], $row["id_account"], $row["id_parent"]);
            $category->setId($row["id"]);
            $level = $this->getLevelOfCategory($row["id"]);
            $category->setLevel($level);

            $categories[] = $category;
        }

        return $categories;
    }

    protected function setCategoriesOfTransaction(string $id_transaction, array $categories) : void {
        $sql = "DELETE FROM transactions_categories WHERE id_transaction=:id_transaction";
        $query = $this->execRequest($sql, ["id_transaction" => $id_transaction]);

        if ($query == false) {
            throw new Exception("Erreur lors de la supression en base de donnée de toutes les catégories d'une transaction");
        }

        $sql = "INSERT INTO transactions_categories(id_transaction, id_category) VALUES (:id_transaction, :id_category)";
        foreach($categories as $category) {
            $query = $this->execRequest($sql, ["id_transaction" =>$id_transaction, "id_category" => $category->getId()]);

            if ($query == false) {
                throw new Exception("Erreur lors de la création des liens entre les catégories et une transaction en base de donnée");
            }
        }
    }
}

?>