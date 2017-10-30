<?php
namespace Lib\System\Crypt;

/**
 * RSA非对称加密、解密、  签名、验签
 * 注意： 明文最多117字节，超过117字节需要分段加密处理，解密也需分段处理。分段，即把数据分成N个117字节的数据分别加密、解密需如此。utf-8编码，一个汉字占3个字节
 * 
 * 									调用示例
		//使用密钥文件签名、验签
    	 $data = '签名串';
    	 $rsa = new Rsa('./private_file/rsa_public_key.pem','./private_file/rsa_private_key.pem');
    	 $sign = $rsa->privateKeySign($data);
    	 $check = $rsa->publicKeyVerify($data,$sign);
    	 pr($sign,$check);
    
    	//使用密钥字符串文件签名、验签
    	 $data = '签名串';
    	 $rsa_public_key = 'MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQDcITVQ31bEL1qY0nEPloFjm/f2vOn1GuBzMYMdYi6S0FvVX/XFbLAklzepz2c0bHSVszT+8WNlU42xQoFuJ0rTe/oDtzxhTDbHgjUPt7fVrKHaPBSJnQIIRRU6YapXq0bn++SuU4QMlSTxb/onzSnM1t6Y2VskQnPMjna63VXehwIDAQAB';
    	 $rsa_private_key = 'MIICXgIBAAKBgQDcITVQ31bEL1qY0nEPloFjm/f2vOn1GuBzMYMdYi6S0FvVX/XFbLAklzepz2c0bHSVszT+8WNlU42xQoFuJ0rTe/oDtzxhTDbHgjUPt7fVrKHaPBSJnQIIRRU6YapXq0bn++SuU4QMlSTxb/onzSnM1t6Y2VskQnPMjna63VXehwIDAQABAoGBAJnKzbAJyVnZZ6dbZ0gns5A/GJeW1rG6rFNupRbzUGycC3zgxRnAXLPDvkzyLT2QBEfOY1k2lmXlYRoVx82IwBoCZ1TGgHJEfIjZrITpZVB+Yv8Jifp5fbScbemYO/gYyEZK3yjKHYzDhOdkctDf+ilokAIBA2ByGnf6G+gfHmfhAkEA/6TVQ7TKpnw96QPV5WNbJtMh5BeGKy3hBoEOi08bWR61iYSWHeb/NNszu+hrpa6MlOYq10RO7CdgSeIaIkLBdwJBANxvtdL39badasjfBasRZxhjBZZijx55uk57iWR6qr8l4cWaIGYb3WSSnERAZvJpE+etZNawW2MEzlUqGWXbj3ECQQC0kuXhUU7jklbYxNDNmwTDw9bomoU28s1EHtz7IgGbTcnFPVYcARK7byp3zJBdE5JRitMwAxwMSzQEfCUhli25AkAGaTFOi2uX/ggHA4V0rjLjYK3e68rhxgSHF8ytIWwp1v4z8wGSNqk/rYvh6EWWMzwi9sYCAGsH/DHMBEds0O/hAkEA9LG87JVuU+AV++B+GAGPpHEMIDrQH9QfwQ0H7PvobcM3pBb0L+wEl+mDB+keHjkGcLnckUQoDUoVQzxWsnoPvQ==';
    	 $rsa = new Rsa($rsa_public_key,$rsa_private_key,false,false);
    	 $sign = $rsa->privateKeySign($data);
    	 $check = $rsa->publicKeyVerify($data,$sign);
    	 pr($sign,$check); 
    	 
    	//使用私钥加密、公钥解密
    	 $data = '签名串';
    	 $rsa = new Rsa('./private_file/rsa_public_key.pem','./private_file/rsa_private_key.pem');
    	 $enData = $rsa->privateKeyEn($data);
    	 $deData = $rsa->publicKeyDe($enData);
    	 pr($enData,$deData);
    
    	//使用公钥加密、私钥解密
    	 $data = '签名串';
    	 $rsa = new Rsa('./private_file/rsa_public_key.pem','./private_file/rsa_private_key.pem');
    	 $enData = $rsa->publicKeyEn($data);
    	 $deData = $rsa->privateKeyDe($enData);
    	 pr($enData,$deData);
 * 
 */
class Rsa {
	//传入的公钥
	private $rsa_public_key = '';
	//传入的公钥
	private $rsa_private_key = '';
	//传入的公钥 true:文件   false:字符串
	private $rsa_public_key_is_file = true;
	//传入的公钥 true:文件   false:字符串
	private $rsa_private_key_is_file = true;
	
