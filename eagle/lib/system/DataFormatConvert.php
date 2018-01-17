<?php

namespace lib\system;

/**
 * 数据格式转换类
 * @description 实现：PHP数组、XML格式之间转换
 *
 * //在数据传输中使用XML格式数据
 * // function doCurl($xmlStr){
 * // $ch = curl_init();
 * // $url = 'http://localhost/note/php/xml/Xml.php';
 * // $timeout = 30;
 * // curl_setopt($ch, CURLOPT_URL, $url);
 * // curl_setopt($ch, CURLOPT_POST, 1);
 * // curl_setopt($ch, CURLOPT_HTTPHEADER, Array("Content-Type:text/xml; charset=utf-8"));
 * // curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //返回的内容不直接输出
 * // curl_setopt($ch, CURLOPT_POSTFIELDS, $xmlStr);//Post提交的数据包
 * // curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
 * // curl_setopt($ch, CURLOPT_HEADER, 0);
 * // return curl_exec($ch);
 * // }
 */
class DataFormatConvert {
    private static $obj = null;
    public function __construct() {}
    public function __destruct() {}
    public static function getInstance() {
        self::$obj = self::$obj ? self::$obj : new self();
        return self::$obj;
    }
    
    /**
     * 数组转XML
     *
     * @param array $arr
     *            PHP数组
     * @return string XML格式数据
     */
    public function arrayToXml( $arr = array() ) {
        $str = PHP_EOL . '<content>' . PHP_EOL;
        if ( $arr ) {
            $str .= $this->recursiveCreateXml( $arr );
        }
        $str .= '</content>';
        return '<?xml version="1.0" encoding="utf-8"?>' . $str;
    }
    
    /**
     * XML转数组（先转成json再转成数组）
     *
     * @param string $xmlStr            
     * @return array
     */
    public function xmlToArray( $xmlStr = '' ) {
        libxml_disable_entity_loader( true );
        $xmlObject = simplexml_load_string( $xmlStr, 'SimpleXMLElement', LIBXML_NOCDATA );
        return json_decode( json_encode( $xmlObject ), true );
    }
    
    /**
     * 递归生成XML格式数据
     *
     * @param array $data
     *            PHP数组
     * @return string XML格式数据
     */
    private function recursiveCreateXml( $arr ) {
        $str = '';
        foreach ( $arr as $k => $v ) {
            if ( is_array( $v ) ) {
                if ( is_numeric( $k ) ) {
                    $str .= "<item>" . PHP_EOL;
                    $str .= $this->recursiveCreateXml( $v );
                    $str .= "</item>" . PHP_EOL;
                } else {
                    $str .= "<{$k}>" . PHP_EOL;
                    $str .= $this->recursiveCreateXml( $v );
                    $str .= "</{$k}>" . PHP_EOL;
                }
            } else {
                $str .= "<{$k}>" . $v . "</{$k}>" . PHP_EOL;
            }
        }
        return $str;
    }
}
