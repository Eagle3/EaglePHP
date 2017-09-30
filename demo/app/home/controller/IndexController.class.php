<?php

namespace home\controller;

use home\controller\CommonController;
use home\model\UserModel;
use lib\system\Page;
use lib\system\Mulupload;
use lib\system\Validate;
use lib\system\crypt\EnDecode;
use lib\system\crypt\Mcrypt3DesEcb;
use lib\system\crypt\Rsa;
use lib\system\Curl;
use lib\system\DataFormatConvert;
use lib\system\Datetime;

class IndexController extends CommonController {
    public function init(){
        //echo 'init operation<br>';
    }
	
    public function index() {
        $this->assign('name', '');
        $this->display('index');
    }
    
    //测试内置模板引擎
    public function testTpl() {
    	$v = new \lib\Template();
    	$v->assign('name', 'jack');
    	
    	$c = $v->fetch('testTpl');
		var_dump($c) ;
		exit;
		
    	$v->display('testTpl');
    }
    
    public function js() {
    	$this->display('js');
    }
    
    public function ajax() {
    	pr($_POST);
    	
    	$data = $_REQUEST;
    	$res = array(
    			'code' => 1,
    			'msg' => 'ok',
    			'data' => $data,
    	);
    	$this->echoAjax($res,'json');
    }
    
    public function ajaxCross() {
    	header('Access-Control-Allow-Origin:*');
    	//header("ACCESS-CONTROL-ALLOW-ORIGIN:http://eagle.test");
    	$data = $_REQUEST;
    	$res = array(
    			'code' => 1,
    			'msg' => 'ok',
    			'data' => $data,
    	);
    	$this->echoAjax($res);
    }
    
    public function jsonp() {
    	header("ACCESS-CONTROL-ALLOW-ORIGIN:http://eagle.test");
    	$data = $_REQUEST;
    	$callback = $data['callbackfun'];
    	$json = json_encode(array(
    			'code' => 1,
    			'msg' => 'ok',
    			'data' => $data,
    	),JSON_UNESCAPED_UNICODE);
    	$this->echoJsonp($callback,$json);
    }
    
    public function model(){
        $model = new UserModel();
        $data = $model->getInfo();
        pr($data);
    }
    
    public function page(){
        $page = new Page(20, 5, 1, 'http://'.$_SERVER['SERVER_NAME'].$_SERVER["REQUEST_URI"]);
        echo $page->pubPageStyleOne();
    }
    
    public function oaction(){      
        $adminIndex = new \admin\controller\IndexController();
        $adminIndex->index();
    }
    
    public function upload(){
        if(isset($_POST['submit']) && $_POST['submit']){
            $mulup = new Mulupload();
            $config = array(
                    'max_number' => 5,//最多上传文件个数
                    'max_size' => 0, //上传大小限制，单位：字节。0，无限制
                    'ext' => array('gif','png','jpg','jpeg','doc','docx','txt','xls','ppt'),//允许上传的类型
                    'save_path' => './upload/',//上传文件的保存路径
            );
            $upload = new Mulupload($config);
            
            //上传文件数组
            $file = $_FILES['pic'];
            pr($upload->doUpload($file),$upload->getErrMsg(),$upload->getDbSavePath());
        }else{
            $this->display('upload');
        }
    }
    
    public function check(){
        $v = new Validate('email');
        $str = 'test@test.com';
        pr($v->check($str));
    }
    
    //测试CURL发送数据
    public function curl(){
		$url = 'http://eagle.test/index.php?r=home&c=index&a=getCurl';
		$data = array(
				'name' => 'jack',
				'from' => '中国',
				'data' => json_encode(array('name' => 'jack','from' => '中国',),JSON_UNESCAPED_UNICODE),
		);
		//$data = 'name=jack&age=20';
      	$curl = new Curl($url,$data,'get',1001);
      	$res = $curl->send();
      	pr($res);
    }
    
    public function dateTest(){
    	$date = new DateTime();
    	
    	pr($date);
    }
    
