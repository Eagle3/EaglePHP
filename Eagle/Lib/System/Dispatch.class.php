<?php

namespace Lib\System;

use Lib\System\Route;

class Dispatch {
    private static $obj = NULL;
    public static function getInstance() {
        if ( !self::$obj ) {
            self::$obj = new self();
        }
        return self::$obj;
    }
    public function run() {
        $paramArr = Route::getInstance()->handRoute();
        $class = "\\" . $paramArr['r'] . "\Controller\\" . $paramArr['c'] . 'Controller';
        $controller = new $class();
        call_user_func_array( array(
                $controller,
                $paramArr['a'] 
        ), array() );
    }
}