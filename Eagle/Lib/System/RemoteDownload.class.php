<?php

/**
 * 打包下载远程图片
 *
 * //传入的图片数组如下格式
 * $fileArr = array(
 * 1 => 'http://pic4.nipic.com/20091217/3885730_124701000519_2.jpg',
 * 2 => 'http://img07.tooopen.com/images/20170316/tooopen_sy_201956178977.jpg',
 * );
 */
namespace Lib\System;

class RemoteDownload {
    private $fileArr = array();
    public function __construct( $fileArr ) {
        $this->fileArr = $fileArr;
    }
    
    // 打包下载素材图片
    public function down() {
        $fileArr = $this->fileArr;
        // 抓取所有素材图片
        foreach ( $fileArr as $k => &$v ) {
            $postfix = substr( $v, strrpos( $v, '.' ) );
            $filename = $k . $postfix;
            $return_content = $this->http_get_data( $v );
            $fp = fopen( $filename, "a" ); // 将文件绑定到流
            fwrite( $fp, $return_content ); // 写入文件
            $v = $filename;
        }
        unset( $v );
        
        // 批量下载
        $filename = "file.zip";
        if ( !file_exists( $filename ) ) {
            $zip = new \ZipArchive();
            if ( $zip->open( $filename, \ZipArchive::CREATE ) == TRUE ) {
                foreach ( $fileArr as $val ) {
                    if ( file_exists( $val ) ) {
                        $zip->addFile( $val );
                    }
                }
                $zip->close();
            }
        }
        
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
        
        // 删除抓取的图片
        foreach ( $fileArr as $key => $val ) {
            @unlink( $val );
        }
        @unlink( 'file.zip' );
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