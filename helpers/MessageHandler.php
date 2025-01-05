<?php

namespace Helpers;

/**
 * Summary of MessageHandler
 * 
 * Module to deal with the errors message of the controllers in a mvc architecture.
 * The goal is to ease the use of displaying state messages.
 * 
 * You can set the style of the message bellow.
 * 
 * If you need you can extend a class to spread the module's utility.
 * 
 * For instance, if you want to display a "Error message" in a controller on the page "main" then you use :
 *      MessageHandler::setMessageToPage("Error message", "main", true/false depending on if you want it displayed as an error message)
 * 
 * and in the view :
 *      MessageHandler::displayMessage("main")
 * 
 */
class MessageHandler {
    protected static array $messages = [];
    protected static array $errors = [];

    /**
     * Summary of setMessageToPage
     * To store the desired message in the errors list or the messages list depending if $error is true/false
     * @param string $message
     * @param string $page
     * @param bool $error
     * @return void
     */
    public static function setMessageToPage(string $message, string $page, bool $error = false) : void {
        if ($page != "") {
            if ($error) {
                self::$errors[$page] = $message;
            } else {
                self::$messages[$page] = $message;
            }
        } else {
            self::$errors["error"] = "Erreur lors de l'ajout d'un message : la variable page est vide";
        }
    }

    /**
     * Summary of displayMessage
     * Display the messages of a page and in red if there is an error
     * @param string $page
     * @return void
     */
    public static function displayMessage(string $page) : void {
        if ($page != "") {
            if (isset(self::$messages[$page])) {
                echo "<div class='message'>".self::$messages[$page]."</div>";
            } 
            if (isset(self::$errors[$page])) {
                echo "<div class='message' style='color: red;'>".self::$errors[$page]."</div>";
            }
            if (isset(self::$errors["error"])) {
                echo "<div class='message' style='color: red;'>".self::$errors["error"]."</div>";
            }
        } else {
            self::$errors["error"] = "Erreur lors de l'affichage d'un message : la variable page est vide";
        }
    }
}

?>

<style>
    /*Style of the messages*/
    
    .message {
        width: 100%;
        padding: 26px;

        display: flex;
        flex-direction: column;
        text-align: center;
        justify-content: center;
    
        color: white;
        font: bold 1em "arial-black", Arial, sans-serif;
    }
</style>