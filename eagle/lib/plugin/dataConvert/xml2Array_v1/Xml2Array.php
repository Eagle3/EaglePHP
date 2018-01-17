<?php

namespace lib\plugin\dataConvert\xml2Array_v1;

class Xml2Array {
    /**
     * 将xml转换为数组
     *
     * @param string $xml:xml文件或字符串            
     * @return array
     */
    public function run( $xml ) {
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
}

