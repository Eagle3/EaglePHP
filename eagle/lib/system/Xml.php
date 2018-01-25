<?php

/**
 * XML与数组转换
 */
namespace lib\system;

class Xml {
    public function __construct() {}
    
    /**
     * DOMDocument写XML - 数组转XML
     *
     * @param array $arrayData
     *            数组
     * @param string $rootNodeName
     *            根(父)节点名称
     * @param object $domDocument
     *            new \DOMDocument
     * @param object $parentNode
     *            父节点对象
     * @return string XML格式字符串
     */
    public function array2Xml_DOMDocument( $arrayData, $rootNodeName = 'root', $domDocument = NULL, $parentNode = NULL ) {
        if ( !$domDocument ) {
            $domDocument = new \DOMDocument( '1.0', 'utf-8' );
            $domDocument->formatOutput = true;
        }
        if ( !$parentNode ) {
            $parentNode = $domDocument->createElement( $rootNodeName );
            $domDocument->appendChild( $parentNode );
        }
        foreach ( $arrayData as $key => $val ) {
            $keyNode = $domDocument->createElement( is_string( $key ) ? $key : 'item' );
            $parentNode->appendChild( $keyNode );
            if ( !is_array( $val ) ) {
                $valObj = $domDocument->createTextNode( $val );
                $keyNode->appendChild( $valObj );
            } else {
                $this->array2Xml_DOMDocument( $val, 'root', $domDocument, $keyNode );
            }
        }
        return $domDocument->saveXML();
    }
    
    /**
     * SimpleXML写XML - 数组转XML
     *
     * @param array $arrayData
     *            数组
     * @param string $rootNodeName
     *            根节点名称
     * @param object $SimpleXMLElement
     *            new \SimpleXMLElement
     * @param object $parentNode
     *            父节点对象
     * @return string XML格式字符串
     */
    public function array2Xml_SimpleXML( $arrayData, $rootNodeName = 'root', $SimpleXMLElement = NULL, $parentNode = NULL ) {
        if ( !$SimpleXMLElement ) {
            $SimpleXMLElement = new \SimpleXMLElement( "<?xml version=\"1.0\" encoding=\"utf-8\"?><{$rootNodeName}></{$rootNodeName}>" );
        }
        foreach ( $arrayData as $key => $val ) {
            if ( !is_array( $val ) ) {
                if ( is_object( $parentNode ) ) {
                    $parentNode->addChild( is_string( $key ) ? $key : 'item', $val );
                } else {
                    $SimpleXMLElement->addChild( is_string( $key ) ? $key : 'item', $val );
                }
            } else {
                if ( is_object( $parentNode ) ) {
                    $keyNode = $parentNode->addChild( is_string( $key ) ? $key : 'item' );
                } else {
                    $keyNode = $SimpleXMLElement->addChild( is_string( $key ) ? $key : 'item' );
                }
                $this->array2Xml_SimpleXML( $val, '', $SimpleXMLElement, $keyNode );
            }
        }
        return $SimpleXMLElement->asXml();
    }
    
    /**
     * XMLWriter写XML - 数组转XML
     *
     * @param array $arrayData
     *            数组
     * @param string $rootNodeName
     *            根节点名称
     * @return string XML格式字符串
     */
    public function array2Xml_XMLWriter( $arrayData, $rootNodeName = 'root' ) {
        $XMLWriter = new \XMLWriter();
        $XMLWriter->openUri( "php://output" );
        $XMLWriter->setIndentString( "\t" );
        $XMLWriter->setIndent( true );
        $XMLWriter->startDocument( '1.0', 'utf-8' );
        $XMLWriter->startElement( $rootNodeName );
        $this->XMLWriterBuildXml( $XMLWriter, $arrayData );
        $XMLWriter->endElement();
        $XMLWriter->endDocument();
        return $XMLWriter->outputMemory();
    }
    private function XMLWriterBuildXml( $XMLWriter, $arrayData ) {
        foreach ( $arrayData as $key => $val ) {
            if ( !is_array( $val ) ) {
                $XMLWriter->startElement( is_string( $key ) ? $key : 'item' );
                $XMLWriter->text( $val );
                $XMLWriter->endElement();
            } else {
                $XMLWriter->startElement( is_string( $key ) ? $key : 'item' );
                $this->XMLWriterBuildXml( $XMLWriter, $val );
                $XMLWriter->endElement();
            }
        }
    }
    
    /**
     * SimpleXML读取-XML字符串转数组
     *
     * @param string $xmlData
     *            XML格式数据
     * @return array 数组
     */
    public function xml2Array_SimpleXML( $xmlData = '' ) {
        libxml_disable_entity_loader( true );
        return json_decode( json_encode( simplexml_load_string( $xmlData, 'SimpleXMLElement', LIBXML_NOCDATA ) ), true );
    }
}