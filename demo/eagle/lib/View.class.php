<?php
namespace lib;
class View {
protected  $smarty = null;
    protected  $smartyConfigArr = array();
    protected  $initSmartyStatus = false;
    
    public function __construct(){}
        
    public function assign($key, $val = null){
        if(!$this->initSmartyStatus){
            $this->initSmarty();
        }
        if (is_array($key) && $key) {
            foreach($key as $k => $v) {
                $this->smarty->assign($k, $v);
            }
        } else {
            $this->smarty->assign($key, $val);
        }
    }
    
    public function fetch($tpl) {
        if(!$this->initSmartyStatus){
            $this->initSmarty();
        }
        
        $this->initAssign();
        $tplFile = $this->getTplFilePath($tpl);
        return $this->smarty->fetch($tplFile);
    }
    
    public function display($tpl){
        if(!$this->initSmartyStatus){
            $this->initSmarty();
        }
        
        $this->initAssign();
        $tplFile = $this->getTplFilePath($tpl);
        $this->smarty->display($tplFile);
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
        $path = $this->smarty->template_dir[0].$tpl.$tplPostfix;
        return $path;
    }
    
    private function initSmarty(){
        //$this->smarty = getSmartyObj();
        $this->smarty = $GLOBALS['SMARTY_OBJ'];
        $this->smartyConfigArr = getConfig('SMARTY_TPL_CONFIG');
        $this->setSmarty();
    }
    
    private function setSmarty(){
        $smartyConfigArr = $this->smartyConfigArr;
        if (isset($smartyConfigArr["template_dir"])) {
            $tplName = getConfig('DEFAULT_TPL_NAME');
            if(is_array($tplName)){
                $tplName = $tplName[ROUTE_NAME];
            }
            $this->smarty->template_dir = $smartyConfigArr["template_dir"].ROUTE_NAME.DIRECTORY_SEPARATOR.$tplName.DIRECTORY_SEPARATOR.CONTROLLER_NAME.DIRECTORY_SEPARATOR;
        }
        
        if (isset($smartyConfigArr["compile_dir"])) {
            $this->smarty->compile_dir = $smartyConfigArr["compile_dir"];
        }
        
        if (isset($smartyConfigArr["caching"])) {
            $this->smarty->caching = $smartyConfigArr["caching"];
        }
        
        if (isset($smartyConfigArr["cache_dir"])) {
            $this->smarty->cache_dir = $smartyConfigArr["cache_dir"];
        }
        
        if ($smartyConfigArr["cache_lifetime"]) {
            $this->smarty->caching = true;
            $this->smarty->cache_lifetime = $smartyConfigArr["cache_lifetime"];
        }
        
        if (isset($smartyConfigArr["delimiter"])) {
            $this->smarty->left_delimiter = $smartyConfigArr["delimiter"]["left_delimiter"];
            $this->smarty->right_delimiter = $smartyConfigArr["delimiter"]["right_delimiter"];
        }
        
        if (isset($smartyConfigArr["debugging"])) {
            $this->smarty->debugging = $smartyConfigArr["debugging"];
        }
        
    }
    
    
    
}