<?php
namespace Lib\System\Cache;
use Lib\System\Cache;

class Redis extends Cache {
    public function __construct($setOptions){
        if(!$this->setOptions){
            $this->setOptions = $setOptions;
        }
        //初始化
        $this->cacheHandler = new \Redis();pr($this->cacheHandler);
        $this->cacheHandler->connect($setOptions['SERVERS'][0]['HOST'],$setOptions['SERVERS'][0]['PORT']);
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
        return $this->cacheHandler->flushDB();
    }

    public function __destruct(){
        //$this->cacheHandler->close();
    }

}