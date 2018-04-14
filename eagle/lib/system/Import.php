<?php

/**
 * 加载第三方类库（如果第三方类库不使用命名空间）
 */
namespace lib\system;

class Import {
    public static function load( $file = '' ) {
        if ( !$file ) {
            return false;
        }
        require_once EAGLE_PATH . $file;
    }
}
