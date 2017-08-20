<?php
namespace lib\system;

class Validate {
    private $typeArr = array(
            'email' => 'email',
            'phone' => 'phone',
            'tel' => 'tel',
            'url' => 'url',
            'card' => 'card',
            'num' => 'num',
            'letter' => 'letter',
            'chinese' => 'chinese',
            'ip' => 'ip',
            'postcode' => 'postcode',
    );
    
    private $typeFun = '';
    
    public function __construct($type){
        $typeArr = $this->typeArr;
        $this->typeFun = $typeArr[$type];
    }
    
    public function check($str){
        return call_user_func_array(array($this,$this->typeFun), array($str));
    }
    
    private function email($str){
        return true;
    }
    
    
}







