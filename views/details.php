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

<h1> Détails </h1>

<div class="body_summary">
    <div class="detailed_expenses">
        <?php displayDetailedTab($detailed_expenses, $months_mapping, $months_id, $categories_name); ?>
    </div>
    <div class="detailed_revenues">
        <?php displayDetailedTab($detailed_revenues, $months_mapping, $months_id, $categories_name); ?>
    </div>
</div>

<?= Helpers\MessageHandler::displayMessage("details") ?>

<?php
    function displayDetailedTab(array $detailed_tab, array $months_mapping, array $months_id, array $categories_name) : void {
        echo "<table>";
            echo "<tr>";
                echo "<td></td>";
                foreach($months_mapping as $key => $month) {
                    echo "<td>".$month."</td>";
                }
            echo "</tr>";

            foreach($detailed_tab as $root_id => $root) {
                if ($root_id != "totals") {
                    displayCategory($root_id, $root, $months_id, $categories_name);
                }
            }
        echo "</table>";
    }

    function displayCategory(string $id_category, array $category, array $months_id, array $categories_name) : void {
        echo "<tr>";
            echo "<td>".$categories_name[$id_category]."</td>";
            foreach($months_id as $month => $num) {
                $expense = $category["expenses_per_month_of_category"][$num];
                if ($expense == 0) {
                    echo "<td></td>";
                } else {
                    echo "<td>".$expense."</td>";
                }
            }
        echo "</tr>";
        foreach($category["expenses_per_month_childs"] as $expenses_per_month_child_id => $expenses_per_month_child) {
            displayCategory($expenses_per_month_child_id, $expenses_per_month_child, $months_id, $categories_name);
        }
    }
?>