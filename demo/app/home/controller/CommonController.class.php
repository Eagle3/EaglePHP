<?php

namespace home\controller;
use lib\Controller;
use lib\system\DataFormatConvert;

class CommonController extends Controller {
	
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
				$res = DataFormatConvert::getInstance()->arrayToXml($data);
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
}