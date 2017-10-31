<?php
namespace Lib\System;
use Lib\System\Log;

/**
 * 简易CURL
				调用示例
		GET方式：
		$url = 'http://eagle.test/index.php?r=home&c=index&a=getCurl';
		$data = array(
				'name' => 'jack',
				'from' => '中国',
				'data' => json_encode(array('name' => 'jack','from' => '中国',),JSON_UNESCAPED_UNICODE),
		);
		//$data = 'name=jack&age=20';
		//此处data可以传数组也可以传字符串，建议传关联数组
      	$curl = new Curl($url,$data,'GET');
      	$res = $curl->send();
      	
      	POST方式：
      	$url = 'http://eagle.test/index.php?r=home&c=index&a=getCurl';
		$data = array(
				'name' => 'jack',
				'from' => '中国',
				'data' => json_encode(array('name' => 'jack','from' => '中国',),JSON_UNESCAPED_UNICODE),
		);
		//$data = 'name=jack&age=20';
		//此处data可以传数组也可以传字符串，建议传关联数组。 1002 时必须传关联数组
      	$curl = new Curl($url,$data,'POST',1001);
      	$res = $curl->send();
 * 
 * 
 */
class Curl {
	private $ch = null;
	private $timeout = 30;
	private $methodArr = array('GET','POST');
    private $method = '';
	private $contentTypeArr = array(
			1001 => 'Content-Type:application/x-www-form-urlencoded',	
			1002 => 'Content-Type: multipart/form-data',
			1003 => 'Content-Type:application/json;charset=utf-8',
			1004 => 'Content-Type:text/xml;charset=utf-8',
	);
	private $contentTypeCode = '';
	private $sendData = '';
	private $requestUrl = '';
	private $curlOption = array();
	
	/**
	 * 初始化CURL类
	 * @param string $requestUrl              请求地址  
	 * @param array $sendData                 要发送的数据  
	 * @param string $method                  http方法
	 * @param number $contentTypeKey          contentType码 
	 */
	public function __construct($requestUrl = '',$sendData = array(),$method = 'GET',$contentTypeKey = 1001){
		$this->requestUrl = $requestUrl;
		$this->sendData = $sendData;
		$method = strtoupper($method);
		$contentTypeKey = (int)$contentTypeKey;
		$this->method = $method && in_array($method, $this->methodArr) ? $method : 'GET';
		if($this->method == 'POST' && in_array($contentTypeKey, array_keys($this->contentTypeArr))){
			$this->contentTypeCode = $contentTypeKey;
		}
	}
	
	public function send(){
		switch ($this->method) {
			case 'GET':
				return $this->doGet();
				break;
			case 'POST':
				return $this->doPost();
				break;
			default:
				return 'http method was not found!';
		}
	}
	
	private function doGet(){
		$this->initCurl();
		$sendData = '';
		if(is_array($this->sendData) && $this->sendData){
			foreach ($this->sendData as $k=>$v){
				$sendData .= $k.'='.$v.'&';
			}
			$sendData = rtrim($sendData,'&');
		}
		if(is_string($this->sendData)){
			$sendData = $this->sendData;
		}
		$url = $this->requestUrl;
		if($sendData){
			if (strpos($url, '?')) {
				$url .= "&" . $sendData;
			} else {
				$url .= "?" . $sendData;
			}
		}
		curl_setopt($this->ch, CURLOPT_URL, $url);
		return $this->endCurl();
	}
	
	private function doPost(){
		$this->initCurl();
		curl_setopt($this->ch, CURLOPT_POST, true);
		
		$postData = '';
		$contentTypeArr = $this->contentTypeArr;
		$httpHeader = array(
			$contentTypeArr[1001],
		);
		if(is_array($this->sendData) && $this->sendData){
			foreach ($this->sendData as $k=>$v){
				$postData .= $k.'='.$v.'&';
			}
			$postData = rtrim($postData,'&');
		}
		if(is_string($this->sendData)){
			$postData = $this->sendData;
		}
		
		switch ($this->contentTypeCode) {
			case 1001 :
				break;
			case 1002 :
				if(is_array($this->sendData) && $this->sendData){
					$postData = $this->sendData;
				}
				if(is_string($this->sendData) && $this->sendData){
					$postData = (array)$this->sendData;
				}
				$httpHeader = array(
						$contentTypeArr[1002],
				);
				break;
			case 1003 :
				$postData = json_encode($this->sendData,JSON_UNESCAPED_UNICODE);
				$httpHeader = array(
						$contentTypeArr[1003],
				);
				break;
			case 1004 :
				//此处数组数据转化为为XML格式字符串
				if(is_array($this->sendData) && $this->sendData){
					$postData = $this->arrayToXml($this->sendData);
				}
				$httpHeader = array(
						$contentTypeArr[1004],
				);
				break;
			default:
				break;
		}
		curl_setopt($this->ch, CURLOPT_POSTFIELDS, $postData);
		return $this->endCurl();
	}
	
