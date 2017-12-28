<?php

namespace Home\Controller;

use Home\Controller\CommonController as CommonController;
use Lib\System\Arr as Arr;
use Lib\System\Mail as Mail;
use Lib\System\Excel as Excel;
use Lib\System\Code as Code;
use Lib\System\File as File;

// use Lib\System\Fsocket;
class IndexController extends CommonController {
    public function init() {
        parent::init();
        // echo 'init operation <br>';
    }
    public function index() {
        // pr( $_SERVER );
        // $o = new \Admin\Controller\IndexController();
        // $o->index2();
        
        // $str = 'w1+-*/<>?:"{}+_)(*&^%$#@!~·！￥……（）——|：“《》？';
        // $str = '201709_1009999';
        // $key = '哈哈哈abcdef#$%123456';
        // $en = new \Lib\System\Crypt\EnDecode();
        // $enStr1 = $en->code1($str, $key, 'ENCODE' ) ;
        // $deStr1 = $en->code1($enStr1,$key, 'DECODE') ;
        // $enStr2 = $en->code2($str, $key,'encode') ;
        // $deStr2 = $en->code2($enStr2, $key,'decode') ;
        // $enStr3 = $en->code3($str, $key,'encode' ) ;
        // $deStr3 = $en->code3($enStr3, $key,'decode') ;
        // pr($enStr1,$deStr1,$enStr2,$deStr2,$enStr3,$deStr3);
        $this->assign( 'name', 'test' );
        $this->display( 'index' );
    }
    
    // 测试httpheader Authorization
    public function oauthor() {
        $header = array(
                'Authorization: MAC id=123,ts=12345678901,nonce=ASgdwgdsbsn,mac=SSKGFJDKJwrtvSBDSKLB==' 
        );
        
        $url = 'http://eagle.test/index.php?r=home&c=index&a=getCurl';
        $data = array(
                'name' => 'jack',
                'from' => '中国',
                'data' => json_encode( array(
                        'name' => 'jack',
                        'from' => '中国' 
                ), JSON_UNESCAPED_UNICODE ) 
        );
        // $data = 'name=jack&age=20';
        $curl = new \Lib\System\Curl( $url, $data, 'post', 1001 );
        $curl->setHttpHeader( $header );
        $res = $curl->send();
        pr( $res );
    }
    public function testTpl() {
        $v = new \Lib\System\Template();
        $v->assign( 'name', 'jack' );
        
        $c = $v->fetch( 'testTpl' );
        var_dump( $c );
        exit();
        
        $v->display( 'testTpl' );
    }
    public function js() {
        $this->display( 'js' );
    }
    public function log() {
        
        // $string = 'http://thirdapp0.qlogo.cn/qzopenapp/b025b6dd00a560fcc7ee9340291c76359572fc67bd88348a03b3bb52003841ef/50';
        // $data = str_replace(array('-', '_'), array('+', '/'), $string);
        // $mod4 = strlen($data) % 4;
        // if ($mod4) {
        // $data .= substr('====', $mod4);
        // }
        // $url = base64_decode($data);
        // $_REQUEST['url'] = $url;
        \Lib\System\Log::debug( var_export( $_REQUEST, true ) );
    }
    public function ajax() {
        // pr($_POST);
        $data = $_REQUEST;
        $res = array(
                'code' => 1,
                'msg' => 'ok',
                'data' => $data 
        );
        $this->echoAjax( $res, 'json' );
    }
    public function ajaxCross() {
        header( 'Access-Control-Allow-Origin:*' );
        // header("ACCESS-CONTROL-ALLOW-ORIGIN:http://eagle.test");
        $data = $_REQUEST;
        $res = array(
                'code' => 1,
                'msg' => 'ok',
                'data' => $data 
        );
        $this->echoAjax( $res );
    }
    public function jsonp() {
        header( "ACCESS-CONTROL-ALLOW-ORIGIN:http://eagle.test" );
        $data = $_REQUEST;
        $callback = $data['callbackfun'];
        $json = json_encode( array(
                'code' => 1,
                'msg' => 'ok',
                'data' => $data 
        ), JSON_UNESCAPED_UNICODE );
        $this->echoJsonp( $callback, $json );
    }
    public function select() {
        $model = new \Home\Model\UserModel();
        $data = $model->getInfo();
        pr( $data );
    }
    public function insert() {
        $model = new \Home\Model\UserModel();
        $data = $model->insertData();
        pr( $data );
    }
    public function update() {
        $model = new \Home\Model\UserModel();
        $data = $model->updateData();
        pr( $data );
    }
    
