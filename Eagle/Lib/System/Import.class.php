<?php

namespace Lib\System;

class Import {
    public static function load( $file = '' ) {
        if( !$file ){
            return false;
        }
        $file = EAGLE_PATH . $file;
        require_once $file;
    }
}
