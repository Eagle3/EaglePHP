<?php

namespace lib\system;

use lib\system\Dispatch;

class App {
    public static function run() {
        $paramArr = Dispatch::handRoute();
        $class = "\\" . $paramArr['r'] . "\controller\\" . $paramArr['c'];
        $controller = new $class();
        call_user_func_array( array(
                $controller,
                $paramArr['a'] 
        ), array() );
    }
}