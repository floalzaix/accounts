<?php

$this->layout("home_template", ["title" => $title]);

?>

<h1>Welcome <?= $user_name ?> </h1>

<div id="body_home">
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
    <div class="accounts">
        <?php
            foreach($accounts as $account) {
                $account->__toString();
            }
        ?>
    </div>
</div>

<?= Helpers\MessageHandler::displayMessage("home") ?>