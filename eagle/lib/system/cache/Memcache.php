<?php

namespace lib\system\cache;
use lib\system\cache\abstractCache;

class Memcache extends abstractCache {
    private static $instance = NULL;
    private $cacheHandler = NULL;
    private $setOptions = array();
    public static function getInstance() {
        if ( is_null( self::$instance ) || !is_object( self::$instance ) ) {
            $cacheConfigArr = getConfig( 'CACHE_CONFIG.MEMCACHE' );
            self::$instance = new self( $cacheConfigArr );
            return self::$instance;
        }
        return self::$instance;
    }
    private function __construct( $setOptions ) {
        if ( !$this->setOptions ) {
            $this->setOptions = $setOptions;
        }
        $this->cacheHandler = new \Memcache();
        
        // memcache扩展向连接池添加多台服务器
        $this->cacheHandler->connect($setOptions['SERVERS'][0]['HOST'],$setOptions['SERVERS'][0]['PORT']);
        $this->cacheHandler->addserver( $setOptions['SERVERS'][1]['HOST'], $setOptions['SERVERS'][1]['PORT'] );
        $this->cacheHandler->addserver($setOptions['SERVERS'][2]['HOST'],$setOptions['SERVERS'][2]['PORT']);
      
        // memcached扩展向连接池添加多台服务器
//         $this->cacheHandler = new \Memcached();
//         $this->cacheHandler->addServers($setOptions['SERVERS']);
        
    }
    public function get( $key ) {
        return $this->cacheHandler->get( $key );
    }
    public function set( $key, $value, $expire = null ) {
        return $this->cacheHandler->set( $key, $value, MEMCACHE_COMPRESSED, $expire ? $expire : $this->setOptions['CACHE_TIME'] );
    }
    public function delete( $key ) {
        return $this->cacheHandler->delete( $key );
    }
    public function clear() {
        return $this->cacheHandler->flush();
    }
    public function incr(){}
    public function decr(){}
    public function __destruct() {
        $this->cacheHandler->close();
    }
}