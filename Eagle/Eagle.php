<?php
header( "Content-type: text/html; charset=utf-8" );

// 项目路径（入口文件所在的目录）
defined( 'PROJECT_PATH' ) or define( 'PROJECT_PATH', dirname( $_SERVER['SCRIPT_FILENAME'] ) . DIRECTORY_SEPARATOR );
// 项目公共函数路径
defined( 'PROJECT_COMMON_PATH' ) or define( 'PROJECT_COMMON_PATH', PROJECT_PATH . 'Common' . DIRECTORY_SEPARATOR );
// 项目配置文件路径
defined( 'PROJECT_CONFIG_PATH' ) or define( 'PROJECT_CONFIG_PATH', PROJECT_PATH . 'Config' . DIRECTORY_SEPARATOR );
// 日志文件目录
defined( 'LOGS_PATH' ) or define( 'LOGS_PATH', PROJECT_PATH . 'Logs' );
// 项目js路径
defined( 'JS_PATH' ) or define( 'JS_PATH', '/Static/js/' );
// 项目css路径
defined( 'CSS_PATH' ) or define( 'CSS_PATH', '/Static/css/' );
// 项目图片路径
defined( 'IMAGE_PATH' ) or define( 'IMAGE_PATH', '/Static/image/' );
// 字体文件路径
defined( 'FONT_PATH' ) or define( 'FONT_PATH', PROJECT_PATH . 'Static/font/' );

// 框架路径
defined( 'EAGLE_PATH' ) or define( 'EAGLE_PATH', __DIR__ . DIRECTORY_SEPARATOR );
// 框架公共函数库路径
defined( 'SYS_COMMON_PATH' ) or define( 'SYS_COMMON_PATH', EAGLE_PATH . 'Common' . DIRECTORY_SEPARATOR );
// 框架公共配置路径
defined( 'SYS_CONFIG_PATH' ) or define( 'SYS_CONFIG_PATH', EAGLE_PATH . 'Config' . DIRECTORY_SEPARATOR );
// 框架语言包路径
defined( 'SYS_LANGUAGE_PATH' ) or define( 'SYS_LANGUAGE_PATH', EAGLE_PATH . 'Language' . DIRECTORY_SEPARATOR );

// 加载框架公共函数库
require SYS_COMMON_PATH . 'function.php';
// 加载项目公共函数库
require PROJECT_COMMON_PATH . 'function.php';

// 系统配置
$sysConfig = getConfig( '', array(
        SYS_CONFIG_PATH 
) );
// 项目配置
$projectConfig = getConfig( '', array(
        PROJECT_CONFIG_PATH 
) );

// 语言包
if ( !isset( $projectConfig['DEFAULT_LANGUAGE'] ) || $projectConfig['DEFAULT_LANGUAGE'] == '' || !in_array( $projectConfig['DEFAULT_LANGUAGE'], array(
        'zh-cn',
        'zh-tw',
        'en-us' 
) ) || !in_array( $sysConfig['DEFAULT_LANGUAGE'], array(
        'zh-cn',
        'zh-tw',
        'en-us' 
) ) ) {
    $lang = 'zh-cn';
} else {
    $lang = $sysConfig['DEFAULT_LANGUAGE'];
}
$langData = getLangConfig( $lang );

// 注册自动加载
require_once EAGLE_PATH . 'Lib/System/Autoloader.class.php';
Lib\System\Autoloader::register();

// 执行
Lib\System\App::getInstance()->run();