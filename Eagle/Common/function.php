<?php
//////////////////////////////////////////////
//											//
//				系统公共函数库			    //
//											//
//////////////////////////////////////////////

/**
 * 调试输出
 */
function pr(){
    echo '<pre>';
    foreach (func_get_args() as $info){
    	//var_dump($info);
    	print_r($info);
    	echo '<br/>';
    }
    echo '</pre>';
    echo '<hr>';
    exit;
}

/**
 * 获取自定义所有常量
 * @return array
 */
function getCustomConstant(){
    return (get_defined_constants(true)['user']);
}

/**
 *  获取配置
 * @param string $param  配置参数，形如：  CHINA.PROVINCE.CITY.COUNTRY  多级参数使用 . 号分割
 * @param array $confArr 要加载的配置文件
 * @return mixed
 */
function getConfig($param = '',$confArr = array(SYS_CONFIG_PATH,PROJECT_CONFIG_PATH)){
    $configData = array();
    foreach ($confArr as $v){
        $file = $v.'config.php';
        $configData = array_merge($configData,require $file);
    }
    if(!$param){
        return $configData;
    }
    
    $param = explode('.', $param);
    $count = count($param);
    if($count == 1){
        return $configData[$param[0]];
    }else{
        foreach ($param as $k=>$v){
            if($v == ''){
                return '参数格式错误';
                break;
            }
            $configData = $configData[$v];
        }
        return $configData;
    }
}

/**
 *  获取语言包
 * @param string $param  语言标识
 * @return array
 */
function getLangConfig($lang = 'zh-cn'){
    return require SYS_LANGUAGE_PATH.$lang.'.php';
}

/**
 * 获取smarty对象
 * @return object Smarty
 */
function getSmartyObj(){
    require_once SMARTY_PATH;
    Smarty_Autoloader::register();
    return new Smarty();
}

/**
 * 遍历目录
 * @param string $dir_path  目录
 */
function listFiles($dir_path){
	if(is_dir($dir_path)){
		$dir_handle = opendir($dir_path);
		if($dir_handle){
			while (($file = readdir($dir_handle)) !== false){
				if($file != '.' && $file != '..' && is_dir($dir_path.'/'.$file)){
					echo "<font color='red'><b>$file</b></font><br />";
					list_files($dir_path.'/'.$file);
				}else{
					if($file != '.' && $file != '..'){
						echo $file.'<br />';
					}
				}
			}
		}
	}
}
/*
 echo '<h1>The red tag is directory</h1><br />';
 list_files('C:\Program Files\TortoiseSVN')
 */

/**
 * 删除目录下所有文件
 * @param string $dir 目录路径
 * @return boolean
 */
function deleteDir($dir){
    if(is_dir($dir)){
        foreach(scandir($dir) as $row){
            if($row == '.' || $row == '..'){
                continue;
            }
            $path = $dir .'/'. $row;
            if(filetype($path) == 'dir'){
                deleteDir($path);
            }else{
                unlink($path);
            }
        }
        rmdir($dir);
    }else{
        return false;
    }

}

/**
 * 检测是否是整型
 * @param string|integer $val 字符串或整型
 * @return boolean
 */
function check_is_int($var){
	//先检测变量是否为数字或数字字符串(防止出现 类似  ‘2asdd’ 字符)
	//再次检测是否是整型(后面 +0 是为了进行一下加法运算，类似  ‘23’ 等字符会被转成23。如果不 +0 运算，那么输入 ‘23’ 不会认为是整型。而表单输入的值多数是字符串‘23’形式，也认为是整型，因此需要运算一下)
	if(is_numeric($var) && is_int($var + 0)){
		return true;
	}
	return false;
}
//var_dump(check_is_int('2'));

/**
 * 检测是否是浮点型
 * @param string|float $var 字符串或浮点数
 * @return boolean
 */
function check_is_float($var){
	if(is_numeric($var) && preg_match('/^[0-9]+(\.[0-9]+)$/', $var)){
		return true;
	}
	return false;
}
//var_dump(check_is_float('2.123'));

/**
 * 检测是否是Ajax请求
 * @return boolean
 */
function check_is_ajax_request(){
	if(isset($_SERVER['HTTP_X_REQUEST_WIDTH']) && strtolower($_SERVER['HTTP_X_REQUEST_WIDTH']) == 'xmlhttprequest'){
		return true;
	}
	return false;
}
//var_dump(check_is_ajax_request());

/**
 * 检测是否是POST请求
 * @return boolean
 */
function check_is_post_request(){
	if(isset($_SERVER['REQUEST_METHOD']) && strtolower($_SERVER['REQUEST_METHOD']) == 'post'){
		return true;
	}
	return false;
}
//var_dump(check_is_post_request());

/**
 * 检测是否是GET请求
 * @return boolean
 */
function check_is_get_request(){
	if(isset($_SERVER['REQUEST_METHOD']) && strtolower($_SERVER['REQUEST_METHOD']) == 'get'){
		return true;
	}
	return false;
}
//var_dump(check_is_get_request());

/**
 * 检测手机号码格式是否正确
 * @param string $phone 手机号
 * @return boolean
 */
function check_is_phone($phone){
	if(preg_match('/^1[34578]{1}\d{9}$/', $phone)){
		return true;
	}
	return false;
}
//var_dump(check_is_phone('19012345678'));

/**
 * 检测Email格式是否正确
 * @param string $eamil 邮箱
 * @return boolean
 */
function check_is_email($eamil){
	if(preg_match('/^[\w\-\.]+@[\w\-\.]+(\.\w+)+$/', $eamil)){
		return true;
	}
	return false;
}
//var_dump(check_is_email('test@qq.com.cn'));

/**
 * 检测客户端浏览器是不是IE
 * @return boolean
 */
function check_is_ie(){
	$agent = strtolower($_SERVER['HTTP_USER_AGENT']);
	if(strpos($agent,'msie')!==false || strpos($agent,'rv:11.0')){ //ie11判断
		return true;
	}
	return false;
}
//var_dump(check_is_ie());

/**
 * 检测客户端浏览器是不是IE6
 * @return boolean
 */
function check_is_ie6(){
	$agent = strtolower($_SERVER['HTTP_USER_AGENT']);
	if(strpos($agent,'msie 6.0')!==false){
		return true;
	}
	return false;
}
//var_dump(check_is_ie6());

