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


<div id="body_details">
    <h1> Détails </h1>

    <div class="detailed_expenses" id="detailed">
        <?php displayDetailedTab( "Dépenses", $cat_levels, $detailed_expenses, $months_mapping, $months_id, $categories_name); ?>
    </div>

    <div class="detailed_revenues" id="detailed">
        <?php displayDetailedTab("Revenues", $cat_levels, $detailed_revenues, $months_mapping, $months_id, $categories_name); ?>
    </div>

    <?= Helpers\MessageHandler::displayMessage("details") ?>
</div>


<?php
    function displayDetailedTab(string $name, array $cat_levels, array $detailed_tab, array $months_mapping, array $months_id, array $categories_name) : void {
        echo "<div id='overflow'>";
            echo "<table>";
                echo "<thead>";
                    echo "<tr>";
                        echo "<th>{$name}</th>";
                        foreach($months_mapping as $key => $month) {
                            echo "<th>".$month."</th>";
                        }
                        echo "<th class='total'>Total</th>";
                    echo "</tr>";
                echo "</thead>";

                echo "<tbody>";
                    foreach($detailed_tab as $root_id => $root) {
                        if ($root_id != "totals") {
                            displayCategory($root_id, $cat_levels, $root, $months_id, $categories_name);
                            echo "<tr class='spacer'><td></td></tr>";
                        } else {
                            echo "<tr>";
                                echo "<td class='total'>Total</td>";
                                foreach($root as $amount) {
                                    $class = ($amount > 0) ? "revenues" : "expenses"; 
                                    $display = ($amount == 0) ? "" : $amount."€";
                                    echo "<td class='{$class}' id='amount_case'>".$display."</td>";
                                }
                            echo "</tr>";
                        }
                    }
                echo "</tbody>";
            echo "</table>";
        echo "</div>";
    }

    function displayCategory(string $id_category, array $cat_levels,  array $category, array $months_id, array $categories_name) : void {
        $div_id = str_contains($id_category, "other") ? "other" : "cat_level_{$cat_levels[$id_category]}";
        echo "<tr class='cat_level_{$cat_levels[$id_category]}' id='{$div_id}'>";
            echo "<td>".$categories_name[$id_category]."</td>";
            foreach($months_id as $month => $num) {
                $expense = $category["expenses_per_month_of_category"][$num];
                if ($expense == 0) {
                    echo "<td id='amount_case'></td>";
                } else {
                    $class = ($expense > 0) ? "revenues" : "expenses"; 
                    echo "<td class='{$class}' id='amount_case'>".$expense."€</td>";
                }
            }
            $class = ($category["expenses_per_month_of_category"]["sum"] > 0) ? "revenues" : "expenses"; 
            $display = ($category["expenses_per_month_of_category"]["sum"] == 0) ? "" : $category["expenses_per_month_of_category"]["sum"]."€";
            echo "<td class='{$class}'>".$display."</td>";
        echo "</tr>";
        foreach($category["expenses_per_month_childs"] as $expenses_per_month_child_id => $expenses_per_month_child) {
            displayCategory($expenses_per_month_child_id, $cat_levels, $expenses_per_month_child, $months_id, $categories_name);
        }
    }
?>