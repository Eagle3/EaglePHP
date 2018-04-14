<?php

namespace lib\system;

use lib\system\Log;
use lib\system\Xml;

/**
 * 简易CURL
 * 调用示例
 * GET方式：
 * $url = 'http://eagle.test/index.php?r=home&c=index&a=getCurl';
 * $data = array(
 * 'name' => 'jack',
 * 'from' => '中国',
 * 'data' => json_encode(array('name' => 'jack','from' => '中国',),JSON_UNESCAPED_UNICODE),
 * );
 * //$data = 'name=jack&age=20';
 * //此处data可以传数组也可以传字符串，建议传关联数组
 * $curl = new Curl($url,$data,'GET');
 * $res = $curl->send();
 *
 * POST方式：
 * $url = 'http://eagle.test/index.php?r=home&c=index&a=getCurl';
 * $data = array(
 * 'name' => 'jack',
 * 'from' => '中国',
 * 'data' => json_encode(array('name' => 'jack','from' => '中国',),JSON_UNESCAPED_UNICODE),
 * );
 * //$data = 'name=jack&age=20';
 * //此处data可以传数组也可以传字符串，建议传关联数组。 1002 时必须传关联数组
 * $curl = new Curl($url,$data,'POST',1001);
 * $res = $curl->send();
 */
class Curl {
    private static $ch = null;
    private static $timeout = 30;
    private static $methodArr = array(
            'GET',
            'POST' 
    );
    private static $method = '';
    private static $contentTypeArr = array(
            1001 => 'Content-Type:application/x-www-form-urlencoded',
            1002 => 'Content-Type: multipart/form-data',
            1003 => 'Content-Type:application/json;charset=utf-8',
            1004 => 'Content-Type:text/xml;charset=utf-8' 
    );
    private static $contentTypeCode = '';
    private static $sendData = '';
    private static $requestUrl = '';
    private static $curlOption = array();
    
    /**
     * 初始化CURL类
     *
     * @param string $requestUrl
     *            请求地址
     * @param array $sendData
     *            要发送的数据
     * @param string $method
     *            http方法
     * @param number $contentTypeKey
     *            contentType码
     */
    public static function init( $requestUrl = '', $sendData = array(), $method = 'GET', $contentTypeKey = 1001 ) {
        self::$requestUrl = $requestUrl;
        self::$sendData = $sendData;
        $method = strtoupper( $method );
        $contentTypeKey = ( int )$contentTypeKey;
        self::$method = $method && in_array( $method, self::$methodArr ) ? $method : 'GET';
        if ( self::$method == 'POST' && in_array( $contentTypeKey, array_keys( self::$contentTypeArr ) ) ) {
            self::$contentTypeCode = $contentTypeKey;
        }
    }
    public static function setOption( $key, $val = 0 ) {
        if ( is_array( $key ) ) {
            foreach ( $key as $k => $v ) {
                self::$curlOption[$k] = $v;
            }
        } else {
            self::$curlOption[$key] = $val;
        }
    }
    public static function send() {
        switch ( self::$method ) {
            case 'GET':
                return self::get();
                break;
            case 'POST':
                return self::post();
                break;
            default :
                return 'http method was not found!';
        }
    }
    private static function get() {
        self::initCurl();
        $sendData = '';
        if ( is_array( self::$sendData ) && self::$sendData ) {
            foreach ( self::$sendData as $k => $v ) {
                $sendData .= $k . '=' . $v . '&';
            }
            $sendData = rtrim( $sendData, '&' );
        }
        if ( is_string( self::$sendData ) ) {
            $sendData = self::$sendData;
        }
        $url = self::$requestUrl;
        
        if ( $sendData ) {
            if ( strpos( $url, '?' ) ) {
                $url .= "&" . $sendData;
            } else {
                $url .= "?" . $sendData;
            }
        }
        curl_setopt( self::$ch, CURLOPT_URL, $url );
        return self::exec();
    }
    private static function post() {
        self::initCurl();
        curl_setopt( self::$ch, CURLOPT_POST, true );
        
        $postData = '';
        $contentTypeArr = self::$contentTypeArr;
        $httpHeader = array(
                $contentTypeArr[1001] 
        );
        if ( is_array( self::$sendData ) && self::$sendData ) {
            foreach ( self::$sendData as $k => $v ) {
                $postData .= $k . '=' . $v . '&';
            }
            $postData = rtrim( $postData, '&' );
        }
        if ( is_string( self::$sendData ) ) {
            $postData = self::$sendData;
        }
        
        switch ( self::$contentTypeCode ) {
            case 1001:
                break;
            case 1002:
                if ( is_array( self::$sendData ) && self::$sendData ) {
                    $postData = self::$sendData;
                }
                if ( is_string( self::$sendData ) && self::$sendData ) {
                    $postData = ( array )self::$sendData;
                }
                $httpHeader = array(
                        $contentTypeArr[1002] 
                );
                break;
            case 1003:
                $postData = json_encode( self::$sendData, JSON_UNESCAPED_UNICODE );
                $httpHeader = array(
                        $contentTypeArr[1003] 
                );
                break;
            case 1004:
                // 此处数组数据转化为为XML格式字符串
                if ( is_array( self::$sendData ) && self::$sendData ) {
                    $postData = Xml::array2Xml_XMLWriter( self::$sendData );
                }
                $httpHeader = array(
                        $contentTypeArr[1004] 
                );
                break;
            default :
                break;
        }
        
        curl_setopt( self::$ch, CURLOPT_POSTFIELDS, $postData );
        return self::exec();
    }
    private static function initCurl() {
        self::$ch = curl_init();
        curl_setopt( self::$ch, CURLOPT_URL, self::$requestUrl );
        curl_setopt( self::$ch, CURLOPT_RETURNTRANSFER, true ); // 返回的内容作为变量储存，而不是直接输出。true作为变量存储；false直接输出
        curl_setopt( self::$ch, CURLOPT_NOSIGNAL, 1 );
        if ( defined( 'CURLOPT_TIMEOUT_MS' ) ) {
            $timeoutMS = self::$timeout * 1000;
            curl_setopt( self::$ch, CURLOPT_NOSIGNAL, 1 );
            curl_setopt( self::$ch, CURLOPT_CONNECTTIMEOUT_MS, $timeoutMS );
            curl_setopt( self::$ch, CURLOPT_TIMEOUT_MS, $timeoutMS );
        } else {
            curl_setopt( self::$ch, CURLOPT_CONNECTTIMEOUT, self::$timeout );
            curl_setopt( self::$ch, CURLOPT_TIMEOUT, self::$timeout );
        }
        
        $arr = explode( '://', self::$requestUrl );
        if ( $arr[0] == 'https' ) {
            curl_setopt( self::$ch, CURLOPT_SSL_VERIFYPEER, false ); // https不验证证书
            curl_setopt( self::$ch, CURLOPT_SSL_VERIFYHOST, false ); // https不验证证书
        }
    }
    private static function exec() {
        if ( self::$curlOption ) {
            foreach ( self::$curlOption as $k => $v ) {
                curl_setopt( self::$ch, $k, $v );
            }
        }
        $res = curl_exec( self::$ch );
        $errno = curl_errno( self::$ch );
        $error = curl_error( self::$ch );
        echo "curl exec error: {$errno} {$error}";
        if ( $errno ) {
            log::error( "curl exec error: {$errno} {$error}" );
        }
        curl_close( self::$ch );
        return $res;
    }
}