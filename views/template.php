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
                <div id="account_name"> <?= $account_name ?> </div>
                <a href="index.php?action=summary&id=<?= $id_account ?>">
                    <img alt="charte de croissance" src="/public/images/icones/charte-de-croissance.png" />
                    Récapitulatif
                </a>
                <a href="index.php?action=details&id=<?= $id_account ?>">
                    <img alt="loupe" src="/public/images/icones/loupe.png" />
                    Détails
                </a>
                <a href="index.php?action=inputs&id=<?= $id_account ?>">
                    <img alt="transaction icone" src="/public/images/icones/transaction.png" />
                    Transactions
                </a>
                <a href="index.php?action=categories&id=<?= $id_account ?>">
                    <img alt="catégorie icone" src="/public/images/icones/categorie.png" />
                    Catégories
                </a>
            </nav>

            <div id="body_account">
                <?= $this->section('content') ?>
            </div>
        </div>
    </body>
</html>
