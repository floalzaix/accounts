<?php
    $this->layout("template", ["title" => $title]);
    
    use Helpers\MessageHandler;
?>

<h1>Register</h1>

<div id="body_register">
    <form action="index.php?action=register" method="POST">
        <input type="text" id="login_name" name="login_name" maxlength="100" placeholder="Nom d'utilisateur" />
        <input type="password" id="login_pwd" name="login_pwd" maxlength="30" placeholder="Mot de passe" />
        <input type="password" id="login_pwd_confirm" name="login_pwd_confirm" maxlength="30" placeholder="Confirmer mot de passe" />
        <input type="submit" id="submit_button" name="submit_button" value="Valider" />
    </form>

    <a href="index.php?action=login" class="bouton">Connection</a>
    <a href="index.php?action=recovery" class="bouton">Mot de passe oublié</a>
</div>

<?= MessageHandler::displayMessage("register"); ?>