<?php
return array(
        /** 只在系统配置文件中有效，项目配置文件中无效  start **/
        
        //smarty模板引擎配置
        'SMARTY_TPL_CONFIG' => array(
                'debugging' => false, //开启调试
                'caching' => false,              //是否使用缓存
                'cache_lifetime' => 0, //缓存时间
                'template_dir' => APP_PATH,//设置模板目录
                'compile_dir' => './tmp/smarty_templates_c',//设置编译目录
                'cache_dir' => './tmp/cache/smarty_templates_cache',//缓存文件夹
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
				'compile_dir' => DIR.'/tmp/system_templates_c/',//设置编译目录
				'cache_dir' => DIR.'/tmp/cache/system_templates_cache/',//缓存文件夹
				//修改左右边界符号
				'delimiter' => array(
						'left_delimiter' => '{',
						'right_delimiter' => '}',
				),
		
		),
		
        /** 只在系统配置文件中有效，项目配置文件中无效  end **/
        
        
        /** 可以在系统配置文件填写，也可在项目配置文件中填写，同时填写以项目中的为准  start **/
        //模板默认目录标识
        'DEFAULT_TPL_NAME' => 'view',
        
        //模板默认后缀
        'DEFAULT_TPL_POSTFIX' => '.html',
        
        //默认语言包
        'DEFAULT_LANGUAGE' => 'zh-cn',
        
        //默认访问
        'DEFAULT_CALL' => array(
                'DEFAULT_ROUTE_PARAM' => 'r',
                'DEFAULT_CONTROLLER_PARAM' => 'c',
                'DEFAULT_ACTION_PARAM' => 'a',
                'DEFAULT_ROUTE' => 'home',
                'DEFAULT_CONTROLLER' => 'Index',
                'DEFAULT_ACTION' => 'index',
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
                        'CACHE_TIME' => 70, //缓存时间
                ),
                'MEMCACHE' => array(
                        'CACHE_TIME' => 80, //缓存时间
                        //多台memcache服务器
                        'SERVERS' => array(
                            array('HOST' => '127.0.0.1', 'PORT' => '11211',),
                            array('HOST' => '127.0.0.1', 'PORT' => '11212',),
                         ),
                ),
        ),
        /** 可以在系统配置文件填写，也可在项目配置文件中填写，同时填写以项目中的为准  end **/
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
);
