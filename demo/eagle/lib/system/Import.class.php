<?php

namespace lib\system;

class Import {
    public static function load( $file = '' ) {
        if( !$file ){
            return false;
        }
        $file = DIR .DIRECTORY_SEPARATOR. $file;
        require_once $file;
    }
}