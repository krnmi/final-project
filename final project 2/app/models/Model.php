<?php

namespace app\models;

use PDO;

abstract class Model {

    private function connect() {
        $dsn = "mysql:host=" . DBHOST . ";port=8889;dbname=" . DBNAME;
        $con = new PDO($dsn, DBUSER, DBPASS);
        return $con;    
    }

    public function query($query, $data = []) {
        $con = $this->connect();
        $stm = $con->prepare($query);
        $check = $stm->execute($data);
    
        if (stripos(trim($query), 'SELECT') === 0) {
            return $stm->fetchAll(PDO::FETCH_ASSOC);
        }
    
        return $check;    
    }

}