    public function delete() {
        $model = new \Home\Model\UserModel();
        $data = $model->deleteData();
        pr( $data );
    }
    public function page() {
        $page = new \Lib\System\Page( 20, 5, 1, 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER["REQUEST_URI"] );
        echo $page->pubPageStyleOne();
    }
    public function oaction() {
        $adminIndex = new \Admin\Controller\IndexController();
        $adminIndex->index();
    }
    public function upload() {
        if ( isset( $_POST['submit'] ) && $_POST['submit'] ) {
            $mulup = new \Lib\System\Mulupload();
            $config = array(
                    'max_number' => 5, // 最多上传文件个数
                    'max_size' => 0, // 上传大小限制，单位：字节。0，无限制
                    'ext' => array(
                            'gif',
                            'png',
                            'jpg',
                            'jpeg',
                            'doc',
                            'docx',
                            'txt',
                            'xls',
                            'ppt' 
                    ), // 允许上传的类型
                    'save_path' => './upload/'  // 上传文件的保存路径
            );
            $upload = new Mulupload( $config );
            
            // 上传文件数组
            $file = $_FILES['pic'];
            pr( $upload->doUpload( $file ), $upload->getErrMsg(), $upload->getDbSavePath() );
        } else {
            $this->display( 'upload' );
        }
    }
    public function check() {
        $v = new \Lib\System\Validate( 'email' );
        $str = 'test@test.com';
        pr( $v->check( $str ) );
    }
    
    // 测试CURL发送数据
    public function curl() {
        $appId = 'abcdef';
        $key = '123456';
        $accessTokenUrl = 'http://eagle.test/index.php?r=home&c=index&a=getCurl';
        $timestamp = time();
        $nonce = md5( uniqid( microtime( true ), true ) );
        $method = 'POST';
        $uri = '/oauth/access_token';
        $host = 'id.gionee.com';
        $port = '443';
        $n = "\n";
        $str = "{$timestamp}\n{$nonce}\n{$method}\n{$uri}\n{$host}\n{$port}\n{$n}";
        $signature = base64_encode( hash_hmac( 'sha1', $str, $key, true ) );
        $postData = array(
                'grant_type' => 'authorization_code', // true string 请求的类型，目前只支持一种authorization_code
                'code' => 'FSDGDJSGPJSWBGNKB', // true string GrantCode值，有获取临时授权码接口获取所得。
                'redirect_uri' => 'http://api.egret-labs.org/v2/game/22853/91234'  // true string 授权回调地址，必须与OAuth/Authorize设置一致。
        );
        
        $header = array(
                'Content-Type:application/x-www-form-urlencoded',
                // 'Authorization: MAC id="'.$appId.'",'.$n.'ts="'.$timestamp.'",'.$n.'nonce="'.$nonce.'",'.$n.'mac="'.$signature.'"',
                "Authorization: MAC id=\"{$appId}\",ts=\"{$timestamp}\",nonce=\"{$nonce}\",mac=\"{$signature}\"" 
        );
        
        $curl = new \Lib\System\Curl( $accessTokenUrl, $postData, 'POST', 1001 );
        $curl->setOption( CURLOPT_HTTPHEADER, $header );
        $res = $curl->send();
        exit();
        
        $url = 'https://blast.triumbest.net:11001/TopUp_server/rechangeEgret.json';
        $data = array(
                'orderId' => '42336448585242594645784665567848',
                'id' => '9100ef200d513db37f13ac6515ab16e3',
                'money' => '6',
                'ext' => '359540;9100ef200d513db37f13ac6515ab16e3',
                'time' => 1508318412,
                'serverId' => '1',
                'goodsId' => '30001',
                'goodsNumber' => 1,
                'sign' => 'efbbb014b5ad5ece7be72873179df784' 
        );
        
        $curl = new \Lib\System\Curl( $url, $data, 'POST', 1001 );
        $res = $curl->send();
        var_dump( $res );
        exit();
        
        $url = 'http://eagle.test/index.php?r=home&c=index&a=getCurl';
        $data = array(
                'name' => 'jack',
                'from' => '中国',
                'data' => json_encode( array(
                        'name' => 'jack',
                        'from' => '中国' 
                ), JSON_UNESCAPED_UNICODE ) 
        );
        // $data = 'name=jack&age=20';
        $curl = new \Lib\System\Curl( $url, $data, 'get', 1001 );
        $res = $curl->send();
        pr( $res );
    }
    public function dateTest() {
        $date = new \Lib\System\DateTime();
        
        pr( $date );
    }
    
