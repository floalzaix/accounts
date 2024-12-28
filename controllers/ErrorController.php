<?php

namespace Controllers;

use League\Plates\Engine;
use Config\Config;

class ErrorController {
    private $templates;

    public function __construct() {
        $this->templates = new Engine("views");
    }

    public function displayError404() : void {
        echo $this->templates->render("err-404", ["title" => Config::get("title")]);
    }
    
    public function displayError500() : void {
        echo $this->templates->render("err-500", ["title" => Config::get("title")]);
    }
}

?>