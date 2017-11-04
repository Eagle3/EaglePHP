<?php

namespace Lib\System;

class Import {
    public static function load( $file = '' ) {
        if( !$file ){
            return false;
        }
        require_once EAGLE_PATH . $file;
    }
}
