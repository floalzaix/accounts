<?php

namespace Controllers\Router\Routes;

use Controllers\Router\Route;
use Exception;

class RouteInputs extends Route {
    protected function get($params = []) : void {
        if ($this->controller->connected()) {
            $message = "";
            $error = false;
            try {
                if (isset($params["del_transaction"]) && $params["del_transaction"]) {
                    $id_account = parent::getParam($params, "id_account");
                    $id_transaction = parent::getParam($params, "id_transaction");
                    $message = "
                        Etes-vous sur de vouloir supprimer cette catégorie ?
                        <form action='index.php?action=inputs&id={$id_account}' method='POST'>
                            <input type='submit' id='confirm' name='confirm' value='Confirmer' />
                            <input type='submit' id='cancel' name='cancel' value='Annuler' />
                            <input type='hidden' id='del_transaction' name='del_transaction' value='true' />
                            <input type='hidden' id='id_transaction' name='id_transaction' value='{$id_transaction}' />
                        </form>
                    ";
                }
            } catch (Exception $err) {
                $error = true;
                $message = $err->getMessage();
            }
            $this->controller->displayInputs([
                "id_account" => parent::getParam($params, "id_account"),
                "message" => $message,
                "error" => $error,
                "edit_transaction" => $params["edit_transaction"] ?? null,
                "id_transaction" => $params["id_transaction"] ?? ""
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
                if ($params["del_transaction"] ?? false) {
                    if (isset($params["confirm"])) {
                        $message = "Transaction supprimée avec succès !";
                        $this->controller->deleteTransaction(parent::getParam($params, "id_transaction"));
                    }
                } elseif ($params["edit_transaction"] ?? false) {
                    $message = "Transaction modifié avec succès !";
                    $account = $this->controller->getAccount($params["id_account"]);
                    $categories = [];
                    $childs = [];
                    for ($i = 1; $i <= $account->getNbOfCategories(); $i++) {
                        $category = $this->controller->getCategory(parent::getParam($params, "cat_{$i}", true));
                        if ($i > 1 && $category != null && (!isset($categories[$i-2]) ? 0 : $categories[$i-2]->getLevel()) != $category->getLevel()-1) {
                            throw new Exception("Erreur une catégorie sélectionné n'a pas de parent sélectionné !");
                        }
                        if (in_array($category, $categories)) {
                            throw new Exception("Une transaction ne peut pas voir plusieur fois la même catégorie");
                        }
                        if ($category != null) {
                            if ($i > 1 && !in_array($category, $childs)) {
                                throw new Exception("Erreur une des catégorie sélectionnée n'est pas enfant de l'autre !");
                            }
                            $childs = $category->getChilds();
                            $categories[] = $category;
                        }
                    }
                    $this->controller->editTransaction(
                        parent::getParam($params, "id_transaction"),
                        parent::getParam($params, "id_account"),
                        parent::getParam($params, "date"),
                        parent::getParam($params, "title"),
                        parent::getParam($params, "bank_date"),
                        $categories,
                        parent::getParam($params, "amount", true)
                    );
                } else {
                    $message = "Transaction crée avec succès !";
                    $account = $this->controller->getAccount($params["id_account"]);
                    $categories = [];
                    $childs = [];
                    for ($i = 1; $i <= $account->getNbOfCategories(); $i++) {
                        $category = $this->controller->getCategory(parent::getParam($params, "cat_{$i}", true));
                        if ($i > 1 && $category != null && (!isset($categories[$i-2]) ? 0 : $categories[$i-2]->getLevel()) != $category->getLevel()-1) {
                            throw new Exception("Erreur une catégorie sélectionné n'a pas de parent sélectionné !");
                        }
                        if (in_array($category, $categories)) {
                            throw new Exception("Une transaction ne peut pas voir plusieur fois la même catégorie");
                        }
                        if ($category != null) {
                            if ($i > 1 && !in_array($category, $childs)) {
                                throw new Exception("Erreur une des catégorie sélectionnée n'est pas enfant de l'autre !");
                            }
                            $childs = $category->getChilds();
                            $categories[] = $category;
                        }
                    }
                    $this->controller->createTransaction(
                        parent::getParam($params, "id_account"),
                        parent::getParam($params, "date"),
                        parent::getParam($params, "title"),
                        parent::getParam($params, "bank_date"),
                        $categories,
                        parent::getParam($params, "amount", true)
                    );
                }
            } catch (Exception $err) {
                $error = true;
                $message = $err->getMessage();
            }
            
            $this->controller->displayInputs(["id_account" => $params["id_account"] ?? "", "message" => $message, "error" => $error]);
        } else {
            header("Location: index.php?action=login");
            exit();
        }
    }
}

?>