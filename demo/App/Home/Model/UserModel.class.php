<?php
namespace Home\Model;

use Home\Model\CommonModel;
use Lib\System\Cache;
use Lib\System\Pdomysql;

class UserModel extends CommonModel {
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
