<?php
/*
 *
 * PHP与HTML5 websocket实现即时聊天
 *
 * 第一步：服务端挂起服务
 * 命令行模式执行： php socketServerSwoole.php
 *
 * 第二步：客户端连接
 * 访问如下地址： http://eagle.test/index.php?r=home&c=index&a=websocketSwoole
 *
 *
 */

// 创建websocket服务器对象，监听0.0.0.0:9502端口
$server = new swoole_websocket_server( "10.0.6.196", 8889 );

$server->on( 'open', function ( swoole_websocket_server $server, $request ) {
    echo "server: handshake success with fd{$request->fd}\n";
} );

$server->on( 'message', function ( swoole_websocket_server $server, $frame ) {
    foreach ( $server->connections as $key => $fd ) {
        $user_message = $frame->data;
        $server->push( $fd, $user_message );
    }
} );

$server->on( 'close', function ( $ser, $fd ) {
    echo "client {$fd} closed\n";
} );

$server->start();