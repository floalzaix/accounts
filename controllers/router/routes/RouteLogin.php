<?php

namespace Controllers\Router\Routes;

use Controllers\Router\Route;
use Exception;

class RouteLogin extends Route {
    protected function get($params = []) : void {
        $this->controller->displayLogin();
    }

    protected function post($params = []) : void {
        if ($this->controller->validLogin(parent::getParam($params, "login_name"), parent::getParam($params, "login_pwd"))) {

        } else {
            throw new Exception("Le mot de passe ou le nom d'utilisateur n'est pas valide");
        }
    }
}

?>