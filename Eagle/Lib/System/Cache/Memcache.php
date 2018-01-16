<?php

namespace Lib\System\Cache;

class Memcache {
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
        // 初始化
        $this->cacheHandler = new \Memcache();
        // 现在的写法只支持一台memcache服务器
        // $this->cacheHandler->connect($setOptions['SERVERS'][0]['HOST'],$setOptions['SERVERS'][0]['PORT']);
        
        $this->cacheHandler->addserver( $setOptions['SERVERS'][0]['HOST'], $setOptions['SERVERS'][0]['PORT'] );
        // $this->cacheHandler->addserver($setOptions['SERVERS'][1]['HOST'],$setOptions['SERVERS'][1]['PORT']);
    }
    public function get( $key ) {
        return $this->cacheHandler->get( $key );
    }
    public function set( $key, $value, $expire = null ) {
        $setOptions = $this->setOptions;
        return $this->cacheHandler->set( $key, $value, MEMCACHE_COMPRESSED, $expire ? $expire : $setOptions['CACHE_TIME'] );
    }
    public function delete( $key ) {
        return $this->cacheHandler->delete( $key );
    }
    public function clear() {
        return $this->cacheHandler->flush();
    }
    public function __destruct() {
        $this->cacheHandler->close();
    }
}