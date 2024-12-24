<?php

namespace Controllers;

use League\Plates\Engine;
use Config\Config;
use Models\UserDAO;
use Models\User;
use Exception;
use Helpers\MessageHandler;

class MainController {
    private $templates;
    private $user_dao;

    public function __construct() {
        $this->templates = new Engine("views");
        $this->user_dao = new UserDAO();
    }

    public function displayLogin($params = []) : void {
        MessageHandler::setMessageToPage($params["message"] ?? "", "login", $params["error"] ?? false);
        echo $this->templates->render("login", ["title" => Config::get("title")]);
    }

    public function displayRegister($params = []) : void {
        MessageHandler::setMessageToPage($params["message"] ?? "", "register", $params["error"] ?? false);
        echo $this->templates->render("register", ["title" => Config::get("title")]);
    }

    public function validLogin(string $name, string $pwd) : bool {
        $user = $this->user_dao->getByName($name);
        if (isset($user) && $user->verifyPassword($pwd)) {
            return true;
        }
        return false;
    }
    public function validRegister(string $name, string $pwd, string $pwd_confirm) : bool {
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
        return true;
    }
}

?>