    // 测试CURL接收数据配合curl使用
    public function getCurl() {
        \Lib\System\Log::debug( 'request:' . var_export( $_REQUEST, true ) . ',header:' . var_export( getallheaders(), true ) );
        echo json_encode( array(
                'request' => $_REQUEST 
        ) );
        
        echo '收到数据';
        
        // 测试curl get
        // $sData = file_get_contents('php://input');
        // file_put_contents('./tmp/curl.log', '-get-'.var_export($_GET,true).PHP_EOL, FILE_APPEND);
        // file_put_contents('./tmp/curl.log', '-php://input：'.var_export($sData,TRUE).PHP_EOL,FILE_APPEND);
        // file_put_contents('./tmp/curl.log', '--------------------------------------------------------'.PHP_EOL,FILE_APPEND);
        
        // 测试curl post Content-Type:application/json;charset=utf-8
        // $sData = file_get_contents('php://input');
        // file_put_contents('./tmp/curl.log', '-post：'.var_export($_POST,true).PHP_EOL, FILE_APPEND);
        // file_put_contents('./tmp/curl.log', '-php://input：'.var_export($sData,TRUE).PHP_EOL,FILE_APPEND);
        // file_put_contents('./tmp/curl.log', '--------------------------------------------------------'.PHP_EOL,FILE_APPEND);
        
        // 测试curl post Content-Type:application/x-www-form-urlencoded
        // $sData = file_get_contents('php://input');
        // file_put_contents('./tmp/curl.log', '-post：'.var_export($_POST,true).PHP_EOL, FILE_APPEND);
        // file_put_contents('./tmp/curl.log', '-php://input：'.var_export($sData,TRUE).PHP_EOL,FILE_APPEND);
        // file_put_contents('./tmp/curl.log', '--------------------------------------------------------'.PHP_EOL,FILE_APPEND);
        
        // 测试curl Content-Type: multipart/form-data
        // $sData = file_get_contents('php://input');
        // file_put_contents('./tmp/curl.log', '-post：'.var_export($_POST,true).PHP_EOL, FILE_APPEND);
        // file_put_contents('./tmp/curl.log', '-php://input：'.var_export($sData,TRUE).PHP_EOL,FILE_APPEND);
        // file_put_contents('./tmp/curl.log', '--------------------------------------------------------'.PHP_EOL,FILE_APPEND);
        
        // 测试curl Content-Type:text/xml;charset=utf-8
        // $xmlData = file_get_contents('php://input');
        // $arrData = DataFormatConvert::getInstance()->xmlToArray($xmlData);
        // $jsonData = DataFormatConvert::getInstance()->xmlToJson($xmlData);
        // file_put_contents('./tmp/curl.log', '-post：'.var_export($_POST,true).PHP_EOL, FILE_APPEND);
        // file_put_contents('./tmp/curl.log', '-$xmlData：'.$xmlData.PHP_EOL,FILE_APPEND);
        // file_put_contents('./tmp/curl.log', '-$arrData：'.var_export($arrData,TRUE).PHP_EOL,FILE_APPEND);
        // file_put_contents('./tmp/curl.log', '-$jsonData：'.$jsonData.PHP_EOL,FILE_APPEND);
        // file_put_contents('./tmp/curl.log', '--------------------------------------------------------'.PHP_EOL,FILE_APPEND);
    }
    
