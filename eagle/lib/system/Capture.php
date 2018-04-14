<?php

namespace lib\system;

class Capture {
    private static $fileArr = NULL;
    private static $saveDir = './tmp/capture/';
    public static function init( $fileArr, $saveDir = '' ) {
        self::$fileArr = $fileArr;
        if ( $saveDir ) {
            self::$saveDir = $saveDir;
        }
    }
    
    // 抓取网络资源图片
    public static function exe() {
        $fileArr = self::$fileArr;
        if ( !file_exists( self::$saveDir ) ) {
            mkdir( self::$saveDir, 0777, TRUE );
        }
        foreach ( $fileArr as $k => &$v ) {
            $postfix = substr( $v, strrpos( $v, '.' ) );
            $filename = self::$saveDir . $k . $postfix;
            $return_content = self::http_get_data( $v );
            $fp = fopen( $filename, "a" );
            fwrite( $fp, $return_content );
            $v = $filename;
        }
        unset( $v ); 
    }
    
    private static function http_get_data( $url ) {
        $ch = curl_init();
        curl_setopt( $ch, CURLOPT_CUSTOMREQUEST, 'GET' );
        curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );
        curl_setopt( $ch, CURLOPT_URL, $url );
        ob_start();
        curl_exec( $ch );
        $return_content = ob_get_contents();
        ob_end_clean();
        $return_code = curl_getinfo( $ch, CURLINFO_HTTP_CODE );
        return $return_content;
    }
}