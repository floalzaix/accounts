<?php

$this->layout("template", ["title" => $title]);

?>

<div id="body_inputs">
    <!-- Global summary -->

    <div class="transactions">
        <?php
            foreach($transactions as $transaction) {
                $transaction->__toString();
            }
        ?>

        <form action="index.php?action=inputs&id=<?= $id_account ?>" method="POST">
            <input type="date" id="date" name="date" min="<?= $year ?>-01-01" max="<?= $year ?>-12-31" />
            <input type="text" id="title" name="title" placeholder="Intitulé" maxlength="100" />
            <input type="date" id="bank_date" name="bank_date" min="<?= $year ?>-01-01" max="<?= $year ?>-12-31" />
            <?php
                for($i = 1; $i <= $nb_of_categories; $i++) {
                    echo "<select id='cat_{$i}' name='cat_{$i}'>";
                        echo "<option value='other'>Autre</option>";
                        foreach($categories as $cat) {
                            echo "<option value='{$cat->getId()}'>".$cat->getName()."</option>";
                        }
                    echo "</select>";
                }
            ?>
            <input type="number" id="amount" name="amount" placeholder="Montant" value="0" />
            <input type="submit" id="submit_button" name="submit_button" value="Ajouter" />
        </form>
    </div>
</div>

<?= Helpers\MessageHandler::displayMessage("inputs") ?>