<?php

namespace lib\system\cache;

class Redis {
    private static $instance = NULL;
    private $cacheHandler = NULL;
    private $setOptions = array();
    public static function getInstance() {
        if ( is_null( self::$instance ) || !is_object( self::$instance ) ) {
            $cacheConfigArr = getConfig( 'CACHE_CONFIG.REDIS' );
            self::$instance = new self( $cacheConfigArr );
            return self::$instance;
        }
        return self::$instance;
    }
    public function __construct( $setOptions ) {
        if ( !$this->setOptions ) {
            $this->setOptions = $setOptions;
        }
        // 初始化
        $this->cacheHandler = new \Redis();
        $this->cacheHandler->connect( $setOptions['SERVERS'][0]['HOST'], $setOptions['SERVERS'][0]['PORT'] );
    }
    public function get( $key ) {
        return unserialize( $this->cacheHandler->get( $key ) );
    }
    public function set( $key, $value, $expire = null ) {
        $value = serialize( $value );
        $setOptions = $this->setOptions;
        return $this->cacheHandler->set( $key, $value, $expire ? $expire : $setOptions['CACHE_TIME'] );
    }
    public function delete( $key ) {
        return $this->cacheHandler->delete( $key );
    }
    public function clear() {
        return $this->cacheHandler->flushDB();
    }
    public function __destruct() {
        // $this->cacheHandler->close();
    }
}