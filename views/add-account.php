<?php

$this->layout("home_template", ["title" => $title]);

?>

<h1>Ajouter un compte</h1>

<div id="body_add_account">
    <form action="index.php?action=add-account" method="POST">
        <input type="text" id="account_name" name="account_name" maxlength="100" placeholder="Nom du compte" />
        <input type="number" id="account_nb_cat" name="account_nb_cat" min="1" max="10" value="2" />
        <input type="submit" id="submit_button" name="submit_button" value="Confirmer" />
    </form>
</div>

<?= Helpers\MessageHandler::displayMessage("add-account") ?>