/**
 * 检测客户端浏览器是不是IE7
 * @return boolean
 */
function check_is_ie7(){
	$agent = strtolower($_SERVER['HTTP_USER_AGENT']);
	if(strpos($agent,'msie 7.0')!==false){
		return true;
	}
	return false;
}
//var_dump(check_is_ie7());

/**
 * 检测客户端浏览器是不是IE8
 * @return boolean
 */
function check_is_ie8(){
	$agent = strtolower($_SERVER['HTTP_USER_AGENT']);
	if(strpos($agent,'msie 8.0')!==false){
		return true;
	}
	return false;
}
//var_dump(check_is_ie8());

/**
 * 检测客户端浏览器是不是IE9
 * @return boolean
 */
function check_is_ie9(){
	$agent = strtolower($_SERVER['HTTP_USER_AGENT']);
	if(strpos($agent,'msie 9.0')!==false){
		return true;
	}
	return false;
}
//var_dump(check_is_ie9());

/**
 * 检测qq号码格式是否正确
 * @param string $qq qq号码
 * @return boolean
 */
function check_is_qq($qq){
	if(preg_match('/^[1-9][0-9]{4,10}$/', $qq)){
		return true;
	}
	return false;
}
//var_dump(check_is_qq('123456789012'));

/**
 * 匹配数字和大小写字母组合
 * @param string $subject
 * @return boolean
 */
function check_is_number_and_letter($subject){
	if(preg_match("/^[0-9A-Za-z]+$/", $subject)){
		return true;
	}
	return false;
}

/**
 * 匹配数字
 * @param string $subject
 * @return boolean
 */
function check_is_number($subject){
	if(preg_match("/^[0-9]+$/", $subject)){
		return true;
	}
	return false;
}

/**
 * 匹配大小写字母
 * @param string $subject
 * @return boolean
 */
function check_is_letter($subject){
	if(preg_match("/^[A-Za-z]+$/", $subject)){
		return true;
	}
	return false;
}

/**
 * 检测IP地址合法性
 * @param string $str
 * @return boolean
 */
function check_is_ip($str){
	$ip=explode('.',$str);
	for($i=0;$i<count($ip);$i++){
		if($ip[$i]>255){
			return false;
		}
	}
	return preg_match('/^[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}$/',$str);
}
//var_dump(check_is_ip('0.0.0.*'));

/**
 * 获取客户端浏览器类型
 * @return boolean
 */
function get_client_brower_type(){
	$agent = strtolower($_SERVER['HTTP_USER_AGENT']);
	if(strpos($agent,'msie')!==false || strpos($agent,'rv:11.0')){ //ie11判断
		return "ie";
	}else if(strpos($agent,'firefox')!==false){
		return "firefox";
	}else if(strpos($agent,'chrome')!==false){
		return "chrome";
	}else if(strpos($agent,'opera')!==false){
		return 'opera';
	}else if((strpos($agent,'chrome')==false) && strpos($agent,'safari')!==false){
		return 'safari';
	}else{
		return 'unknown';
	}
}
//var_dump(get_client_brower_type());

/**
 * 获取客户端IP地址
 */
function get_ip(){
	$ip='未知IP地址';
	if (isset($_SERVER)) {
		if(!empty($_SERVER['HTTP_CLIENT_IP'])){
			$arr = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
			foreach ($arr AS $ips) {
				$ips = trim($ip);
				if ($ip != 'unknown') {
					$realip = $ips;
					break;
				}
			}
			return check_is_ip($realip) ? $realip : $ip;
		}elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
			return check_is_ip($_SERVER['HTTP_X_FORWARDED_FOR'])?$_SERVER['HTTP_X_FORWARDED_FOR']:$ip;
		}else{
			return check_is_ip($_SERVER['REMOTE_ADDR'])?$_SERVER['REMOTE_ADDR']:$ip;
		}
	} else {
		if (getenv('HTTP_X_FORWARDED_FOR')) {
			return check_is_ip(getenv('HTTP_X_FORWARDED_FOR')) ? getenv('HTTP_X_FORWARDED_FOR') : $ip;
		} elseif (getenv('HTTP_CLIENT_IP')) {
			return check_is_ip(getenv('HTTP_CLIENT_IP')) ? getenv('HTTP_CLIENT_IP') : $ip;
		} else {
			return check_is_ip(getenv('REMOTE_ADDR')) ? getenv('REMOTE_ADDR') : $ip;
		}
	}

}
//var_dump(get_ip());

/**
 * 获取用户来路
 * @return string | null
 */
function get_refer(){
	return $_SERVER['HTTP_REFERER'];
}
//var_dump(get_refer());

/**
 * 取得文件扩展
 * @param $filename 文件名
 * @return 扩展名
 */
function get_file_ext($filename) {
	return substr($filename, strrpos($filename, '.')+1);
}
//var_dump(get_file_ext('index.class.php'));

/**
 * 获取缓存过期时间戳
 * 例如：每个小时的第三分钟（08:03:00）开始更新数据，那么缓存过期时间就设置到下个小时的第二分钟59秒过期(09:02:59，但是过期时间最好设置稍长点，程序执行需要时间，以免数据不能同步)
 * @param integer/string  $min  每小时几分更新（例如：03）
 * @return $exp                 过期时间戳
 */
function getExpTime($exe_min = 0){
	$exp = 0;
	//更新时间
	$exe_min = (int)$exe_min;
	$exe_min = $exe_min >= 0 && $exe_min <= 59 ? $exe_min : ($exe_min < 0 ? 0 : 59);
	if(mb_strlen($exe_min) == 1){
		$exe_min_before_format = $exe_min - 1 > 0 ? (string)'0'.($exe_min - 1) : '59';
	}else{
		$exe_min_before_format = (string)($exe_min - 1);
	}

	//当前小时和分钟
	$cur_hour = (int)date('H',time());
	$cur_min = (int)date('i',time());
	$cur_hour_format = mb_strlen($cur_hour) == 1 ? (string)'0'.($cur_hour): (string)($cur_hour);
	$next_hour_format = mb_strlen($cur_hour) == 1 ? (string)'0'.($cur_hour + 1): (string)($cur_hour + 1);

	//加上30s防止请求和更新程序同时执行造成数据不同步
	if($exe_min == 0){
		$exp = strtotime(date('Y-m-d '.$cur_hour_format.':'.$exe_min_before_format.':59',time())) + 30;
	}else{
		if($cur_min < $exe_min){
			$exp = strtotime(date('Y-m-d '.$cur_hour_format.':'.$exe_min_before_format.':59',time())) + 30;
		}elseif($cur_min >= $exe_min){
			$exp = strtotime(date('Y-m-d '.$next_hour_format.':'.$exe_min_before_format.':59',time())) + 30;
		}
	}

	return $exp;
}

