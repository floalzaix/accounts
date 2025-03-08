<?php

namespace Controllers;

use League\Plates\Engine;
use Config\Config;
use Helpers\DateHandler;
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
    }

    public function displayDetails($params = []) : void {
        $id_account = $params["id_account"] ?? "";
        $account = $this->account_dao->getById($id_account);

        $categories = $this->category_dao->getAllOfAccount($id_account);

        $detailed_expenses = $this->getDetailedExpensesOfAccount($id_account, $account->getNbOfCategories(),  $categories);
        $detailed_revenues = $this->getDetailedRevenuesOfAccount($id_account, $account->getNbOfCategories(), $categories);

        $categories_name = [];
        $cat_levels = [];
        for($i = 1; $i <= 10; $i++) {
            $categories_name["other_{$i}"] = "Autres {$i}";
            $cat_levels["other_{$i}"] = $i + 1;
        }
        foreach($categories as $category) {
            $categories_name[$category->getId()] = $category->getName();
            $cat_levels[$category->getId()] = $category->getLevel();
        }

        MessageHandler::setMessageToPage($params["message"] ?? "", "details", $params["error"] ?? false);
        echo $this->templates->render("details", [
            "title" => Config::get("title"),
            "id_account" => $id_account,
            "months_id" => DateHandler::getMonthsNums(),
            "account_name" => $account->getName(),
            "detailed_expenses" => $detailed_expenses,
            "detailed_revenues" => $detailed_revenues,
            "categories_name" => $categories_name,
            "cat_levels" => $cat_levels
        ]);
    }

    public function displaySummary($params = []) : void {
        $id_account = $params["id_account"] ?? "";
        $month = $params["month"] ?? "january";

        $account = $this->account_dao->getById($id_account);

        $expenses = $this->getExpensesOfAccount($id_account);
        $revenues = $this->getRevenuesOfAccount($id_account);
        $balance = $revenues+$expenses;

        $month_num = DateHandler::getMonthNum($month);

        $mexpenses = $this->getMExpensesOfAccount($id_account, $month_num);
        $mrevenues = $this->getMRevenuesOfAccount($id_account, $month_num);
        $mbalance = $this->getBalanceEndMonth($id_account, $month_num);

        $mtop_transactions = $this->getMTopTransactionsOfAccount($id_account, $month_num);

        MessageHandler::setMessageToPage($params["message"] ?? "", "summary", $params["error"] ?? false);
        echo $this->templates->render("summary", [
            "title" => Config::get("title"),
            "id_account" => $id_account,
            "months_id" => DateHandler::getMonthsNums(),
            "month" => $month,
            "account_name" => $account->getName(),
            "balance" => $balance,
            "revenues" => $revenues,
            "expenses" => $expenses,
            "mbalance" => $mbalance,
            "mrevenues" => $mrevenues,
            "mexpenses" => $mexpenses,
            "mtop_transactions" => $mtop_transactions,
            "nb_of_categories" => $account->getNbOfCategories()
        ]);
    }
    public function getExpensesOfAccount(string $id_account) : float {
        return $this->account_dao->getExpenses($id_account);
    }
    public function getRevenuesOfAccount(string $id_account) : float {
        return $this->account_dao->getRevenues($id_account);
    }
    public function getMExpensesOfAccount(string $id_account, int $month) : float {
        return $this->account_dao->getMExpenses($id_account, $month);
    }
    public function getMRevenuesOfAccount(string $id_account, int $month) : float {
        return $this->account_dao->getMRevenues($id_account, $month);
    }
    public function getMTopTransactionsOfAccount(string $id_account, int $month) : array {
        return $this->transaction_dao->getMTopTransactions($id_account, Config::get("limit", 5), $month);
    }
    public function getBalanceEndMonth(string $id_account, int $month) : float {
        return $this->transaction_dao->getBalanceEndMonth($id_account, $month);
    }
    public function getDetailedExpensesOfAccount(string $id_account, int $nb_of_cat, array $categories) : array {
        $tab = [];
        foreach($categories as $category) {
            if($category->getLevel() == 1) {
                $tab[$category->getId()] = $this->getDetailedExpensesPerMonthOfCategory($id_account, $nb_of_cat, $category, DateHandler::getMonthsNums());
            }
        }
        
        $tot = 0;
        foreach(DateHandler::getMonthsNums() as $m => $num) {
            $S = 0;
            foreach($tab as $roots) {
                $S+= $roots["expenses_per_month_of_category"][$num] ?? 0;
            }
            $tab["totals"]["tot_month_".$num] = $S;
            $tot+= $S;
        }
        $tab["totals"]["sum"] = $tot;

        return $tab;
    }
    public function getDetailedExpensesPerMonthOfCategory(string $id_account, int $nb_of_cat,  Category $category, array $months) : array {
        $expenses_per_month_of_category = $this->transaction_dao->getExpensesPerMonthOfCategory($id_account, $category->getId(), $months);

        $childs = $category->getChilds();
        $expenses_per_month_of_childs = [];
        $expenses_per_month_of_childs["other_{$category->getLevel()}"] = [];
        foreach($childs as $child) {
            $expenses_per_month_of_childs[$child->getId()] = $this->getDetailedExpensesPerMonthOfCategory(
                $id_account, 
                $nb_of_cat,
                $child,
                $months
            );
        }

        $expenses_per_month_child_without_category = [];

        foreach($months as $month => $num) {
            $S = 0;
            foreach($expenses_per_month_of_childs as $child_expenses_per_month) {
                $S+= $child_expenses_per_month["expenses_per_month_of_category"][$num] ?? 0;
            }
            if ($S != $expenses_per_month_of_category[$num] && !empty($expenses_per_month_of_childs)  && $category->getLevel() != $nb_of_cat && abs($expenses_per_month_of_category[$num]-$S) > 0.001) {
                $expenses_per_month_child_without_category[$num] = $expenses_per_month_of_category[$num]-$S;
            }
        }

        if(!empty($expenses_per_month_child_without_category)) {
            $S = 0;
            foreach($months as $month => $num) {
                if (!isset($expenses_per_month_child_without_category[$num])) {
                    $expenses_per_month_child_without_category[$num] = 0;
                } else {
                    $S+= $expenses_per_month_child_without_category[$num];
                }
            }
            $expenses_per_month_child_without_category["sum"] = $S;

            $expenses_per_month_of_childs["other_{$category->getLevel()}"] = [
                "expenses_per_month_of_category" => $expenses_per_month_child_without_category,
                "expenses_per_month_childs" => []
            ];
        } else {
            unset($expenses_per_month_of_childs["other_{$category->getLevel()}"]);
        }

        return ["expenses_per_month_of_category" => $expenses_per_month_of_category, "expenses_per_month_childs" => $expenses_per_month_of_childs];
    }
    public function getDetailedRevenuesOfAccount(string $id_account, int $nb_of_cat, array $categories) : array {
        $tab = [];
        foreach($categories as $category) {
            if($category->getLevel() == 1) {
                $tab[$category->getId()] = $this->getDetailedRevenuesPerMonthOfCategory($id_account, $nb_of_cat, $category, DateHandler::getMonthsNums());
            }
        }
        
        $tot = 0;
        foreach(DateHandler::getMonthsNums() as $m => $num) {
            $S = 0;
            foreach($tab as $roots) {
                $S+= $roots["expenses_per_month_of_category"][$num] ?? 0;
            }
            $tab["totals"]["tot_month_".$num] = $S;
            $tot+= $S;
        }
        $tab["totals"]["sum"] = $tot;

        return $tab;
    }
    public function getDetailedRevenuesPerMonthOfCategory(string $id_account, int $nb_of_cat, Category $category, array $months) : array {
        $expenses_per_month_of_category = $this->transaction_dao->getRevenuesPerMonthOfCategory($id_account, $category->getId(), $months);

        $childs = $category->getChilds();
        $expenses_per_month_of_childs = [];
        $expenses_per_month_of_childs["other_{$category->getLevel()}"] = [];
        foreach($childs as $child) {
            $expenses_per_month_of_childs[$child->getId()] = $this->getDetailedRevenuesPerMonthOfCategory(
                $id_account, 
                $nb_of_cat,
                $child,
                $months
            );
        }

        $expenses_per_month_child_without_category = [];

        foreach($months as $month => $num) {
            $S = 0;
            foreach($expenses_per_month_of_childs as $child_expenses_per_month) {
                $S+= $child_expenses_per_month["expenses_per_month_of_category"][$num] ?? 0;
            }
            if ($S != $expenses_per_month_of_category[$num] && !empty($expenses_per_month_of_childs) && $category->getLevel() != $nb_of_cat && abs($expenses_per_month_of_category[$num]-$S) > 0.001) {
                $expenses_per_month_child_without_category[$num] = $expenses_per_month_of_category[$num]-$S;
            }
        }

        if(!empty($expenses_per_month_child_without_category)) {
            $S = 0;
            foreach($months as $month => $num) {
                if (!isset($expenses_per_month_child_without_category[$num])) {
                    $expenses_per_month_child_without_category[$num] = 0;
                } else {
                    $S+= $expenses_per_month_child_without_category[$num];
                }
            }
            $expenses_per_month_child_without_category["sum"] = $S;
            
            $expenses_per_month_of_childs["other_{$category->getLevel()}"] = [
                "expenses_per_month_of_category" => $expenses_per_month_child_without_category,
                "expenses_per_month_childs" => []
            ];
        } else {
            unset($expenses_per_month_of_childs["other_{$category->getLevel()}"]);
        }

        return ["expenses_per_month_of_category" => $expenses_per_month_of_category, "expenses_per_month_childs" => $expenses_per_month_of_childs];
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
            "account_name" => $account->getName(),
            "edit_transaction" => $params["edit_transaction"] ?? false,
            "transaction" => $transaction
        ]);
    }

    public function createTransaction(string $id_account, string $date, string $title, string $bank_date, array $categories, float $amount) : void {
        $transaction = new Transaction($id_account, $date, $title, $bank_date, $amount);
        $transaction->setCategories($categories);
        $this->transaction_dao->create($transaction);
    }
    public function editTransaction(string $id_transaction, string $id_account, string $date, string $title, string $bank_date, array $categories, float $amount) : void {
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