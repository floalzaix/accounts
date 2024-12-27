<?php

$this->layout("home_template", ["title" => $title]);

?>

<h1>Welcome <?= $user_name ?> </h1>

<div id="body_home">
    <!-- Global summary -->

    <?php
        foreach($accounts as $account) {
            $account->__toString();
        }
    ?>
</div>