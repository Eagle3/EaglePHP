<?php

namespace Lib\Plugin\Curl\php_curl_class\src\Curl;

class StrUtil
{
    /**
     * Return true when $haystack starts with $needle.
     *
     * @access public
     * @param  $haystack
     * @param  $needle
     *
     * @return bool
     */
    public static function startsWith($haystack, $needle)
    {
        return mb_substr($haystack, 0, mb_strlen($needle)) === $needle;
    }
}
