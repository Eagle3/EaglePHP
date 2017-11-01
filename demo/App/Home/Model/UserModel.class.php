<?php
namespace Home\Model;

use Lib\System\Cache;
use Lib\System\Model;

class UserModel extends Model {
    public function getInfo(){
        $cache = Cache::getInstance();
        $key = 'test';
        $data = $cache->get(md5($key));
        if(!$data){
            echo '查询数据库<br>';
            $pdo = Model::getInstance();
            $data = $pdo->getAll('select * from user where id > :id ',array(':id'=>0));
            $cache->set(md5($key),$data);
        }
        return $data;
    }
}
