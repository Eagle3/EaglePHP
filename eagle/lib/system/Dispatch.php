<?php

namespace lib\system;

class Dispatch {
    private static $Route = '';
    private static $Controller = '';
    private static $Action = '';
    private static $RouteParam = '';
    private static $ControllerParam = '';
    private static $ActionParam = '';

    public static function handRoute() {
        $Call = getConfig( 'DEFAULT_CALL' );
        self::$Route = $Call['ROUTE'];
        self::$Controller = $Call['CONTROLLER'];
        self::$Action = $Call['ACTION'];
        self::$RouteParam = $Call['ROUTE_PARAM'];
        self::$ControllerParam = $Call['CONTROLLER_PARAM'];
        self::$ActionParam = $Call['ACTION_PARAM'];
        
        $route = isset( $_GET[self::$RouteParam] ) && $_GET[self::$RouteParam] ? $_GET[self::$RouteParam] : self::$Route;
        $controller = isset( $_GET[self::$ControllerParam] ) && $_GET[self::$ControllerParam] ? $_GET[self::$ControllerParam] : self::$Controller;
        $controller = ucfirst( $controller );
        $action = isset( $_GET[self::$ActionParam] ) && $_GET[self::$ActionParam] ? $_GET[self::$ActionParam] : self::$Action;
        
        // 如果请求的方法不存在: 可以走默认请求；此处也可以不判断，让程序报错; 或者转向404页面
        $class = DIRECTORY_SEPARATOR . $route . DIRECTORY_SEPARATOR . "controller" . DIRECTORY_SEPARATOR . $controller . '.php';
        if ( !file_exists( APP_PATH . $class ) ) {
//             $route = self::$Route;
//             $controller = self::$Controller;
//             $action = self::$Action;
            header('Location: /404.html');
        }
        
        defined( 'ROUTE_NAME' ) or define( 'ROUTE_NAME', $route );
        defined( 'CONTROLLER_NAME' ) or define( 'CONTROLLER_NAME', $controller );
        defined( 'ACTION_NAME' ) or define( 'ACTION_NAME', $action );
        
        return array(
                'r' => $route,
                'c' => $controller,
                'a' => $action 
        );
    }
}