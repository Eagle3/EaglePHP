<?php

namespace Lib\System;

/**
 * 数据格式转换类
 * @description 实现：PHP数组、XML、JSON、对象四种格式之间转换
 *
 * $arr = array(
 * 'code' => 0,
 * 'msg' => 'ok',
 * 'data' => array(
 * array(
 * 'name' => '北京',
 * 'district' => array(
 * 'name' => 'jack',
 * 'age' => 20,
 * 'sex' => 'man',
 * 'city' => 'beijing',
 * 'people' => array(
 * 'nan' => 360,
 * 'nv' => 299
 * )
 * )
 * ),
 * array(
 * 'name' => '天津',
 * 'district' => array(
 * 'name' => 'jack2',
 * 'age' => 202,
 * 'sex' => 'man2',
 * 'city' => 'beijing2'
 * )
 * )
 * )
 * );
 *
 *
 *
 * // XML格式数据解析成数组格式并输出
 * // echo '<pre/>';
 * // print_r(DataFormatConvert::getInstance()->xmlToArray($xmlStr));
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
     * JSON转XML(先转成数组，再转成XML)
     *
     * @param string $jsonStr            
     * @return string
     */
    public function jsonToXml( $jsonStr = '' ) {
        if ( !$jsonStr ) {
            $str = PHP_EOL . '<content>' . PHP_EOL;
            $str .= '</content>';
            return '<?xml version="1.0" encoding="utf-8"?>' . $str;
        }
        return $this->arrayToXml( json_decode( $jsonStr, true ) );
    }
    
    /**
     * XML转JSON(先解析成XML对象，然后转成JSON)
     *
     * @param string $xmlStr            
     * @return string
     */
    public function xmlToJson( $xmlStr = '' ) {
        if ( !$xmlStr ) {
            return array();
        }
        libxml_disable_entity_loader( true );
        $xmlstring = simplexml_load_string( $xmlStr, 'SimpleXMLElement', LIBXML_NOCDATA );
        return json_encode( $xmlstring );
    }
    
    /**
     * 将数组转换为xml
     *
     * @param array $arr:数组            
     * @param object $dom:Document对象，默认null即可            
     * @param object $node:节点对象，默认null即可            
     * @param string $root:根节点名称            
     * @param string $cdata:是否加入CDATA标签，默认为false            
     * @return string
     */
    public function arrayToXml2( $arr, $dom = null, $node = null, $root = 'xml', $cdata = false ) {
        if ( !$dom ) {
            $dom = new \DOMDocument( '1.0', 'utf-8' );
        }
        if ( !$node ) {
            $node = $dom->createElement( $root );
            $dom->appendChild( $node );
        }
        foreach ( $arr as $key => $value ) {
            $child_node = $dom->createElement( is_string( $key ) ? $key : 'node' );
            $node->appendChild( $child_node );
            if ( !is_array( $value ) ) {
                if ( !$cdata ) {
                    $data = $dom->createTextNode( $value );
                } else {
                    $data = $dom->createCDATASection( $value );
                }
                $child_node->appendChild( $data );
            } else {
                $this->arrayToXml2( $value, $dom, $child_node, $root, $cdata );
            }
        }
        return $dom->saveXML();
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
     * 将xml转换为数组
     * 
     * @param string $xml:xml文件或字符串            
     * @return array
     */
    public function xmlToArray2( $xml ) {
        // 考虑到xml文档中可能会包含<![CDATA[]]>标签，第三个参数设置为LIBXML_NOCDATA
        if ( file_exists( $xml ) ) {
            libxml_disable_entity_loader( false );
            $xml_string = simplexml_load_file( $xml, 'SimpleXMLElement', LIBXML_NOCDATA );
        } else {
            libxml_disable_entity_loader( true );
            $xml_string = simplexml_load_string( $xml, 'SimpleXMLElement', LIBXML_NOCDATA );
        }
        $result = json_decode( json_encode( $xml_string ), true );
        return $result;
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
