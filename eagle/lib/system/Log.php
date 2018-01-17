<?php

namespace lib\system;

class Log {
    public static function error( $content ) {
        self::put( $content, 'error' );
    }
    public static function debug( $content ) {
        self::put( $content, 'debug' );
    }
    public static function warning( $content ) {
        self::put( $content, 'warning' );
    }
    public static function info( $content ) {
        self::put( $content, 'info' );
    }
    private static function put( $content, $type = 'error' ) {
        $dir = LOGS_PATH . DIRECTORY_SEPARATOR . $type;
        is_dir( $dir ) || mkdir( $dir, 0777, true );
        file_put_contents( $dir . DIRECTORY_SEPARATOR . date( 'YmdH' ) . '.log', date( 'Y:m:d H:i:s' ) . ' ' . $content . PHP_EOL, FILE_APPEND );
    }
}