<?php

namespace lib\system;

class Download {
    public function __construct( $filename ) {}
    public function exe( $file ) {
        $position = strripos( $file, '.' );
        $postfix = substr( $file, $position + 1 );
        $file = iconv( 'utf-8', 'gbk', $file );
        $name = date( 'Ymdhis' ) . '.' . $postfix;
        header( "Content-type: application/octet-stream" );
        header( "Content-Type: application/force-download" );
        header( "Content-Transfer-Encoding: binary" );
        header( "Content-Disposition: attachment; filename=" . $name );
        readfile( $file );
        // $contents = file_get_contents($file);
        // echo $contents;
        exit();
    }
}