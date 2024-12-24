<?php

require_once("helpers/psr4_autoloader_class.php");
$loader = new Helpers\Psr4AutoloaderClass();
$loader->register();

$loader->addNamespace("\League\Plates", "/vendor/plates-3.6.0/src");
$loader->addNamespace("\Controllers", "/controllers");
$loader->addNamespace("\Controllers\Router", "/controllers/router");
$loader->addNamespace("\Controllers\Router\Routes", "/controllers/router/routes");
$loader->addNamespace("\Models", "/models");
$loader->addNamespace("\Helpers", "/helpers");
$loader->addNamespace("\Config", "/config");


use Controllers\Router\Router;

$router = new Router();

$router->routing($_GET, $_POST);

?>