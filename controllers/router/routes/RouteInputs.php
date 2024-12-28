<?php

namespace Controllers\Router\Routes;

use Controllers\Router\Route;
use Exception;

class RouteInputs extends Route {
    protected function get($params = []) : void {
        if ($this->controller->connected()) {
            $message = "";
            $error = false;
            try {
                if (isset($params["del_transaction"]) && $params["del_transaction"]) {
                    $message = "Transaction suprimé avec succès";
                    $this->controller->deleteTransaction(parent::getParam($params, "id_transaction"));
                }
            } catch (Exception $err) {
                $error = true;
                $message = $err->getMessage();
            }
            $this->controller->displayInputs([
                "id_account" => parent::getParam($params, "id_account"),
                "message" => $message,
                "error" => $error,
                "edit_transaction" => $params["edit_transaction"] ?? null,
                "id_transaction" => $params["id_transaction"] ?? ""
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
                if ($params["edit_transaction"] ?? false) {
                    $message = "Transaction modifié avec succès !";
                    $account = $this->controller->getAccount($params["id_account"]);
                    $categories = [];
                    for ($i = 1; $i <= $account->getNbOfCategories(); $i++) {
                        $category = $this->controller->getCategory(parent::getParam($params, "cat_{$i}"));
                        if (in_array($category, $categories)) {
                            throw new Exception("Une transaction ne peut pas voir plusieur fois la même catégorie");
                        }
                        $categories[] = $category;
                    }
                    $this->controller->editTransaction(
                        parent::getParam($params, "id_transaction"),
                        parent::getParam($params, "id_account"),
                        parent::getParam($params, "date"),
                        parent::getParam($params, "title"),
                        parent::getParam($params, "bank_date"),
                        $categories,
                        parent::getParam($params, "amount", true)
                    );
                } else {
                    $message = "Transaction crée avec succès !";
                    $account = $this->controller->getAccount($params["id_account"]);
                    $categories = [];
                    for ($i = 1; $i <= $account->getNbOfCategories(); $i++) {
                        $category = $this->controller->getCategory(parent::getParam($params, "cat_{$i}"));
                        if (in_array($category, $categories)) {
                            throw new Exception("Une transaction ne peut pas voir plusieur fois la même catégorie");
                        }
                        $categories[] = $category;
                    }
                    $this->controller->createTransaction(
                        parent::getParam($params, "id_account"),
                        parent::getParam($params, "date"),
                        parent::getParam($params, "title"),
                        parent::getParam($params, "bank_date"),
                        $categories,
                        parent::getParam($params, "amount", true)
                    );
                }
            } catch (Exception $err) {
                $error = true;
                $message = $err->getMessage();
            }
            
            $this->controller->displayInputs(["id_account" => $params["id_account"] ?? "", "message" => $message, "error" => $error]);
        } else {
            header("Location: index.php?action=login");
            exit();
        }
    }
}

?>