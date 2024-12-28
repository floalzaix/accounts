<?php

$this->layout("template", ["title" => $title, "id_account" => $id_account]);


?>

<div id="body_inputs">
    <!-- Global summary -->

    <div class="transactions">
        <?php
            foreach($transactions as $trans) {
                $trans->__toString();
            }
        ?>

        <form action="index.php?action=inputs&id=<?= $id_account ?>" method="POST">
            <?php if ($edit_transaction) {  ?>
                <input type="date" id="date" name="date" min="<?= $year ?>-01-01" max="<?= $year ?>-12-31" value="<?= $transaction->getDate() ?>" />
                <input type="text" id="title" name="title" value="<?= $transaction->getTitle() ?>" maxlength="100" />
                <input type="date" id="bank_date" name="bank_date" min="<?= $year ?>-01-01" max="<?= $year ?>-12-31" value="<?= $transaction->getBankDate() ?>" />
                <?php
                    for($i = 1; $i <= $nb_of_categories; $i++) {
                        $cat_selected = null;
                        if (!empty($transaction->getCategories())) {
                            $cat_selected = $transaction->getCategories()[$i-1];
                        }
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
                    }
                ?>
                <input type="number" id="amount" name="amount" value="<?= $transaction->getAmount() ?>"  />
                <input type="submit" id="submit_button" name="submit_button" value="Modifier" />
                <input type="hidden" id="edit_transaction" name="edit_transaction" value="true" />
                <input type="hidden" id="id_transaction" name="id_transaction" value="<?= $transaction->getId() ?>" />
            <?php } else { ?>
                <input type="date" id="date" name="date" min="<?= $year ?>-01-01" max="<?= $year ?>-12-31" value="2025-01-01" />
                <input type="text" id="title" name="title" placeholder="Intitulé" maxlength="100" />
                <input type="date" id="bank_date" name="bank_date" min="<?= $year ?>-01-01" max="<?= $year ?>-12-31" value="2025-01-01" />
                <?php
                    for($i = 1; $i <= $nb_of_categories; $i++) {
                        echo "<select id='cat_{$i}' name='cat_{$i}'>";
                            echo "<option value=''>Aucune</option>";
                            foreach($categories as $cat) {
                                if ($cat->getLevel() == $i) {
                                    echo "<option value='{$cat->getId()}'>".$cat->getName()."</option>";
                                }
                            }
                        echo "</select>";
                    }
                ?>
                <input type="number" id="amount" name="amount" value="0"  />
                <input type="submit" id="submit_button" name="submit_button" value="Ajouter" />
            <?php } ?>
        </form>
    </div>
</div>

<?= Helpers\MessageHandler::displayMessage("inputs") ?>