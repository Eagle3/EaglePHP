<?php
namespace home\controller;

use home\controller\Common;
use admin\controller\Index as adminIndex;
use home\model\City;
use home\model\User;
use lib\system\Arr;
use lib\system\Mail;
use lib\system\Excel;
use lib\system\Code;
use lib\system\File;
use lib\system\crypt\EnDecode;
use lib\system\Curl;
use lib\system\Template;
use lib\system\Log;
use lib\system\Page;
use lib\system\Mulupload;
use lib\system\Upload;
use lib\system\Import;
use lib\system\Validate;
use lib\plugin\curl\php_curl_class\src\curl\Curl as Curl2;
use lib\plugin\dataConvert\xml2Array_v1\Xml2Array as Xml2ArrayV1;
use lib\plugin\dataConvert\xml2Array_v2\Xml2Array as Xml2ArrayV2;
use lib\system\DataFormatConvert;
use lib\plugin\dataConvert\array2Xml_v1\Array2Xml as Array2XmlV1;
use lib\plugin\dataConvert\array2Xml_v2\Array2Xml as Array2XmlV2;
use lib\system\Crypt\Mcrypt3DesEcb;
use lib\system\Crypt\Rsa;
use lib\system\Cache\File as FileCache;
use lib\system\Cache\Redis;
use lib\system\Cache\Memcache;
use lib\system\Download;
use lib\system\Compress\Zip;
use lib\system\Capture;
use lib\plugin\Image\SimpleImage\src\claviska\SimpleImage;
use lib\system\Mp3ToWav;
use lib\system\Socket\Server;
use lib\system\Socket\Client;
use lib\system\Luck;
use lib\system\Reward\CreateReward;
use lib\plugin\hanziToPinyin\src\Pinyin;
use lib\system\Xml;

class Index extends Common {
    public function init() {
        parent::init();
        echo '初始化方法';
        // echo 'init operation <br>';
    }
    
    public function phpinfo() {
        phpinfo(); 
    }
    
