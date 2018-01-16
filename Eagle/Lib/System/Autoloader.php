<?php

namespace Lib\System;

class Autoloader {
    public function __construct(){
        
    }
    
    public static function register() {
        spl_autoload_register( array(
                new self(),
                'autoload' 
        ) );
    }
    private function autoload( $class ) {
        $class = str_replace( array(
                '\\',
                '/' 
        ), DIRECTORY_SEPARATOR, $class );
        //$classFile = $class . '.class.php';
        $classFile = $class . '.php';
        $classDir = array(
                EAGLE_PATH,
                APP_PATH 
        );
        
        foreach ( $classDir as $key => $val ) {
            if ( file_exists( $val . $classFile ) ) {
                // echo $val.$classFile.'<br>';
                require $val . $classFile;
                break;
            }
            continue;
        }
    }
}