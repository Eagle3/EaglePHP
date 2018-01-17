<?php

namespace lib\system;

class Controller {
    private $ENGINE_NAME = 'Smarty';
    private $obj = NULL;
    private function initialize() {
        $TPL_ENGINE_CONFIG = getConfig( 'TPL_ENGINE' );
        if ( !$TPL_ENGINE_CONFIG || !in_array( $TPL_ENGINE_CONFIG, array(
                1,
                2 
        ) ) ) {
            $TPL_ENGINE_CONFIG = 1;
        }
        switch ( $TPL_ENGINE_CONFIG ) {
            case 1:
                $this->ENGINE_NAME = 'Smarty';
                break;
            case 2:
                $this->ENGINE_NAME = 'Template';
                break;
        }
    }
    public function __construct() {
        $this->initialize();
        if ( method_exists( $this, 'init' ) ) {
            $this->init();
        }
    }
    public function __call( $method, $args ) {
        echo "method doesn't exist";
    }
    public function fetch( $tpl ) {
        $this->judgeView();
        return $this->obj->fetch( $tpl );
    }
    public function assign( $key, $val = NULL ) {
        $this->judgeView();
        $this->obj->assign( $key, $val );
    }
    public function display( $tpl ) {
        $this->judgeView();
        $this->obj->display( $tpl );
    }
    private function judgeView() {
        if ( !$this->obj ) {
            switch ( $this->ENGINE_NAME ) {
                case 'Smarty':
                    $this->obj = new \lib\system\Smarty();
                    break;
                case 'Template':
                    $this->obj = new \lib\system\Template();
                    break;
            }
        }
    }
}