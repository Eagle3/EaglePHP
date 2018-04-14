<?php

namespace lib\system\cache;

abstract class abstractCache {
    abstract public function get( $key );
    abstract public function set( $key, $value, $expire = null );
    abstract public function delete( $key );
    abstract public function clear();
    abstract public function incr();
    abstract public function decr();
}