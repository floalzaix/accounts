<?php

namespace Controllers\Router\Routes;

use Controllers\Router\Route;

use Exception;

class RouteHome extends Route {
    protected function get($params = []) : void {
        if ($this->controller->connected()) {
            $message = $params["message"] ?? "";
            $error = $params["error"] ?? false;

            try {
                if ($params["del_account"] ?? false) {
                    $id_account = parent::getParam($params, "id_account");
                    $message = "
                        Etes-vous sur de vouloir supprimer ce compte ?
                        <form action='index.php?action=home' method='POST'>
                            <input type='submit' id='confirm' name='confirm' value='Confirmer' />
                            <input type='submit' id='cancel' name='cancel' value='Annuler' />
                            <input type='hidden' id='del_account' name='del_account' value='true' />
                            <input type='hidden' id='id_account' name='id_account' value='{$id_account}' />
                        </form>
                    ";
                }
            } catch (Exception $err) {
                $error = true;
                $message = $err->getMessage();
            }

            $this->controller->displayHome([
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

            try {
                if ($params["del_account"] ?? false) {
                    if (isset($params["confirm"])) {
                        $message = "Compte supprimé avec succès !";
                        $id_account = parent::getParam($params, "id_account");
                        $this->controller->deleteAccount($id_account);
                    }
                }
            } catch (Exception $err) {
                $error = true;
                $message = $err->getMessage();
            }

            $this->controller->displayHome([
                "message" => $message,
                "error" => $error
            ]);

            echo "<meta http-equiv='refresh' content='0; url=index.php?action=home&message={$message}&error={$error}' />";
        } else {
            header("Location: index.php?action=login");
            exit();
        }
    }
}

?>