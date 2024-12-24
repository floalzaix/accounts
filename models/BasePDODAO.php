<?php

namespace Models;

use PDO;
use Config\Config;
use PDOStatement;

class BasePDODAO {
    private $db;

    private function getDB() : void {
        if (!isset($this->db)) {
            $this->db = new PDO(Config::get("dsn"), Config::get("user"), Config::get("password"));
        }
    }

    protected function execRequest(string $sql, array $params=[]) : bool|PDOStatement {
        $this->getDB();
        $query = $this->db->prepare($sql);
        $query->execute($params);
        return $query;
    }
}

?>