    public function index() {
        echo 'EaglePHP works ; windows nginx <br>';
        exit;
        // pr( $_SERVER );
        // $o = Index();
        // $o->index2();
        
        // $str = 'w1+-*/<>?:"{}+_)(*&^%$#@!~·！￥……（）——|：“《》？';
        // $str = '201709_1009999';
        // $key = '哈哈哈abcdef#$%123456';
        // $en = new EnDecode();
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
    
    //99乘法表
    public function times99(){
        for( $i=1;$i<=9;$i++ ){
            for( $j=1;$j<=$i;$j++ ){
                echo "{$j} x {$i} = ".($i*$j)."&nbsp;&nbsp;";
            }
            echo '<br>';
        }
        echo '<br>';
        
        for( $i=9;$i>=1;$i-- ){
            for( $j=1;$j<=$i;$j++ ){
                echo "{$j} x {$i} = ".($i*$j)."&nbsp;&nbsp;";
            }
            echo '<br>';
        }
        echo '<br>';
        
        echo '<table>';
        for( $i=9;$i>=1;$i-- ){
            echo '<tr>';
            echo $this->space( 9 - $i );
            for( $j=1;$j<=$i;$j++ ){
                echo '<td align="right">';
                echo "{$j} x {$i} = ".($i*$j).'&nbsp;&nbsp;';
                echo '</td>';
            }
            echo '</tr>';
        }
        echo '</table>';
        echo '<br>';
        
        echo '<table>';
        for( $i=1;$i<=9;$i++ ){
            echo '<tr>';
            echo $this->space( 9 - $i );
            for( $j=1;$j<=$i;$j++ ){
                echo '<td align="right">';
                echo "{$j} x {$i} = ".($i*$j).'&nbsp;&nbsp;';
                echo '</td>';
            }
            echo '</tr>';
        }
        echo '</table>';
        echo '<br>';
        
    }
    
    //阶乘
    public function jiecheng(){
        $num = 1;
        $s = '';
        $i = 10;
        for ( $n = $i;$n >= 1;$n-- ){
            $s .= "{$n} x ";
            $num = $num * $n;
        }
        $s = rtrim($s,' x');
        echo "!{$i} = {$s} = {$num}";
    }
    
    
    
    private function space( $n ){
        $s = '';
        if( $n == 0 ){
            return $s;
        }
        for( $i=1;$i<=$n;$i++ ){
            $s .= "<td></td>";
        }
        return $s;
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
        $curl = new Curl( $url, $data, 'post', 1001 );
    }
    public function testTpl() {
        $v = new Template();
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
        Log::debug( var_export( $_REQUEST, true ) );
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
        $model = new User();
        $data = $model->getInfo();
        //pr( count($data), $data );
        
        $pinyin = new Pinyin();
        
        /*
         * - 内存型，适用于服务器内存空间较富余，优点：转换快
         * - 小内存型(默认)，适用于内存比较紧张的环境，优点：占用内存小，转换不如内存型快
         * - I/O型，适用于虚拟机，内存限制比较严格环境。优点：非常微小内存消耗。缺点：转换慢，不如内存型转换快,php >= 5.5
         */
        
        // 内存型
        //$pinyin = new Pinyin('lib\plugin\hanziToPinyin\src\FileDictLoader');
        // I/O型
        // $pinyin = new Pinyin('Overtrue\Pinyin\GeneratorFileDictLoader');
        
        $city_f = array();
        foreach ( $data as $k => $v ){
            $res = $pinyin->convert( $v['area_name']);
            if( $res[0] ){
                $v['first_letter'] = substr($res[0], 0,1);
                $city_f[$v['area_name']] = $v['first_letter'];
            }else{
                continue;
            }
        }
        
        $d = var_export( $city_f,true );
        file_put_contents('./data/cityData/cityFirstLetter.php', $d);
        
    }
    public function insert() {
        $model = new User();
        $data = $model->insertData();
        pr( $data );
    }
    public function insertAll() {
        $model = new User();
        $data = $model->insertAllData();
        pr( $data );
    }
    public function update() {
        $model = new User();
        $data = $model->updateData();
        pr( $data );
    }
    public function delete() {
        $model = new User();
        $data = $model->deleteData();
        pr( $data );
    }
    public function page() {
        $page = new Page( 20, 5, 1, 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER["REQUEST_URI"] );
        echo $page->pubPageStyleOne();
    }
    public function oaction() {
        $adminIndex = new adminIndex();
        $adminIndex->index();
    }
    public function upload() {
        if ( isset( $_POST['submit'] ) && $_POST['submit'] ) {
            
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
    
    // 接收远程上传文件
    public function apiupload() {
        // \lib\system\Log::debug( var_export( $_FILES, true ) );
        $config = array(
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
                'save_path' => './Upload/'  // 上传文件的保存路径
        );
        $upload = new Upload( $config );
        // 上传文件数组
        $file = $_FILES['uploadfile'];
        $upload->doUpload( $file );
    }
    
    // 第三方上传类
    public function upload2() {
        Import::load( 'Lib/Plugin/Upload/src/class.upload.php' );
        $handle = new \upload( $_FILES['pic'] );
        if ( $handle->uploaded ) {
            $handle->file_new_name_body = 'image_resized';
            $handle->image_resize = true;
            $handle->image_x = 100;
            $handle->image_ratio_y = true;
            $handle->process( './Upload/' );
            if ( $handle->processed ) {
                echo 'image resized';
                $handle->clean();
            } else {
                echo 'error : ' . $handle->error;
            }
        }
    }
    public function check() {
        $str = 'test@test.com';
        $v = new Validate( $str, 'email' );
        
        pr( $v->check() );
    }
    
    // 测试CURL发送数据
    public function curl() {
        $url = 'http://eagle.local.test/index.php?r=home&c=index&a=getCurl';
        $data = array(
                'name' => 'jack',
                'from' => '中国',
                'data' => json_encode( array(
                        'name' => 'jack',
                        'from' => '中国'
                ), JSON_UNESCAPED_UNICODE )
        );
        //$data = 'name=jack&age=20';
        Curl::init( $url, $data, 'get', 1001 );
        $res = Curl::send();
        pr( $res );
        
        Curl::init( 'https://www.baidu.com', array() );
        $res = Curl::send();
        pr($res) ;
        
        $appId = 'abcdef';
        $key = '123456';
        $accessTokenUrl = 'http://eagle.local.test/index.php?r=home&c=index&a=getCurl';
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
        
        $curl = Curl::init( $accessTokenUrl, $postData, 'POST', 1001 );
        Curl::setOption( CURLOPT_HTTPHEADER, $header );
        $res = Curl::send();
        pr( $res );
        
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
        
        Curl::init( $url, $data, 'POST', 1001 );
        $res = Curl::send();
        pr( $res );
    }
    
    // 第三方类库curl
    public function curl2() {
        $curl = new Curl2();
        
        // GET请求
        /*
         *
         * $curl->get('https://www.baidu.com/', array(
         * //'key' => 'value',
         * ));
         * $curl->setOpts(array(
         * //CURLOPT_SSL_VERIFYPEER => false, // https不验证证书
         * //CURLOPT_SSL_VERIFYHOST => false, // https不验证证书
         * CURLOPT_HEADER => false,
         * ));
         * $curl->exec();
         * if ($curl->error) {
         * echo 'Error: ' . $curl->errorCode . ': ' . $curl->errorMessage . "\n";
         * } else {
         * echo 'Response:' . "\n";
         * echo ($curl->response);
         * }
         *
         * exit;
         */
        
        
        $url = 'http://eagle.local.test/index.php?r=home&c=index&a=getCurl';

        $data = 'name=jack&age=20';
        $curl->get($url, array(
                'name' => 'jack',
                'from' => '中国',
                'data' => json_encode( array(
                        'name' => 'jack',
                        'from' => '中国'
                ), JSON_UNESCAPED_UNICODE )
                ));
        pr( 'Error: ' . $curl->errorCode . ': ' . $curl->errorMessage . "\n", $curl->response );
        
        
        /*
         * //POST请求
         * $curl->post('https://httpbin.org/post', array(
         * 'id' => '1',
         * 'content' => 'Hello world!',
         * 'date' => date('Y-m-d H:i:s'),
         * ));
         * $curl->setOpts(array(
         * //CURLOPT_SSL_VERIFYPEER => false,
         * //CURLOPT_SSL_VERIFYHOST => false,
         * CURLOPT_HEADER => false,
         * ));
         * $curl->exec();
         * if ($curl->error) {
         * echo 'Error: ' . $curl->errorCode . ': ' . $curl->errorMessage . "\n";
         * } else {
         * echo 'Data server received via POST:' . "\n";
         * var_dump($curl->response->form);
         * }
         */
        
        /*
         * //post json格式,返回对象格式数据
         *
         * $data = array(
         * 'id' => '1',
         * 'content' => 'Hello world!',
         * 'date' => date('Y-m-d H:i:s'),
         * );
         * $curl->setHeader('Content-Type', 'application/json');
         * $curl->post('https://httpbin.org/post', $data);
         * pr($curl->response->json);
         */
        
        /*
         * //post json格式,返回数组格式数据
         *
         * $data = array(
         * 'id' => '1',
         * 'content' => 'Hello world!',
         * 'date' => date('Y-m-d H:i:s'),
         * );
         * $curl->setDefaultJsonDecoder($assoc = true);
         * $curl->setHeader('Content-Type', 'application/json');
         * $curl->post('https://httpbin.org/post', $data);
         * pr($curl->response['json']);
         *
         */
        
        /*
         *
         * //curl文件上传
         * $myfile = curl_file_create('./static/image/1.jpg', 'image/jpeg', 'postname.jpg');
         *
         * // HINT: If API documentation refers to using something like curl -F "myimage=image.png",
         * // curl --form "myimage=image.png", or the html form is similar to <form enctype="multipart/form-data" method="post">,
         * // then try uncommenting the following line:
         * // $curl->setHeader('Content-Type', 'multipart/form-data');
         *
         * //$url = 'https://httpbin.org/post';
         * $url = 'http://eagle.test/index.php?r=home&c=index&a=apiupload';
         * //$curl->setOpt(CURLOPT_PROXY,'127.0.0.1:8888'); //设置代理服务器，可以用作抓包使用
         * $curl->post($url, array(
         * 'uploadfile' => $myfile, //postname.jpg 上传文件名；uploadfile参数名（name="uploadfile"）
         * ));
         *
         * if ($curl->error) {
         * echo 'Error: ' . $curl->errorCode . ': ' . $curl->errorMessage . "\n";
         * } else {
         * echo 'Success' . "\n";
         * }
         */
    }
    
    // 测试CURL接收数据配合curl使用
    public function getCurl() {
        header('Content-type: application/json');
        //Log::debug( 'request:' . var_export( $_REQUEST, true ) . ',header:' . var_export( getallheaders(), true ) );
        echo json_encode( array(
                'request' => $_REQUEST 
        ) );
        exit;
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
        $jsonData = DataFormatConvert::getInstance()->xmlToJson($xmlData);
        // file_put_contents('./tmp/curl.log', '-post：'.var_export($_POST,true).PHP_EOL, FILE_APPEND);
        // file_put_contents('./tmp/curl.log', '-$xmlData：'.$xmlData.PHP_EOL,FILE_APPEND);
        // file_put_contents('./tmp/curl.log', '-$arrData：'.var_export($arrData,TRUE).PHP_EOL,FILE_APPEND);
        // file_put_contents('./tmp/curl.log', '-$jsonData：'.$jsonData.PHP_EOL,FILE_APPEND);
        // file_put_contents('./tmp/curl.log', '--------------------------------------------------------'.PHP_EOL,FILE_APPEND);
    }
    public function xml2array() {
        $xmlData = '<?xml version="1.0" encoding="utf-8"?>
				<content>
				<name>jack</name>
				<from>中国</from>
				<data>{"name":"jack","from":"中国"}</data>
				</content>';
        
        $xmlData = '<xml>
  <appid><![CDATA[wx2421b1c4370ec43b]]></appid>
  <attach><![CDATA[支付测试]]></attach>
  <bank_type><![CDATA[CFT]]></bank_type>
  <fee_type><![CDATA[CNY]]></fee_type>
  <is_subscribe><![CDATA[Y]]></is_subscribe>
  <mch_id><![CDATA[10000100]]></mch_id>
  <nonce_str><![CDATA[5d2b6c2a8db53831f7eda20af46e531c]]></nonce_str>
  <openid><![CDATA[oUpF8uMEb4qRXf22hE3X68TekukE]]></openid>
  <out_trade_no><![CDATA[1409811653]]></out_trade_no>
  <result_code><![CDATA[SUCCESS]]></result_code>
  <return_code><![CDATA[SUCCESS]]></return_code>
  <sign><![CDATA[B552ED6B279343CB493C5DD0D78AB241]]></sign>
  <sub_mch_id><![CDATA[10000100]]></sub_mch_id>
  <time_end><![CDATA[20140903131540]]></time_end>
  <total_fee>1</total_fee>
<coupon_fee><![CDATA[10]]></coupon_fee>
<coupon_count><![CDATA[1]]></coupon_count>
<coupon_type><![CDATA[CASH]]></coupon_type>
<coupon_id><![CDATA[10000]]></coupon_id>
<coupon_fee><![CDATA[100]]></coupon_fee>
  <trade_type><![CDATA[JSAPI]]></trade_type>
  <transaction_id><![CDATA[1004400740201409030005092168]]></transaction_id>
</xml>';
        
        /*
         * $xmlData = '<?xml version="1.0" encoding="utf-8"?><xml><name>jack</name><from>中国</from><data><node>北京</node><node>上海</node></data></xml>';
         */
        
        /*
         * $xmlData = '<?xml version="1.0" encoding="utf-8"?><xml><appid>wx2421b1c4370ec43b</appid><attach>支付测试</attach><bank_type>CFT</bank_type><fee_type>CNY</fee_type><is_subscribe>Y</is_subscribe><mch_id>10000100</mch_id><nonce_str>5d2b6c2a8db53831f7eda20af46e531c</nonce_str><openid>oUpF8uMEb4qRXf22hE3X68TekukE</openid><out_trade_no>1409811653</out_trade_no><result_code>SUCCESS</result_code><return_code>SUCCESS</return_code><sign>B552ED6B279343CB493C5DD0D78AB241</sign><sub_mch_id>10000100</sub_mch_id><time_end>20140903131540</time_end><total_fee>1</total_fee><coupon_fee><node>10</node><node>100</node></coupon_fee><coupon_count>1</coupon_count><coupon_type>CASH</coupon_type><coupon_id>10000</coupon_id><trade_type>JSAPI</trade_type><transaction_id>1004400740201409030005092168</transaction_id></xml>';
         */
        
        //$arrData = DataFormatConvert::getInstance()->xmlToArray( $xmlData );
        //$arrData = DataFormatConvert::getInstance()->xmlToArray2( $xmlData );
        
        $o = new Xml2ArrayV1();
        $arr = $o->run($xmlData);
        pr( $arr);
        
        $o = new Xml2ArrayV2();
        $arr = $o->xmlstr_to_array($xmlData);
        pr( $arr);
        
    }
    public function array2xml() {
        header( "Content-type: text/xml" );
        
        $arr = array(
                'name' => 'jack',
                'from' => '中国' 
        );
        
        $arr = array(
                'name' => 'jack',
                'from' => '中国',
                'data' => json_encode( array(
                        'name' => 'jack',
                        'from' => '中国' 
                ), JSON_UNESCAPED_UNICODE ) 
        );
        
        $arr = array(
                'name' => 'jack',
                'from' => '中国',
                'data' => array(
                        'city' => array(
                                'city1' => '北京',
                                'city2' => '上海' 
                        ) 
                ) 
        );
        
        $arr = array(
                'name' => 'jack',
                'from' => '中国',
                'data' => array(
                        '北京',
                        '上海' 
                ) 
        );
        
        $arr = array(
                'appid' => 'wx2421b1c4370ec43b',
                'attach' => '支付测试',
                'bank_type' => 'CFT',
                'fee_type' => 'CNY',
                'is_subscribe' => 'Y',
                'mch_id' => '10000100',
                'nonce_str' => '5d2b6c2a8db53831f7eda20af46e531c',
                'openid' => 'oUpF8uMEb4qRXf22hE3X68TekukE',
                'out_trade_no' => '1409811653',
                'result_code' => 'SUCCESS',
                'return_code' => 'SUCCESS',
                'sign' => 'B552ED6B279343CB493C5DD0D78AB241',
                'sub_mch_id' => '10000100',
                'time_end' => '20140903131540',
                'total_fee' => '1',
                'coupon_fee' => array(
                        0 => '10',
                        1 => '100' 
                ),
                'coupon_count' => '1',
                'coupon_type' => 'CASH',
                'coupon_id' => '10000',
                'trade_type' => 'JSAPI',
                'transaction_id' => '1004400740201409030005092168' 
        );
        
/*         $arr = array('product' => array(
                '@id' => 7,
                'name' => 'some string',
                'seo' => 'some-string',
                'ean' => '',
                'producer' => array(
                        'name' => null,
                        'photo' => '1.png'
                ),
                'stock' => 123,
                'trackstock' => 0,
                'new' => 0,
                'pricewithoutvat' => 1111,
                'price' => 1366.53,
                'discountpricenetto' => null,
                'discountprice' => null,
                'vatvalue' => 23,
                'currencysymbol' => 'PLN',
                '#description' => '',
                '#longdescription' => '',
                '#shortdescription' => '',
                'category' => array(
                        'photo' => '1.png',
                        'name' => 'test3',
                ),
                'staticattributes' => array(
                        'attributegroup' => array(
                                1 => array(
                                        '@name' => 'attributes group',
                                        'attribute' => array(
                                                0 => array(
                                                        'name' => 'second',
                                                        'description' => 'desc2',
                                                        'file' => '',
                                                ),
                                                1 =>
                                                array(
                                                        'name' => 'third',
                                                        'description' => 'desc3',
                                                        'file' => '',
                                                ),
                                        )
                                )
                        )
                ),
                'attributes' => array(),
                'photos' => array(
                        'photo' => array(
                                0 => array(
                                        '@mainphoto' => '1',
                                        '%' => '1.png',
                                ),
                                1 => array(
                                        '@mainphoto' => '0',
                                        '%' => '2.png',
                                ),
                                2 => array(
                                        '@mainphoto' => '0',
                                        '%' => '3.png',
                                )
                        )
                )
        ));
         */
        
          // echo DataFormatConvert::getInstance()->arrayToXml($arr);
        
        $o = new Array2XmlV1();
        //echo $o->buildXML($arr);
        
        echo Array2XmlV2::run($arr);
        
    }
    
    // 测试加密解密数据
    public function endata() {
        $Authcode = new EnDecode();
        
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
        $Mcrypt3DesEcb = new Mcrypt3DesEcb();
        /*
         * $Mcrypt3DesEcb = new Mcrypt3DesEcb();
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
         * $rsa = new Rsa('./data/private_file/rsa_public_key.pem','./data/private_file/rsa_private_key.pem');
         * $sign = $rsa->privateKeySign($data);
         * $check = $rsa->publicKeyVerify($data,$sign);
         * pr($sign,$check);
         */
        
        // 使用密钥字符串文件签名、验签
        /*
         * $data = '签名串';
         * $rsa_public_key = 'MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQDcITVQ31bEL1qY0nEPloFjm/f2vOn1GuBzMYMdYi6S0FvVX/XFbLAklzepz2c0bHSVszT+8WNlU42xQoFuJ0rTe/oDtzxhTDbHgjUPt7fVrKHaPBSJnQIIRRU6YapXq0bn++SuU4QMlSTxb/onzSnM1t6Y2VskQnPMjna63VXehwIDAQAB';
         * $rsa_private_key = 'MIICXgIBAAKBgQDcITVQ31bEL1qY0nEPloFjm/f2vOn1GuBzMYMdYi6S0FvVX/XFbLAklzepz2c0bHSVszT+8WNlU42xQoFuJ0rTe/oDtzxhTDbHgjUPt7fVrKHaPBSJnQIIRRU6YapXq0bn++SuU4QMlSTxb/onzSnM1t6Y2VskQnPMjna63VXehwIDAQABAoGBAJnKzbAJyVnZZ6dbZ0gns5A/GJeW1rG6rFNupRbzUGycC3zgxRnAXLPDvkzyLT2QBEfOY1k2lmXlYRoVx82IwBoCZ1TGgHJEfIjZrITpZVB+Yv8Jifp5fbScbemYO/gYyEZK3yjKHYzDhOdkctDf+ilokAIBA2ByGnf6G+gfHmfhAkEA/6TVQ7TKpnw96QPV5WNbJtMh5BeGKy3hBoEOi08bWR61iYSWHeb/NNszu+hrpa6MlOYq10RO7CdgSeIaIkLBdwJBANxvtdL39badasjfBasRZxhjBZZijx55uk57iWR6qr8l4cWaIGYb3WSSnERAZvJpE+etZNawW2MEzlUqGWXbj3ECQQC0kuXhUU7jklbYxNDNmwTDw9bomoU28s1EHtz7IgGbTcnFPVYcARK7byp3zJBdE5JRitMwAxwMSzQEfCUhli25AkAGaTFOi2uX/ggHA4V0rjLjYK3e68rhxgSHF8ytIWwp1v4z8wGSNqk/rYvh6EWWMzwi9sYCAGsH/DHMBEds0O/hAkEA9LG87JVuU+AV++B+GAGPpHEMIDrQH9QfwQ0H7PvobcM3pBb0L+wEl+mDB+keHjkGcLnckUQoDUoVQzxWsnoPvQ==';
         * $rsa = new Rsa($rsa_public_key,$rsa_private_key,false,false);
         * $sign = $rsa->privateKeySign($data);
         * $check = $rsa->publicKeyVerify($data,$sign);
         * pr($sign,$check);
         */
        
        // 使用私钥加密、公钥解密
        /*
         * $data = 'svsv'; //明文最多117字节，超过117字节需要分段加密处理，解密也需分段处理
         * $rsa = new Rsa('./data/private_file/rsa_public_key.pem','./data/private_file/rsa_private_key.pem');
         * $enData = $rsa->privateKeyEn($data);
         * $deData = $rsa->publicKeyDe($enData);
         * pr($enData,$deData);
         */
        
        // 使用公钥加密、私钥解密
        /*
         * $data = '签名串';
         * $rsa = new Rsa('./data/private_file/rsa_public_key.pem','./data/private_file/rsa_private_key.pem');
         * $enData = $rsa->publicKeyEn($data);
         * $deData = $rsa->privateKeyDe($enData);
         * pr($enData,$deData);
         */
        
        // 使用私钥加密、公钥解密 (超过117字节时分段加密)
        /*
         * //$data = 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa9' //明文最多117字节，超过117字节需要分段加密处理，解密也需分段处理
         * $data = 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa是aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa是999';
         * $rsa = new Rsa('./data/private_file/rsa_public_key.pem','./data/private_file/rsa_private_key.pem');
         * $enData = $rsa->privateKeyEnm($data);
         * $deData = $rsa->publicKeyDem($enData);
         * pr($enData,$deData);
         */
        
        // 使用公钥加密、私钥解密 (超过117字节时分段加密)
        /*
         * $data = 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa是aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa是999';
         * $rsa = new Rsa('./data/private_file/rsa_public_key.pem','./data/private_file/rsa_private_key.pem');
         * $enData = $rsa->publicKeyEnM($data);
         * $deData = $rsa->privateKeyDeM($enData);
         * pr($enData,$deData);
         */
    }
    public function arr() {
        // $arr10k = getConfig('arrSortData.10k');
        // $arr20k = getConfig('arrSortData.20k');
        // $arr50k = getConfig('arrSortData.50k');
        $arr = Arr::createHugeArr( 10000 );
        
        $t1 = microtime( true );
        $arr = Arr::phpSort( $arr, 'desc' );
        $t2 = microtime( true );
        pr( $t2 - $t1, $arr );
        
        $t1 = microtime( true );
        $arr = Arr::bubbleSort( $arr, 'desc' );
        $t2 = microtime( true );
        pr( $t2 - $t1, $arr );
        
        // $arr = Arr::quickSort($arr,'desc');
        // pr($arr);
        
        // $arr = Arr::quickSort($arr,'desc');
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
        
        /*
         * $headFields = array(
         * '名字',
         * '年龄'
         * );
         *
         * $keys = array(
         * 'name',
         * 'age'
         * );
         *
         * $data = array(
         * array(
         * 'name' => 'jack',
         * 'age' => 20
         * ),
         * array(
         * 'name' => 'jack2',
         * 'age' => 21
         * ),
         * array(
         * 'name' => 'jack3',
         * 'age' => 22
         * ),
         * array(
         * 'name' => 'jack4',
         * 'age' => 23
         * )
         * );
         */
        
        $model = new City();
        $data = $model->getInfo();
        
        $headFields = array(
                '城市ID',
                '父城市ID',
                '城市名' 
        );
        
        $keys = array(
                'area_id',
                'parent_id',
                'area_name' 
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
        // Excel::export( $data ); // 默认
        Excel::export( $data, $version, $fileName, $headFields, $keys ); // 传递自定义参数
    }
    
    // 输出验证码
    public function code() {
        Code::init();
        Code::set( array(
                'width' => 100,
                'height' => 40 
        ) );
        Code::output();
    }
    
    // 校验验证码
    public function vCode() {
        $code = $_REQUEST['code'];
        var_dump( $this->verifyCode( $code ) );
    }
    public function file() {
        $file = PROJECT_PATH . 'data/readMe.txt';
        pr( File::dir2array( PROJECT_PATH, true ) );
    }
    public function fileCache() {
        $file= FileCache::getInstance();
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
        $file->set( 'test', $data );
        pr( $file->get( 'test' ) );
    }
    public function redis() {
        $redis = Redis::getInstance();
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
        $Memcache = Memcache::getInstance();
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
        Download::exe( './static/font/consolab.ttf' );
    }
    
    // 文件打包
    public function zip() {
        // 传入目录地址
        $file = array(
                './static/js/Eagle.js',
                './static/css/base.css',
                './index.php' 
        );
        
        $file = './static/js/Eagle.js';
        $file = './static/';
        
        $file = array(
                1 => 'http://pic4.nipic.com/20091217/3885730_124701000519_2.jpg',
                2 => 'http://img07.tooopen.com/images/20170316/tooopen_sy_201956178977.jpg' 
        );
        
        $zip = new Zip( $file );
        $zipFilePath = $zip->exe();
        // unlink($zipFilePath);
        
        pr( $zipFilePath );
    }
    
    // 远程资源抓取
    public function capture() {
        // 传入远程图片地址
        $fileArr = array(
                1 => 'http://pic4.nipic.com/20091217/3885730_124701000519_2.jpg',
                2 => 'http://img07.tooopen.com/images/20170316/tooopen_sy_201956178977.jpg' 
        );
        Capture::init( $fileArr );
        
        Capture::exe();
    }
    public function validate() {
        // $str = '132@qq.com';
        // $Validate = new \lib\system\Validate( $str,'email' );
        // $res = $Validate->check();
        
        // $str = '13012345678';
        // $Validate = new \lib\system\Validate( $str,'phone' );
        // $res = $Validate->check();
        
        // $str = '021-87888822';
        // $Validate = new \lib\system\Validate( $str,'tel' );
        // $res = $Validate->check();
        $str = 'https://www.baidu.com';
        $Validate = new Validate( $str, 'url' );
        $res = $Validate->check();
        var_dump( $res );
    }
    
    // 第三方类库图片处理
    public function image3() {
        try {
            // Create a new SimpleImage object
            $img = new SimpleImage();
            
            // Manipulate it
            $img->fromFile( './static/image/2.jpg' )-> // load parrot.jpg
                                               // ->autoOrient() // adjust orientation based on exif data 根据exif数据调整方向
                                               // ->bestFit(512, 500) // proportinoally resize to fit inside a 250x400 box 按比例调整图像大小，以适应特定的宽度和高度。
                                               // ->resize(512, 500) // 不按比例缩放图片
                                               // ->flip('y') // flip horizontally 水平翻转 ( x y both)
                                               // ->crop(500, 500, 100, 100) // 剪切图片中的一部分
            thumbnail( 300, 300, 'right' )-> // 创建缩略图
                                           // ->colorize('DarkGreen') // tint dark green 给图片着色深绿色
                                           // ->border('black', 5) // add a 5 pixel black border 添加一个5像素的黑色边框
                                           // ->overlay('./static/image/water.jpg', 'bottom right') // add a watermark image 添加水印图片
                                           // ->toFile('./static/image/2_thumb_right.jpg', null, 100) //把处理后的图片保存
            toScreen(); // output to the screen 输出到屏幕
        } catch ( \Exception $err ) {
            echo $err->getMessage();
        }
    }
    public function mp3towav() {
        $mp3file = './Data/audio/ghsy.mp3';
        Mp3ToWav::run( $mp3file );
    }
    public function socketServer() {
        $Socket = new Server();
        $Socket->run();
    }
    public function socketClient() {
        $Socket = new Client();
        $Socket->run();
    }
    
    public function webSocket() {
        $this->display('websocket');
    }
    
    public function webSocketSwoole() {
        $this->display('websocket_swoole');
    }
    
    public function luck() {
        $weight = [
                [
                        'name' => '超级大奖',
                        'weight' => 0  // 0
                ],
                [
                        'name' => '特等奖',
                        'weight' => 1  // 1/10000
                ],
                [
                        'name' => '一等奖',
                        'weight' => 2  // 2/10000
                ],
                [
                        'name' => '二等奖',
                        'weight' => 7  // 7/10000
                ],
                [
                        'name' => '未中奖',
                        'weight' => 99990  // 9990/10000
                ] 
        ];
        
        Luck::init( $weight, 100000 );
        $res = Luck::run(); // 抽奖
        $total = Luck::test( 10000 ); // 统计10000次抽奖获奖记录
        pr( $res, $total );
    }
    public function randm() {
        header( 'content-type:text/html;charset=utf-8' );
        ini_set( 'memory_limit', '256M' );
        $create_reward = new CreateReward();
        $total = 100;
        $num = 10;
        $max = 12;
        $min = 6;
        // 性能测试
        for ( $i = 0; $i < 5; $i ++ ) {
            // $time_start = microtime_float();
            $reward_arr = $create_reward->random_red( $total, $num, $max, $min );
            // $time_end = microtime_float();
            // $time[] = $time_end - $time_start;
        }
        echo array_sum( $time ) / 5;
        function microtime_float() {
            list ( $usec, $sec ) = explode( " ", microtime() );
            return (( float )$usec + ( float )$sec);
        }
        pr( $reward_arr );
        
        /*
         * 检查结果
         * $reward_arr = $create_reward->random_red($total, $num, $max, $min);
         * sort($reward_arr);//正序，最小的在前面
         * $sum = 0;
         * $min_count = 0;
         * $max_count = 0;
         * foreach($reward_arr as $i => $val) {
         * if ($i<3) {
         * echo "<br />第".($i+1)."个红包，金额为：".$val."<br />";
         * }
         * if ($val == $max) {
         * $max_count++;
         * }
         * if ($val < $min) {
         * $min_count++;
         * }
         * $val = $val*100;
         * $sum += $val;
         * }
         * //检测钱是否全部发完
         * echo '<hr>已生成红包总金额为：'.($sum/100).';总个数为：'.count($reward_arr).'<hr>';
         * //检测有没有小于0的值
         * echo "<br />最大值:".($val/100).',共有'.$max_count.'个最大值，共有'.$min_count.'个值比最小值小';
         */
        
        /*
         * 正态分布图
         * //对红包进行排序一下以显示正态分布特性
         * $reward_arr = $create_reward->random_red($total, $num, $max, $min);
         * $show = array();
         * rsort($reward_arr);
         * foreach($reward_arr as $k=>$value)
         * {
         * $t=$k%2;
         * if(!$t) $show[]=$value;
         * else array_unshift($show,$value);
         * }
         * echo "设定最大值为:".$max.',最小值为:'.$min.'<hr />';
         * echo "<table style='font-size:12px;width:600px;border:1px solid #ccc;text-align:left;'><tr><td>红包金额</td><td>图示</td></tr>";
         * foreach($show as $val)
         * {
         * #线条长度计算
         * $width=intval($num*$val*300/$total);
         * echo "<tr><td>&nbsp;{$val}&nbsp;</td><td width='500px;text-align:left;'><hr style='width:{$width}px;height:3px;border:none;border-top:3px double red;margin:0 auto 0 0px;'></td></tr>";
         * }
         * echo "</table>";
         */
    }
    public function hanzi2pinyin() {
        $pinyin = new Pinyin();
        
        /*
         * - 内存型，适用于服务器内存空间较富余，优点：转换快
         * - 小内存型(默认)，适用于内存比较紧张的环境，优点：占用内存小，转换不如内存型快
         * - I/O型，适用于虚拟机，内存限制比较严格环境。优点：非常微小内存消耗。缺点：转换慢，不如内存型转换快,php >= 5.5
         */
        
        // 内存型
        // $pinyin = new Pinyin('Overtrue\Pinyin\MemoryFileDictLoader');
        // I/O型
        // $pinyin = new Pinyin('Overtrue\Pinyin\GeneratorFileDictLoader');
        
        $res = $pinyin->convert( '带着希望去旅行，比到达终点更美好' );
        // ["dai", "zhe", "xi", "wang", "qu", "lv", "xing", "bi", "dao", "da", "zhong", "dian", "geng", "mei", "hao"]
        
        $res = $pinyin->convert( '带着希望去旅行，比到达终点更美好', PINYIN_UNICODE );
        // ["dài","zhe","xī","wàng","qù","lǚ","xíng","bǐ","dào","dá","zhōng","diǎn","gèng","měi","hǎo"]
        
        $res = $pinyin->convert( '带着希望去旅行，比到达终点更美好', PINYIN_ASCII );
        // ["dai4","zhe","xi1","wang4","qu4","lv3","xing2","bi3","dao4","da2","zhong1","dian3","geng4","mei3","hao3"]
        
        // 生成用于链接的拼音字符串
        $res = $pinyin->permalink( '带着希望去旅行' ); // dai-zhe-xi-wang-qu-lv-xing
        $res = $pinyin->permalink( '带着希望去旅行', '.' ); // dai.zhe.xi.wang.qu.lv.xing
                                                   
        // 获取首字符字符串
        $res = $pinyin->abbr( '带着希望去旅行' ); // dzxwqlx
        $res = $pinyin->abbr( '带着希望去旅行', '-' ); // d-z-x-w-q-l-x
        
        /*
         * 翻译整段文字为拼音
         * 将会保留中文字符：，。 ！ ？ ： “ ” ‘ ’ 并替换为对应的英文符号。
         */
        
        $res = $pinyin->sentence( '带着希望去旅行，比到达终点更美好！' );
        // dai zhe xi wang qu lv xing, bi dao da zhong dian geng mei hao!
        $res = $pinyin->sentence( '带着希望去旅行，比到达终点更美好！', true );
        // dài zhe xī wàng qù lǚ xíng, bǐ dào dá zhōng diǎn gèng měi hǎo!
        
        /*
         * 翻译姓名
         * 姓名的姓的读音有些与普通字不一样，比如 ‘单’ 常见的音为 dan，而作为姓的时候读 shan。
         */
        
        $res = $pinyin->name( '单雄信' ); // ['shan', 'mou', 'mou']
        $res = $pinyin->name( '单雄信', PINYIN_UNICODE ); // ["shàn","mǒu","mǒu"]
        
        pr( $res );
    }
    
    // 二维码
    public function qrcode() {
        Import::load( 'lib/plugin/PHPQrcode/phpqrcode.php' );
        
        // 定义纠错级别
        $errorLevel = "L";
        // 定义生成图片宽度和高度;默认为3
        $size = "5";
        // 定义生成内容
        $content = "微信公众平台：思维与逻辑;公众号:siweiyuluoji";
        // 调用QRcode类的静态方法png生成二维码图片//
        \QRcode::png( $content, false, $errorLevel, $size );
        
        // 生成网址类型
        $url = "https://www.baidu.com";
        // \QRcode::png($url, false, $errorLevel, $size);
        
        // \QRcode::png('PHP QR Code :)');
    }
    public function tree() {
        header( "content-type:text/html;charset=utf-8" );
        $categories = array(
                array(
                        'id' => 1,
                        'name' => '电脑',
                        'pid' => 0 
                ),
                array(
                        'id' => 2,
                        'name' => '手机',
                        'pid' => 0 
                ),
                array(
                        'id' => 3,
                        'name' => '笔记本',
                        'pid' => 1 
                ),
                array(
                        'id' => 4,
                        'name' => '台式机',
                        'pid' => 1 
                ),
                array(
                        'id' => 5,
                        'name' => '智能机',
                        'pid' => 2 
                ),
                array(
                        'id' => 6,
                        'name' => '功能机',
                        'pid' => 2 
                ),
                array(
                        'id' => 7,
                        'name' => '超级本',
                        'pid' => 3 
                ),
                array(
                        'id' => 8,
                        'name' => '游戏本',
                        'pid' => 3 
                ) 
        );
        
        /* ======================非递归实现======================== */
        $tree = array();
        // 第一步，将分类id作为数组key,并创建children单元
        foreach ( $categories as $category ) {
            $tree[$category['id']] = $category;
            $tree[$category['id']]['children'] = array();
        }
        // 第二步，利用引用，将每个分类添加到父类children数组中，这样一次遍历即可形成树形结构。
        foreach ( $tree as $key => $item ) {
            if ( $item['pid'] != 0 ) {
                $tree[$item['pid']]['children'][] = &$tree[$key]; // 注意：此处必须传引用否则结果不对
                if ( $tree[$key]['children'] == null ) {
                    unset( $tree[$key]['children'] ); // 如果children为空，则删除该children元素（可选）
                }
            }
        }
        // //第三步，删除无用的非根节点数据
        foreach ( $tree as $key => $category ) {
            if ( $category['pid'] != 0 ) {
                unset( $tree[$key] );
            }
        }
        
        pr( $tree );
    }
    
    //城市联动信息按格式写入文件,不用查询数据库
    public function city() {
        $model = new City();
        $all = $model->getInfo();
        
        $p = array_filter( $all, function ( $val ) {
            if ( $val['area_type'] === 1 ) {
                return true;
            }
        }, ARRAY_FILTER_USE_BOTH );
        unset( $val );
        $p = array_combine(array_column($p, 'area_id'), $p);
        
        foreach ( $p as $k => $v ) {
            foreach ($all as $key => $val){
                if( $val['parent_id'] == $v['area_id'] ){
                    $p[$k]['children'][$val['area_id']] = $val;
                    foreach ($all as $key2 => $val2){
                        if( $p[$k]['children'][$val['area_id']]['area_id'] == $val2['parent_id'] ){
                            $p[$k]['children'][$val['area_id']]['children'][$val2['area_id']]= $val2;
                        }
                    }
                }            
            }
        }

        //pr( $p );
        
        //file_put_contents( './Data/cityData/city.php', '<?php '.PHP_EOL.' return '.var_export( $p, true ).';' );
        //file_put_contents( './Data/cityData/city.json', json_encode( $p, JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT) );
        //file_put_contents( './Data/cityData/city.js', 'var city = '.json_encode( $p, JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT).';');
    }
    
    public function canvas(){
        $this->display('canvas');
    }
    
    //页面静态化
    public function html(){
        //ob_start(); 
        $model = new City();
        $data= $model->getInfo();
        
        $i = 1;
        foreach ( $data as $k=>$v ){
            if( $i == 4 ){
                break;
            }
            $this->assign('data',$v);
            $data = $this->fetch('html');
            $fileName = "./html/{$v['area_id']}.html";
            //$fileName = mb_convert_encoding($fileName, 'gbk','utf-8');
            file_put_contents($fileName, $data);
            $i++;
        }
        
        // ob_end_clean();
    }
    
   
    public function xml(){
        /*    */
        $data = [
            'name' => 'jack',
            'from' => '中国',
            'data' => array(
                    array(
                            '广州',
                            '南宁'
                    ),
                    'city' => array(
                            '北京',
                            '上海'
                    )
            ),
        ];
        
        /* 
        //$xml = XML::array2Xml_DOMDocument($data);
        
       // $xml = XML::array2Xml_SimpleXML($data);
        
        $xml = XML::array2Xml_XMLWriter($data);
        
        header( "Content-type: text/xml" );
        echo $xml;
        exit;
      */
        
        /*   */
        $xmldata = <<<xml
        <root>
          <name>jack</name>
          <from>中国</from>
          <data>
            <item>
                <item>广州</item>
                <item>南宁</item>
            </item>
            <city>
              <item>北京</item>
              <item>上海</item>
            </city>
          </data>
        </root>
xml;

        $xmldata= '<xml>
  <appid><![CDATA[wx2421b1c4370ec43b]]></appid>
  <attach><![CDATA[支付测试]]></attach>
  <bank_type><![CDATA[CFT]]></bank_type>
  <fee_type><![CDATA[CNY]]></fee_type>
  <is_subscribe><![CDATA[Y]]></is_subscribe>
  <mch_id><![CDATA[10000100]]></mch_id>
  <nonce_str><![CDATA[5d2b6c2a8db53831f7eda20af46e531c]]></nonce_str>
  <openid><![CDATA[oUpF8uMEb4qRXf22hE3X68TekukE]]></openid>
  <out_trade_no><![CDATA[1409811653]]></out_trade_no>
  <result_code><![CDATA[SUCCESS]]></result_code>
  <return_code><![CDATA[SUCCESS]]></return_code>
  <sign><![CDATA[B552ED6B279343CB493C5DD0D78AB241]]></sign>
  <sub_mch_id><![CDATA[10000100]]></sub_mch_id>
  <time_end><![CDATA[20140903131540]]></time_end>
  <total_fee>1</total_fee>
<coupon_fee><![CDATA[10]]></coupon_fee>
<coupon_count><![CDATA[1]]></coupon_count>
<coupon_type><![CDATA[CASH]]></coupon_type>
<coupon_id><![CDATA[10000]]></coupon_id>
<coupon_fee><![CDATA[100]]></coupon_fee>
  <trade_type><![CDATA[JSAPI]]></trade_type>
  <transaction_id><![CDATA[1004400740201409030005092168]]></transaction_id>
</xml>';
        
        $arr = XML::xml2Array_SimpleXML($xmldata);
        
        pr( $arr );
        
    }
    
    
        
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
}
