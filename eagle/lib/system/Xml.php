<?php

/**
 * XML与数组转换
 */
namespace lib\system;

class Xml {
    public function __construct() {}
    
    /**
     * 数组转XML
     *
     * @param array $arrayData
     *            数组
     * @param string $parentNodeName
     *            父节点名称
     * @param object $domDocument
     *            new \DOMDocument
     * @param object $parentNode
     *            父节点对象
     * @return string XML格式字符串
     */
    public function array2Xml( $arrayData = [], $parentNodeName = 'root', $domDocument = NULL, $parentNode = NULL ) {
        if ( !$domDocument ) {
            $domDocument = new \DOMDocument( '1.0', 'utf-8' );
            $domDocument->formatOutput = true;
        }
        if ( !$parentNode ) {
            $parentNode = $domDocument->createElement( $parentNodeName );
            $domDocument->appendChild( $parentNode );
        }
        foreach ( $arrayData as $key => $val ) {
            $keyNode = $domDocument->createElement( is_string( $key ) ? $key : "item" );
            $parentNode->appendChild( $keyNode );
            if ( !is_array( $val ) ) {
                $valObj = $domDocument->createTextNode( $val );
                $keyNode->appendChild( $valObj );
            } else {
                $this->array2Xml( $val, $key, $domDocument, $keyNode );
            }
        }
        return $domDocument->saveXML();
    }
    
    /**
     * XML字符串转数组
     *
     * @param string $xmlData
     *            XML格式数据
     * @return array 数组
     */
    public function xml2Array( $xmlData = '' ) {
        libxml_disable_entity_loader( true );
        return json_decode( json_encode( simplexml_load_string( $xmlData, 'SimpleXMLElement', LIBXML_NOCDATA ) ), true );
    }
}