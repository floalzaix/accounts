<?php

namespace Controllers\Router\Routes;

use Controllers\Router\Route;
use Exception;

class RouteLogin extends Route {
    protected function get($params = []) : void {
        $this->controller->displayLogin([
            "message" => $params["message"] ?? "",
            "error" => $params["error"] ?? false
        ]);
    }

    protected function post($params = []) : void {
        $error = false;
        $message = "";
        try {
            if (!isset($params["deco"])) {
                $user = $this->controller->validLogin(parent::getParam($params, "login_name"), parent::getParam($params, "login_pwd"));
                if (isset($user)) {
                    $this->controller->authentification($user);
                    header("Location: index.php?action=home");
                    exit();
                } else {
                    throw new Exception("Le mot de passe ou le nom d'utilisateur n'est pas valide");
                }
            } elseif (isset($params["deco"]) && $params["deco"]) {
                $this->controller->disconnection();
            }
        } catch (Exception $err) {
            $error = true;
            $message = $err->getMessage();
        }
        $this->controller->displayLogin(["message" => $message, "error" => $error]);

        echo "<meta http-equiv='refresh' content='0; url=index.php?action=login&message={$message}&error={$error}' />";
    }
}

?>