//var_dump(date('Y-m-d H:i:s',getExpTime('03')));

/**
 * 格式化数值，默认保留两位小数，默认不四舍五入（一般金钱的格式需要格式化）
 * @param string|integer|float $num 格式化的数值
 * @param integer decimals 保留小数的位数，默认是2位
 * @param boolean $format 是否四舍五入，默认不四舍五入
 * @return string 返回一个浮点类型字符串  如：'123.32'
 */
function format_number( $num = '', $decimals = 2, $format = false){
	if(empty($num)){
		return number_format(0,$decimals,'.',',');
	}
	if($format){
		return number_format($num,$decimals,'.',',');
	}
	return sprintf("%.".$decimals."f",substr(sprintf('%.8f',$num), 0,($decimals - 8)));
}
//var_dump(format_number('12.578999',4));

/**
 * gbk转utf-8
 * @param string|array $data 	  字符串或数组
 * @return string|array 	  	  返回字符串或数组
 */
function gbk_to_utf8($data){
	if(!is_array($data)){
		return iconv('gbk', 'utf-8', $data);
	}
	foreach($data as $key=>$val){
		if(is_array($val)) {
			//如果还是数组递归处理
			$data[$key] = data_iconv($val, 'gbk', 'utf-8');
		} else {
			$data[$key] = iconv('gbk', 'utf-8', $val);
		}
	}
	return $data;
}
//$data = array('中国',123);
//var_dump($data);
//var_dump(data_iconv($data));

/**
 * utf-8转gbk
 * @param string|array $data 	  字符串或数组
 * @return string|array 	  	  返回字符串或数组
 */
function utf8_to_gbk($data){
	if(!is_array($data)){
		return iconv('utf-8', 'gbk', $data);
	}
	foreach($data as $key=>$val){
		if(is_array($val)) {
			//如果还是数组递归处理
			$data[$key] = data_iconv($val, 'utf-8', 'gbk');
		} else {
			$data[$key] = iconv('utf-8', 'gbk', $val);
		}
	}
	return $data;
}
//$data = array('中国',123);
//var_dump($data);
//var_dump(data_iconv($data));

/**
 * 中文字符串截取不出现乱码
 * @param string $str 输入字符串
 * @param integer $start 开始位置
 * @param integer $length 截取长度
 * @param string $charset 编码
 * @param string $is_c 是否是中文
 * @return string
 */
function msubstr($str, $start = 0, $length, $charset = "utf-8", $is_c=true) {
	if (function_exists ( "mb_substr" )) {
		$slice = mb_substr ( $str, $start, $length, $charset );
	} elseif (function_exists ( 'iconv_substr' )) {
		$slice = iconv_substr ( $str, $start, $length, $charset );
	} else {
		$re ['utf-8'] = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
		$re ['gb2312'] = "/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/";
		$re ['gbk'] = "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/";
		$re ['big5'] = "/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/";
		preg_match_all ( $re [$charset], $str, $match );
		$slice = join ( "", array_slice ( $match [0], $start, $length ) );
	}
	if (mb_strlen ( $str ) != mb_strlen ( $slice ) && $is_c) {
		$slice .= "…";
	}
	return $slice;
}
//var_dump(msubstr('中国ass服务过问过1231123efweggr',1,20));

/**
 * 文件下载
 * @param $filepath 文件路径
 * @param $filename 文件名称
 */
function file_down($filepath, $filename = '') {
	if(!$filename) $filename = basename($filepath);
	if(check_is_ie()) $filename = rawurlencode($filename);
	$filetype = get_file_ext($filename);
	$filesize = sprintf("%u", filesize($filepath));
	if(ob_get_length() !== false) @ob_end_clean();
	header('Pragma: public');
	header('Last-Modified: '.gmdate('D, d M Y H:i:s') . ' GMT');
	header('Cache-Control: no-store, no-cache, must-revalidate');
	header('Cache-Control: pre-check=0, post-check=0, max-age=0');
	header('Content-Transfer-Encoding: binary');
	header('Content-Encoding: none');
	header('Content-type: '.$filetype);
	header('Content-Disposition: attachment; filename="'.$filename.'"');
	header('Content-length: '.$filesize);
	readfile($filepath);
	exit;
}
//若有中文文件名会出现问题
//file_down('E:\wyk\google.exe')

/**
 * 数组按照某一个键名分组分组并排序（比如：数据库取出的数据是二维数组时，比如要按照价格把商品分组，就需要数组分组）
 *（具体显示效果可测试查看）
 * @param array $arr 二维数组
 * @param string $key_value 按此键名分组
 * @param string $order 排序方式,默认升序
 * @param string boolean 键值相同时是否保留
 * @return array
 */
function array_group_by($arr, $key_value, $order = 'asc', $multi = true){
	if(empty($arr)){
		return $arr;
	}
	$data = array();
	foreach ($arr as $key=>$value){
		$k = $value[$key_value];
		if($multi){
			$data[$k][] = $value;
		}else{
			$data[$k] = $value;
		}
	}
	if($order == 'desc'){
		krsort($data);	//按键名降序排列
	}else{
		ksort($data);   //按键名升序排列
	}
	//$data = array_values($data);
	return $data;
}

/**
 * 改变 数组分组并排序后的 数组显示格式 （具体显示效果可测试查看）
 * @param array $arr 二维数组
 * @param string $key_value 按此键名分组
 * @param string $order 排序方式,默认升序
 * @param string boolean 键值相同时是否保留
 * @return array
 */
