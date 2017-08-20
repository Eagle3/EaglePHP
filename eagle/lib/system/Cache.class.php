<?php

namespace lib\system;

class Cache {
    private static $instance = NULL;
    protected $cacheHandler = NULL;
    protected $setOptions = array();
    
    private function connect() {
        $cacheConfigArr = getConfig('CACHE_CONFIG');
        $cacheTag = '';
        $setOptions = array();
        switch ($cacheConfigArr['CACHE_TYPE']) {
            case 0:
                $cacheTag = 'FILE';
                $setOptions = $cacheConfigArr['FILE'];
                break;
            case 1:
                $cacheTag = 'memcache';
                $setOptions = $cacheConfigArr['MEMCACHE'];
                break;
            case 2:
                $cacheTag = 'redis';
                $setOptions = $cacheConfigArr['REDIS'];
                break;
            default:
                $cacheTag = 'file';
                $setOptions = $cacheConfigArr['FILE'];
        }
        $cacheTag = "lib\system\cache\\".ucfirst(strtolower($cacheTag));
        $instance = new $cacheTag($setOptions);
        self::$instance = $instance;
        return $instance;
    }
    
    public static function getInstance() {
        if (is_null(self::$instance) || !is_object(self::$instance)) {
            $obj = new Cache();
            return $obj->connect();
        }
        return self::$instance;
    }
}