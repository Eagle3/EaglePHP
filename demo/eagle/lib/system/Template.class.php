<?php
namespace lib\system;
class Template {
	private $tplVar = array();
	private $SystemConfigArr = array();
	private $template_dir = '';
	private $compile_dir = '';
	private $caching = '';
	private $cache_dir = '';
	private $cache_lifetime = '';
	private $left_delimiter = '';
	private $right_delimiter = '';
	private $debugging = '';
	
    public function __construct(){
    	$this->initSystem();
    	$this->initAssign();
    }
        
    public function assign( $tplVar, $tplValue = null){
    	if( is_array($tplVar) && count($tplVar) > 0 ){
    		foreach ( $tplVar as $k => $v ){
    			$this->tplVar[$k] = $v;
    		}
    	}else{
    		$this->tplVar[$tplVar] = $tplValue;
    	}
    }
    
    public function fetch($tpl) {
    	$tplFile = $this->getTplFilePath($tpl);
    	is_dir($this->compile_dir) || @mkdir($this->compile_dir, 0777);
    	$filename = $this->compile_dir.md5($tplFile).'.php';
    	$contentC = $this->tplParse( file_get_contents($tplFile) );
    	file_put_contents($filename, $contentC);
    	ob_start();
    	include $filename;
    	return ob_get_clean();
    }
    
    public function display($tpl){
    	echo $this->fetch($tpl);
    	exit;
    }
	
    private function initAssign(){
    	$this->assign(array(
    			'PROJECT_JS_PATH' => PROJECT_JS_PATH,
    			'PROJECT_CSS_PATH' => PROJECT_CSS_PATH,
    			'PROJECT_IMAGE_PATH' => PROJECT_IMAGE_PATH,
    			'FILE_VERSION' => '?v='.time().mt_rand(10000, 99999),
    	));
    }
    
    private function getTplFilePath($tpl){
    	$tplPostfix = getConfig('DEFAULT_TPL_POSTFIX');
    	$path = $this->template_dir.$tpl.$tplPostfix;
    	return $path;
    }
    
    private function initSystem(){
    	$this->SystemConfigArr = getConfig('SYSTEM_TPL_CONFIG');
    	$SystemConfigArr = $this->SystemConfigArr;
    	if (isset($SystemConfigArr["template_dir"])) {
    		$tplName = getConfig('DEFAULT_TPL_NAME');
    		if(is_array($tplName)){
    			$tplName = $tplName[ROUTE_NAME];
    		}
    		$this->template_dir = $SystemConfigArr["template_dir"].ROUTE_NAME.DIRECTORY_SEPARATOR.$tplName.DIRECTORY_SEPARATOR.CONTROLLER_NAME.DIRECTORY_SEPARATOR;
    	}
    
    	if (isset($SystemConfigArr["compile_dir"])) {
    		$this->compile_dir = $SystemConfigArr["compile_dir"];
    	}
    
    	if (isset($SystemConfigArr["caching"])) {
    		$this->caching = $SystemConfigArr["caching"];
    	}
    
    	if (isset($SystemConfigArr["cache_dir"])) {
    		$this->cache_dir = $SystemConfigArr["cache_dir"];
    	}
    
    	if ($SystemConfigArr["cache_lifetime"]) {
    		$this->caching = true;
    		$this->cache_lifetime = $SystemConfigArr["cache_lifetime"];
    	}
    
    	if (isset($SystemConfigArr["delimiter"])) {
    		$this->left_delimiter = $SystemConfigArr["delimiter"]["left_delimiter"];
    		$this->right_delimiter = $SystemConfigArr["delimiter"]["right_delimiter"];
    	}
    
    	if (isset($SystemConfigArr["debugging"])) {
    		$this->debugging = $SystemConfigArr["debugging"];
    	}
    }
        
	private function tplParse($tpl){
		$left = $this->left_delimiter;
		$right = $this->right_delimiter;
		
		$pattern = array(
				'/'.$left.'\$([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)'.$right.'/i'
		);
		
		$replace = array(
				'<?php echo $this->tplVar[\'${1}\']; ?>'
		);
		

		return preg_replace($pattern, $replace, $tpl);
		
	}
}