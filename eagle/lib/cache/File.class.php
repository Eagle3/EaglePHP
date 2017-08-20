<?php
namespace lib\cache;
use lib\Cache;
class File extends Cache {
    public function __construct($setOptions){
        if(!$this->setOptions){
            $this->setOptions = $setOptions;
        }
        //初始化： 创建应用缓存目录
        if (!is_dir($this->setOptions['CACHE_PATH'])) {
            mkdir($this->setOptions['CACHE_PATH'],0777,true);
        }
    }
    
    public function get($key){
        $path = $this->setOptions['CACHE_PATH'];
        $prefix = $this->setOptions['CACHE_PREFIX'];
        $postfix = $this->setOptions['CACHE_POSTFIX'];
        $fileName = $path.$prefix.'_'.$key.$postfix;
        
        if(file_exists($fileName)){
            $data = unserialize(file_get_contents($fileName));
            if($data['expire'] < time()){
                return false;
            }else{
                return $data['data'];
            }
        }
        return false;
    }
    
    public function set($key,$value,$expire = null){
        $path = $this->setOptions['CACHE_PATH'];
        $prefix = $this->setOptions['CACHE_PREFIX'];
        $postfix = $this->setOptions['CACHE_POSTFIX'];
        $fileName = $path.$prefix.'_'.$key.$postfix;
        $expire = $expire ? (int)$expire : $this->setOptions['CACHE_TIME'];
        $data = serialize(array('data' => $value,'expire' => time() + $expire));
        if(file_put_contents($fileName,$data)){
            return true;
        }
        return false;
    }
    
    public function rm($key){
        $path = $this->setOptions['CACHE_PATH'];
        $prefix = $this->setOptions['CACHE_PREFIX'];
        $postfix = $this->setOptions['CACHE_POSTFIX'];
        $key = iconv('utf-8', 'gbk', $key); //删除中文时会出错，文件名最好不要有中文
        $file = $path.$prefix.'_'.$key.$postfix;
        if ($file != '.' && $file != '..' && is_file($file) && file_exists($file)) {
            return unlink($file);
        }
        return false;
    }
    
    public function clear(){
        $path = $this->setOptions['CACHE_PATH'];
        deleteDir($path);
    }
      
}