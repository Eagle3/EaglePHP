<?php

namespace home\model;

use lib\system\Model;
//use lib\system\Cache\File;
use lib\system\Cache\Memcache;
//use lib\system\Cache\Redis;

class User extends Model {

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
    public function insertData() {
        $model = new Model();
        return $model->insert( 'user', array(
                'name' => 'jack11',
                'test' => 20 
        ) );
    }
    
    public function insertAllData() {
        /*
        set_time_limit(0);
        $model = new Model();
        $insertData = array();
        $s = microtime(true);
        for ( $num=1;$num<=90;$num++){
            for ($i=1;$i<=1000;$i++){
                $fun = 'getChar2';
                if( mt_rand(0,10) % 2 == 0 ){
                    $fun = 'getChar';
                }
                
                $insertData[$i-1]['uid'] = md5(uniqid('',true));
                $insertData[$i-1]['name'] = $this->$fun();
                $insertData[$i-1]['content'] = $this->$fun();;
            }
           $model->insertAll( 'ceshi', $insertData);
           $insertData = [];
        }
        $e = microtime(true);
        echo $e - $s.'<br>';
        return 1;
        */
        
        set_time_limit(0);
        $model = new Model();
        $insertData = array();
        $s = microtime(true);
        for ( $num=1;$num<=3000;$num++){
            for ($i=1;$i<=1000;$i++){
                $insertData[$i-1]['from'] = md5(uniqid('',true));
            }
            $model->insertAll( 'ceshi2', $insertData);
            $insertData = [];
        }
        $e = microtime(true);
        echo $e - $s.'<br>';
        return 1;
    }
    
    // $num为生成汉字的数量
    private function getChar($num = 8)  
    {
        $b = '';
        for ($i=0; $i<$num; $i++) {
            // 使用chr()函数拼接双字节汉字，前一个chr()为高位字节，后一个为低位字节
            $a = chr(mt_rand(0xB0,0xD0)).chr(mt_rand(0xA1, 0xF0));
            // 转码
            $b .= iconv('GB2312', 'UTF-8', $a);
        }
        return $b;
    }
    
    // $num为生成字母的数量
    private function getChar2( $length = 8 ){// 密码字符集，可任意添加你需要的字符
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $password = '';
        for ( $i = 0; $i < $length; $i++ ){
        
            // 这里提供两种字符获取方式
            // 第一种是使用 substr 截取$chars中的任意一位字符；
            // 第二种是取字符数组 $chars 的任意元素
            // $password .= substr($chars, mt_rand(0, strlen($chars) – 1), 1);
            $password .= $chars[ mt_rand(0, strlen($chars) - 1) ];
        }
        return $password; 
    }
    
    public function updateData() {
        $model = new Model();
        return $model->update( 'user', array(
                'name' => 'jack66',
                'test' => 21 
        ), 'id=:id', array(
                ':id' => 8 
        ) );
    }
    public function deleteData() {
        $model = new Model();
        return $model->delete( 'user', 'id=:id', array(
                ':id' => 7 
        ) );
    }
}
