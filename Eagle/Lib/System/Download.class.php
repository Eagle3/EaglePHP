<?php

namespace Lib\System;

class Download{
    public function __construct( $filename){
        
    }
    
    public function exe(){
        if ( !file_exists( $filename ) ) {
            echo '无法找到文件';
            exit();
        }
        header( "Cache-Control: public" );
        header( "Content-Description: File Transfer" );
        header( 'Content-disposition: attachment; filename=' . basename( $filename ) ); // 文件名
        header( "Content-Type: application/zip" ); // zip格式的
        header( "Content-Transfer-Encoding: binary" ); // 告诉浏览器，这是二进制文件
        header( 'Content-Length: ' . filesize( $filename ) ); // 告诉浏览器，文件大小
        @readfile( $filename );
    }
}