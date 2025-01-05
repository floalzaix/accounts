<?php

$this->layout("home_template", ["title" => $title]);

?>


<div id="body_home">
    <h1 id="home_title"> <?= $user_name ?> </h1>

    <div class="card">
        <div class="amount_title">Solde total </div>
        <div class="amount" id="balance"><?= $balance ?> €</div>
    </div>
    <div class="card">
        <div class="amount_title">Entrées</div>
        <div class="amount" id="revenues"><?= $revenues ?> €</div>
    </div>
    <div class="card">
        <div class="amount_title">Sorties</div>
        <div class="amount" id="expenses"><?= $expenses ?> €</div>
    </div>
    <div class="accounts">
        <?php
            foreach($accounts as $account) {
                $account->__toString();
            }
        ?>
    </div>

    <?= Helpers\MessageHandler::displayMessage("home") ?>
</div>
