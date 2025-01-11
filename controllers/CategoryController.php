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
            "account_name" => $account->getName(),
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
    public function editCategory(string $id, string $id_account, string $name, string $id_parent) : void {
        $category = new Category($name, $id_account, !empty($id_parent) ? $id_parent : null);
        $category->setId($id);

        $old_cat = $this->category_dao->getById($id);
        if (!isset($old_cat)) {
            throw new Exception("Erreur la catégorie séléctionné n'existe pas !");
        }

        $account = $this->account_dao->getById($id_account);
        if (!isset($account)) {
            throw new Exception("Aucun compte sélectionné");
        }
        $nb_max_of_cat = $account->getNbOfCategories(); 

        $parent_cat = $this->category_dao->getById($id_parent);
        $level = (isset($parent_cat) ? $parent_cat->getLevel() : 0) + 1;
        $childs = $old_cat->getChilds();

        $switch_cat = $this->searchInChilds($childs, $id_parent);
        if (isset($switch_cat)) {
            $level = $old_cat->getLevel()+1;
            [$last_level_child_cat, $nb_same_level_sleeves, $deepest_branch_ids] = $this->getLastLevelOfChild($childs, $old_cat->getLevel());
            var_dump($last_level_child_cat, $nb_same_level_sleeves, $switch_cat->getId(), $deepest_branch_ids);
            if ($last_level_child_cat >= $nb_max_of_cat && ($nb_same_level_sleeves != 1 || !in_array($switch_cat->getId(), $deepest_branch_ids))) {
                throw new Exception("Erreur lors de la modification : une catégorie aurait un niveau supérieur à celui autorisé par le compte !");
            } 

            $this->category_dao->removeChild($switch_cat->getIdParent(), $switch_cat->getId());

            $childs = $this->removeSwitchCat($childs, $switch_cat->getId());
            $childs = array_merge($childs, $switch_cat->getChilds());
            
            $new_switch_cat = new Category($switch_cat->getName(), $id_account, $old_cat->getIdParent());
            $new_switch_cat->setId($switch_cat->getId());
            $new_switch_cat->setLevel($old_cat->getLevel());
            $new_switch_cat->setChilds( [$category]);

            $this->category_dao->removeChild($old_cat->getIdParent() ?? "", $id);
            $this->category_dao->create($new_switch_cat);
        } else {
            [$last_level_child_cat, $nb_same_level_sleeves, $deepest_branch_ids] = $this->getLastLevelOfChild($childs, $old_cat->getLevel());
            if ($last_level_child_cat - $old_cat->getLevel() + $level-1 > $nb_max_of_cat) {
                throw new Exception("Erreur lors de la modification : une catégorie aurait un niveau supérieur à celui autorisé par le compte !");
            }

            $this->category_dao->removeChild($old_cat->getIdParent() ?? "", $id);
            $this->category_dao->addChild($id_parent, $id);
        }

        $this->updateLevelOfChilds($childs, $level, $nb_max_of_cat);

        $category->setLevel($level);
        $category->setChilds($childs);

        $this->category_dao->create($category);
    }
    private function searchInChilds(array $childs, string $id) : ?Category {
        $category = null;
        foreach($childs as $child) {
            if ($child->getId() == $id) {
                $category = $child;
                break;
            } else {
                $category = $this->searchInChilds($child->getChilds(), $id) ?? $category;
            }
        }
        return $category;
    }
    private function updateLevelOfChilds(array $childs, int $level, int $nb_of_cat_max) : void {
        foreach($childs as $child) {
            if ($level == $nb_of_cat_max) {
                throw new Exception("Erreur lors de la mise à jour des niveau des catégories : le niveau d'une ou plusieurs catégories excéde le niveau max des catégories du compte !");
            }
            $this->category_dao->setLevelOfCategory($child->getId(), $level+1);
            $this->updateLevelOfChilds($child->getChilds(), $level+1, $nb_of_cat_max);
        }
    }
    private function getLastLevelOfChild(array $childs, int $cat_level) : array {
        if (empty($childs)) {
            return [$cat_level, 1, []];
        }
        $max = 0;
        $nb_same_level_sleeves = 1;
        $depest_branch_ids = [];
        foreach($childs as $child) {
            [$child_max_level, $nb_sleeves, $depest_branch_ids] = $this->getLastLevelOfChild($child->getChilds(), $child->getLevel());
            if ($child_max_level > $max) {
                $max = $child_max_level;
                $nb_same_level_sleeves = $nb_sleeves;
                $depest_branch_ids[] = $child->getId();
            } elseif ($child_max_level == $max) {
                $nb_same_level_sleeves+= $nb_sleeves;
            }
        }
        return [$max, $nb_same_level_sleeves, $depest_branch_ids];
    }
    private function removeSwitchCat(array $childs, string $id_switch_cat) : array {
        $res = [];
        foreach($childs as $child) {
            if ($child->getId() != $id_switch_cat) {
                $res[] = $child;
                $child->setChilds($this->removeSwitchCat($child->getChilds(), $id_switch_cat));
            }
        }
        return $res;
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