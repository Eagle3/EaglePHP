<?php
namespace home\model;
use lib\system\Pdomysql;
use lib\system\Cache;
class UserModel extends Pdomysql {
    public function getInfo(){
        $cache = Cache::getInstance();
        $key = 'test';
        $data = $cache->get(md5($key));
        if(!$data){
            echo 1;
            $pdo = Pdomysql::getInstance();
            $data = $pdo->getAll('select * from user where id > :id ',array(':id'=>340));
            $cache->set(md5($key),$data);
        }
       return $data;
    }
}
