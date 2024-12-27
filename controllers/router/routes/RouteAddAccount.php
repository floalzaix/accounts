<?php

namespace Controllers\Router\Routes;

use Controllers\Router\Route;
use Exception;

class RouteAddAccount extends Route {
    public function get($params = []) : void {
        if ($this->controller->connected()) {
            $this->controller->displayAddAccount();
        } else {
            header("Location: index.php?action=login");
            exit();
        }
    }

    public function post($params = []) : void {
        if ($this->controller->connected()) {
            $message = "Le compte a été créer avec succès !";
            $error = false;
            try {
                $this->controller->createAccount(parent::getParam($params, "account_name"), parent::getParam($params, "account_nb_cat"));
            } catch (Exception $err) {
                $error = true;
                $message = $err->getMessage();
            }
            $this->controller->displayAddAccount(["message" => $message, "error" => $error]);
        } else {
            header("Location: index.php?action=login");
            exit();
        }
    }
}

?>