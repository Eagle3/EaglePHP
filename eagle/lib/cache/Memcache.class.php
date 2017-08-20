<?php
namespace lib\cache;
use lib\Cache;
class Memcache extends Cache {
    public function __construct($setOptions){
        if(!$this->setOptions){
            $this->setOptions = $setOptions;
        }
        //初始化
        $this->cacheHandler = new \Memcache();
        //现在的写法只支持一台memcache服务器
        //$this->cacheHandler->connect($setOptions['SERVERS'][0]['HOST'],$setOptions['SERVERS'][0]['PORT']);
        
        $this->cacheHandler->addserver($setOptions['SERVERS'][0]['HOST'],$setOptions['SERVERS'][0]['PORT']);
        //$this->cacheHandler->addserver($setOptions['SERVERS'][1]['HOST'],$setOptions['SERVERS'][1]['PORT']);
    }
    
    public function get($key){
        return $this->cacheHandler->get($key);
    }
    
    public function set($key,$value,$expire = null){
        $setOptions = $this->setOptions;
        return $this->cacheHandler->set($key,$value, MEMCACHE_COMPRESSED, $expire ? $expire : $setOptions['CACHE_TIME']);
    }
    
    public function delete($key){
        return $this->cacheHandler->delete($key);
       
    }
    
    public function clear(){
        return $this->cacheHandler->flush();
    }
    
    public function __destruct(){
        $this->cacheHandler->close();
    }
}