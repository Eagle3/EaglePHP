<?php
/**
 * 文件上传类
	
	//调用示例
	include 'Upload.class.php';
	//设置上传参数
	$config = array(
			'max_size' => 0, //上传大小限制，单位：字节。0，无限制
			'ext' => array('gif','png','jpg','jpeg'),//允许上传的类型
			'save_path' => './upload/pic/',//上传文件的保存路径
	);
	$upload = new Upload($config);
	
	//上传文件数组
	$file = $_FILES['pic'];
	
	echo $upload->doUpload($file);
	echo $upload->getErrMsg();
	echo $upload->getDbSavePath();

 */

namespace Lib\System;

class Upload{
	//上传配置
	private $config = array(
		'max_size' => 0, //上传大小限制，单位：字节。0，无限制
		'ext' => array('gif','png','jpg','jpeg','mp3','silk'),//允许上传的类型
		'save_path' => './upload/',//上传文件的保存路径
	);
	//错误信息
	private $error_msg = '';
	//唯一文件名
	private $unique_name = '';
	//上传后成功后文件路径，(包含路径和文件名，用于保存数据库)
	private $db_save_path = '';
	
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
		}
		
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
		
		$this->getUniqueName($file);
		
		if(!$this->move($file)){
			return false;
		}
		return $this->getDbSavePath();
		//return true;
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
		$ext_config_arr = $this->config['ext'];
		return empty($ext_config_arr) ? true : in_array(strtolower($ext_arr[count($ext_arr) - 1]), $ext_config_arr);
	}
	
	/**
	 * 检查上传文件大小
	 * @param  integer $size 文件大小($file['size'])
	 * @return boolean
	 */
	private function checkSize($size){
	    return empty($max_size_config) ? true : $size <= $this->config['max_size'];
	}
	
	/**
	 * 获得文件唯一文件名（重新命名）
	 * @param array $file  上传文件数组
	 */
	private function getUniqueName($file){
		$arr = explode('.',$file['name']);
		$this->unique_name = md5(uniqid('',true)).'.'.$arr[count($arr)-1];
	}
	
	/**
	 * 从临时文件夹移动到指定上传的目录
	 * @param  array 	$file  上传文件数组
	 * @return boolean
	 */
	private function move($file){
		//不存在上传的目录则创建
	    if(!file_exists($this->config['save_path'].date('Ymd').'/')){
	        if(!mkdir($this->config['save_path'].date('Ymd').'/',0777,true)){
				$this->error_msg .= '创建上传目录失败！<br>';
				return false;
			}
		}
		//存在则直接使用
		$this->config['save_path'] = $this->config['save_path'].date('Ymd').'/';
		if(move_uploaded_file($file['tmp_name'], $this->config['save_path'].$this->unique_name)){
		    if(strstr($this->config['save_path'], './')){
		        $this->config['save_path']= str_replace('./', '/', $this->config['save_path']);
			}else{
			    $this->config['save_path'] = '/'.$this->config['save_path'];
			}
			$this->db_save_path = $this->config['save_path'].$this->unique_name;
			return true;
		}else{
			$this->error_msg .= '文件上传失败！<br>';
			return false;
		}
		
	}
	
}