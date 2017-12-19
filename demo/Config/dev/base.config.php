<?php
return array(
        //项目中是否全局开启session true开启
        'SESSION_OPEN' => true,
        
        //模板引擎选择 1:Smarty 2:内置
        'TPL_ENGINE' => 1,
        
        //smarty模板引擎配置
        'SMARTY_TPL_CONFIG' => array(
                'debugging' => false, //开启调试
                'caching' => false,              //是否使用缓存
                'cache_lifetime' => 0, //缓存时间
                'template_dir' => APP_PATH,//设置模板目录
                'compile_dir' => '/eagleTemp/smarty_templates_c',//设置编译目录
                'cache_dir' => '/eagleTemp/cache/smarty_templates_cache',//缓存文件夹 
                //修改左右边界符号
                'delimiter' => array(
                        'left_delimiter' => '{',
                        'right_delimiter' => '}',
                ),
        
        ),
        
        //系统内置模板引擎配置
        'SYSTEM_TPL_CONFIG' => array(
                'debugging' => false, //开启调试
                'caching' => false,              //是否使用缓存
                'cache_lifetime' => 0, //缓存时间
                'template_dir' => APP_PATH,//设置模板目录
                'compile_dir' => DIR.'/Tmp/system_templates_c/',//设置编译目录
                'cache_dir' => DIR.'/Tmp/cache/system_templates_cache/',//缓存文件夹
                //修改左右边界符号
                'delimiter' => array(
                        'left_delimiter' => '{',
                        'right_delimiter' => '}',
                ),

        ),
        
        
        //验证码验证类型： 1，cookie  2，session
        'DEFAULT_CODE_VERIFY' => 2,
        'DEFAULT_CODE_NAME' => '_verifyCode',
        
        //模板默认后缀
        'DEFAULT_TPL_POSTFIX' => '.html',
        
        //分组下默认模板
        'DEFAULT_TPL_NAME' => array(
                'Home' => 'View',
                'Admin' => 'View',
        ),
        
        //默认语言包
        'DEFAULT_LANGUAGE' => 'zh-cn',
        
        //默认访问
        'DEFAULT_CALL' => array(
        		'ROUTE_PARAM' => 'r',
        		'CONTROLLER_PARAM' => 'c',
        		'ACTION_PARAM' => 'a',
                'ROUTE' => 'Home',
                'CONTROLLER' => 'Index',
                'ACTION' => 'index',
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
                'FILE' => array(
                        'CACHE_TIME' => 3, //缓存时间
                        'CACHE_PATH' => './Tmp/cache/file_cache/',//设置文件缓存目录
                        'CACHE_PREFIX' => 'file', //文件缓存前缀
                        'CACHE_POSTFIX' => '.txt', //缓存文件后缀名
                ),
                'REDIS' => array(
                        'CACHE_TIME' => 3, //缓存时间
                        //多台redis服务器
                        'SERVERS' => array(
                                array('HOST' => '10.0.2.195', 'PORT' => '6379',),
                                //array('HOST' => '127.0.0.1', 'PORT' => '6379',),
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