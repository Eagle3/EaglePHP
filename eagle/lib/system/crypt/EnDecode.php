<?php

namespace lib\system\crypt;

class EnDecode {
    
    /**
     * discuz 加密解密算法
     *
     * $string 明文或密文
     * $operation 加密ENCODE或解密DECODE
     * $key 密钥
     * $expiry 密钥有效期
     */
    function code1( $string, $key = '', $operation = 'DECODE', $expiry = 0 ) {
        // 动态密匙长度，相同的明文会生成不同密文就是依靠动态密匙
        // 加入随机密钥，可以令密文无任何规律，即便是原文和密钥完全相同，加密结果也会每次不同，增大破解难度。
        // 取值越大，密文变动规律越大，密文变化 = 16 的 $ckey_length 次方
        // 当此值为 0 时，则不产生随机密钥
        $ckey_length = 4;
        
        // 密匙
        // $GLOBALS['discuz_auth_key'] 这里可以根据自己的需要修改
        $key = md5( $key ? $key : $GLOBALS['discuz_auth_key'] );
        
        // 密匙a会参与加解密
        $keya = md5( substr( $key, 0, 16 ) );
        // 密匙b会用来做数据完整性验证
        $keyb = md5( substr( $key, 16, 16 ) );
        // 密匙c用于变化生成的密文
        $keyc = $ckey_length ? ($operation == 'DECODE' ? substr( $string, 0, $ckey_length ) : substr( md5( microtime() ), -$ckey_length )) : '';
        // 参与运算的密匙
        $cryptkey = $keya . md5( $keya . $keyc );
        $key_length = strlen( $cryptkey );
        // 明文，前10位用来保存时间戳，解密时验证数据有效性，10到26位用来保存$keyb(密匙b)，解密时会通过这个密匙验证数据完整性
        // 如果是解码的话，会从第$ckey_length位开始，因为密文前$ckey_length位保存 动态密匙，以保证解密正确
        $string = $operation == 'DECODE' ? base64_decode( substr( $string, $ckey_length ) ) : sprintf( '%010d', $expiry ? $expiry + time() : 0 ) . substr( md5( $string . $keyb ), 0, 16 ) . $string;
        $string_length = strlen( $string );
        $result = '';
        $box = range( 0, 255 );
        $rndkey = array();
        // 产生密匙簿
        for ( $i = 0; $i <= 255; $i ++ ) {
            $rndkey[$i] = ord( $cryptkey[$i % $key_length] );
        }
        // 用固定的算法，打乱密匙簿，增加随机性，好像很复杂，实际上并不会增加密文的强度
        for ( $j = $i = 0; $i < 256; $i ++ ) {
            $j = ($j + $box[$i] + $rndkey[$i]) % 256;
            $tmp = $box[$i];
            $box[$i] = $box[$j];
            $box[$j] = $tmp;
        }
        // 核心加解密部分
        for ( $a = $j = $i = 0; $i < $string_length; $i ++ ) {
            $a = ($a + 1) % 256;
            $j = ($j + $box[$a]) % 256;
            $tmp = $box[$a];
            $box[$a] = $box[$j];
            $box[$j] = $tmp;
            // 从密匙簿得出密匙进行异或，再转成字符
            $result .= chr( ord( $string[$i] ) ^ ($box[($box[$a] + $box[$j]) % 256]) );
        }
        if ( $operation == 'DECODE' ) {
            // substr($result, 0, 10) == 0 验证数据有效性
            // substr($result, 0, 10) - time() > 0 验证数据有效性
            // substr($result, 10, 16) == substr(md5(substr($result, 26).$keyb), 0, 16) 验证数据完整性
            // 验证数据有效性，请看未加密明文的格式
            if ( (substr( $result, 0, 10 ) == 0 || substr( $result, 0, 10 ) - time() > 0) && substr( $result, 10, 16 ) == substr( md5( substr( $result, 26 ) . $keyb ), 0, 16 ) ) {
                return substr( $result, 26 );
            } else {
                return '';
            }
        } else {
            // 把动态密匙保存在密文里，这也是为什么同样的明文，生产不同密文后能解密的原因
            // 因为加密后的密文可能是一些特殊字符，复制过程可能会丢失，所以用base64编码
            return $keyc . str_replace( '=', '', base64_encode( $result ) );
        }
    }
    function code2( $tex, $key, $type = "encode" ) {
        $chrArr = array(
                'a',
                'b',
                'c',
                'd',
                'e',
                'f',
                'g',
                'h',
                'i',
                'j',
                'k',
                'l',
                'm',
                'n',
                'o',
                'p',
                'q',
                'r',
                's',
                't',
                'u',
                'v',
                'w',
                'x',
                'y',
                'z',
                'A',
                'B',
                'C',
                'D',
                'E',
                'F',
                'G',
                'H',
                'I',
                'J',
                'K',
                'L',
                'M',
                'N',
                'O',
                'P',
                'Q',
                'R',
                'S',
                'T',
                'U',
                'V',
                'W',
                'X',
                'Y',
                'Z',
                '0',
                '1',
                '2',
                '3',
                '4',
                '5',
                '6',
                '7',
                '8',
                '9' 
        );
        if ( $type == "decode" ) {
            if ( strlen( $tex ) < 14 )
                return false;
            $verity_str = substr( $tex, 0, 8 );
            $tex = substr( $tex, 8 );
            if ( $verity_str != substr( md5( $tex ), 0, 8 ) ) {
                // 完整性验证失败
                return false;
            }
        }
        $key_b = $type == "decode" ? substr( $tex, 0, 6 ) : $chrArr[rand() % 62] . $chrArr[rand() % 62] . $chrArr[rand() % 62] . $chrArr[rand() % 62] . $chrArr[rand() % 62] . $chrArr[rand() % 62];
        $rand_key = $key_b . $key;
        $rand_key = md5( $rand_key );
        $tex = $type == "decode" ? base64_decode( substr( $tex, 6 ) ) : $tex;
        $texlen = strlen( $tex );
        $reslutstr = "";
        for ( $i = 0; $i < $texlen; $i ++ ) {
            $reslutstr .= $tex{$i} ^ $rand_key{$i % 32};
        }
        if ( $type != "decode" ) {
            $reslutstr = trim( $key_b . base64_encode( $reslutstr ), "==" );
            $reslutstr = substr( md5( $reslutstr ), 0, 8 ) . $reslutstr;
        }
        return $reslutstr;
    }
    function code3( $tex, $key, $type = "encode" ) {
        $chrArr = array(
                'a',
                'b',
                'c',
                'd',
                'e',
                'f',
                'g',
                'h',
                'i',
                'j',
                'k',
                'l',
                'm',
                'n',
                'o',
                'p',
                'q',
                'r',
                's',
                't',
                'u',
                'v',
                'w',
                'x',
                'y',
                'z',
                'A',
                'B',
                'C',
                'D',
                'E',
                'F',
                'G',
                'H',
                'I',
                'J',
                'K',
                'L',
                'M',
                'N',
                'O',
                'P',
                'Q',
                'R',
                'S',
                'T',
                'U',
                'V',
                'W',
                'X',
                'Y',
                'Z',
                '0',
                '1',
                '2',
                '3',
                '4',
                '5',
                '6',
                '7',
                '8',
                '9' 
        );
        if ( $type == "decode" ) {
            if ( strlen( $tex ) < 14 )
                return false;
            $verity_str = substr( $tex, 0, 8 );
            $tex = substr( $tex, 8 );
            if ( $verity_str != substr( md5( $tex ), 0, 8 ) ) {
                // 完整性验证失败
                return false;
            }
        }
        $key_b = $type == "decode" ? substr( $tex, 0, 6 ) : $chrArr[rand() % 62] . $chrArr[rand() % 62] . $chrArr[rand() % 62] . $chrArr[rand() % 62] . $chrArr[rand() % 62] . $chrArr[rand() % 62];
        $rand_key = $key_b . $key;
        $rand_key = md5( $rand_key );
        $tex = $type == "decode" ? base64_decode( substr( $tex, 6 ) ) : $tex;
        $texlen = strlen( $tex );
        $reslutstr = "";
        for ( $i = 0; $i < $texlen; $i ++ ) {
            $reslutstr .= $tex{$i} ^ $rand_key{$i % 32};
        }
        if ( $type != "decode" ) {
            $reslutstr = trim( $key_b . base64_encode( $reslutstr ), "==" );
            $reslutstr = substr( md5( $reslutstr ), 0, 8 ) . $reslutstr;
        }
        return $reslutstr;
    }
}