	public function __construct($rsa_public_key, $rsa_private_key, $rsa_public_key_is_file = true, $rsa_private_key_is_file = true){
		if(!$rsa_public_key_is_file){
			$rsa_public_key = "-----BEGIN PUBLIC KEY-----\n" .
					wordwrap($rsa_public_key, 64, "\n", true) .
					"\n-----END PUBLIC KEY-----";
			$this->rsa_public_key_is_file = false;
		}
		if(!$rsa_private_key_is_file){
			$rsa_private_key = "-----BEGIN RSA PRIVATE KEY-----\n" .
				wordwrap($rsa_private_key, 64, "\n", true) .
				"\n-----END RSA PRIVATE KEY-----";
			$this->rsa_private_key_is_file = false;
		}
		$this->rsa_public_key = $rsa_public_key;
		$this->rsa_private_key = $rsa_private_key;
	}
	
	/**
	 * 私钥生成签名
	 * @param string $data 原始数据
	 */
	public function privateKeySign($data, $signType = OPENSSL_ALGO_SHA1){
		if($this->rsa_private_key_is_file){
			$priKey = file_get_contents($this->rsa_private_key);
			$res = openssl_get_privatekey($priKey);
			openssl_sign($data, $sign, $res, $signType);
			openssl_free_key($res);
			return base64_encode($sign);
		}else{
			openssl_sign($data, $sign, $this->rsa_private_key);
			return base64_encode($sign);
		}
	}
	
	/**
	 * 公钥验签
	 * @param string $data 原始数据
	 * @param string $sign 待验签 签名
	 */
	public function publicKeyVerify($data, $sign, $signType = OPENSSL_ALGO_SHA1){
		if($this->rsa_public_key_is_file){
			$pubKey = file_get_contents($this->rsa_public_key);
			$res = openssl_get_publickey($pubKey);
			$result = (bool) openssl_verify($data, base64_decode($sign), $res, $signType);
			openssl_free_key($res);
			return $result;
		}else{
			$result = (bool) openssl_verify($data, base64_decode($sign), $this->rsa_public_key, $signType);
			return $result;
		}
	}
	
	/**
	 * 私钥加密
	 * @param string $data 原始数据
	 */
	public function privateKeyEn($data, $padding = OPENSSL_PKCS1_PADDING){
		if($this->rsa_private_key_is_file){
			$priKey = file_get_contents($this->rsa_private_key);
			$res = openssl_get_privatekey($priKey);
			openssl_private_encrypt($data,$enData,$priKey,$padding);
			openssl_free_key($res);
			return base64_encode($enData);
		}else{
			openssl_private_encrypt($data,$enData,$this->rsa_private_key,$padding);
			return base64_encode($enData);;
		}
	}
	
	/**
	 * 公钥解密
	 * @param string $enData 加密的数据
	 */
	public function publicKeyDe($enData, $padding = OPENSSL_PKCS1_PADDING){
		$enData = base64_decode($enData);
		if($this->rsa_public_key_is_file){
			$pubKey = file_get_contents($this->rsa_public_key);
			$res = openssl_get_publickey($pubKey);
			openssl_public_decrypt($enData,$deData,$res,$padding);
			openssl_free_key($res);
			return $deData;
		}else{
			openssl_public_decrypt($enData,$deData,$this->rsa_public_key,$padding);
			return $deData;
		}
	}
	
	/**
	 * 私钥加密(超过117字节时分段加密)
	 * @param string $data 原始数据
	 */
	public function privateKeyEnM($data, $padding = OPENSSL_PKCS1_PADDING){
		if($this->rsa_private_key_is_file){
			$privateKey = file_get_contents($this->rsa_private_key);
			$res = openssl_get_privatekey($privateKey);
			$dataArr=str_split($data,117);
			foreach($dataArr as $sub){
				openssl_private_encrypt($sub,$sub_enData,$privateKey,$padding);
				$enDataArr[] = $sub_enData;
			}
			openssl_free_key($res);
			return base64_encode(implode('',$enDataArr));
		}else{
			$dataArr=str_split($data,117);
			foreach($dataArr as $sub){
				openssl_private_encrypt($sub,$sub_enData,$this->rsa_private_key,$padding);
				$enDataArr[] = $sub_enData;
			}
			openssl_free_key($res);
			return base64_encode(implode('',$enDataArr));
		}
	}
	
