<!doctype html>
<html lang="fr">
    <head>
        <meta charset="UTF-8"/>
        <link rel="stylesheet" href="public/css/main.css"/>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title><?= $this->e($title) ?></title>
    </head>
    <body>
        <!-- #contenu -->
        <main id="main_account">
            <a href="index.php?action=home">Home</a>
            <a href="index.php?action=add-account">+</a>
            <a href="index.php?action=deco">Quit</a>

            <nav class="side_nav">
                <div class="account_name_nav"> <?= $account_name ?> </div>
                <a href="index.php?action=summary&id=<?= $id_account ?>">Récapitulatif</a>
                <a href="index.php?action=details&id=<?= $id_account ?>">Détails</a>
                <a href="index.php?action=inputs&id=<?= $id_account ?>">Transactions</a>
                <a href="index.php?action=categories&id=<?= $id_account ?>">Catégories</a>
            </nav>

            <?= $this->section('content') ?>
        </main>
    </body>
</html>
