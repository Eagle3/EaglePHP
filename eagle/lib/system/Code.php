<?php

namespace lib\system;

class Code {
    private static $charSet = 'abcdefghkmnprstuvwxyzABCDEFGHKMNPRSTUVWXYZ23456789';
    private static $code;
    private static $imgHandle;
    private static $fontColor;
    private static $fontPath = '';
    private static $verifyType = 'cookie';
    private static $verifyName = '_verifyCode';
    private static $width = 130;
    private static $height = 50;
    private static $fontSize = 20;
    private static $codeLen = 4;
    public static function init() {
        self::$fontPath = FONT_PATH . 'georgiab.ttf';
        $verifyType = getConfig( 'DEFAULT_CODE_VERIFY' );
        $verifyName = getConfig( 'DEFAULT_CODE_NAME' );
        if ( ( int )$verifyType == 1 ) {
            self::$verifyType = 'cookie';
        } else {
            self::$verifyType = 'session';
        }
        self::$verifyName = $verifyName;
    }
    public static function set( $setArr = array() ) {
        if ( $setArr ) {
            foreach ( $setArr as $k => $v ) {
                self::$$k = $v;
            }
        }
    }
    
    // 获取验证码
    public static function getCode() {
        return strtolower( self::$code );
    }
    public static function output() {
        if ( isset( $_SESSION[self::$verifyName] ) ) {
            unset( $_SESSION[self::$verifyName] );
        }
        self::createBg();
        self::createCode();
        if ( self::$verifyType == 'cookie' ) {
            setcookie( self::$verifyName, self::$code );
        } else {
            // session_start();
            $_SESSION[self::$verifyName] = self::$code;
        }
        self::createLine();
        self::createFont();
        self::export();
    }
    
    // 生成背景
    private static function createBg() {
        self::$imgHandle = imagecreatetruecolor( self::$width, self::$height );
        $color = imagecolorallocate( self::$imgHandle, mt_rand( 157, 255 ), mt_rand( 157, 255 ), mt_rand( 157, 255 ) );
        imagefilledrectangle( self::$imgHandle, 0, self::$height, self::$width, 0, $color );
    }
    
    // 生成随机码
    private static function createCode() {
        $_len = strlen( self::$charSet ) - 1;
        for ( $i = 0; $i < self::$codeLen; $i ++ ) {
            self::$code .= self::$charSet[mt_rand( 0, $_len )];
        }
    }
    
    // 生成线条、雪花
    private static function createLine() {
        // 线条
        for ( $i = 0; $i < 6; $i ++ ) {
            $color = imagecolorallocate( self::$imgHandle, mt_rand( 0, 156 ), mt_rand( 0, 156 ), mt_rand( 0, 156 ) );
            imageline( self::$imgHandle, mt_rand( 0, self::$width ), mt_rand( 0, self::$height ), mt_rand( 0, self::$width ), mt_rand( 0, self::$height ), $color );
        }
        // 雪花
        for ( $i = 0; $i < 100; $i ++ ) {
            $color = imagecolorallocate( self::$imgHandle, mt_rand( 200, 255 ), mt_rand( 200, 255 ), mt_rand( 200, 255 ) );
            imagestring( self::$imgHandle, mt_rand( 1, 5 ), mt_rand( 0, self::$width ), mt_rand( 0, self::$height ), '*', $color );
        }
    }
    
    // 生成文字
    private static function createFont() {
        $_x = self::$width / self::$codeLen;
        for ( $i = 0; $i < self::$codeLen; $i ++ ) {
            self::$fontColor = imagecolorallocate( self::$imgHandle, mt_rand( 0, 156 ), mt_rand( 0, 156 ), mt_rand( 0, 156 ) );
            imagettftext( self::$imgHandle, self::$fontSize, mt_rand( -30, 30 ), $_x * $i + mt_rand( 1, 5 ), self::$height / 1.4, self::$fontColor, self::$fontPath, self::$code[$i] );
        }
    }
    
    // 输出
    private static function export() {
        header( 'Content-type:image/png' );
        imagepng( self::$imgHandle );
        imagedestroy( self::$imgHandle );
    }
}