function manage_array_structure($arr, $key_value, $order = 'asc', $multi = true){
	$data = array_group_by($arr, $key_value, $order, $multi);
	if(empty($data)){
		return $data;
	}

	$res = array();

	foreach ($data as $key=>$value){
		$res[] = array(
				$key_value => $key,
				'data' => $value,
		);
	}

	/*$res数组中也可以再添加其他键值，比如接口数据返回数据时可添加：状态码、状态信息、返回数据*/
	/*
	 $res['status'] = '1';
	 $res['msg'] = '登录成功！';
	 $res['return_datas'] = array();
	 foreach ($data as $key=>$value){
		$res['return_datas'][] = array(
		$key_value => $key,
		'data' => $value,
		);
		}
		*/

	//$res = array_values($res);
	return $res;
}
/*
 $arr = array(
 array('name'=>'手机','brand'=>'苹果','price'=>960),
 array('name'=>'手机','brand'=>'诺基亚','price'=>1050),
 array('name'=>'笔记本电脑','brand'=>'lenovo','price'=>4300),
 array('name'=>'剃须刀','brand'=>'飞利浦','price'=>3100),
 array('name'=>'跑步机','brand'=>'三和松石','price'=>4900),
 array('name'=>'手机','brand'=>'三星','price'=>1050),
 array('name'=>'手表','brand'=>'卡西欧','price'=>960),
 array('name'=>'液晶电视','brand'=>'索尼','price'=>6299),
 array('name'=>'激光打印机','brand'=>'惠普','price'=>1200),
 array('name'=>'四大名著','brand'=>'书籍','price'=>960),
 );
 */
//echo '<pre/>';print_r(array_group_by($arr,'price','asc',true));
//echo '<pre/>';print_r(array_group_by($arr,'price','desc',true));
//echo '<pre/>';print_r(manage_array_structure($arr,'price','asc',true));
//echo '<pre/>';print_r(manage_array_structure($arr,'price','desc',true));

/**
 * 页面定时跳转
 * @param string $msgTitle 跳转标题
 * @param string $message 跳转信息
 * @param string $jumpUrl 跳转URL
 * @param string $secs 跳转停留秒数，默认3秒钟
 */
function jump_page($msgTitle,$message,$jumpUrl,$secs = 3){
	$str = '<!DOCTYPE HTML>';
	$str .= '<html>';
	$str .= '<head>';
	$str .= '<meta charset="utf-8">';
	$str .= '<title>页面提示</title>';
	$str .= '<style type="text/css">';
	$str .= '*{margin:0; padding:0}a{color:#369; text-decoration:none;}a:hover{text-decoration:underline}body{height:100%; font:12px/18px Tahoma, Arial,  sans-serif; color:#424242; background:#fff}.message{width:450px; height:120px; margin:16% auto; border:1px solid #99b1c4; background:#ecf7fb}.message h3{height:28px; line-height:28px; background:#2c91c6; text-align:center; color:#fff; font-size:14px}.msg_txt{padding:10px; margin-top:8px}.msg_txt h4{line-height:26px; font-size:14px}.msg_txt h4.red{color:#f30}.msg_txt p{line-height:22px}';
	$str .= '</style>';
	$str .= '</head>';
	$str .= '<body>';
	$str .= '<div class="message">';
	$str .= '<h3>'.$msgTitle.'</h3>';
	$str .= '<div class="msg_txt">';
	$str .= '<h4 class="red">'.$message.'</h4>';
	$str .= "  ";
	$str .= <<<JUMPCONTENT
			系统将在 <span id='remain_sec' style='color:blue;font-weight:bold'>$secs</span> 秒后自动跳转,如果不想等待,直接点击 <a href='{$jumpUrl}'>这里</a> 跳转
			<script type="text/javascript">
		        var i = $secs - 1;
		        var intervalid;
		        intervalid = setInterval("fun()", 1000);
		        function fun() {
		            if (i == 0) {
		                window.location.href = "{$jumpUrl}";
		                clearInterval(intervalid);
		            }
		            document.getElementById("remain_sec").innerHTML = i;
		            i--;
		        }
			</script>
JUMPCONTENT;
	$str .= '</div>';
	$str .= '</div>';
	$str .= '</body>';
	$str .= '</html>';
	echo $str;
}
//jump_page('操作提示','文件修改成功！','https://www.baidu.com',5);

/**
 * 把某一时间段的秒数转为 n天n小时n分钟n秒
 * @param integer $secs 秒数
 * @return string
 */
function formatSec($secs){
	if(!is_numeric($secs)){
		return '';
	}
	$secs = (int)$secs;

	$format_day = '';
	$format_hour = '';
	$format_minute = '';
	$format_second = '';

	if($secs > 60 * 60 * 24){
		$format_day = floor ($secs / (60 * 60 * 24)).'天';
	}

	if($secs > 60 * 60){
		$format_hour = floor (($secs - ($format_day * 60 * 60 * 24)) / (60 * 60)).'小时';
	}

	if($secs > 60){
		$format_minute = floor (($secs - ($format_hour * 60 * 60) - ($format_day * 60 * 60 * 24)) / 60).'分钟';
	}

	if($secs > 0){
		$format_second = (($secs - ($format_day * 60 * 60 * 24)) - ($format_hour * 60 * 60) - ($format_minute * 60)).'秒';
	}
	return $format_day.$format_hour.$format_minute.$format_second;
}
//echo formatSec((86400 * 2) + 7499);

/**
 * 构造批量插入或更新SQL：INSERT INTO 或者 REPLACE INTO
 * @param array $arr_data 二维数组数据
 * @param string $table_name 数据库表名
 * @param boolean $replace  insert into语句 或者 replace into语句
 * @return string 组装好的sql语句
 */
function formatInsertSql($params,$table_name,$replace=false){
	$one_dimension = reset($params);

	if(!is_array($params) || !is_array($one_dimension))
		return false;

		$insert_sql = ($replace ? "REPLACE " : "INSERT ") . "INTO `{$table_name}` (";
		foreach($one_dimension as $field => $v) {
			$insert_sql .= "`$field`,";
		}

		$insert_sql = substr($insert_sql, 0, -1) . ") VALUES (";

		foreach($params as $vals) {
			foreach($vals as $k => $v) {
				$insert_sql .= "'$v',";
			}
			$insert_sql = substr($insert_sql, 0, -1) . "),(";
		}
		$insert_sql = substr($insert_sql, 0, -2);

		return $insert_sql;
}
//测试二维数组数据
/*
 $arr_data = array(
 0 => array(
 'id' => 1,
 'name' => 'jack',
 'age' => 20,
 ),
 1 => array(
 'id' => 2,
 'name' => 'james',
 'age' => 25,
 ),
 );
 $table_name = 'user';
 echo format_sql($arr_data,$table_name);
 */

