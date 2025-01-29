<?php

$this->layout("template", ["title" => $title, "id_account" => $id_account, "account_name" => $account_name]);

?>

<div id="body_inputs">
    <div class="transactions">
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
                        foreach($transactions as $trans) {
                            $trans->toString($nb_of_categories);
                        }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <?= Helpers\MessageHandler::displayMessage("inputs") ?>

    <form action="index.php?action=inputs&id=<?= $id_account ?>" method="POST">
        <?php if ($edit_transaction) {  ?>
            <div class="input">
                <img alt='calendrier' src='/public/images/icones/calendrier.png' />
                <input type="date" id="date" name="date" min="<?= $year ?>-01-01" max="<?= $year ?>-12-31" value="<?= $transaction->getDate() ?>" />
            </div>
            <div class="input">
                <img alt='titre' src='/public/images/icones/titre.png' />
                <input type="text" id="title" name="title" value="<?= $transaction->getTitle() ?>" maxlength="100" />
            </div>
            <div class="input">
                <img alt='calendrier' src='/public/images/icones/calendrier.png' />
                <input type="date" id="bank_date" name="bank_date" min="<?= $year ?>-01-01" max="<?= $year ?>-12-31" value="<?= $transaction->getBankDate() ?>" />
            </div>
            <?php
                for($i = 1; $i <= $nb_of_categories; $i++) {
                    $cat_selected = null;
                    if (!empty($transaction->getCategories()) && isset($transaction->getCategories()[$i-1])) {
                        $cat_selected = $transaction->getCategories()[$i-1];
                    }
                    echo "<div class='input'>";
                        echo "<img alt='categories' src='/public/images/icones/categorie.png' />";
                        echo "<select id='cat_{$i}' name='cat_{$i}'>";
                            echo "<option value=''>Aucune</option>";
                            foreach($categories as $cat) {
                                if ($cat->getLevel() == $i) {
                                    if ($cat->getId() == (isset($cat_selected) ? $cat_selected->getId() : "")) {
                                        echo "<option value='{$cat->getId()}' selected>" . $cat->getName() . "</option>";
                                    } else {
                                        echo "<option value='{$cat->getId()}'>" . $cat->getName() . "</option>";
                                    }
                                }
                            }
                        echo "</select>";
                    echo "</div>";
                }
            ?>
            <div class="input">
                <input type="number" id="amount" name="amount" step="0.01" value="<?= $transaction->getAmount() ?>"  /> €
            </div>
            <input type="image" class="icon" id="submit_button" name="submit_button" alt="Soumettre" src="/public/images/icones/soumettre.png"/>
            <input type="hidden" id="edit_transaction" name="edit_transaction" value="true" />
            <input type="hidden" id="id_transaction" name="id_transaction" value="<?= $transaction->getId() ?>" />
        <?php } else { ?>
            <div class="input">
                <img alt='calendrier' src='/public/images/icones/calendrier.png' />
                <input type="date" id="date" name="date" min="<?= $year ?>-01-01" max="<?= $year ?>-12-31" value="2025-01-01" />
            </div>
            <div class="input">
                <img alt='titre' src='/public/images/icones/titre.png' />
                <input type="text" id="title" name="title" placeholder="Intitulé" maxlength="100" />
            </div>
            <div class="input">
                <img alt='calendrier' src='/public/images/icones/calendrier.png' />
                <input type="date" id="bank_date" name="bank_date" min="<?= $year ?>-01-01" max="<?= $year ?>-12-31" value="2025-01-01" />
            </div>
            <?php
                for($i = 1; $i <= $nb_of_categories; $i++) {
                    echo "<div class='input'>";
                        echo "<img alt='categories' src='/public/images/icones/categorie.png' />";
                        echo "<select id='cat_{$i}' name='cat_{$i}'>";
                            echo "<option value=''>Aucune</option>";
                            foreach($categories as $cat) {
                                if ($cat->getLevel() == $i) {
                                    echo "<option value='{$cat->getId()}'>".$cat->getName()."</option>";
                                }
                            }
                        echo "</select>";
                    echo "</div>";
                }
            ?>
            <div class="input">
                <input type="number" id="amount" name="amount" step="0.01" value="0.00"  /> €
            </div>
            <input type="image" class="icon" id="submit_button" name="submit_button" alt="Soumettre" src="/public/images/icones/soumettre.png"/>
        <?php } ?>
    </form>
</div>