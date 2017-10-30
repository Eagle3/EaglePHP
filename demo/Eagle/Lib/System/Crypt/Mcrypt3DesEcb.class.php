<?php
namespace Lib\System\Crypt;

/**
 * php对称加密解密算法之  3DES ECB PKCS7（Java默认PKCS7模式填充） 加密解密
 */

class Mcrypt3DesEcb {
	private $iv = 0;

	public function enData($str,$key){
		//待加密的字符串，用PKCS7填充
		$str = $this->paddingPKCS7($str);
		//初始化向量来增加安全性
		$td = mcrypt_module_open( MCRYPT_3DES, '', MCRYPT_MODE_ECB, '' );
		//$iv = mcrypt_create_iv( mcrypt_enc_get_iv_size( $td ), MCRYPT_RAND );
		$iv = $this->iv;
		@mcrypt_generic_init( $td, $key, $iv );
		//开始加密
		$result = mcrypt_generic( $td, $str );
		mcrypt_generic_deinit($td);
		mcrypt_module_close($td);
		return base64_encode($result);
	}
	
	public function deData($str,$key){
		$td = mcrypt_module_open(MCRYPT_3DES, '', MCRYPT_MODE_ECB, '');  
        $iv = $this->iv; 
        @mcrypt_generic_init($td, $key, $iv);  
        $ret = trim(mdecrypt_generic($td, base64_decode($str)));  
        $ret = $this->unPaddingPKCS7($ret);
        mcrypt_generic_deinit($td);  
        mcrypt_module_close($td);  
        return $ret;  
	}
	
	private function paddingPKCS7($input){
		$srcdata = $input;
		$block_size = mcrypt_get_block_size(MCRYPT_3DES, 'ecb');
		$padding_char = $block_size - (strlen($input) % $block_size);
		$srcdata .= str_repeat(chr($padding_char),$padding_char);
		return $srcdata;
	}
	
	private function unPaddingPKCS7($text)  {
        $pad = ord($text{strlen($text) - 1});  
        if ($pad > strlen($text)) {  
            return false;  
        }  
        if (strspn($text, chr($pad), strlen($text) - $pad) != $pad) {  
            return false;  
        }  
        return substr($text, 0, -1 * $pad);   
    }  

}
