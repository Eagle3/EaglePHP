<?php

namespace Lib\System;

class Capture {
    private $fileArr = NULL;
    private $saveDir = './Tmp/capture/';
    public function __construct( $fileArr, $saveDir = '' ) {
        $this->fileArr = $fileArr;
        if ( $saveDir ) {
            $this->saveDir = $saveDir;
        }
    }
    
    // 抓取网络资源图片
    public function exe() {
        $fileArr = $this->fileArr;
        if ( !file_exists( $this->saveDir ) ) {
            mkdir( $this->saveDir, 0777, TRUE );
        }
        foreach ( $fileArr as $k => &$v ) {
            $postfix = substr( $v, strrpos( $v, '.' ) );
            $filename = $this->saveDir . $k . $postfix;
            $return_content = $this->http_get_data( $v );
            $fp = fopen( $filename, "a" );
            fwrite( $fp, $return_content );
            $v = $filename;
        }
        unset( $v );
    }
    
    /**
     * 抓取远程图片
     */
    private function http_get_data( $url ) {
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