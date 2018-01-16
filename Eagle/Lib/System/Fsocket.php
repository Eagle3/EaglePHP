<?php

namespace Lib\System;

class Fsocket {
    private static $crlf = PHP_EOL; // "\r\n"
    private static $url = '';
    private static $port = '80';
    private static $httpMethod = 'GET';
    private static $httpVersion = 'HTTP/1.1';
    private static $handle = null;
    private static $res = '';
    private static $referer = '';
    private static $requestBody = array(); // 请求主体信息
    
    /**
     * 设置请求参数
     * 
     * @param string $url
     *            请求URL
     * @param array $requestBody
     *            请求数据
     * @param string $httpMethod
     *            请求方法
     */
    public static function set( $url, $requestBody = array(), $httpMethod = 'GET', $port = 80, $referer = '' ) {
        if ( !$url ) {
            return false;
        }
        self::$url = $url;
        self::$requestBody = $requestBody;
        self::$httpMethod = strtoupper( $httpMethod );
        self::$referer = $referer ? $referer : $_SERVER['SERVER_NAME'];
        self::$port = $port ? $port : 80;
    }
    public static function send() {
        $url = parse_url( self::$url );
        self::open();
        $res = self::call();
        self::close();
        return $res;
    }
    private static function open() {
        $res = fsockopen( self::$url, self::$port, $errno, $errMsg, 3 );
        var_dump( $res );
        exit();
    }
    private static function call() {
        // 设置请求行、头信信息、body体信息
        $formatUrl = self::$url;
        $data = '';
        if ( self::$requestBody ) {
            if ( self::$httpMethod == 'GET' ) {
                $dataq = '?';
                if ( strpos( '?', self::$url ) ) {
                    $data = '&';
                }
            }
            foreach ( self::$requestBody as $k => $v ) {
                $data .= $k . '=' . $v . '&';
            }
            $data = rtrim( $data, '&' );
            $formatUrl .= $data;
        }
        $requestLine = self::$httpMethod . ' ' . $formatUrl . ' ' . self::$httpVersion;
        $referer = self::$referer;
        $length = strlen( $data );
        
        // 设置头信信息
        $head = <<<HEADER
{$requestLine}
Accept: text/plain, text/html
Referer: {$referer}
Accept-Language: zh-CN,zh;q=0.8
Content-Type: application/x-www-form-urlencodem
Cookie: token=value; pub_cookietime=2592000; pub_sauth1=value; pub_sauth2=value
User-Agent: Mozilla/5.0 (X11; Linux i686) AppleWebKit/537.17 (KHTML, like Gecko) Chrome/24.0.1312.56 Safari/537.17
Host: {$referer}
Content-Length: {$length}
Pragma: no-cache
Cache-Control: no-cache
Connection: closern
{$data}
HEADER;
        fwrite( self::$handle, $head );
        $result = '';
        while ( !feof( self::$handle ) ) {
            $result .= fread( self::$handle, 512 );
        }
        return array(
                'status' => 'ok',
                'header' => isset( $result[0] ) ? $result[0] : '',
                'content' => isset( $result[1] ) ? $result[1] : '',
                'error' => "{$errMsg} ({$errno})" 
        );
    }
    private static function close() {
        fclose( self::$handle );
    }
}