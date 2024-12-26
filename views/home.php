<?php

$this->layout("template", ["title" => $title]);

?>

<h1>Welcome <?= $user->getName() ?> </h1>

<div id="body_home">
    <!-- Global summary -->

    <?php
        foreach($accounts as $account) {
            $account->__toString();
        }
    ?>
</div>