<?php

namespace Controllers\Router\Routes;

use Controllers\Router\Route;
use Exception;

class RouteSummary extends Route {
    protected function get($params = []) : void {
        if ($this->controller->connected()) {
            $this->controller->displaySummary([
                "month" => $params["month"] ?? "january",
                "id_account" => $params["id_account"] ?? "",
                "message" => $params["message"] ?? "",
                "error" => $params["error"] ?? false
            ]);
        } else {
            header("Location: index.php?action=login");
            exit();
        }
    }

    protected function post($params = []) : void {
        if ($this->controller->connected()) {
            $message = "";
            $error = "";
            $id_account = "";
            $month = "january";
            try {   
                $id_account = parent::getParam($params, "id_account");
                $month = parent::getParam($params, "month_selected");
            } catch (Exception $err) {
                $error = true;
                $message = $err->getMessage();
            }
            $this->controller->displaySummary([
                "message" => $message,
                "error" => $error,
                "month" => $month,
                "id_account" => $id_account
            ]);

            echo "<meta http-equiv='refresh' content='0; url=index.php?action=summary&id={$id_account}&month={$month}&message={$message}&error={$error}' />";
        } else {
            header("Location: index.php?action=login");
            exit();
        }
    }
}

?>