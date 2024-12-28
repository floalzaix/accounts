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
        <main id="contenu">
            <a href="index.php?action=home">Home</a>
            <a href="index.php?action=add-account">+</a>
            <a href="index.php?action=deco">Quit</a>

            <nav class="side_nav">
                <a href="index.php?action=inputs&id=<?= $id_account ?>">Données</a>
                <a href="index.php?action=summary&id=<?= $id_account ?>">Récap</a>
            </nav>

            <?= $this->section('content') ?>
        </main>
    </body>
</html>
