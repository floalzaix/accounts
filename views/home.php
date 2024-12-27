<?php

$this->layout("template", ["title" => $title]);

?>

<h1>Welcome <?= $user_name ?> </h1>

<div id="body_home">
    <!-- Global summary -->

    <?php
        foreach($accounts as $account) {
            echo $account->__toString();
        }
    ?>
</div>