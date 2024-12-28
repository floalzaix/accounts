<?php 

namespace Controllers;

use League\Plates\Engine;
use Config\Config;
use Helpers\MessageHandler;
use Models\Account;
use Models\AccountDAO;
use Models\CategoryDAO;
use Models\Transaction;
use Models\TransactionDAO;
use Models\Category;

class AccountsController {
    private $templates;
    private $account_dao;
    private $transaction_dao;
    private $category_dao;

    public function __construct() {
        $this->templates = new Engine("views");
        $this->account_dao = new AccountDAO();
        $this->transaction_dao = new TransactionDAO();
        $this->category_dao = new CategoryDAO();
        $other = new Category("Autres", "all");
        $other->setId("other");
        $this->category_dao->create($other);
    }

    public function displayAddAccount($params = []) : void {
        MessageHandler::setMessageToPage($params["message"] ?? "", "add-account", $params["error"] ?? false);
        echo $this->templates->render("add-account", ["title" => Config::get("title")]);
    }

    public function displayInputs($params = []) : void {
        $account = $this->account_dao->getById($params["id_account"] ?? "");
        $transactions = $this->transaction_dao->getAllOfAccount($params["id_account"] ?? "");
        $categories = $this->category_dao->getAllOfAccount($params["id_account"] ?? "");

        $transaction = null;
        if ($params["edit_transaction"] ?? false) {
            $transaction = $this->transaction_dao->getById($params["id_transaction"] ?? "");
        }

        MessageHandler::setMessageToPage($params["message"] ?? "", "inputs", $params["error"] ?? false);
        echo $this->templates->render("inputs", [
            "title" => Config::get("title"),
            "year" => Config::get("year"),
            "transactions" => $transactions,
            "nb_of_categories" => isset($account) ? $account->getNbOfCategories() : 0,
            "categories" => $categories,
            "id_account" => $params["id_account"] ?? "",
            "edit_transaction" => $params["edit_transaction"] ?? false,
            "transaction" => $transaction
        ]);
    }

    public function createTransaction(string $id_account, string $date, string $title, string $bank_date, array $categories, int $amount) : void {
        $transaction = new Transaction($id_account, $date, $title, $bank_date, $amount);
        $transaction->setCategories($categories);
        $this->transaction_dao->create($transaction);
    }
    public function editTransaction(string $id_transaction, string $id_account, string $date, string $title, string $bank_date, array $categories, int $amount) : void {
        $transaction = new Transaction($id_account, $date, $title, $bank_date, $amount);
        $transaction->setId($id_transaction);   
        $transaction->setCategories($categories);
        $this->transaction_dao->create($transaction);
    }
    public function getAccount(string $id_account) : ?Account {
        return $this->account_dao->getById($id_account);
    }
    public function getCategory(string $id_cat) : ?Category {
        return $this->category_dao->getById($id_cat);
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

    public function deleteTransaction(string $id) : void {
        $this->transaction_dao->delete($id);
    }
}

?>