<?php

namespace lib\system;

use lib\system\Dispatch;

class App {
    private static $obj = NULL;
    public static function getInstance() {
        if ( !self::$obj ) {
            self::$obj = new self();
        }
        return self::$obj;
    }
    public function run() {
        $paramArr = Dispatch::getInstance()->handRoute();
        $class = "\\" . $paramArr['r'] . "\controller\\" . $paramArr['c'];
        $controller = new $class();
        call_user_func_array( array(
                $controller,
                $paramArr['a'] 
        ), array() );
    }
}