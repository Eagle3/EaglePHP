<?php
return array(
        //模板默认后缀
        'DEFAULT_TPL_POSTFIX' => '.html',
        
        //分组下默认模板
        'DEFAULT_TPL_NAME' => array(
                'home' => 'view',
                'admin' => 'view',
        ),
        
        //默认语言包
        'DEFAULT_LANGUAGE' => 'zh-cn',
        
        //默认访问
        'DEFAULT_CALL' => array(
                'DEFAULT_ROUTE_PARAM' => '_EAGLE_ROUTE_',
                'DEFAULT_CONTROLLER_PARAM' => '_EAGLE_CONTROLLER_',
                'DEFAULT_ACTION_PARAM' => '_EAGLE_ACTION_',
        		'DEFAULT_ROUTE_PARAM' => 'r',
        		'DEFAULT_CONTROLLER_PARAM' => 'c',
        		'DEFAULT_ACTION_PARAM' => 'a',
                'DEFAULT_ROUTE' => 'home',
                'DEFAULT_CONTROLLER' => 'Index',
                'DEFAULT_ACTION' => 'index',
        ),
        
        //数据库配置
        'PDO_CONFIG' => array(
                'host' => 'localhost',
                'userName' => 'root',
                'passWord' => 'root',
                'port' => 3306,
                'dbName' => 'test',
                'prefix' => '',
                'charSet' => 'utf8',
                'driverOptions' => array(
        
                ),
        ),
        
        //缓存配置
        'CACHE_CONFIG' => array(
                'CACHE_TYPE' => 1, //缓存类型：0=file | 1=memcache | 2=redis
                'FILE' => array(
                        'CACHE_TIME' => 60, //缓存时间
                        'CACHE_PATH' => './tmp/cache/file_cache/',//设置文件缓存目录
                        'CACHE_PREFIX' => 'file', //文件缓存前缀
                        'CACHE_POSTFIX' => '.txt', //缓存文件后缀名
                ),
                'REDIS' => array(
                        'CACHE_TIME' => 2, //缓存时间
                        //多台redis服务器
                        'SERVERS' => array(
                                array('HOST' => '127.0.0.1', 'PORT' => '6379',),
                                //array('HOST' => '10.0.6.194', 'PORT' => '6379',),
                        ),
                ),
                'MEMCACHE' => array(
                        'CACHE_TIME' => 3, //缓存时间
                        //多台memcache服务器
                        'SERVERS' => array(
                                array('HOST' => '127.0.0.1', 'PORT' => '11211',),
                                //array('HOST' => '10.0.6.194', 'PORT' => '11211',),
                        ),
                ),
        ),
);