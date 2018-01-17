<?php

namespace lib\system;

class Validate {
    private $str = '';
    private $typeFun = '';
    private $typeArr = array(
            'email' => 'email',
            'phone' => 'phone',
            'tel' => 'tel',
            'url' => 'url',
            'card' => 'card',
            'chinese' => 'chinese',
            'ip' => 'ip',
            'postcode' => 'postcode',
            'html' => 'html',
            'xml' => 'xml',
            'money' => 'money',
    );
    public function __construct( $str, $type ) {
        $this->str = $str;
        $typeArr = $this->typeArr;
        $this->typeFun = $typeArr[$type];
    }
    public function check() {
        return call_user_func( array(
                $this,
                $this->typeFun 
        ) );
    }
    private function email() {
        //return ( bool )filter_var( $this->str, FILTER_VALIDATE_EMAIL );
        return  preg_match( '/^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/', $this->str );
    }
    private function phone() {
        return preg_match( '/^13[\d]{9}$|^14[5,7,9]{1}\d{8}$|^15[\d]{9}$|^17[0,1,3,6,7,8]{1}\d{8}$|^18[\d]{9}$/', $this->str );
    }
    private function tel() {
        // 支持手机号码，3-4位区号，7-8位直播号码，1－4位分机号
        return preg_match( '/((^13[\d]{9}$|^14[5,7,9]{1}\d{8}$|^15[\d]{9}$|^17[0,1,3,6,7,8]{1}\d{8}$|^18[\d]{9}$)|^((\d{7,8})|(\d{4}|\d{3})-(\d{7,8})|(\d{4}|\d{3})-(\d{7,8})-(\d{4}|\d{3}|\d{2}|\d{1})|(\d{7,8})-(\d{4}|\d{3}|\d{2}|\d{1}))$)/', $this->str );
    }
    private function url() {
        return preg_match( '/^(http(?:s)?\:\/\/[a-zA-Z0-9]+(?:(?:\.|\-)[a-zA-Z0-9]+)+(?:\:\d+)?(?:\/[\w\-]+)*(?:\/?|\/\w+\.[a-zA-Z]{2,4}(?:\?[\w]+\=[\w\-]+)?)?(?:\&[\w]+\=[\w\-]+)*)$/', $this->str );
    }
    private function card() {
        return preg_match( '/(^\d{15}$)|(^\d{18}$)|(^\d{17}(\d|X|x)$)/', $this->str );
    }
    private function chinese() {
        return preg_match( '/^[\u4e00-\u9fa5]+$/', $this->str );
    }
    private function ip() {
        return preg_match( '/((?:(?:25[0-5]|2[0-4]\\d|[01]?\\d?\\d)\\.){3}(?:25[0-5]|2[0-4]\\d|[01]?\\d?\\d))/', $this->str );
    }
    private function html() {
        return preg_match( '/<(\S*?)[^>]*>.*?|<.*? />/', $this->str );
    }
    private function xml() {
        return preg_match( '/^([a-zA-Z]+-?)+[a-zA-Z0-9]+\\.[x|X][m|M][l|L]$/', $this->str );
    }
    private function money() {
        return preg_match( '/^([0-9]+|[0-9]{1,3}(,[0-9]{3})*)(.[0-9]{1,2})?$/', $this->str );
    }
}