    //测试CURL接收数据配合curl使用
    public function getCurl(){
    	echo '收到数据';
    	
    	//测试curl get
//     	$sData = file_get_contents('php://input');
//     	file_put_contents('./tmp/curl.log', '-get-'.var_export($_GET,true).PHP_EOL, FILE_APPEND);
//     	file_put_contents('./tmp/curl.log', '-php://input：'.var_export($sData,TRUE).PHP_EOL,FILE_APPEND);
//    	file_put_contents('./tmp/curl.log', '--------------------------------------------------------'.PHP_EOL,FILE_APPEND);

    	//测试curl post Content-Type:application/json;charset=utf-8
//     	$sData = file_get_contents('php://input');
//     	file_put_contents('./tmp/curl.log', '-post：'.var_export($_POST,true).PHP_EOL, FILE_APPEND);
//     	file_put_contents('./tmp/curl.log', '-php://input：'.var_export($sData,TRUE).PHP_EOL,FILE_APPEND);
//     	file_put_contents('./tmp/curl.log', '--------------------------------------------------------'.PHP_EOL,FILE_APPEND);
    	
    	//测试curl post Content-Type:application/x-www-form-urlencoded
//     	$sData = file_get_contents('php://input');
//     	file_put_contents('./tmp/curl.log', '-post：'.var_export($_POST,true).PHP_EOL, FILE_APPEND);
//     	file_put_contents('./tmp/curl.log', '-php://input：'.var_export($sData,TRUE).PHP_EOL,FILE_APPEND);
//     	file_put_contents('./tmp/curl.log', '--------------------------------------------------------'.PHP_EOL,FILE_APPEND);
    	
    	
    	//测试curl Content-Type: multipart/form-data
//     	$sData = file_get_contents('php://input');
//     	file_put_contents('./tmp/curl.log', '-post：'.var_export($_POST,true).PHP_EOL, FILE_APPEND);
//     	file_put_contents('./tmp/curl.log', '-php://input：'.var_export($sData,TRUE).PHP_EOL,FILE_APPEND);
//     	file_put_contents('./tmp/curl.log', '--------------------------------------------------------'.PHP_EOL,FILE_APPEND);
    	
    	//测试curl Content-Type:text/xml;charset=utf-8
//     	$xmlData = file_get_contents('php://input');
//     	$arrData = DataFormatConvert::getInstance()->xmlToArray($xmlData);
//     	$jsonData = DataFormatConvert::getInstance()->xmlToJson($xmlData);
//     	file_put_contents('./tmp/curl.log', '-post：'.var_export($_POST,true).PHP_EOL, FILE_APPEND);
//     	file_put_contents('./tmp/curl.log', '-$xmlData：'.$xmlData.PHP_EOL,FILE_APPEND);
//     	file_put_contents('./tmp/curl.log', '-$arrData：'.var_export($arrData,TRUE).PHP_EOL,FILE_APPEND);
//     	file_put_contents('./tmp/curl.log', '-$jsonData：'.$jsonData.PHP_EOL,FILE_APPEND);
//     	file_put_contents('./tmp/curl.log', '--------------------------------------------------------'.PHP_EOL,FILE_APPEND);
    }
    
    //测试数据格式转换
    public function dataConvert(){
    	echo '<pre/>';
    	
    	$xmlData = '<?xml version="1.0" encoding="utf-8"?>
				<content>
				<name>jack</name>
				<from>中国</from>
				<data>{"name":"jack","from":"中国"}</data>
				</content>';
    	$jsonData = DataFormatConvert::getInstance()->xmlToJson($xmlData);
    	echo $jsonData;
    	file_put_contents('./tmp/curl.log', '-$jsonData：'.$jsonData.PHP_EOL,FILE_APPEND);
    	file_put_contents('./tmp/curl.log', '--------------------------------------------------------'.PHP_EOL,FILE_APPEND);
    	 
    	echo '<br>';
    
    	print_r( DataFormatConvert::getInstance()->xmlToArray($xmlData) );
    	 
    	echo '<br>';
   }
    
   //测试数据格式转换2,测试数组转XML数据并输出
   public function dataConvert2(){
   	header("Content-type: text/xml");   	 
   	$arr = array(
   			'name' => 'jack',
   			'from' => '中国',
   			'data' => json_encode(array('name' => 'jack','from' => '中国',),JSON_UNESCAPED_UNICODE),
   	);
   	echo DataFormatConvert::getInstance()->arrayToXml($arr);
   }
    
    //测试加密解密数据
    public function endata(){
    	 
    	//自定义加密解密算法1
    	/* $Authcode = new EnDecode();
    	 $a = '{"Json解析":"支持格式化高亮折叠","Json格式验证":"更详细准确的错误信息"｝';
    	 $b = $Authcode->code1($a, "ENCODE", "这是密钥");
    	 echo $b."<br/>";
    	 echo $Authcode->code1($b, "DECODE", "这是密钥");
    	 echo "<br/>"; */
    	 
    	//自定义加密解密算法1
    	/* $psa = $Authcode->code2("这是明文","这是密钥",'encode');
    	 echo $psa.'<br>';
    	 echo $Authcode->code2($psa,"这是密钥",'decode');
    	 echo "<br/>"; */
    	 
    	//3DES ECB PKCS7（Java默认PKCS7模式填充） 加密解密
    	/* $Mcrypt3DesEcb = new Mcrypt3DesEcb();
    	 $str = '北京欢迎你';
    	 $key = '123456';
    	 echo '明文：'.$str ;
    	 echo '<br>';
    	 $en = $Mcrypt3DesEcb->enData($str,$key);
    	 echo '密文：'.$en ;
    	 echo '<br>';
    	 $de = $Mcrypt3DesEcb->deData($en,$key);
    	 echo '解密：'.$de;
    	 echo '<br>'; */
    }
    
