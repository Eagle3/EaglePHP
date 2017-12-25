<?php

namespace Lib\System;

class Download {
    public function __construct( $filename ) {}
    public function exe( $file ) {
        $position = strripos( $file, '.' );
        $postfix = substr( $file, $position + 1 );
        $file = iconv( 'utf-8', 'gbk', $file ); // 兼容windows中文文件名
        $name = date( 'Ymdhis' ) . '.' . $postfix;
        header( "Content-type: application/octet-stream" );
        header( "Content-Type: application/force-download" );
        header( "Content-Transfer-Encoding: binary" ); // 告诉浏览器，这是二进制文件
        header( "Content-Disposition: attachment; filename=" . $name );
        @readfile( $file );
        // $contents = file_get_contents($file);
        // echo $contents;
        exit();
    }
}