<?php

namespace Controllers\Router\Routes;

use Controllers\Router\Route;
use Exception;

class RouteRegister extends Route {
    protected function get($params = []) : void {
        $message = urldecode($params["message"] ?? "");
        $this->controller->displayRegister([
            "message" => $message,
            "error" => $params["error"] ?? false
        ]);
    }

    protected function post($params = []) : void {
        $error = false;
        $message = "";
        try {
            $user = $this->controller->validRegister(parent::getParam($params, "login_name"), parent::getParam($params, "login_pwd"), parent::getParam($params, "login_pwd_confirm"));
            if (isset($user)) {
                $this->controller->authentification($user);
                header("Location: index.php?action=home");
                exit();
            }
        } catch (Exception $err) {
            $error = true;
            $message = $err->getMessage();
        }
        $this->controller->displayRegister(["message" => $message, "error" => $error]);

        $message = urlencode($message);

        echo "<meta http-equiv='refresh' content='0; url=index.php?action=register&message={$message}&error={$error}' />";
    }
}

?>