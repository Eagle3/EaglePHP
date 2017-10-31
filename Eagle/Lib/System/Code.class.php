<?php
namespace Lib\System;

class Code {
    private $charSet = 'abcdefghkmnprstuvwxyzABCDEFGHKMNPRSTUVWXYZ23456789';
    private $code;
    private $imgHandle;
    private $fontColor;
    private $fontPath = '';
    
    private $width = 130;
    private $height = 50;
    private $fontSize = 20;
    private $codeLen = 4;
    
    public function __construct(){
        $this->fontPath = PROJECT_FONT_PATH . 'Elephant.ttf';
    }
    
    public function set( $setArr = array() ){
        if( $setArr ){
            foreach ( $setArr as $k => $v ) {
                $this->$k = $v;
            }
        }
    }
    
    //获取验证码
    public function getCode() {
        return strtolower($this->code);
    }
    
    public function output(){
        $this->createBg();
        $this->createCode();
        $this->createLine();
        $this->createFont();
        $this->export();
    }
    
    //生成背景
    private function createBg() {
        $this->imgHandle = imagecreatetruecolor($this->width, $this->height);
        $color = imagecolorallocate($this->imgHandle, mt_rand(157,255), mt_rand(157,255), mt_rand(157,255));
        imagefilledrectangle($this->imgHandle,0,$this->height,$this->width,0,$color);
    }
    
    //生成随机码
    private function createCode() {
        $_len = strlen($this->charSet)-1;
        for ($i=0;$i<$this->codeLen;$i++) {
            $this->code .= $this->charSet[mt_rand(0,$_len)];
        }
    }
    
    //生成线条、雪花
    private function createLine() {
        //线条
        for ($i=0;$i<6;$i++) {
            $color = imagecolorallocate($this->imgHandle,mt_rand(0,156),mt_rand(0,156),mt_rand(0,156));
            imageline($this->imgHandle,mt_rand(0,$this->width),mt_rand(0,$this->height),mt_rand(0,$this->width),mt_rand(0,$this->height),$color);
        }
        //雪花
        for ($i=0;$i<100;$i++) {
            $color = imagecolorallocate($this->imgHandle,mt_rand(200,255),mt_rand(200,255),mt_rand(200,255));
            imagestring($this->imgHandle,mt_rand(1,5),mt_rand(0,$this->width),mt_rand(0,$this->height),'*',$color);
        }
    }
    
    //生成文字
    private function createFont() {
        $_x = $this->width / $this->codeLen;
        for ($i=0;$i<$this->codeLen;$i++) {
            $this->fontColor = imagecolorallocate($this->imgHandle,mt_rand(0,156),mt_rand(0,156),mt_rand(0,156));
            imagettftext($this->imgHandle,$this->fontSize,mt_rand(-30,30),$_x*$i+mt_rand(1,5),$this->height / 1.4,$this->fontColor,$this->fontPath,$this->code[$i]);
        }
    }
   
    //输出
    private function export() {
        header('Content-type:image/png');
        imagepng($this->imgHandle);
        imagedestroy($this->imgHandle);
    }
}