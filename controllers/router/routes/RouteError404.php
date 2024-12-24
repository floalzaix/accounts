<?php

namespace Controllers\Router\Routes;

use Controllers\Router\Route;

class RouteError404 extends Route {
    protected function get($params = []) : void {
        $this->controller->displayError404();
    }

    protected function post($params = []) : void {
        $this->controller->displayError404();
    }
}

?>