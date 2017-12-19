<?php

namespace Home\Model;

use Lib\System\Model;
use Lib\System\Cache\File;
use Lib\System\Cache\Memcache;
use Lib\System\Cache\Redis;

class UserModel extends Model {
    public function getInfo() {
        // $cache = File::getInstance();
        $cache = Memcache::getInstance();
        // $cache = Redis::getInstance();
        
        $key = 'test';
        $data = $cache->get( md5( $key ) );
        if ( !$data ) {
            echo '查询数据库<br>';
            $pdo = Model::getInstance();
            $data = $pdo->getAll( 'select * from user where id > :id ', array(
                    ':id' => 0 
            ) );
            $cache->set( md5( $key ), $data );
        }
        return $data;
    }
}
