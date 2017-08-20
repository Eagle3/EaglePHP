<?php
/**
 * 多文件上传，也支持单文件上传

	//调用示例
	include 'Mulupload.class.php';
	//设置上传参数
	$config = array(
			'max_number' => 5,//最多上传文件个数
			'max_size' => 0, //上传大小限制，单位：字节。0，无限制
			'ext' => array('gif','png','jpg','jpeg','doc','docx','txt','xls','ppt'),//允许上传的类型
			'save_path' => './upload/',//上传文件的保存路径
	);
	$upload = new Mulupload($config);
	
	//上传文件数组
	$file = $_FILES['pic'];
	var_dump($upload->doUpload($file),$upload->getErrMsg(),$upload->getDbSavePath());

 */

namespace lib\system;

class Mulupload{
	//上传配置
	private $config = array(
		'max_number' => 5,//最多上传文件个数
		'max_size' => 0, //单个文件上传大小限制，单位：字节。0，无限制
		'ext' => array('gif','png','jpg','jpeg'),//允许上传的类型
		'save_path' => './upload/',//上传文件的保存路径（ 或者写成 upload/ ）,不能写成/upload/
	);
	//错误信息
	private $error_msg = '';
	//唯一文件名
	private $unique_name = '';
	//拼接后的上传路径
	private $join_path = '';
	//上传后成功后文件路径，(包含路径和文件名，用于保存数据库)
	private $db_save_path = array();
	
	function __construct($config = array()){
		///获取配置 
		$this->config = array_merge($this->config, $config);
	}
	
	/**
	 * 使用 $this->name 获取配置
	 * @param  string $name 配置名称
	 * @return multitype    配置值
	 */
	public function __get($name) {
		return $this->config[$name];
	}
	
	/**
	 * 返回错误信息
	 * @return string
	 */
	public function getErrMsg(){
		return $this->error_msg;
	}
	
	/**
	 * 返回上传成功后文件路径，(包含路径和文件名，用于保存在数据库)
	 * @return string
	 */
	public function getDbSavePath(){
		return $this->db_save_path;
	}
	
	/**
	 * 执行上传操作
	 * @param  array $file 文件数组
	 * @return boolean
	 */
	public function doUpload( $file = array() ){
		if(!$file){
			$this->error_msg .= '上传参数为空';
			return false;
		}else{
			if(!$file['name'] || !$file['type'] || !$file['tmp_name'] || !$file['size']){
				$this->error_msg .= '上传参数错误';
				return false;
			}
			$total = count($file['name']);
			
			if($total > $this->max_number){
				$this->error_msg .= '最多只能上传'.$this->max_number.'个文件！';
				return false;
			}
			
			$files = array();
			for ($i=0;$i<$total;$i++){
				$files[$i]['name'] = $file['name'][$i];
				$files[$i]['type'] = $file['type'][$i];
				$files[$i]['tmp_name'] = $file['tmp_name'][$i];
				$files[$i]['error'] = $file['error'][$i];
				$files[$i]['size'] = $file['size'][$i];
			}
			unset($file);
		}
		
		//多个文件中有一个错误，返回false
		foreach ($files as $key=>$file){
			if(!$this->checkError($file['error'])){
				return false;
			}
			
			if(!$this->checkExt($file['name'])){
				$this->error_msg .= '上传文件类型错误';
				return false;
			}
			
			if(!$this->checkSize($file['size'])){
				$this->error_msg .= '上传文件大小超过'.$this->max_size / 1024 .'kb';
				return false;
			}
		}
		
		//处理多个文件上传，失败，返回上传失败的文件
		foreach ($files as $key=>$file){
			$this->getUniqueName($file);
			if(!$this->move($file)){
				return false;
			}
		}
		
		return true;
	}
	
	/**
	 * 检查上传错误代码
	 * @param  integer $error_number  ($file['error'])
	 * @return boolean
	 */
	private function checkError($error_number){
		$return = false;
		switch ($error_number) {
			case 0:
				//没有错误发生，文件上传成功
				$return = true;
				break;
			case 1:
				$this->error_msg .= '上传的文件超过了 php.ini 中 upload_max_filesize 选项限制的值！';
				break;
			case 2:
				$this->error_msg .= '上传文件的大小超过了 HTML 表单中 MAX_FILE_SIZE 选项指定的值！';
				break;
			case 3:
				$this->error_msg .= '文件只有部分被上传！';
				break;
			case 4:
				$this->error_msg .= '没有文件被上传！';
				break;
			case 6:
				$this->error_msg .= '找不到临时文件夹！';
				break;
			case 7:
				$this->error_msg .= '文件写入失败！';
				break;
			default:
				$this->error_msg .= '未知上传错误！';
		}
		
		return $return;
	}
	
	/**
	 * 检查上传文件类型
	 * @param  string  $ext 文件类型($file['type'])  
	 * @return boolean
	 */
	private function checkExt($ext){
		$ext_arr = explode('.', $ext);
		$ext_config_arr = $this->ext;
		return empty($ext_config_arr) ? true : in_array(strtolower($ext_arr[1]), $ext_config_arr);
	}
	
	/**
	 * 检查上传文件大小
	 * @param  integer $size 文件大小($file['size'])
	 * @return boolean
	 */
	private function checkSize($size){
		$max_size_config = $this->max_size;
		return empty($max_size_config) ? true : $size <= $max_size_config;
	}
	
	/**
	 * 获得文件唯一文件名（重新命名）
	 * @param array $file  上传文件数组
	 */
	private function getUniqueName($file){
		$arr = explode('.',$file['name']);
		$this->unique_name = md5(uniqid()).'.'.$arr[count($arr)-1];
	}
	
	/**
	 * 从临时文件夹移动到指定上传的目录
	 * @param  array 	$file  上传文件数组
	 * @return boolean
	 */
	private function move($file){
		//不存在上传的目录则创建
		if(!file_exists($this->save_path.date('Ymd').'/')){
			if(!mkdir($this->save_path.date('Ymd').'/',0777,true)){
				$this->error_msg .= '创建上传目录失败！';
				return false;
			}
			
		}
		$this->join_path = $this->save_path.date('Ymd').'/';
		if(move_uploaded_file($file['tmp_name'], $this->join_path.$this->unique_name)){
			if(strstr($this->save_path, './')){
				$save_path = str_replace('./', '/', $this->save_path);
			}else{
				$save_path = '/'.$this->save_path;
			}
			array_push($this->db_save_path, $save_path.$this->unique_name);
			return true;
		}else{
			$this->error_msg .= '文件:'.$file['name'].'上传失败！';
			return false;
		}
		
	}
	
}