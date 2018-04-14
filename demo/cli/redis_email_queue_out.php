<?php
/**
 * redis 待发送邮件出队列
 * 
 * 命令行执行此文件，挂起服务
 */

set_time_limit( 0 );
header( "Content-type: text/html; charset=utf-8" );
$redis = new Redis();
$redis->pconnect( '10.0.2.195', 6379 ) or die( 'redis connect error' );

while ( true ) {
    $data = $redis->lPop( 'redis_email_queue' );
    if ( !empty( $data ) ) {
        echo $data.PHP_EOL;
        //调用发送邮件接口
        
    }
    sleep( rand() % 3 );
}