/**
 * 数组转为SQL条件 （AND条件）
 * @param array $arr
 * @return string
 */
function array_to_sql_condition($arr) {
	$condition = "";
	foreach ($arr as $key=>$val){
		$condition .= " `$key` " .'='. " '$val' " .' AND ';
	}
	//删除条件中最后一个 'AND'
	$condition = substr($condition, 0, strripos($condition, 'AND'));
	return $condition;
}
/*
 $arr = array(
 'name' => '张三',
 'age' => '26',
 'sex' => '男',
 );
 echo array_to_sql_condition($arr);
 */

/**
 * 数组的值转为小写或大写(一维数组或者二维数组)
 * @param array $data_arr
 * @param string $type 转换类型
 * @return array
 */
function arrayToLowerUpper($data_arr,$type="L"){
	foreach($data_arr as $key=>&$val){
		if(is_array($val)){
			foreach ($val as $k=>&$v){
				$v = $type == 'L' ? strtolower($v) : strtoupper($v);
			}
			unset($v);
		}else{
			$val = $type == 'L' ? strtolower($val) : strtoupper($val);
		}
	}
	unset($val);
	return $data_arr;
}

/**
 *  更改数组的键名(用于二维数组)，一般用于导入的excel文件被解析后，更改键名用来入库
 array(
 array (
 'A' => 2
 'B' => 1
 'C' => '北京'
 'D' => 1
 ),
 )
 改成
 array(
 array (
 'id'   => 2
 'pid'  => 1
 'name' => '北京'
 'type' => 1
 ),
 )
 * @param   $source_arr  数组
 * @param   $keys_arr    要更该的键名（数组格式，数目和顺序与原数组一致）
 */
function changeArrayKeys($source_arr,$keys_arr){
	if(empty($keys_arr) || empty($source_arr)){
		return $source_arr;
	}
	$data = array();
	foreach ($source_arr as $key=>$val){
		$data[] = array_combine($keys_arr, $val);
	}
	return $data;
}

/**
 * excel导出 （复杂版，添加了一些样式，数据不宜过多，最好在100条以内）
 * @param string $fileName    导出的文件名
 * @param array  $headFields  导出的字段格式化后名称
 * @param unknown $keys       导出的字段名称
 * @param array  $data        导出的数据
 * @param string $version     导出的版本 2003或者2007
 */
