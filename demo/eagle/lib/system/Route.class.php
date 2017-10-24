<?php
namespace lib\system;
class Route{
    private $defaultRoute = '';
    private $defaultController = '';
    private $defaultAction = '';
    private $defaultRouteParam = '';
    private $defaultControllerParam = '';
    private $defaultActionParam = '';
    private static $obj = NULL;
    
    public function __construct(){
        $defaultCall = getConfig('DEFAULT_CALL');
        $this->defaultRoute = $defaultCall['DEFAULT_ROUTE'];
        $this->defaultController = $defaultCall['DEFAULT_CONTROLLER'];
        $this->defaultAction = $defaultCall['DEFAULT_ACTION'];
        $this->defaultRouteParam = $defaultCall['DEFAULT_ROUTE_PARAM'];
        $this->defaultControllerParam = $defaultCall['DEFAULT_CONTROLLER_PARAM'];
        $this->defaultActionParam = $defaultCall['DEFAULT_ACTION_PARAM'];
    }
    
    public static function getInstance(){
        if(!self::$obj){
            self::$obj = new self();
        }
        return self::$obj;
    }
    
    public function handRoute(){
        $route = isset($_GET[$this->defaultRouteParam]) && $_GET[$this->defaultRouteParam] ? $_GET[$this->defaultRouteParam] : $this->defaultRoute;
        $controller = isset($_GET[$this->defaultControllerParam]) && $_GET[$this->defaultControllerParam] ? $_GET[$this->defaultControllerParam] : $this->defaultController;
        $action = isset($_GET[$this->defaultActionParam]) && $_GET[$this->defaultActionParam] ? $_GET[$this->defaultActionParam] : $this->defaultAction;
        defined('ROUTE_NAME') or define('ROUTE_NAME', $route);
        defined('CONTROLLER_NAME') or define('CONTROLLER_NAME', ucfirst($controller));
        defined('ACTION_NAME') or define('ACTION_NAME', $action);
        return array(
                'r' => $route,
                'c' => $controller,
                'a' => $action,
        );
    }
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
}