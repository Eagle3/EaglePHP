<?php

namespace lib\system\compress;

class Zip {
    private $fileName = NULL;
    private $saveDir = './Tmp/zip/';
    
    /**
     * 要打包的文件
     *
     *
     * @param mixed $fileName
     *            1.可以是一个目录或者文件
     *            2.
     *            可以是一个数组，数组中的每一项必须是一个文件（不能是目录）
     * @param string $saveDir
     *            保存的压缩文件路径
     */
    public function __construct( $fileName, $saveDir = '' ) {
        $this->fileName = $fileName;
        if ( $saveDir ) {
            $this->saveDir = $saveDir;
        }
    }
    public function exe() {
        if ( !is_string( $this->fileName ) && !is_array( $this->fileName ) ) {
            return '参数输入错误: __construct() 第一个参数不对';
        }
        if ( !file_exists( $this->saveDir ) ) {
            mkdir( $this->saveDir, 0777, TRUE );
        }
        $zipFilename = $this->saveDir . 'file.zip';
        if ( file_exists( $zipFilename ) ) {
            unlink( $zipFilename );
        }
        $zip = new \ZipArchive();
        if ( !$zip->open( $zipFilename, \ZipArchive::CREATE ) ) {
            return 'zip文档打开失败';
        }
        switch ( $this->fileName ) {
            case is_string( $this->fileName ) && is_file( $this->fileName ):
                $zip->addFile( $this->fileName );
                break;
            case is_string( $this->fileName ) && is_dir( $this->fileName ):
                $this->recursiveAddFileToZip( $this->fileName, $zip );
                break;
            case is_array( $this->fileName ):
                foreach ( $this->fileName as $val ) {
                    if ( file_exists( $val ) ) {
                        $zip->addFile( $val );
                    }
                }
                break;
        }
        $zip->close();
        return $zipFilename;
    }
    
    /**
     * 递归把目录下的所有文件加入压缩包
     *
     * @param string $dir
     *            要压缩的目录
     *            
     * @param unknown $zip
     *            ZipArchive 对象
     *            
     */
    private function recursiveAddFileToZip( $path, $zip ) {
        $handler = opendir( $path );
        while ( ($filename = readdir( $handler )) !== false ) {
            if ( $filename != '.' && $filename != '..' ) {
                if ( is_dir( $path . '/' . $filename ) ) {
                    $this->recursiveAddFileToZip( $path . '/' . $filename, $zip );
                } else {
                    $zip->addFile( $path . '/' . $filename );
                }
            }
        }
        @closedir( $path );
    }
}