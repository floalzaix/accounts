<?php

namespace Controllers;

use League\Plates\Engine;
use Config\Config;
use Models\UserDAO;
use Models\AccountDAO;
use Models\User;
use Exception;
use Helpers\MessageHandler;

class MainController {
    private $templates;
    private $user_dao;
    private $account_dao;

    public function __construct() {
        session_start();
        $this->templates = new Engine("views");
        $this->user_dao = new UserDAO();
        $this->account_dao = new AccountDAO();
    }

    public function displayHome($params = []) : void {
        $user = $this->user_dao->getByName($params["user"] ?? (isset($_SESSION["user"]) ? $_SESSION["user"]->getName() : ""));
        $accounts = $this->account_dao->getAllOfUser(isset($user) ? $user->getId() : "");

        $expenses = $this->getExpensesOfAllAccounts($user->getId());
        $revenues = $this->getRevenuesOfAllAccounts($user->getId());
        $balance = $revenues+$expenses;

        MessageHandler::setMessageToPage($params["message"] ?? "", "home", $params["error"] ?? false);
        echo $this->templates->render("home", [
            "title" => Config::get("title"),
            "user_name" => isset($user) ? $user->getName() : "",
            "accounts" => $accounts,
            "expenses" => $expenses,
            "revenues" => $revenues,
            "balance" => $balance
        ]);
    }
    public function getExpensesOfAllAccounts(string $id_user) : int {
        return $this->user_dao->getExpensesOfAllAccounts($id_user);
    }
    public function getRevenuesOfAllAccounts(string $id_user) : int {
        return $this->user_dao->getRevenuesOfAllAccounts($id_user);
    }

    public function displayLogin($params = []) : void {
        MessageHandler::setMessageToPage($params["message"] ?? "", "login", $params["error"] ?? false);
        echo $this->templates->render("login", ["title" => Config::get("title")]);
    }

    public function displayRegister($params = []) : void {
        MessageHandler::setMessageToPage($params["message"] ?? "", "register", $params["error"] ?? false);
        echo $this->templates->render("register", ["title" => Config::get("title")]);
    }

    public function validLogin(string $name, string $pwd) : ?User {
        $user = $this->user_dao->getByName($name);
        if (isset($user) && $user->verifyPassword($pwd)) {
            return $user;
        }
        return null;
    }
    public function validRegister(string $name, string $pwd, string $pwd_confirm) : ?User {
        $user = $this->user_dao->getByName($name);
        if (isset($user)) {
            throw new Exception("Erreur un utilisateur à déjà ce nom");
        }
        if ($pwd != $pwd_confirm) {
            throw new Exception("Erreur les mots de passe ne sont pas identiques");
        }
        $hash = hash("sha256", $pwd);
        $user = new User($name, $hash);
        $this->user_dao->createUser($user);
        return $user;
    }
    public function authentification(User $user) : void {
        $_SESSION["user"] = $user;
    }
    public function connected() : bool {
        if (isset($_SESSION["user"])) {
            return true;
        }
        return false;
    }
    public function disconnection() : void {
        session_unset();
        session_destroy();
        header("Location: index.php?action=login");
        exit();
    }

    public function deleteAccount(string $id) : void {
        $this->account_dao->delete($id);
    }
}

?>