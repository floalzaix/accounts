<?php

namespace Controllers\Router\Routes;

use Controllers\Router\Route;

class RouteRegister extends Route {
    protected function get($params = []) : void {
        $this->controller->displayRegister();
    }

    protected function post($params = []) : void {
        if ($this->controller->validRegister(parent::getParam($params, "login_name"), parent::getParam($params, "login_pwd"), parent::getParam($params, "login_pwd_confirm"))) {
            
        }
    }
}

?>