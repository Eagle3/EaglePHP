<?php

namespace Lib\System;

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
                $cacheTag = 'File';
                $setOptions = $cacheConfigArr['FILE'];
                break;
            case 1:
                $cacheTag = 'Memcache';
                $setOptions = $cacheConfigArr['MEMCACHE'];
                break;
            case 2:
                $cacheTag = 'Redis';
                $setOptions = $cacheConfigArr['REDIS'];
                break;
            default:
                $cacheTag = 'File';
                $setOptions = $cacheConfigArr['FILE'];
        }
        
        $cacheTag = "Lib\System\Cache\\".ucfirst(strtolower($cacheTag));
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