    // 测试数据格式转换
    public function dataConvert() {
        echo '<pre/>';
        
        $xmlData = '<?xml version="1.0" encoding="utf-8"?>
				<content>
				<name>jack</name>
				<from>中国</from>
				<data>{"name":"jack","from":"中国"}</data>
				</content>';
        $jsonData = \Lib\System\DataFormatConvert::getInstance()->xmlToJson( $xmlData );
        echo $jsonData;
        file_put_contents( './tmp/curl.log', '-$jsonData：' . $jsonData . PHP_EOL, FILE_APPEND );
        file_put_contents( './tmp/curl.log', '--------------------------------------------------------' . PHP_EOL, FILE_APPEND );
        
        echo '<br>';
        
        print_r( \Lib\System\DataFormatConvert::getInstance()->xmlToArray( $xmlData ) );
        
        echo '<br>';
    }
    
    // 测试数据格式转换2,测试数组转XML数据并输出
    public function dataConvert2() {
        header( "Content-type: text/xml" );
        $arr = array(
                'name' => 'jack',
                'from' => '中国',
                'data' => json_encode( array(
                        'name' => 'jack',
                        'from' => '中国' 
                ), JSON_UNESCAPED_UNICODE ) 
        );
        echo \Lib\System\DataFormatConvert::getInstance()->arrayToXml( $arr );
    }
    
    // 测试加密解密数据
    public function endata() {
        $Authcode = new \Lib\System\Crypt\EnDecode();
        
        // 自定义加密解密算法1
        /*
         * $a = '{"Json解析":"支持格式化高亮折叠","Json格式验证":"更详细准确的错误信息"｝';
         * $b = $Authcode->code1($a, "ENCODE", "这是密钥");
         * echo $b."<br/>";
         * echo $Authcode->code1($b, "DECODE", "这是密钥");
         * echo "<br/>";
         */
        
        // 自定义加密解密算法1
        /*
         * $psa = $Authcode->code2("这是明文","这是密钥",'encode');
         * echo $psa.'<br>';
         * echo $Authcode->code2($psa,"这是密钥",'decode');
         * echo "<br/>";
         */
        
        // 3DES ECB PKCS7（Java默认PKCS7模式填充） 加密解密
        /*
         * $Mcrypt3DesEcb = new \Lib\System\Crypt\Mcrypt3DesEcb();
         * $str = '北京欢迎你';
         * $key = '123456';
         * echo '明文：'.$str ;
         * echo '<br>';
         * $en = $Mcrypt3DesEcb->enData($str,$key);
         * echo '密文：'.$en ;
         * echo '<br>';
         * $de = $Mcrypt3DesEcb->deData($en,$key);
         * echo '解密：'.$de;
         * echo '<br>';
         */
    }
    
