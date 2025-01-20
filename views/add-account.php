<?php

$this->layout("home_template", ["title" => $title]);

?>

<div id="body_add_account">
    <h1>Ajouter un compte</h1>

    <form action="index.php?action=add-account" method="POST">
        <div class="input">
            <img alt='titre' src='/public/images/icones/titre.png' />
            <input type="text" id="account_name" name="account_name" maxlength="100" placeholder="Nom du compte" />
        </div>
        <div class="input">
            <img alt='categories' src='/public/images/icones/categorie.png' />
            <input type="number" id="account_nb_cat" name="account_nb_cat" min="1" max="10" value="2" />
        </div>
        <input type="image" class="icon" id="submit_button" name="submit_button" alt="Soumettre" src="/public/images/icones/soumettre.png"/>
    </form>
    
    <?= Helpers\MessageHandler::displayMessage("add-account") ?>
</div>