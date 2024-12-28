<?php

namespace Controllers\Router\Routes;

use Controllers\Router\Route;

class RouteError extends Route {
    protected function get($params = []) : void {
        if ($params["500"] ?? false) {
            $this->controller->displayError500();
        } else {
            $this->controller->displayError404();
        }
    }

    protected function post($params = []) : void {
        if ($params["500"] ?? false) {
            $this->controller->displayError500();
        } else {
            $this->controller->displayError404();
        }
    }
}

?>