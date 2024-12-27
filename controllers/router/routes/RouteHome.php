<?php

namespace Controllers\Router\Routes;

use Controllers\Router\Route;

class RouteHome extends Route {
    protected function get($params = []) : void {
        if ($this->controller->connected()) {
            $this->controller->displayHome();
        } else {
            header("Location: index.php?action=login");
            exit();
        }
    }

    protected function post($params = []) : void {
        if ($this->controller->connected()) {
            $this->controller->displayHome();
        } else {
            header("Location: index.php?action=login");
            exit();
        }
    }
}

?>