    // 非对称加密、解密、验签 (openssl)
    public function rsa() {
        // 使用密钥文件签名、验签
        /*
         * $data = '签名串';
         * $rsa = new \Lib\System\Crypt\Rsa('./data/private_file/rsa_public_key.pem','./data/private_file/rsa_private_key.pem');
         * $sign = $rsa->privateKeySign($data);
         * $check = $rsa->publicKeyVerify($data,$sign);
         * pr($sign,$check);
         */
        
        // 使用密钥字符串文件签名、验签
        /*
         * $data = '签名串';
         * $rsa_public_key = 'MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQDcITVQ31bEL1qY0nEPloFjm/f2vOn1GuBzMYMdYi6S0FvVX/XFbLAklzepz2c0bHSVszT+8WNlU42xQoFuJ0rTe/oDtzxhTDbHgjUPt7fVrKHaPBSJnQIIRRU6YapXq0bn++SuU4QMlSTxb/onzSnM1t6Y2VskQnPMjna63VXehwIDAQAB';
         * $rsa_private_key = 'MIICXgIBAAKBgQDcITVQ31bEL1qY0nEPloFjm/f2vOn1GuBzMYMdYi6S0FvVX/XFbLAklzepz2c0bHSVszT+8WNlU42xQoFuJ0rTe/oDtzxhTDbHgjUPt7fVrKHaPBSJnQIIRRU6YapXq0bn++SuU4QMlSTxb/onzSnM1t6Y2VskQnPMjna63VXehwIDAQABAoGBAJnKzbAJyVnZZ6dbZ0gns5A/GJeW1rG6rFNupRbzUGycC3zgxRnAXLPDvkzyLT2QBEfOY1k2lmXlYRoVx82IwBoCZ1TGgHJEfIjZrITpZVB+Yv8Jifp5fbScbemYO/gYyEZK3yjKHYzDhOdkctDf+ilokAIBA2ByGnf6G+gfHmfhAkEA/6TVQ7TKpnw96QPV5WNbJtMh5BeGKy3hBoEOi08bWR61iYSWHeb/NNszu+hrpa6MlOYq10RO7CdgSeIaIkLBdwJBANxvtdL39badasjfBasRZxhjBZZijx55uk57iWR6qr8l4cWaIGYb3WSSnERAZvJpE+etZNawW2MEzlUqGWXbj3ECQQC0kuXhUU7jklbYxNDNmwTDw9bomoU28s1EHtz7IgGbTcnFPVYcARK7byp3zJBdE5JRitMwAxwMSzQEfCUhli25AkAGaTFOi2uX/ggHA4V0rjLjYK3e68rhxgSHF8ytIWwp1v4z8wGSNqk/rYvh6EWWMzwi9sYCAGsH/DHMBEds0O/hAkEA9LG87JVuU+AV++B+GAGPpHEMIDrQH9QfwQ0H7PvobcM3pBb0L+wEl+mDB+keHjkGcLnckUQoDUoVQzxWsnoPvQ==';
         * $rsa = new \Lib\System\Crypt\Rsa($rsa_public_key,$rsa_private_key,false,false);
         * $sign = $rsa->privateKeySign($data);
         * $check = $rsa->publicKeyVerify($data,$sign);
         * pr($sign,$check);
         */
        
        // 使用私钥加密、公钥解密
        /*
         * $data = 'svsv'; //明文最多117字节，超过117字节需要分段加密处理，解密也需分段处理
         * $rsa = new \Lib\System\Crypt\Rsa('./data/private_file/rsa_public_key.pem','./data/private_file/rsa_private_key.pem');
         * $enData = $rsa->privateKeyEn($data);
         * $deData = $rsa->publicKeyDe($enData);
         * pr($enData,$deData);
         */
        
        // 使用公钥加密、私钥解密
        /*
         * $data = '签名串';
         * $rsa = new \Lib\System\Crypt\Rsa('./data/private_file/rsa_public_key.pem','./data/private_file/rsa_private_key.pem');
         * $enData = $rsa->publicKeyEn($data);
         * $deData = $rsa->privateKeyDe($enData);
         * pr($enData,$deData);
         */
        
        // 使用私钥加密、公钥解密 (超过117字节时分段加密)
        /*
         * //$data = 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa9' //明文最多117字节，超过117字节需要分段加密处理，解密也需分段处理
         * $data = 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa是aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa是999';
         * $rsa = new \Lib\System\Crypt\Rsa('./data/private_file/rsa_public_key.pem','./data/private_file/rsa_private_key.pem');
         * $enData = $rsa->privateKeyEnm($data);
         * $deData = $rsa->publicKeyDem($enData);
         * pr($enData,$deData);
         */
        
        // 使用公钥加密、私钥解密 (超过117字节时分段加密)
        /*
         * $data = 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa是aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa是999';
         * $rsa = new \Lib\System\Crypt\Rsa('./data/private_file/rsa_public_key.pem','./data/private_file/rsa_private_key.pem');
         * $enData = $rsa->publicKeyEnM($data);
         * $deData = $rsa->privateKeyDeM($enData);
         * pr($enData,$deData);
         */
    }
    public function arr() {
        // $arr10k = getConfig('arrSortData.10k');
        // $arr20k = getConfig('arrSortData.20k');
        // $arr50k = getConfig('arrSortData.50k');
        $arr = \Lib\System\Arr::createHugeArr( 10000 );
        
        $t1 = microtime( true );
        $arr = \Lib\System\Arr::phpSort( $arr, 'desc' );
        $t2 = microtime( true );
        pr( $t2 - $t1, $arr );
        
        $t1 = microtime( true );
        $arr = \Lib\System\Arr::bubbleSort( $arr, 'desc' );
        $t2 = microtime( true );
        pr( $t2 - $t1, $arr );
        
        // $arr = \Lib\System\Arr::quickSort($arr,'desc');
        // pr($arr);
        
        // $arr = \Lib\System\Arr::quickSort($arr,'desc');
        // pr($arr);
    }
    public function mail() {
        $subject = "这是邮件标题";
        $content = "这是邮件内容";
        $res = Mail::send( $subject, $content );
        pr( $res );
    }
    