function exportExcelComplex( $fileName, $headFields, $keys, $data, $version = '2003' ){
	//导入PHPExcel类库，因为PHPExcel没有用命名空间，只能inport导入
	import("Org.Util.PHPExcel");
	import("Org.Util.PHPExcel.Writer.Excel5"); 		//用于输出.xls
	import("Org.Util.PHPExcel.Writer.Excel2007"); //用于输出.xlsx
	import("Org.Util.PHPExcel.IOFactory.php");

	import("Org.Util.PHPExcel.Writer.Style.Color");
	import("Org.Util.PHPExcel.Writer.Style.Font");
	import("Org.Util.PHPExcel.Writer.Style.Alignment");
	import("Org.Util.PHPExcel.Writer.Style.Border");

	$objPHPExcel = new \PHPExcel();

	//字段总数
	$totalFields = count($headFields);
	//A字母ASCII码
	$asciiA = ord('A');

	##### 设置excel属性   开始  (可省略) #####
	//创建人
	$objPHPExcel->getProperties()->setCreator("wyk");
	//最后修改人
	$objPHPExcel->getProperties()->setLastModifiedBy("wyk");
	//标题
	$objPHPExcel->getProperties()->setTitle("Office 2003 XLSX Test Document");
	//题目
	$objPHPExcel->getProperties()->setSubject("Office 2003 XLSX Test Document");
	//描述
	$objPHPExcel->getProperties()->setDescription("Test document for Office 2003 XLSX, generated using PHP classes.");
	//关键字
	$objPHPExcel->getProperties()->setKeywords("office 2003 openxml php");
	//种类
	$objPHPExcel->getProperties()->setCategory("Test result file");
	##### 设置excel属性  结束  #####

	##### 设置excel主题   开始 (可省略)#####
	//填入主标题
	$objPHPExcel->getActiveSheet()->setCellValue('A1', $fileName.'详细信息');
	//主标题 合并单元格
	$objPHPExcel->getActiveSheet()->mergeCells('A1:'.chr($asciiA + $totalFields - 1).'1');
	//主标题 高度
	$objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(40);
	//主标题 宽度
	$objPHPExcel->getActiveSheet()->getColumnDimension('A1')->setAutoSize(true);
	//设置align 垂直、水平居中
	$objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
	$objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	//主标题 字体样式、大小、颜色
	$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setSize(14);
	$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->getColor()->setARGB(\PHPExcel_Style_Color::COLOR_BLUE);
	//设置border
	//$objPHPExcel->getActiveSheet()->getStyle('A1')->getBorders()->getTop()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
	//设置border的color
	//$objPHPExcel->getActiveSheet()->getStyle('A1')->getBorders()->getLeft()->getColor()->setARGB('FF993300');

	//填入副标题
	$objPHPExcel->getActiveSheet()->setCellValue('A2', '(导出日期：'.date('Y-m-d').')');
	//副标题 合并单元格
	$objPHPExcel->getActiveSheet()->mergeCells('A2:'.chr($asciiA + $totalFields - 1).'2');
	//副标题 高度
	$objPHPExcel->getActiveSheet()->getRowDimension('2')->setRowHeight(40);
	//副标题 宽度
	$objPHPExcel->getActiveSheet()->getColumnDimension('A2')->setAutoSize(true);
	//设置align 垂直、水平居中
	$objPHPExcel->getActiveSheet()->getStyle('A2')->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
	$objPHPExcel->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	//设置border
	//$objPHPExcel->getActiveSheet()->getStyle('A2')->getBorders()->getTop()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
	//设置border的color
	//$objPHPExcel->getActiveSheet()->getStyle('A2')->getBorders()->getLeft()->getColor()->setARGB('FF993300');
	//主标题 字体样式、大小、颜色
	$objPHPExcel->getActiveSheet()->getStyle('A2')->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('A2')->getFont()->setSize(14);
	$objPHPExcel->getActiveSheet()->getStyle('A2')->getFont()->getColor()->setARGB(\PHPExcel_Style_Color::COLOR_BLUE);
	##### 设置excel主题  结束  #####

	##### 设置excel表头   开始  (样式设置可省略)#####
	for ($i = $asciiA;$i<($asciiA + $totalFields);$i++){
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue(chr($i).'3', $headFields[$i - $asciiA]);
		//宽度
		$objPHPExcel->getActiveSheet()->getColumnDimension(chr($i).'3')->setWidth(15);
		//高度
		$objPHPExcel->getActiveSheet()->getRowDimension(3)->setRowHeight(10);
		//设置align 垂直、水平居中
		$objPHPExcel->getActiveSheet()->getStyle(chr($i).'3')->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$objPHPExcel->getActiveSheet()->getStyle(chr($i).'3')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		//设置border
		// $objPHPExcel->getActiveSheet()->getStyle(chr($i).'3')->getBorders()->getTop()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
		// $objPHPExcel->getActiveSheet()->getStyle(chr($i).'3')->getBorders()->getBottom()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
		// $objPHPExcel->getActiveSheet()->getStyle(chr($i).'3')->getBorders()->getLeft()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
		// $objPHPExcel->getActiveSheet()->getStyle(chr($i).'3')->getBorders()->getRight()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
		//设置border的color
		// $objPHPExcel->getActiveSheet()->getStyle(chr($i).'3')->getBorders()->getLeft()->getColor()->setARGB('FF993300');
	}
	##### 设置excel表头  结束  #####

	##### 插入excel内容   开始 (样式设置可省略) #####
	$len = count($data);
	for ($i = 0; $i < $len; $i++) { //行写入
		for ($j = $asciiA; $j < ($asciiA + $totalFields); $j++) { //行写入
			$objPHPExcel->getActiveSheet(0)->setCellValue(chr($j) . ($i + 4), $data[$i][$keys[$j - $asciiA]]);
			//宽度
			$objPHPExcel->getActiveSheet()->getColumnDimension(chr($j))->setWidth(15);
			//高度
			$objPHPExcel->getActiveSheet()->getRowDimension($i + 3)->setRowHeight(20);
			//设置align 垂直、水平居中
			$objPHPExcel->getActiveSheet()->getStyle(chr($j).($i + 4))->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$objPHPExcel->getActiveSheet()->getStyle(chr($j).($i + 4))->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			//设置border
			// $objPHPExcel->getActiveSheet()->getStyle(chr($j).($i + 4))->getBorders()->getTop()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
			// $objPHPExcel->getActiveSheet()->getStyle(chr($j).($i + 4))->getBorders()->getBottom()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
			// $objPHPExcel->getActiveSheet()->getStyle(chr($j).($i + 4))->getBorders()->getLeft()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
			// $objPHPExcel->getActiveSheet()->getStyle(chr($j).($i + 4))->getBorders()->getRight()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
			//设置border的color
			// $objPHPExcel->getActiveSheet()->getStyle(chr($j).($i + 4))->getBorders()->getLeft()->getColor()->setARGB('FF993300');
		}
	}
	##### 插入excel内容  结束 #####

	##### 导出下载   开始 #####
	if($version == '2003'){
		//保存为excel2003格式
		/*  header('Content-Type: application/vnd.ms-excel');
		 header('Content-Disposition: attachment;filename="' . $fileName . '.xls"');
		 header('Cache-Control: max-age=0');
		 $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		 $objWriter->save('php://output');
		 exit;*/

		$objWriter = new \PHPExcel_Writer_Excel5($objPHPExcel);
		header("Pragma: public");
		header("Expires: 0");
		header("Cache-Control:must-revalidate, post-check=0, pre-check=0");
		header("Content-Type:application/force-download");
		header("Content-Type:application/vnd.ms-execl");
		header("Content-Type:application/octet-stream");
		header("Content-Type:application/download");

		$encoded_filename = urlencode($fileName);
		$ua = $_SERVER["HTTP_USER_AGENT"];
		if (preg_match("/MSIE/", $ua)) {
			header('Content-Disposition: attachment; filename="' . $encoded_filename . '.xls"');
		} else if (preg_match("/Firefox/", $ua)) {
			header('Content-Disposition: attachment; filename*="utf8\'\'' . $fileName . '.xls"');
		} else {
			header('Content-Disposition: attachment; filename="' . $fileName . '.xls"');
		}

		header("Content-Transfer-Encoding:binary");
		$objWriter->save('php://output');
		exit;
	}elseif($version == '2007'){
		//保存为excel2007格式
		/* header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		 header('Content-Disposition: attachment;filename="' . $fileName . '.xlsx"');
		 header('Cache-Control: max-age=0');
		 $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		 $objWriter->save('php://output');
		 exit; */

		$objWriter = new \PHPExcel_Writer_Excel2007($objPHPExcel);
		header("Pragma: public");
		header("Expires: 0");
		header("Cache-Control:must-revalidate, post-check=0, pre-check=0");
		header("Content-Type:application/force-download");
		header("Content-Type:application/vnd.ms-execl");
		header("Content-Type:application/octet-stream");
		header("Content-Type:application/download");

		$encoded_filename = urlencode($fileName);
		$ua = $_SERVER["HTTP_USER_AGENT"];
		if (preg_match("/MSIE/", $ua)) {
			header('Content-Disposition: attachment; filename="' . $encoded_filename . '.xlsx"');
		} else if (preg_match("/Firefox/", $ua)) {
			header('Content-Disposition: attachment; filename*="utf8\'\'' . $fileName . '.xlsx"');
		} else {
			header('Content-Disposition: attachment; filename="' . $fileName . '.xlsx"');
		}

		header("Content-Transfer-Encoding:binary");
		$objWriter->save('php://output');
		exit;
	}
	##### 导出下载  结束  #####
}

