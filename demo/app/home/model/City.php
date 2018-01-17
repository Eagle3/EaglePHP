<?php

namespace home\model;

use lib\system\Model;

class City extends Model {
    public function getInfo() {
        $pdo = Model::getInstance();
        $data = $pdo->getAll( 'select *  from citys ');
        return $data;
    }
}