<?php 

namespace Controllers;

use League\Plates\Engine;
use Config\Config;
use Helpers\MessageHandler;
use Models\Account;
use Models\AccountDAO;

class AccountsController {
    private $templates;
    private $account_dao;

    public function __construct() {
        $this->templates = new Engine("views");
        $this->account_dao = new AccountDAO();
    }

    public function displayAddAccount($params = []) : void {
        MessageHandler::setMessageToPage($params["message"] ?? "", "add-account", $params["error"] ?? false);
        echo $this->templates->render("add-account", ["title" => Config::get("title")]);
    }

    public function createAccount(string $name, int $nb_of_categories) : Account {
        $account = new Account($name, $_SESSION["user"]->getId(), $nb_of_categories);
        $this->account_dao->create($account);
        return $account;
    }

    public function connected() : bool {
        if (isset($_SESSION["user"])) {
            return true;
        }
        return false;
    }
}

?>