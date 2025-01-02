<?php

namespace Controllers\Router\Routes;

use Controllers\Router\Route;

use Exception;

class RouteDetails extends Route {
    protected function get($params = []) : void {
        if ($this->controller->connected()) {
            $message = $params["message"] ?? "";
            $error = $params["error"] ?? false;
            $id_account = "";

            try {
                $id_account = parent::getParam($params, "id_account");
            } catch (Exception $err) {
                $error = true;
                $message = $err->getMessage();
            }

            $this->controller->displayDetails([
                "id_account" => $id_account,
                "message" => $message,
                "error" => $error
            ]);
        } else {
            header("Location: index.php?action=login");
            exit();
        }
    }

    protected function post($params = []) : void {
        if ($this->controller->connected()) {
            $message = "";
            $error = false;
            $id_account = "";

            try {
                $id_account = parent::getParam($params, "id_account");
            } catch (Exception $err) {
                $error = true;
                $message = $err->getMessage();
            }

            $this->controller->displayDetails([
                "id_account" => $id_account,
                "message" => $message,
                "error" => $error
            ]);

            echo "<meta http-equiv='refresh' content='0; url=index.php?action=details&message={$message}&error={$error}' />";
        } else {
            header("Location: index.php?action=login");
            exit();
        }
    }
}

?>