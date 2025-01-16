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

<div id="body_summary">
    <h1>Récapitulatif</h1>

    <div class="block_amount" id="account_balance">
        <div class="amount_title">Solde</div>
        <div class="amounts"><?= $balance ?>€</div>
    </div>
    <div class="block_amount" id="account_expenses">
        <div class="amount_title">Dépenses</div>
        <div class="amounts"><?= $expenses ?>€</div>
    </div>
    <div class="block_amount" id="account_revenues">
        <div class="amount_title">Recettes</div>
        <div class="amounts"><?= $revenues ?>€</div>
    </div>
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
            <input type="image" class="icon" id="submit_button" name="submit_button" alt="Soumettre" src="/public/images/icones/soumettre.png"/>
    </form>
    <div id="permonth">
        <div class="mblock_amount" id="account_mbalance">
            <div class="amount_title">Solde fin du mois</div>
            <div class="amounts"><?= $mbalance ?>€</div>
        </div>
        <div class="mblock_amount" id="account_expenses">
            <div class="amount_title">Dépenses du mois</div>
            <div class="amounts"><?= $mexpenses ?>€</div>
        </div>
        <div class="mblock_amount" id="account_revenues">
            <div class="amount_title">Recettes du mois</div>
            <div class="amounts"><?= $mrevenues ?>€</div>
        </div>
        <div id="mtransactions">
            <div id="overflow">
                <table>
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Intitulé</th>
                            <th>Banque</th>
                            <?php
                                for($i = 1; $i <= $nb_of_categories; $i++) {
                                    echo "<th>Catégorie {$i}</th>";
                                }
                            ?>
                            <th>Montant</th>
                            <th>X</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            foreach($mtop_transactions as $trans) {
                                $trans->toString($nb_of_categories);
                            }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?= Helpers\MessageHandler::displayMessage("summary") ?>