    //非对称加密、解密、验签 (openssl)
    public function rsa(){
    	//使用密钥文件签名、验签
    	/* $data = '签名串';
    	 $rsa = new Rsa('./private_file/rsa_public_key.pem','./private_file/rsa_private_key.pem');
    	 $sign = $rsa->privateKeySign($data);
    	 $check = $rsa->publicKeyVerify($data,$sign);
    	 pr($sign,$check); */
    
    	//使用密钥字符串文件签名、验签
    	/* $data = '签名串';
    	 $rsa_public_key = 'MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQDcITVQ31bEL1qY0nEPloFjm/f2vOn1GuBzMYMdYi6S0FvVX/XFbLAklzepz2c0bHSVszT+8WNlU42xQoFuJ0rTe/oDtzxhTDbHgjUPt7fVrKHaPBSJnQIIRRU6YapXq0bn++SuU4QMlSTxb/onzSnM1t6Y2VskQnPMjna63VXehwIDAQAB';
    	 $rsa_private_key = 'MIICXgIBAAKBgQDcITVQ31bEL1qY0nEPloFjm/f2vOn1GuBzMYMdYi6S0FvVX/XFbLAklzepz2c0bHSVszT+8WNlU42xQoFuJ0rTe/oDtzxhTDbHgjUPt7fVrKHaPBSJnQIIRRU6YapXq0bn++SuU4QMlSTxb/onzSnM1t6Y2VskQnPMjna63VXehwIDAQABAoGBAJnKzbAJyVnZZ6dbZ0gns5A/GJeW1rG6rFNupRbzUGycC3zgxRnAXLPDvkzyLT2QBEfOY1k2lmXlYRoVx82IwBoCZ1TGgHJEfIjZrITpZVB+Yv8Jifp5fbScbemYO/gYyEZK3yjKHYzDhOdkctDf+ilokAIBA2ByGnf6G+gfHmfhAkEA/6TVQ7TKpnw96QPV5WNbJtMh5BeGKy3hBoEOi08bWR61iYSWHeb/NNszu+hrpa6MlOYq10RO7CdgSeIaIkLBdwJBANxvtdL39badasjfBasRZxhjBZZijx55uk57iWR6qr8l4cWaIGYb3WSSnERAZvJpE+etZNawW2MEzlUqGWXbj3ECQQC0kuXhUU7jklbYxNDNmwTDw9bomoU28s1EHtz7IgGbTcnFPVYcARK7byp3zJBdE5JRitMwAxwMSzQEfCUhli25AkAGaTFOi2uX/ggHA4V0rjLjYK3e68rhxgSHF8ytIWwp1v4z8wGSNqk/rYvh6EWWMzwi9sYCAGsH/DHMBEds0O/hAkEA9LG87JVuU+AV++B+GAGPpHEMIDrQH9QfwQ0H7PvobcM3pBb0L+wEl+mDB+keHjkGcLnckUQoDUoVQzxWsnoPvQ==';
    	 $rsa = new Rsa($rsa_public_key,$rsa_private_key,false,false);
    	 $sign = $rsa->privateKeySign($data);
    	 $check = $rsa->publicKeyVerify($data,$sign);
    	 pr($sign,$check); */
    	 
    	//使用私钥加密、公钥解密
    	/* $data = 'svsv';  //明文最多117字节，超过117字节需要分段加密处理，解密也需分段处理
    	 $rsa = new Rsa('./private_file/rsa_public_key.pem','./private_file/rsa_private_key.pem');
    	 $enData = $rsa->privateKeyEn($data);
    	 $deData = $rsa->publicKeyDe($enData);
    	 pr($enData,$deData); */
    	
    	
    	//使用公钥加密、私钥解密
    	 /* $data = '签名串';
    	 $rsa = new Rsa('./private_file/rsa_public_key.pem','./private_file/rsa_private_key.pem');
    	 $enData = $rsa->publicKeyEn($data);
    	 $deData = $rsa->privateKeyDe($enData);
    	 pr($enData,$deData); */
    	 
    	 //使用私钥加密、公钥解密 (超过117字节时分段加密)
    	/*  //$data = 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa9'  //明文最多117字节，超过117字节需要分段加密处理，解密也需分段处理
    	 $data = 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa是aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa是999';
    	 $rsa = new Rsa('./private_file/rsa_public_key.pem','./private_file/rsa_private_key.pem');
    	 $enData = $rsa->privateKeyEnm($data);
    	 $deData = $rsa->publicKeyDem($enData);
    	 pr($enData,$deData); */
    	 
    	 
    	 //使用公钥加密、私钥解密 (超过117字节时分段加密)
    	  /* $data = 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa是aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa是999';
    	  $rsa = new Rsa('./private_file/rsa_public_key.pem','./private_file/rsa_private_key.pem');
    	  $enData = $rsa->publicKeyEnM($data);
    	  $deData = $rsa->privateKeyDeM($enData);
    	  pr($enData,$deData); */
    }
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
}