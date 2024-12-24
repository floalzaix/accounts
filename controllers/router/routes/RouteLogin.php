<?php

namespace Controllers\Router\Routes;

use Controllers\Router\Route;
use Exception;

class RouteLogin extends Route {
    protected function get($params = []) : void {
        $this->controller->displayLogin();
    }

    protected function post($params = []) : void {
        $logged = false;
        $error = false;
        $message = "";

        try {
            if ($this->controller->validLogin(parent::getParam($params, "login_name"), parent::getParam($params, "login_pwd"))) {
                $logged = true;
            } else {
                throw new Exception("Le mot de passe ou le nom d'utilisateur n'est pas valide");
            }
        } catch (Exception $err) {
            $error = true;
            $message = $err->getMessage();
        }

        if($logged) {
            
        } else {
            $this->controller->displayLogin(["message" => $message, "error" => $error]);
        }
    }
}

?>