	/**
	 * 公钥解密(超过117字节时分段加密)
	 * @param string $enData 加密的数据
	 */
	public function publicKeyDeM($enData, $padding = OPENSSL_PKCS1_PADDING){
		$enData = base64_decode($enData);
		if($this->rsa_public_key_is_file){
			$pubKey = file_get_contents($this->rsa_public_key);
			$res = openssl_get_publickey($pubKey);
			$deData = '';
			$len = strlen($enData)/128;
			for ($i = 0; $i<$len; $i++){
				$data = substr($enData,$i*128,128);
				openssl_public_decrypt($data,$decrypt,$pubKey,$padding);
				$deData .= $decrypt;
			}
			openssl_free_key($res);
			return $deData;
		}else{
			$deData = '';
			$len = strlen($enData)/128;
			for ($i = 0; $i<$len; $i++){
				$data = substr($enData,$i*128,128);
				openssl_public_decrypt($data,$decrypt,$this->rsa_public_key,$padding);
				$deData .= $decrypt;
			}
			return $deData;
		}
	}
	
	/**
	 * 公钥加密
	 * @param string $data 原始数据
	 */
	public function publicKeyEn($data, $padding = OPENSSL_PKCS1_PADDING){
		if($this->rsa_private_key_is_file){
			$publicKey = file_get_contents($this->rsa_public_key);
			$res = openssl_get_publickey($publicKey);
			openssl_public_encrypt($data,$enData,$publicKey,$padding);
			openssl_free_key($res);
			return base64_encode($enData);
		}else{
			openssl_public_encrypt($data,$enData,$this->rsa_public_key,$padding);
			return base64_encode($enData);;
		}
	}
	
	/**
	 * 私钥解密
	 * @param string $enData 加密的数据
	 */
	public function privateKeyDe($enData, $padding = OPENSSL_PKCS1_PADDING){
		$enData = base64_decode($enData);
		if($this->rsa_private_key_is_file){
			$privateKey = file_get_contents($this->rsa_private_key,$padding);
			$res = openssl_get_privatekey($privateKey);
			openssl_private_decrypt($enData,$deData,$res,$padding);
			openssl_free_key($res);
			return $deData;
		}else{
			openssl_private_decrypt($enData,$deData,$this->rsa_private_key,$padding);
			return $deData;
		}
	}
	
	/**
	 * 私钥加密(超过117字节时分段加密)
	 * @param string $data 原始数据
	 */
	public function publicKeyEnM($data, $padding = OPENSSL_PKCS1_PADDING){
		if($this->rsa_public_key_is_file){
			$publicKey = file_get_contents($this->rsa_public_key);
			$res = openssl_get_publickey($publicKey);
			$dataArr=str_split($data,117);
			foreach($dataArr as $sub){
				openssl_public_encrypt($sub,$sub_enData,$publicKey,$padding);
				$enDataArr[] = $sub_enData;
			}
			openssl_free_key($res);
			return base64_encode(implode('',$enDataArr));
		}else{
			$dataArr=str_split($data,117);
			foreach($dataArr as $sub){
				openssl_public_encrypt($sub,$sub_enData,$this->rsa_public_key,$padding);
				$enDataArr[] = $sub_enData;
			}
			openssl_free_key($res);
			return base64_encode(implode('',$enDataArr));
		}
	}
	
	/**
	 * 公钥解密(超过117字节时分段加密)
	 * @param string $enData 加密的数据
	 */
	public function privateKeyDeM($enData, $padding = OPENSSL_PKCS1_PADDING){
		$enData = base64_decode($enData);
		if($this->rsa_private_key_is_file){
			$privateKey = file_get_contents($this->rsa_private_key);
			$res = openssl_get_privatekey($privateKey);
			$deData = '';
			$len = strlen($enData)/128;
			for ($i = 0; $i<$len; $i++){
				$data = substr($enData,$i*128,128);
				openssl_private_decrypt($data,$decrypt,$privateKey,$padding);
				$deData .= $decrypt;
			}
			openssl_free_key($res);
			return $deData;
		}else{
			$deData = '';
			$len = strlen($enData)/128;
			for ($i = 0; $i<$len; $i++){
				$data = substr($enData,$i*128,128);
				openssl_private_decrypt($data,$decrypt,$this->rsa_private_key,$padding);
				$deData .= $decrypt;
			}
			return $deData;
		}
	}
	
	
}