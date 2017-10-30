<?php
namespace lib\system;

use lib\system\Smarty;

class Controller extends Smarty {
    public function __construct(){
        parent::__construct();
        if(method_exists($this,'init')){
            $this->init();
        }
    }
    
    public function __call($method,$args) {
        echo "method doesn't exist";
    }
    
}