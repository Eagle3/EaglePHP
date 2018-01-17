<?php
return array(
        //缓存配置
        'CACHE_CONFIG' => array(
                'CACHE_TYPE' => 'file', //缓存类型：file memcache redis
                'CACHE_TIME' => 60, //缓存时间
                'CACHE_PATH' => './tmp/cache/file_cache',//设置文件缓存目录
        ),
);