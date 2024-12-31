<?php

namespace Controllers;

use Models\AccountDAO;
use Models\Category;
use Models\CategoryDAO;
use League\Plates\Engine;
use Config\Config;
use Helpers\MessageHandler;
use Exception;

class CategoryController {
    private $templates;
    private $category_dao;
    private $account_dao;

    public function __construct() {
        $this->templates = new Engine("views");
        $this->category_dao = new CategoryDAO();
        $this->account_dao = new AccountDAO();
    }

    public function displayCategories($params = []) : void {
        $categories = $this->category_dao->getAllOfAccount($params["id_account"] ?? "");
        $account = $this->account_dao->getById($params["id_account"] ?? "");
        $nb_of_categories = isset($account) ? $account->getNbOfCategories() : 0;
        $cat =$this->category_dao->getById($params["id_cat"] ?? "");
        
        MessageHandler::setMessageToPage($params["message"] ?? "", "categories", $params["error"] ?? false);
        echo $this->templates->render("categories", [
            "title" => Config::get("title"),
            "id_account" => $params["id_account"] ?? "",
            "categories" => $categories,
            "edit_category" => $params["edit_category"] ?? false,
            "nb_of_categories" => $nb_of_categories,
            "cat" => $cat
        ]);
    }

    public function createCategory(string $id_account, string $name, string $level, string $id_parent) : void {
        $category = new Category($name, $id_account, !empty($id_parent) ? $id_parent : null);
        $category->setChilds([]);
        $nb_max = $this->account_dao->getById($id_account)->getNbOfCategories();
        if ($level > $nb_max) {
            throw new Exception("Erreur ce compte ne supporte pas des catégories d'un niveau supérieur à : ".$nb_max);
        }
        $category->setLevel($level);

        $this->category_dao->create($category);

        $this->category_dao->addChild($id_parent ?? "", $category->getId());
    }
    public function editCategory(string $id, string $id_account, string $name, string $level, string $id_parent) : void {
        $old_cat = $this->category_dao->getById($id);
        if (!isset($old_cat)) {
            throw new Exception("Erreur la catégorie séléctionné n'existe pas !");
        }
        $this->category_dao->removeChild($old_cat->getIdParent() ?? "", $id);

        $category = new Category($name, $id_account, !empty($id_parent) ? $id_parent : null);
        $category->setId($id);
        $category->setChilds([]);
        $nb_max = $this->account_dao->getById($id_account)->getNbOfCategories();
        if ($level > $nb_max) {
            throw new Exception("Erreur ce compte ne supporte pas des catégories d'un niveau supérieur à : ".$nb_max);
        }
        $category->setLevel($level);

        $this->category_dao->create($category);

        $this->category_dao->addChild($id_parent ?? "", $category->getId());
    }
    public function deleteCategory(string $id) : void {
        $this->category_dao->delete($id);
    }
    public function getLevelOfCategory($id_category) : int {
        $category = $this->category_dao->getById($id_category);
        return isset($category) ? $category->getLevel() : 0;
    }

    public function connected() : bool {
        if (isset($_SESSION["user"])) {
            return true;
        }
        return false;
    }
}

?>