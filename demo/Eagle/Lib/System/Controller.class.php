<?php
namespace Lib\System;

class Controller {
    
    private $ENGINE_NAME = 'Smarty';
    private $obj = NULL;
    
    public function __construct(){
        if(method_exists($this,'init')){
            $this->init();
        }
        $TPL_ENGINE = 1;
        $TPL_ENGINE_CONFIG = getConfig('TPL_ENGINE');
        if( $TPL_ENGINE_CONFIG && in_array( $TPL_ENGINE_CONFIG, array( 1,2 ) ) ){
            switch ( $TPL_ENGINE_CONFIG ) {
                case 1:
                    $this->ENGINE_NAME = 'Smarty';
                    break;
                case 2:
                     $this->ENGINE_NAME = 'Template';
                    break;
            }
        }
    }
    
    public function __call($method,$args) {
        echo "method doesn't exist";
    }
    
    public function fetch( $tpl ) {
        $this->judgeView();
        return $this->obj->fetch( $tpl );
    }
    
    public function assign( $key, $val = NULL ){
        $this->judgeView();
        $this->obj->assign( $key, $val );
    }
    
    public function display( $tpl ){
        $this->judgeView();
        $this->obj->display( $tpl );
    }
    
    private function judgeView(){
        if( !$this->obj ){
            switch ( $this->ENGINE_NAME ) {
                case 'Smarty':
                    $this->obj = new \Lib\System\Smarty();
                    break;
                case 'Template':
                    $this->obj = new \Lib\System\Template();
                    break;
            }
        }
    }
}