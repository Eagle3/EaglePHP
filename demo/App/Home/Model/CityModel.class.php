<?php

namespace Home\Model;

use Lib\System\Model;

class CityModel extends Model {
    public function getInfo() {
        $pdo = Model::getInstance();
        $data = $pdo->getAll( 'select area_id ,parent_id,area_name  from citys ');
        return $data;
    }
}