/**
 * excel导出 （精简版，去掉不必要的样式，数据最好在500以内）
 * @param string $fileName    导出的文件名
 * @param array  $headFields  导出的字段格式化后名称
 * @param unknown $keys       导出的字段名称
 * @param array  $data        导出的数据
 * @param string $version     导出的版本 2003或者2007
 */
function exportExcelSimple( $fileName, $headFields, $keys,$data, $version = '2003'){
	//导入PHPExcel类库，因为PHPExcel没有用命名空间，使用inport导入
	import("Org.Util.PHPExcel");
	//用于输出.xls文件
	import("Org.Util.PHPExcel.Writer.Excel5");
	//用于输出.xlsx
	import("Org.Util.PHPExcel.Writer.Excel2007");
	import("Org.Util.PHPExcel.IOFactory.php");

	$objPHPExcel = new \PHPExcel();

	//字段总数
	$totalFields = count($headFields);
	//A字母ASCII码
	$asciiA = ord('A');

	##### 设置excel主题   开始 (可省略，注释掉)#####
	/* 	//填入主标题
	 $objPHPExcel->getActiveSheet()->setCellValue('A1', $fileName.'详细信息');
	 //主标题 合并单元格
	 $objPHPExcel->getActiveSheet()->mergeCells('A1:'.chr($asciiA + $totalFields - 1).'1');

	 //填入副标题
	 $objPHPExcel->getActiveSheet()->setCellValue('A2', '(导出日期：'.date('Y-m-d').')');
	 //副标题 合并单元格
	 $objPHPExcel->getActiveSheet()->mergeCells('A2:'.chr($asciiA + $totalFields - 1).'2'); */
	##### 设置excel主题  结束  #####

	##### 设置excel表头   开始 #####
	for ($i = $asciiA;$i<($asciiA + $totalFields);$i++){
		//如果没有主题
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue(chr($i).'1', $headFields[$i - $asciiA]);

		//如果有主题
		//$objPHPExcel->setActiveSheetIndex(0)->setCellValue(chr($i).'3', $headFields[$i - $asciiA]);
	}
	##### 设置excel表头  结束  #####

	##### 插入excel内容   开始 #####
	$len = count($data);
	for ($i = 0; $i < $len; $i++) { //行写入
		for ($j = $asciiA; $j < ($asciiA + $totalFields); $j++) { //行写入
			//如果没有主题
			$objPHPExcel->getActiveSheet(0)->setCellValue(chr($j) . ($i + 2), $data[$i][$keys[$j - $asciiA]]);
				
			//如果有主题
			//$objPHPExcel->getActiveSheet(0)->setCellValue(chr($j) . ($i + 4), $data[$i][$keys[$j - $asciiA]]);
		}
	}
	##### 插入excel内容  结束 #####

	##### 导出下载   开始 #####
	if($version == '2003'){
		//保存为excel2003格式
		/*  header('Content-Type: application/vnd.ms-excel');
		 header('Content-Disposition: attachment;filename="' . $fileName . '.xls"');
		 header('Cache-Control: max-age=0');
		 $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		 $objWriter->save('php://output');
		 exit;*/

		$objWriter = new \PHPExcel_Writer_Excel5($objPHPExcel);
		header("Pragma: public");
		header("Expires: 0");
		header("Cache-Control:must-revalidate, post-check=0, pre-check=0");
		header("Content-Type:application/force-download");
		header("Content-Type:application/vnd.ms-execl");
		header("Content-Type:application/octet-stream");
		header("Content-Type:application/download");

		$encoded_filename = urlencode($fileName);
		$ua = $_SERVER["HTTP_USER_AGENT"];
		if (preg_match("/MSIE/", $ua)) {
			header('Content-Disposition: attachment; filename="' . $encoded_filename . '.xls"');
		} else if (preg_match("/Firefox/", $ua)) {
			header('Content-Disposition: attachment; filename*="utf8\'\'' . $fileName . '.xls"');
		} else {
			header('Content-Disposition: attachment; filename="' . $fileName . '.xls"');
		}

		header("Content-Transfer-Encoding:binary");
		$objWriter->save('php://output');
		exit;
	}elseif($version == '2007'){
		//保存为excel2007格式
		/* header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		 header('Content-Disposition: attachment;filename="' . $fileName . '.xlsx"');
		 header('Cache-Control: max-age=0');
		 $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		 $objWriter->save('php://output');
		 exit; */

		$objWriter = new \PHPExcel_Writer_Excel2007($objPHPExcel);
		header("Pragma: public");
		header("Expires: 0");
		header("Cache-Control:must-revalidate, post-check=0, pre-check=0");
		header("Content-Type:application/force-download");
		header("Content-Type:application/vnd.ms-execl");
		header("Content-Type:application/octet-stream");
		header("Content-Type:application/download");

		$encoded_filename = urlencode($fileName);
		$ua = $_SERVER["HTTP_USER_AGENT"];
		if (preg_match("/MSIE/", $ua)) {
			header('Content-Disposition: attachment; filename="' . $encoded_filename . '.xlsx"');
		} else if (preg_match("/Firefox/", $ua)) {
			header('Content-Disposition: attachment; filename*="utf8\'\'' . $fileName . '.xlsx"');
		} else {
			header('Content-Disposition: attachment; filename="' . $fileName . '.xlsx"');
		}

		header("Content-Transfer-Encoding:binary");
		$objWriter->save('php://output');
		exit;
	}
	##### 导出下载  结束  #####
}

/**
 * 导入excel文件，解析成数组返回
 * @param string  $fileName  excel文件
 * @return array
 */