    // Excel表格导出
    public function export() {
        $fileName = '测试';
        $headFields = array(
                '名字',
                '年龄' 
        );
        
        $keys = array(
                'name',
                'age' 
        );
        
        $data = array(
                array(
                        'name' => 'jack',
                        'age' => 20 
                ),
                array(
                        'name' => 'jack2',
                        'age' => 21 
                ),
                array(
                        'name' => 'jack3',
                        'age' => 22 
                ),
                array(
                        'name' => 'jack4',
                        'age' => 23 
                ) 
        );
        
        $version = '2003';
        
        /**
         * excel导出 （精简版，去掉不必要的样式，数据最好在500以内）
         * 
         * @param array $data
         *            导出的数据 必须
         * @param string $version
         *            导出的版本 2003或者2007 可选
         * @param string $fileName
         *            导出的文件名 可选
         * @param array $headFields
         *            导出的字段格式化后名称（表格头部列的名称） 可选
         * @param unknown $keys
         *            导出的字段名称 可选
         */
        Excel::export( $data ); // 默认
                                    // Excel::import( $data, $version, $fileName, $headFields, $keys ); //传递自定义参数
    }
    
    // 输出验证码
    public function code() {
        $code = new Code();
        $code->set( array(
                'width' => 100,
                'height' => 40 
        ) );
        $code->output();
    }
    
    // 校验验证码
    public function vCode() {
        $code = $_REQUEST['code'];
        var_dump( $this->verifyCode( $code ) );
    }
    public function file() {
        $file = PROJECT_PATH . 'Data/readMe.txt';
        pr( File::dir2array( PROJECT_PATH, true ) );
    }
    public function redis() {
        $redis = \Lib\System\Cache\Redis::getInstance();
        $data = array(
                array(
                        'name' => 'jack',
                        'age' => 20 
                ),
                array(
                        'name' => 'jack1',
                        'age' => 21 
                ) 
        );
        $redis->set( 'test', $data );
        pr( $redis->get( 'test' ) );
    }
    public function memcache() {
        $Memcache = \Lib\System\Cache\Memcache::getInstance();
        $data = array(
                array(
                        'name' => 'jack',
                        'age' => 20 
                ),
                array(
                        'name' => 'jack1',
                        'age' => 21 
                ) 
        );
        $Memcache->set( 'test', $data );
        pr( $Memcache->get( 'test' ) );
    }
    
    // 文件下载
    public function down() {
        $down = new \Lib\System\Download();
        $down->exe( './Static/font/consolab.ttf');
    }
    
    // 文件打包
    public function zip() {
        // 传入目录地址
        
        $file = array(
                './Static/js/Eagle.js',
                './Static/css/base.css',
                './index.php'
        );
        
        $file = './Static/js/Eagle.js';
        $file = './Static/';
        
        $file = array(
                1 => 'http://pic4.nipic.com/20091217/3885730_124701000519_2.jpg',
                2 => 'http://img07.tooopen.com/images/20170316/tooopen_sy_201956178977.jpg'
        );
        
        $zip = new \Lib\System\Compress\Zip( $file );
        $zipFilePath = $zip->exe();
        //unlink($zipFilePath);
        
        pr( $zipFilePath );
    }
    
    // 远程资源抓取
    public function capture() {
        // 传入远程图片地址
        $fileArr = array(
                1 => 'http://pic4.nipic.com/20091217/3885730_124701000519_2.jpg',
                2 => 'http://img07.tooopen.com/images/20170316/tooopen_sy_201956178977.jpg' 
        );
        $remoteDownload = new \Lib\System\Capture( $fileArr );
        
        $remoteDownload->exe();
    }
}
