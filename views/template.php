<!doctype html>
<html lang="fr">
    <head>
        <meta charset="UTF-8"/>
        <link rel="stylesheet" href="public/css/main.css"/>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title><?= $this->e($title) ?></title>
    </head>
    <body>
        <div id="main_account">
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

            <nav id="side_menu">
                <div class="account_name_nav"> <?= $account_name ?> </div>
                <a href="index.php?action=summary&id=<?= $id_account ?>">Récapitulatif</a>
                <a href="index.php?action=details&id=<?= $id_account ?>">Détails</a>
                <a href="index.php?action=inputs&id=<?= $id_account ?>">Transactions</a>
                <a href="index.php?action=categories&id=<?= $id_account ?>">Catégories</a>
            </nav>

            <?= $this->section('content') ?>
        </div>
    </body>
</html>
