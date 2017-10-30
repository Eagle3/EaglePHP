<?php
header("Content-type: text/html; charset=utf-8");

// 项目路径（入口文件所在的目录）
defined('PROJECT_PATH') or define('PROJECT_PATH', dirname($_SERVER['SCRIPT_FILENAME']) . DIRECTORY_SEPARATOR);
//项目公共函数路径
defined('PROJECT_COMMON_PATH') or define('PROJECT_COMMON_PATH', PROJECT_PATH . 'common' . DIRECTORY_SEPARATOR);

//项目配置文件路径
defined('PROJECT_CONFIG_PATH') or define('PROJECT_CONFIG_PATH', PROJECT_PATH . 'config' . DIRECTORY_SEPARATOR);
//项目js路径
defined('PROJECT_JS_PATH') or define('PROJECT_JS_PATH', '/static/js/');
//项目css路径
defined('PROJECT_CSS_PATH') or define('PROJECT_CSS_PATH', '/static/css/');
//项目图片路径
defined('PROJECT_IMAGE_PATH') or define('PROJECT_IMAGE_PATH', '/static/image/');
//日志文件目录
defined('LOGS_PATH') or define('LOGS_PATH', PROJECT_PATH . 'logs');

// 框架路径
defined('EAGLE_PATH') or define('EAGLE_PATH', __DIR__ . DIRECTORY_SEPARATOR);

// 框架公共函数库路径
defined('SYS_COMMON_PATH') or define('SYS_COMMON_PATH', EAGLE_PATH . 'common' . DIRECTORY_SEPARATOR);
// 框架公共配置路径
defined('SYS_CONFIG_PATH') or define('SYS_CONFIG_PATH', EAGLE_PATH . 'config' . DIRECTORY_SEPARATOR);
// 框架语言包路径
defined('SYS_LANGUAGE_PATH') or define('SYS_LANGUAGE_PATH', EAGLE_PATH . 'language' . DIRECTORY_SEPARATOR);

//加载框架公共函数库
require SYS_COMMON_PATH . 'function.php';
//加载项目公共函数库
require PROJECT_COMMON_PATH . 'function.php';

//系统配置
$sysConfig = getConfig('',array(SYS_CONFIG_PATH));

//项目配置
$projectConfig = getConfig('',array(PROJECT_CONFIG_PATH));


//语言包
if(!isset($projectConfig['DEFAULT_LANGUAGE']) || $projectConfig['DEFAULT_LANGUAGE'] == '' || !in_array($projectConfig['DEFAULT_LANGUAGE'], array('zh-cn','zh-tw','en-us')) || !in_array($sysConfig['DEFAULT_LANGUAGE'], array('zh-cn','zh-tw','en-us'))){
    $lang = 'zh-cn';
}else{
    $lang = $sysConfig['DEFAULT_LANGUAGE'];
}
$langData = getLangConfig($lang);

// smarty模板引擎路径
// defined('SMARTY_PATH') or define('SMARTY_PATH', EAGLE_PATH.'lib/plugin/smarty/Autoloader.php');
// $GLOBALS['SMARTY_OBJ'] = getSmartyObj();

function autoLoad($class){
    //echo $class.'<br>';
    $class = str_replace('\\','/',$class);
    $classFile = $class.'.class.php';
    $classDir = array(
            EAGLE_PATH,
            APP_PATH,
    );
    //pr( $classDir );
    foreach ($classDir as $key=>$val){
        if(file_exists($val.$classFile)){
        	//echo $val.$classFile.'<br>';
            require $val.$classFile;
            break;
        }
        continue;
    }
}

spl_autoload_register('autoLoad');

\lib\system\Dispatch::getInstance()->run();