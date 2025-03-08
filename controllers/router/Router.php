<?php

namespace Controllers\Router;

use Controllers\CategoryController;
use Controllers\MainController;
use Controllers\ErrorController;
use Controllers\AccountsController;

use Controllers\Router\Routes\RouteAddAccount;
use Controllers\Router\Routes\RouteCategories;
use Controllers\Router\Routes\RouteHome;
use Controllers\Router\Routes\RouteInputs;
use Controllers\Router\Routes\RouteLogin;
use Controllers\Router\Routes\RouteError;
use Controllers\Router\Routes\RouteRegister;
use Controllers\Router\Routes\RouteSummary;
use Controllers\Router\Routes\RouteDetails;

use Exception;
use Helpers\DateHandler;

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
            "accounts" => new AccountsController(),
            "category" => new CategoryController()
        ];
    }

    private function createRouteList() : void {
        $this->route_list = [
            "login" => new RouteLogin($this->ctrl_list["main"]),
            "err" => new RouteError($this->ctrl_list["error"]),
            "register" => new RouteRegister($this->ctrl_list["main"]),
            "home" => new RouteHome($this->ctrl_list["main"]),
            "add-account" => new RouteAddAccount($this->ctrl_list["accounts"]),
            "inputs" => new RouteInputs($this->ctrl_list["accounts"]),
            "categories" => new RouteCategories(($this->ctrl_list["category"])),
            "summary" => new RouteSummary($this->ctrl_list["accounts"]),
            "details" => new RouteDetails($this->ctrl_list["accounts"]),
        ];
    }

    private function getRoute(string $route_name) : Route {
        $route = $this->route_list["err"];
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
        $action = $get[$this->action_key] ?? null;
        $post["message"] = $get["message"] ?? "";
        $post["error"] = $get["error"] ?? false;
        if (isset($action)) {
            $route = $this->getRoute($action);
            if ($action == "deco") {
                $route = $this->route_list["login"];
                $post["deco"] = true;
            } elseif ($action == "inputs") {
                $route = $this->route_list["inputs"];
                $post["id_account"] = $get["id"] ?? "";
            } elseif ($action == "categories") {
                $route = $this->route_list["categories"];
                $post["id_account"] = $get["id"] ?? "";
            } elseif ($action == "del-transaction") {
                $route = $this->route_list["inputs"];
                $post["id_account"] = $get["id"] ?? "";
                $post["id_transaction"] = $get["id_transaction"] ?? "";
                $post["del_transaction"] = true;
            } elseif ($action == "edit-transaction") {
                $route = $this->route_list["inputs"];
                $post["id_account"] = $get["id"] ?? "";
                $post["id_transaction"] = $get["id_transaction"] ?? "";
                $post["edit_transaction"] = true;
            } elseif ($action == "del-category") {
                $route = $this->route_list["categories"];
                $post["id_account"] = $get["id"] ?? "";
                $post["id_cat"] = $get["id_cat"] ?? "";
                $post["del_category"] = true;
            } elseif ($action == "edit-category") {
                $route = $this->route_list["categories"];
                $post["id_account"] = $get["id"] ?? "";
                $post["id_cat"] = $get["id_cat"] ?? "";
                $post["edit_category"] = true;
            } elseif ($action == "summary") {
                $post["id_account"] = $get["id"] ?? "";
                $post["month"] = $get["month"] ?? DateHandler::getTodayMonth();
            } elseif ($action == "del-account") {
                $route = $this->route_list["home"];
                $post["id_account"] = $get["id"] ?? "";
                $post["del_account"] = true;
            } elseif ($action == "details") {
                $post["id_account"] = $get["id"] ?? "";
            }
        } 
        try {
            $route->action($post, $method);
        } catch (Exception $err) {
            $route = $this->route_list["err"];
            $post["500"] = true;
            $route->action($post, $method);
            var_dump($err->getMessage());
        }
    }
}