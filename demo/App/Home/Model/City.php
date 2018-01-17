<?php

namespace Home\Model;

use Lib\System\Model;

class City extends Model {
    public function getInfo() {
        $pdo = Model::getInstance();
        $data = $pdo->getAll( 'select *  from citys ');
        return $data;
    }
}