function importExcel($excelFileName ='', $sheet=0){
	//导入PHPExcel类库，因为PHPExcel没有用命名空间，使用inport导入
	import("Org.Util.PHPExcel");
	//用于读取.xls文件
	import("Org.Util.PHPExcel.Reader.Excel5");
	//用于读取.xlsx
	import("Org.Util.PHPExcel.Reader.Excel2007");

	if(empty($excelFileName) or !file_exists($excelFileName)){die('file not exists');}
	$PHPReader = new PHPExcel_Reader_Excel2007();        //建立reader对象
	if(!$PHPReader->canRead($excelFileName)){
		$PHPReader = new PHPExcel_Reader_Excel5();
		if(!$PHPReader->canRead($excelFileName)){
			echo 'no Excel';
			return ;
		}
	}
	$PHPExcel = $PHPReader->load($excelFileName);        //建立excel对象
	$currentSheet = $PHPExcel->getSheet($sheet);         //读取excel文件中的指定工作表
	$allColumn = $currentSheet->getHighestColumn();      //取得最大的列号
	$allRow = $currentSheet->getHighestRow();            //取得一共有多少行
	$data = array();
	for($rowIndex=1;$rowIndex<=$allRow;$rowIndex++){        //循环读取每个单元格的内容。注意行从1开始，列从A开始
		for($colIndex='A';$colIndex<=$allColumn;$colIndex++){
			$addr = $colIndex.$rowIndex;
			$cell = $currentSheet->getCell($addr)->getValue();
			if($cell instanceof PHPExcel_RichText){ //富文本转换字符串
				$cell = $cell->__toString();
			}
			$data[$rowIndex][$colIndex] = $cell;
		}
	}
	unlink($excelFileName);

	return $data;
}

/**
 * 计算百分比
 */
function percent($num,$total_num){
	if(abs($num) <= 0 || abs($total_num) <= 0){
		return '0.00';
	}
	return bcadd(0, round((($num / $total_num) * 100) ,2),2);
}

if(!function_exists('array_column')) {
	function array_column( $input, $columnKey, $indexKey = null)
	{
		$result = array();
		if (null === $indexKey) {
			if (null === $columnKey) {
				$result = array_values($input);
			} else {
				foreach ($input as $row) {
					$result[] = $row[$columnKey];
				}
			}
		} else {
			if (null === $columnKey) {
				foreach ($input as $row) {
					$result[$row[$indexKey]] = $row;
				}
			} else {
				foreach ($input as $row) {
					$result[$row[$indexKey]] = $row[$columnKey];
				}
			}
		}
		return $result;
	}
}

/**
 * 数组格式转为IN条件
 * @param  $arr  （一维数组）
 * @return string
 */
function arrayToIn($arr = array()){
	if(!$arr){
		return "''";
	}
	$str = '';
	foreach ($arr as $key=>$val){
		$str .= "'{$val}',";
	}
	return rtrim(trim($str),',');;
}

/**
 * 计算各个时刻数据，数组按时刻倒序排列，时刻名为键
 * @param array $scope_arr 时刻数组
 * @return array
 */
function scopeNum($scope_arr = array()){
	if(!$scope_arr){
		return array();
	}
	$keys_arr = array_keys($scope_arr);
	$min_key = min($keys_arr);
	foreach ($scope_arr as $key=> &$val){
		if($key != $min_key){
			$val  = $val - $scope_arr[next($keys_arr)];
		}
	}
	unset($val);
	return $scope_arr;
}

/**
 * 输入开始日期和结束日期，返回开始日期和结束日期间的所有日期
 * @param array $date_list
 * @param string $start_date
 * @param string $end_date
 * @return array
 *
 */
function getFormatDiffDate($start_date,$end_date,$date_list = array()){
	$diff_days = ((strtotime($end_date) - strtotime($start_date)) / 86400) + 1;
	$date_len = count($date_list);
	if($date_len != $diff_days){
		$date_list[0] = $start_date;
		$date_list[$diff_days - 1] = $end_date;
		$d_up = $diff_days - 2;
		for($d=1;$d<=$d_up;$d++){
			$date_list[$d] = date('Y-m-d',strtotime('+'.$d.' days',strtotime($start_date)));
		}
	}
	sort($date_list);
	return array_combine($date_list, array_pad(array(), $diff_days, 0));;
}

/**
 * 获取起止日期之间的天数
 * @param string $start_date 开始日期
 * @param string $end_date   结束日期
 * @return integer           开始日期到结束日期之间的天数
 */
function getDiffDays($start_date,$end_date){
	return ((strtotime($end_date) - strtotime($start_date)) / 86400) + 1;
}

/**
 * 秒数转为分秒格式(例如：30'25")
 * @param integer $timestamp
 * @return string
 */
function getTimeToMS($timestamp){
	$timestamp = intval($timestamp);
	if($timestamp < 60){
		return $timestamp.'"';
	}
	$m = floor($timestamp / 60);
	$s = $timestamp % 60;
	return $m."'".$s.'"';
}

/**
 * 分秒格式(例如：1'25") 转为秒数 （85）
 * @param integer $timestamp
 * @return integer
 */
function getMSToTime($str){
	$m = $s =0;
	if(strstr($str,"'")){
		$arr = explode("'", $str);
		$m = $arr[0];
		$arr2 = explode('"', $arr[1]);
		$s = $arr2[0];
	}else{
		$arr = explode('"', $str);
		$s = $arr[0];
	}
	return $m * 60 + $s;
}

/*
 * 是不是闰年
 *
 *  能被4整除但不能被100整除，或能被400整除的年份即为闰年
 *
 *  1. 如果是世纪年： 例如 1800 1900 2000， 被400整除即为闰年
 *  2. 如果非世纪年： 例如 1996 1998 1999， 能被4整除即为闰年
 */
function leapYear( $year = null ) {
    $year = !$year ? date('Y') : $year;
    if( ( $year % 100 != 0 && $year % 4 == 0 ) || ( $year % 400 == 0 )  ){
        return true;
    }
    return false;
}

function quickSort($arr) {
	//先判断是否需要继续进行
	$length = count($arr);
	if($length <= 1) {
		return $arr;
	}
	//如果没有返回，说明数组内的元素个数 多余1个，需要排序
	//选择一个标尺
	//选择第一个元素
	$base_num = $arr[0];
	//遍历 除了标尺外的所有元素，按照大小关系放入两个数组内
	//初始化两个数组
	$left_array = array();//小于标尺的
	$right_array = array();//大于标尺的
	for($i=1; $i<$length; $i++) {
		if($base_num < $arr[$i]) {
			//放入左边数组
			$left_array[] = $arr[$i];
		} else {
			//放入右边
			$right_array[] = $arr[$i];
		}
	}
	//再分别对 左边 和 右边的数组进行相同的排序处理方式
	//递归调用这个函数,并记录结果
	$left_array = quickSort($left_array);
	$right_array = quickSort($right_array);
	//合并左边 标尺 右边
	return array_merge($left_array, array($base_num), $right_array);
}
//var_dump(quick_sort($arr));





















