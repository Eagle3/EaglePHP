<?php
// 不要在此文件中写配置选项
$dir_path = __DIR__ . DIRECTORY_SEPARATOR;
$configArr = array();
if ( is_dir( $dir_path ) ) {
    $dir_handle = opendir( $dir_path );
    if ( $dir_handle ) {
        while ( ($file = readdir( $dir_handle )) !== false ) {
            if ( $file != '.' && $file != '..' && $file != 'config.php' ) {
                if ( file_exists( $dir_path . $file ) && strpos( $file, '.config.php' ) ) {
                    $configArr = array_merge( $configArr, require $dir_path . $file );
                }
            }
        }
    }
}
return $configArr;