<?php

namespace Controllers\Router;

use Controllers\MainController;
use Controllers\ErrorController;
use Controllers\AccountsController;

use Controllers\Router\Routes\RouteAddAccount;
use Controllers\Router\Routes\RouteHome;
use Controllers\Router\Routes\RouteLogin;
use Controllers\Router\Routes\RouteError404;
use Controllers\Router\Routes\RouteRegister;

class Router {
    private $ctrl_list;
    private $route_list;
    private $action_key;

    public function __construct($action_key = "action") {
        $this->action_key = $action_key;
        $this->createControllerList();
        $this->createRouteList();
    }

    private function createControllerList() : void {
        $this->ctrl_list = [
            "main" => new MainController(),
            "error" => new ErrorController(),
            "accounts" => new AccountsController()
        ];
    }

    private function createRouteList() : void {
        $this->route_list = [
            "login" => new RouteLogin($this->ctrl_list["main"]),
            "err-404" => new RouteError404($this->ctrl_list["error"]),
            "register" => new RouteRegister($this->ctrl_list["main"]),
            "home" => new RouteHome($this->ctrl_list["main"]),
            "add-account" => new RouteAddAccount($this->ctrl_list["accounts"])
        ];
    }

    private function getRoute(string $route_name) : Route {
        $route = $this->route_list["err-404"];
        if (isset($this->route_list[$route_name])) {
            $route = $this->route_list[$route_name];
        }
        return $route;
    }

    public function routing($get, $post) : void {
        $route = $this->route_list["login"];
        $method = "GET";
        if (!empty($post)) {
            $method = "POST";
        }
        $action = $get[$this->action_key];
        if (isset($action)) {
            $route = $this->getRoute($action);
            if ($action == "deco") {
                $route = $this->route_list["login"];
                $post["deco"] = true;
            }
        } 
        $route->action($post, $method);
    } 
}

?>