<?php 
    /**
     * Définition du template
     */
    $this->layout("template", ["title" => $title]);
?>

<h1>Login</h1>

<div id="body_login">
    <form action="index.php" method="POST">
        <input type="text" id="login_name" name="login_name" maxlength="100" placeholder="Nom d'utilisateur" />
        <input type="password" id="login_pwd" name="login_pwd" maxlength="30" placeholder="Mot de passe" />
        <input type="submit" id="submit_button" name="submit_button" value="Valider" />
    </form>

    <a href="index.php?action=register" class="bouton">Creer</a>
    <a href="index.php?action=recovery" class="bouton">Mot de passe oublié</a>
</div>