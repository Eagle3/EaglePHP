<?php
/**
 * redis 待发送邮件入队列
 * 
 * 命令行执行此文件
 */

$redis = new Redis();
while ( true ) {
    $redis->connect( '10.0.2.195', 6379 ) or die( 'redis connect error' );
    
    //模拟产生待发送邮件并入redis队列
    $email = mt_rand( 100, 10000 ) . '@qq.com';
    $redis->rPush( 'redis_email_queue', $email );
    
    sleep( 1 );
}