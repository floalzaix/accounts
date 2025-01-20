<?php

$this->layout("template", ["title" => $title, "id_account" => $id_account, "account_name" => $account_name]);

?>

<div id="body_categories">
    <h1>Catégories</h1>

    <div id="categories_display">
        <?php
            foreach($categories as $category) {
                if ($category->getLevel() == 1) {
                    echo "<div class='first_cat'>";
                        $category->__toString();
                    echo "</div>";
                }
            }
        ?>
    </div>

    <form action="index.php?action=categories&id=<?= $id_account ?>" method="POST">
        <?php if ($edit_category) { ?>
            <div class="input">
                <img alt='titre' src='/public/images/icones/titre.png' />
                <input type="text" id="name" name="name" maxlength="50" value="<?= $cat->getName() ?>" />
            </div>
            <div class='input'>
                <img alt='categories' src='/public/images/icones/categorie.png' />
                <select id="parent" name="parent">
                    <?php
                        echo "<option value=''>Racine</option>";
                        foreach($categories as $category) {
                            if ($cat->getId() != $category->getId()) {
                                if ($cat->getIdParent() == $category->getId()) {
                                    echo "<option value='{$category->getId()}' selected>".$category->getName()."</option>";
                                } else {
                                    echo "<option value='{$category->getId()}'>".$category->getName()."</option>";
                                }
                            }
                        }
                    ?>
                </select>
            </div>
            <input type="image" class="icon" id="submit_button" name="submit_button" alt="Soumettre" src="/public/images/icones/soumettre.png"/>
            <input type="hidden" id="edit_category" name="edit_category" value="true" />
            <input type="hidden" id="id_cat" name="id_cat" value="<?= $cat->getId() ?>" />
        <?php } else { ?>
            <div class="input">
                <img alt='titre' src='/public/images/icones/titre.png' />
                <input type="text" id="name" name="name" maxlength="50" placeholder="Nom de la catégorie" />
            </div>
            <div class='input'>
                <img alt='categories' src='/public/images/icones/categorie.png' />
                <select id="parent" name="parent">
                    <?php
                        echo "<option value=''>Racine</option>";
                        foreach($categories as $category) {
                            if ($category->getLevel() <= $nb_of_categories-1) {
                                echo "<option value='{$category->getId()}'>".$category->getName()."</option>";
                            }
                        }
                    ?>
                </select>
            </div>
            <input type="image" class="icon" id="submit_button" name="submit_button" alt="Soumettre" src="/public/images/icones/soumettre.png"/>
        <?php } ?>
    </form>
    
    <?= Helpers\MessageHandler::displayMessage("categories") ?>
</div>
