<?php 

namespace Controllers\Router\Routes;

use Controllers\Router\Route;
use Exception;

class RouteCategories extends Route {
    protected function get($params = []) : void {
        if ($this->controller->connected()) {
            $message = "";
            $error = false;
            try {
                if ($params["del_category"] ?? false) {
                    $id_account = parent::getParam($params, "id_account");
                    $id_cat = parent::getParam($params, "id_cat");
                    $message = "
                        Etes-vous sur de vouloir supprimer cette catégorie ?
                        <form action='index.php?action=categories&id={$id_account}' method='POST'>
                            <input type='submit' id='confirm' name='confirm' value='Confirmer' />
                            <input type='submit' id='cancel' name='cancel' value='Annuler' />
                            <input type='hidden' id='del_category' name='del_category' value='true' />
                            <input type='hidden' id='id_cat' name='id_cat' value='{$id_cat}' />
                        </form>
                    ";
                }
            } catch (Exception $err) {
                $error = true;
                $message = $err->getMessage();
            }
            $this->controller->displayCategories([
                "id_account" => parent::getParam($params, "id_account"),
                "edit_category" => $params["edit_category"] ?? false,
                "id_cat" => $params["id_cat"] ?? "",
                "message" => $message,
                "error" => $error
            ]);
        } else {
            header("Location: index.php?action=login");
            exit();
        }
    }

    protected function post($params = []) : void {
        if ($this->controller->connected()) {
            $message = "";
            $error = false;
            try {
                if (($params["del_category"] ?? false)) {
                    if (isset($params["confirm"])) {
                        $message = "Catégorie supprimée avec succès !";
                        $this->controller->deleteCategory(parent::getParam($params, "id_cat"));
                    }
                } elseif ($params["edit_category"] ?? false) {
                    $message = "Catégorie modifié avec succés !";
                    $level_parent = $this->controller->getLevelOfCategory(parent::getParam($params, "parent", true));
                    $this->controller->editCategory(
                        parent::getParam($params, "id_cat"),
                        parent::getParam($params, "id_account"),
                        parent::getParam($params, "name"),
                        $level_parent+1,
                        parent::getParam($params, "parent", true)
                    );
                } else {
                    $message = "Catégorie ajouté avec succés !";
                    $level_parent = $this->controller->getLevelOfCategory(parent::getParam($params, "parent", true));
                    $this->controller->createCategory(
                        parent::getParam($params, "id_account"),
                        parent::getParam($params, "name"),
                        $level_parent+1,
                        parent::getParam($params, "parent", true)
                    );
                }
            } catch (Exception $err) {
                $error = true;
    	        $message = $err->getMessage();
            }
            $this->controller->displayCategories([
                "id_account" => parent::getParam($params, "id_account"),
                "message" => $message,
                "error" => $error,
            ]);
        } else {
            header("Location: index.php?action=login");
            exit();
        }
    }
}

?>