	public function setOption( $key, $val = 0 ){
		if( is_array( $key ) ){
			foreach ( $key as $k => $v ) {
				$this->curlOption[$k] = $v;
			}
		}else{
			$this->curlOption[$key] = $val;
		}
	}
		
	private function initCurl(){
		//$ch = curl_init();
		//curl_setopt($ch, CURLOPT_URL, $this->requestUrl);
		
		$ch = curl_init($this->requestUrl);
		$this->ch = $ch;
		curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, true); //返回的内容作为变量储存，而不是直接输出。true作为变量存储；false直接输出
		if (defined('CURLOPT_TIMEOUT_MS')) {
			$timeoutMS = $this->timeout * 1000;
			curl_setopt($this->ch, CURLOPT_NOSIGNAL, 1);
			curl_setopt($this->ch, CURLOPT_CONNECTTIMEOUT_MS, $timeoutMS);
			curl_setopt($this->ch, CURLOPT_TIMEOUT_MS, $timeoutMS);
		} else {
			curl_setopt($this->ch, CURLOPT_CONNECTTIMEOUT, $this->timeout);
			curl_setopt($this->ch, CURLOPT_TIMEOUT, $this->timeout);
		}
		
		$arr = explode('://',$this->requestUrl);
		if( $arr[0] == 'https' ){
			curl_setopt($this->ch, CURLOPT_SSL_VERIFYPEER, false); //https不验证证书
			curl_setopt($this->ch, CURLOPT_SSL_VERIFYHOST, false); //https不验证证书
		}
	}
	
	private function endCurl(){
		if( $this->curlOption ){
			foreach ( $this->curlOption as $k=>$v){
				curl_setopt($this->ch, $k, $v);
			}
		}
		$res = curl_exec($this->ch);
		$errno = curl_errno($this->ch);
		$error = curl_error($this->ch);
		if ($errno) {
			log::error(  "curl error:{$error}"   )  ;
		}
		curl_close($this->ch);
		return $res;
	}
	
	
	/**
	 * JSON转XML(先转成数组，再转成XML)
	 * @param string $jsonStr
	 * @return string
	 */
	private function jsonToXml($jsonStr = ''){
		if(!$jsonStr){
			$str = PHP_EOL . '<content>' . PHP_EOL;
			$str .= '</content>';
			return '<?xml version="1.0" encoding="utf-8"?>' . $str;
		}
		return $this->arrayToXml(json_decode($jsonStr,true));
	}
	
	/**
	 * 数组转XML
	 * @param array $arr  PHP数组
	 * @return string      XML格式数据
	 */
	private function arrayToXml($arr = array()){
		$str = PHP_EOL . '<content>' . PHP_EOL;
		if ($arr) {
			$str .= $this->recursiveCreateXml($arr);
		}
		$str .= '</content>';
		return '<?xml version="1.0" encoding="utf-8"?>' . $str;
	}
	
	/**
	 * XML转JSON(先解析成XML对象，然后转成JSON)
	 * @param string $xmlStr
	 * @return string
	 */
	private function xmlToJson($xmlStr = ''){
		if(!$xmlStr){
			return array();
		}
		libxml_disable_entity_loader(true);
		$xmlstring = simplexml_load_string($xmlStr, 'SimpleXMLElement', LIBXML_NOCDATA);
		return json_encode($xmlstring);
	}
	
	/**
	 * XML转数组（先转成json再转成数组）
	 * @param string $xmlStr
	 * @return array
	 */
	private function xmlToArray($xmlStr = ''){
		libxml_disable_entity_loader(true);
		$xmlstring = simplexml_load_string($xmlStr, 'SimpleXMLElement', LIBXML_NOCDATA);
		return json_decode(json_encode($xmlstring),true);
	}
	
	/**
	 * 递归生成XML格式数据
	 * @param array $data PHP数组
	 * @return string     XML格式数据
	 */
	private function recursiveCreateXml($arr) {
		$str = '';
		foreach($arr as $k => $v) {
			if (is_array($v)) {
				if(is_numeric($k)){
					$str .= "<item>" . PHP_EOL;
					$str .= $this->recursiveCreateXml($v);
					$str .= "</item>" . PHP_EOL;
				}else{
					$str .= "<{$k}>" . PHP_EOL;
					$str .= $this->recursiveCreateXml($v);
					$str .= "</{$k}>" . PHP_EOL;
				}
			} else {
				$str .= "<{$k}>" . $v . "</{$k}>" . PHP_EOL;
			}
		}
		return $str;
	}
	
}