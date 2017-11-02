<?php

namespace Lib\System;

class Route {
    private $Route = '';
    private $Controller = '';
    private $Action = '';
    private $RouteParam = '';
    private $ControllerParam = '';
    private $ActionParam = '';
    private static $obj = NULL;
    public function __construct() {
        $Call = getConfig( 'DEFAULT_CALL' );
        $this->Route = $Call['ROUTE'];
        $this->Controller = $Call['CONTROLLER'];
        $this->Action = $Call['ACTION'];
        $this->RouteParam = $Call['ROUTE_PARAM'];
        $this->ControllerParam = $Call['CONTROLLER_PARAM'];
        $this->ActionParam = $Call['ACTION_PARAM'];
    }
    public static function getInstance() {
        if ( !self::$obj ) {
            self::$obj = new self();
        }
        return self::$obj;
    }
    public function handRoute() {
        $route = isset( $_GET[$this->RouteParam] ) && $_GET[$this->RouteParam] ? $_GET[$this->RouteParam] : $this->Route;
        $route = ucfirst( $route );
        $controller = isset( $_GET[$this->ControllerParam] ) && $_GET[$this->ControllerParam] ? $_GET[$this->ControllerParam] : $this->Controller;
        $controller = ucfirst( $controller );
        $action = isset( $_GET[$this->ActionParam] ) && $_GET[$this->ActionParam] ? $_GET[$this->ActionParam] : $this->Action;
        
        //如果请求的文件不存在走默认请求。此处也可以不判断，让程序报错
        $class = "\\" . $route . "\Controller\\" . $controller. 'Controller.class.php';
        if ( !file_exists( APP_PATH . $class ) ) {
            $route = $this->Route;
            $controller = $this->Controller;
            $action = $this->Action;
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