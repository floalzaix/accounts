<?php

namespace Models;

use Exception;
use IntlDateFormatter;
use DateTime;
use Helpers\MessageHandler;

class Transaction {
    private string $id;
    private string $id_account;
    private string $date;
    private string $title;
    private string $bank_date;
    private array $categories;
    private float $amount;

    public function __construct(string $id_account, string $date, string $title, string $bank_date, float $amount) {
        $this->createId();
        $this->id_account = $id_account;
        $this->date = $date;
        $this->title = $title;
        $this->bank_date = $bank_date;
        $this->amount = $amount;
    }

    /**
     * Summary of createId
     * Create a unique id for the transaction
     * @return void
     */
    private function createId() : void {
        $this->id = uniqid("transaction_");
    }

    private function formatDate(string $date, $localisation="fr_FR") : string {
        $formatter = new IntlDateFormatter(
            $localisation,
            IntlDateFormatter::SHORT,
            IntlDateFormatter::NONE
        );
        
        return $formatter->format(new DateTime($date));
    }

    public function toString(int $nb_of_cat) : string {
        $date = $this->formatDate($this->getDate());
        $bank_date = $this->formatDate($this->getBankDate());
        echo "<tr class='transaction'>";
            echo "<td class='date'>".$date."</td>";
                
            echo "<td class='title'>";
                echo "<div>".$this->getTitle()."</div>";
            echo "</td>";

            echo "<td class='date'>".$bank_date."</td>";

            for ($i = 0; $i < $nb_of_cat; $i++) {
                $cat_on_level = false;
                for ($j = 0; $j < $this->getNbOfCat(); $j++) {
                    $cat = $this->getCategories()[$j];
                    if ($cat->getLevel() == $i+1) {
                        echo "<td class='cat'>";
                            echo "<div>".$cat->getName()."</div>";
                        echo "</td>";
                        $cat_on_level = true;
                    } 
                }

                if (!$cat_on_level && $i == 0) {
                    MessageHandler::setMessageToPage("Une ou plusieurs transactions n'ont pas de catégories racine", "inputs", true);
                    echo "<td class='cat'></td>";
                } elseif (!$cat_on_level) {
                    echo "<td class='cat'></td>";
                }
            }

            echo "<td class='amount'>".$this->getAmount()."€</td>";

            echo "<td class='buttons'>";
                echo "<a href='index.php?action=del-transaction&id={$this->getIdAccount()}&id_transaction={$this->getId()}'>";
                    echo "<img alt='trash' src='/public/images/icones/supprimer.png' />";
                echo "</a>";
                echo "<a href='index.php?action=edit-transaction&id={$this->getIdAccount()}&id_transaction={$this->getId()}'>";
                    echo "<img alt='modify' src='/public/images/icones/bouton-modifier.png' />";
                echo "</a>";
            echo "</td>";
        echo "</tr>";
        return "This the display of a transaction !";
    }

    public function getNbOfCat() : int {
        return count($this->getCategories());
    }

    //Getters
    public function getId() : string {
        return $this->id;
    }
    public function getIdAccount() : string {
        return $this->id_account;
    }
    public function getDate() : string {
        return $this->date;
    }
    public function getTitle() : string {
        return $this->title;
    }
    public function getBankDate() : string {
        return $this->bank_date;
    }
    public function getCategories() : array {
        return $this->categories;
    }
    public function getAmount() : float {
        return $this->amount;
    }

    //Setters
    public function setId(string $id) : void {
        $this->id = $id;
    }
    public function setIdAccount(string $id_account) : void {
        $this->id_account = $id_account;
    }
    public function setDate(string $date) : void {
        $this->date = $date;
    }
    public function setTitle(string $title) : void {
        $this->title = $title;
    }
    public function setBankDate(string $bank_date) : void {
        $this->bank_date = $bank_date;
    }
    public function setCategories(array $categories) : void {
        $this->categories = $categories;
    }
    public function setAmount(float $amount) : void {
        $this->amount = $amount;
    }
}

?>