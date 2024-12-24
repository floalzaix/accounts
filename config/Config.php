<?php

namespace Config;

use Exception;

/**
 * Summary of Config
 * Creates a configuration file framwork to configure your project
 */
class Config {
    private static $params;

    /**
     * Summary of get
     * A function to get the parameter set in the configuration file read prod.ini or dev.ini
     * @param mixed $param
     * @param mixed $default_value
     * @return mixed
     */
    public static function get($param, $default_value = null) : mixed {
        if (!isset($params)) {
            self::getParams();
        }
        $value = $default_value;
        if (isset(self::$params[$param])) {
            $value = self::$params[$param];
        }
        return $value;
    }

    /**
     * Summary of getParams
     * Set the $param value of the class to the parameters of the prod.ini ou dev.init
     * @throws \Exception
     * @return void
     */
    private static function getParams() : void {
        $file = "config/prod.ini";
        if (!file_exists($file)) {
            $file = "config/dev.ini";
        }
        if (file_exists($file)) {
            self::$params = parse_ini_file($file);
        } else {
            throw new Exception("Il n'y a pas de fichier de configuration prod.ini ou dev.ini");
        }
    }
}

?>
