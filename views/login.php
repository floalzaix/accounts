<?php 
    /**
     * Définition du template
     */
    $this->layout("login_template", ["title" => $title]);
    
?>

<div id="body_login">
    <h1>Connexion</h1>

    <form action="index.php?action=login" method="POST">
        <div class="input">
            <img class="icon" alt="Icon nom d'utilisateur" src="/public/images/icones/compte.png" />
            <input type="text" id="login_username_name" name="login_name" maxlength="100" placeholder="Nom d'utilisateur" />
        </div>

        <div class="input">
            <img class="icon" alt="Icon mot de passe" src="/public/images/icones/cle.png" />
            <input type="password" id="login_userpwd_pwd" name="login_pwd" maxlength="30" placeholder="Mot de passe" />
        </div>

        <input type="image" class="icon" id="submit_button" name="submit_button" alt="Soumettre" src="/public/images/icones/soumettre.png"/>
    </form>

    <div id="login_links">
        <a href="index.php?action=register" class="button">CREER</a>
        <a href="index.php?action=recovery" class="button" id="empty">MOT DE PASSE OUBLIE</a>
    </div>

    <?= Helpers\MessageHandler::displayMessage("login") ?>
</div>
