<?php

namespace Home\Controller;

class CommonController extends \Lib\System\Controller {
    protected $verifyType = 'cookie';
    protected $verifyName = '_verifyCode';
    
    public function init(){
        $verifyType = getConfig('DEFAULT_CODE_VERIFY');
        if( (int)$verifyType == 1 ){
            $this->verifyType = 'cookie';
        }else{
            $this->verifyType = 'session';
        }
        $verifyName = getConfig('DEFAULT_CODE_NAME');
        $this->verifyName = $verifyName;
    }
    
	public function echoJsonp($callback,$data){
		echo $callback.'('."'{$data}'".')';
		exit;
	}
	
	public function echoAjax($data,$type = 'json'){
		switch ($type) {
			case 'json' :
				header('Content-type: application/json;charset=utf-8');
				$res = json_encode($data,JSON_UNESCAPED_UNICODE);
				break;
			case 'xml' :
				header("Content-type: text/xml;charset=utf-8");
				$res = \Lib\System\DataFormatConvert\DataFormatConvert::getInstance()->arrayToXml($data);
				break;
			case 'text' :
				header( 'Content-Type:text/html;charset=utf-8 ');
				if(is_array($data)){
					$res = json_encode($data,JSON_UNESCAPED_UNICODE);
				}elseif(is_string($data)){
					$res = $data;
				}elseif(is_object($data)){
					$res = $res = json_encode($data,JSON_UNESCAPED_UNICODE);
				}elseif(is_bool($data)){
					$res = (int)$data;
				}else{
					$res = $data;
				}
				break;
		}
		echo $res;
		exit;
	}
	
	public function verifyCode( $code ){
	    if( $this->verifyType == 'cookie' && isset($_COOKIE[$this->verifyName]) && strtolower($code) == strtolower($_COOKIE[$this->verifyName])){
	        return true;
	    }
	    //session_start();
	    if( $this->verifyType == 'session' && isset($_SESSION[$this->verifyName]) && strtolower($code) == strtolower($_SESSION[$this->verifyName])){
	        return true;
	    }
	    return false;
	}
	
}