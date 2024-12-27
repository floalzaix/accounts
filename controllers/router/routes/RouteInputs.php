<?php

namespace Controllers\Router\Routes;

use Controllers\Router\Route;
use Exception;

class RouteInputs extends Route {
    protected function get($params = []) : void {
        if ($this->controller->connected()) {
            $this->controller->displayInputs($params);
        } else {
            header("Location: index.php?action=login");
            exit();
        }
    }

    protected function post($params = []) : void {
        if ($this->controller->connected()) {
            $message = "Transaction crée avec succès !";
            $error = false;
            try {
                $account = $this->controller->getAccount($params["id_account"]);
                $categories = [];
                for ($i = 1; $i <= $account->getNbOfCategories(); $i++) {
                    $categories[] = $this->controller->getCategory(parent::getParam($params, "cat_{$i}"));
                }
                $this->controller->createTransaction(
                    parent::getParam($params, "id_account"),
                    parent::getParam($params, "date"),
                    parent::getParam($params, "title"),
                    parent::getParam($params, "bank_date"),
                    $categories,
                    parent::getParam($params, "amount")
                );
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