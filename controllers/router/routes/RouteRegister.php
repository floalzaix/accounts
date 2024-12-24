<?php

namespace Controllers\Router\Routes;

use Controllers\Router\Route;
use Exception;

class RouteRegister extends Route {
    protected function get($params = []) : void {
        $this->controller->displayRegister();
    }

    protected function post($params = []) : void {
        $registered = false;
        $error = false;
        $message = "";
        try {
            if ($this->controller->validRegister(parent::getParam($params, "login_name"), parent::getParam($params, "login_pwd"), parent::getParam($params, "login_pwd_confirm"))) {
                $registered = true;
            }
        } catch (Exception $err) {
            $error = true;
            $message = $err->getMessage();
        }
        
        if ($registered) {
            
        } else {
            $this->controller->displayRegister(["message" => $message, "error" => $error]);
        }
    }
}

?>