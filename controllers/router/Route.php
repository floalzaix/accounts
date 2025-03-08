<?php

namespace Controllers\Router;

use Exception;

abstract class Route {
    protected $controller;

    public function __construct($controller) {
        $this->controller = $controller;
    }

    protected abstract function get($params = []) : void;
    protected abstract function post($params = []) : void;

    public function action($params, $method) : void {
        if ($method == "POST") {
            $this->post($params);
        } elseif ($method == "GET") {
            $this->get($params);
        } else {
            throw new Exception("Erreur : la méthode fournie est ni GET ni POST dans la focntion action d'une route");
        }
    }

    protected function getParam(array $params, string $param, $canBeEmpty = false) : mixed {
        if (isset($params[$param])) {
            if (!$canBeEmpty && empty($params[$param])) {
                throw new Exception("Erreur : le paramètre {$param} est vide");
            }
            return $params[$param];
        } else {
            throw new Exception("Erreur : le paramètre {$param} n'est pas définit");
        }
    }
}

?>