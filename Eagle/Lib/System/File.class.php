<?php
namespace Lib\System;

class File {

    private static $_files = array();
    
    public static function read($file) {
        $content = "";
        if (file_exists($file)) {
            if (is_dir($file)) {
                $content = self::_openDir($file, true);
            } else {
                $content = file_get_contents($file);
            }
        }
        return $content;
    }
    
    public static function write($file, $content, $type = 'a'){
        $dir = dirname($file);
        if (!is_dir($dir)) mkdir($dir, 0755, true);
        if ($type == 'w') $res = file_put_contents($file, $content);
        if ($type == 'a') $res = file_put_contents($file, $content, FILE_APPEND);
        return $res;
    }
    
    public static function cp($sourceFile, $toFile) {
        $dir = dirname($toFile);
        if (!is_dir($dir)) mkdir($dir, 0755, true);
        return copy($sourceFile, $toFile);
    }
    
    public static function mv($sourceFile, $toFile) {
        $res = self::cp($sourceFile, $toFile);
        if ($res) {
            self::del($sourceFile);
        }
    }
    
    public static function del($file) {
        if (file_exists($file)) {
            if (is_dir($file)) {
                self::_rmDir($file);
            } else {
                return unlink($file);
            }
        }
    }
    
    private static function _openDir($dir, $recursive = false) {
        if (self::$_files && $recursive) self::$_files = array();
        $files = array();
        if (is_dir($dir)) {
            $dirFiles = scandir($dir);
            $dirFiles = array_diff($dirFiles, array(".", ".."));
            if ($dirFiles) {
                foreach($dirFiles as $file) {
                    $file = "{$dir}/{$file}";
                    self::$_files[] = $file;
                    if (is_dir($file)) {
                        self::_openDir($file);
                    }
                }
            }
        }
        return self::$_files;
    }
    
    private static function _rmDir($dir) {
        $files = scandir($dir);
        $files = array_diff($files, array(".", ".."));
        if ($files) {
            foreach($files as $file) {
                $file = "{$dir}/{$file}";
                if (is_dir($file)) {
                    self::_rmDir($file);
                } else {
                    unlink($file);
                }
            }
        }
        return rmdir($dir);
    }
    
}