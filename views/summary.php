<?php

$this->layout("template", ["title" => $title, "id_account" => $id_account, "account_name" => $account_name]);

$months_mapping = [
    "january" => "Janvier",
    "february" => "Février",
    "march" => "Mars",
    "april" => "Avril",
    "may" => "Mai",
    "june" => "Juin",
    "july" => "Juillet",
    "august" => "Août",
    "september" => "Septembre",
    "october" => "Octobre",
    "november" => "Novembre",
    "december" => "Décembre"
];

?>

<h1>Récapitulatif de <?= $account_name ?> </h1>

<div class="body_summary">
    <div id="balance">
        <div class="amount_title">Solde</div>
        <div class="amount"><?= $balance ?></div>
    </div>
    <div id="expenses">
        <div class="amount_title">Dépenses</div>
        <div class="amount"><?= $expenses ?></div>
    </div>
    <div id="revenues">
        <div class="amount_title">Recettes</div>
        <div class="amount"><?= $revenues ?></div>
    </div>
    <div id="permonth">
        <form action="index.php?action=summary&id=<?= $id_account ?>" method="POST">
            <select id="month_selected" name="month_selected">
                <?php 
                    foreach($months_mapping as $k => $m) {
                        if ($month == $k) {
                            echo "<option value='{$k}' selected>$m</option>";
                        } else {
                            echo "<option value='{$k}'>$m</option>";
                        }
                    }
                ?>
            </select>
            <input type="submit" id="submit_button" name="submit_button" value="Sélectionner" />
        </form>
        <div id="mbalance">
            <div class="amount_title">Solde à la fin du mois</div>
            <div class="amount"><?= $mbalance ?></div>
        </div>
        <div id="mexpenses">
            <div class="amount_title">Dépenses</div>
            <div class="amount"><?= $mexpenses ?></div>
        </div>
        <div id="mrevenues">
            <div class="amount_title">Recettes</div>
            <div class="amount"><?= $mrevenues ?></div>
        </div>
        <div id="mtop_transactions">
            <?php
                foreach($mtop_transactions as $transaction) {
                    $transaction->__toString();
                }
            ?>
        </div>
    </div>
</div>

<?= Helpers\MessageHandler::displayMessage("summary") ?>

