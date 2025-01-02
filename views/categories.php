<?php

$this->layout("template", ["title" => $title, "id_account" => $id_account, "account_name" => $account_name]);

?>

<div id="body_categories">
    <div class="categories_display">
        <?php
            foreach($categories as $category) {
                if ($category->getLevel() == 1) {
                    $category->__toString();
                }
            }
        ?>

        <form action="index.php?action=categories&id=<?= $id_account ?>" method="POST">
            <?php if ($edit_category) { ?>
                <input type="text" id="name" name="name" maxlength="50" value="<?= $cat->getName() ?>" />
                <select id="parent" name="parent">
                    <?php
                        echo "<option value=''>Racine</option>";
                        foreach($categories as $category) {
                            if ($category->getLevel() <= $nb_of_categories-1 && $cat->getId() != $category->getId()) {
                                if ($cat->getIdParent() == $category->getId()) {
                                    echo "<option value='{$category->getId()}' selected>".$category->getName()."</option>";
                                } else {
                                    echo "<option value='{$category->getId()}'>".$category->getName()."</option>";
                                }
                            }
                        }
                    ?>
                </select>
                <input type="submit" id="submit_button" name="submit_button" value="Modifier" />
                <input type="hidden" id="edit_category" name="edit_category" value="true" />
                <input type="hidden" id="id_cat" name="id_cat" value="<?= $cat->getId() ?>" />
            <?php } else { ?>
                <input type="text" id="name" name="name" maxlength="50" placeholder="Nom de la catégorie" />
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
                <input type="submit" id="submit_button" name="submit_button" value="Ajouter" />
            <?php } ?>
        </form>
    </div>
</div>

<?= Helpers\MessageHandler::displayMessage("categories") ?>