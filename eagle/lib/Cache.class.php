<?php
namespace lib;

class Cache {
    private static $instance = NULL;
    
    public function __construct(){
        $cacheConfig = getConfig('');
        
    }
    
    /**
     * 单例模式获取Pdomysql对象
     * @return object Pdomysql对象
     */
    public static function getInstance(){
        if(is_null(self::$instance) || !is_object(self::$instance)){
            return new self();
        }
        return self::$instance;
    }
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
}