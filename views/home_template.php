<!doctype html>
<html lang="fr">
    <head>
        <meta charset="UTF-8"/>
        <link rel="stylesheet" href="public/css/main.css"/>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title><?= $this->e($title) ?></title>
    </head>
    <body>
        <div id="main_home">
            <nav id="upper_menu">
                <a href="index.php?action=home">
                    <img alt="accueil" src="/public/images/icones/accueil.png" />
                </a>
                <a id="plus" href="index.php?action=add-account">
                    <img alt="accueil" src="/public/images/icones/plus.png" />
                </a>
                <a href="index.php?action=deco">
                    <img alt="accueil" src="/public/images/icones/deconnexion.png" />
                </a>
            </nav>

            <?= $this->section('content') ?>
        </div>
    </body>
</html>
