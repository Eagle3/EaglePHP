<?php

namespace lib\system\cache;

use lib\system\cache\abstractCache;

class Redis extends abstractCache {
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
        $this->cacheHandler = new \Redis();
        $this->cacheHandler->connect( $setOptions['SERVERS'][0]['HOST'], $setOptions['SERVERS'][0]['PORT'] );
        $select_db = 0;
        if ( isset( $setOptions['SELECT_DB'] ) ) {
            $select_db = $setOptions['SELECT_DB'];
        }
       
        $this->cacheHandler->select( $select_db );
    }
    public function get( $key ) {
        return unserialize( $this->cacheHandler->get( $key ) );
    }
    public function set( $key, $value, $expire = NULL ) {
        return $this->cacheHandler->set( $key, serialize( $value ), $expire ? $expire : $this->setOptions['CACHE_TIME'] );
    }
    public function delete( $key ) {
        return $this->cacheHandler->delete( $key );
    }
    public function clear() {
        return $this->cacheHandler->flushDB();
    }
    public function incr() {}
    public function decr() {}
    public function __destruct() {
        // $this->cacheHandler->close();
    }
}