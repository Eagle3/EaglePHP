<?php
namespace lib\system;

//使用smarty模板引擎
use lib\system\View;

class Controller extends View {
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