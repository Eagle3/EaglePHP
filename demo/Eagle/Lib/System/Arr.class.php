<?php

namespace Lib\System;

class Arr {
    public static function createHugeArr( $num = 10000 ) {
        $arr = range( 1, $num );
        shuffle( $arr );
        return $arr;
    }
    
    /**
     * php内置排序
     *
     * @param array $arr            
     * @param string $sort            
     */
    public static function phpSort( $arr, $sort = 'ASC' ) {
        if ( strtoupper( $sort ) == 'ASC' ) {
            sort( $arr );
        } else {
            arsort( $arr );
        }
        return $arr;
    }
    
    /**
     * 冒泡排序
     *
     * @param array $arr            
     * @param string $sort            
     */
    public static function bubbleSort( $arr, $sort = 'ASC' ) {
        $len = count( $arr );
        // 此循环控制轮数
        for ( $i = 1; $i < $len; $i ++ ) {
            // 此循环控制每轮比较次数
            for ( $j = 0; $j < $len - $i; $j ++ ) {
                $swop = false;
                switch ( strtoupper( $sort ) ) {
                    case 'ASC':
                        $arr[$j] > $arr[$j + 1] && $swop = true;
                        break;
                    default :
                        $arr[$j] < $arr[$j + 1] && $swop = true;
                        break;
                }
                if ( $swop ) {
                    $tmp = $arr[$j];
                    $arr[$j] = $arr[$j + 1];
                    $arr[$j + 1] = $tmp;
                }
            }
        }
        return $arr;
    }
    
    /**
     * 快速排序
     *
     * @param array $arr            
     * @param string $sort            
     */
    public static function quickSort( $arr, $sort = 'asc' ) {
        $len = count( $arr ); 
        if ( $len <= 1 ) {
            return $arr;
        }
        $baseNum = $arr[0];
        $minArr = array();
        $maxArr = array();
        for ( $i = 1; $i < $len; $i ++ ) {
            switch ( $sort ) {
                case 'asc':
                    if ( $arr[$i] < $baseNum ) {
                        $minArr[] = $arr[$i];
                    } else {
                        $maxArr[] = $arr[$i];
                    }
                    break;
                default:
                    if ( $arr[$i] > $baseNum ) {
                        $maxArr[] = $arr[$i];
                    } else {
                        $minArr[] = $arr[$i];
                    }
                    break;
            }
        }
        $minArr = self::quickSort( $minArr, $sort );
        $maxArr = self::quickSort( $maxArr, $sort );
        if( $sort == 'asc' ){
            return array_merge( $minArr, array( $baseNum ), $maxArr );
        }
        return array_merge( $maxArr, array( $baseNum ), $minArr );
    }
    
    
}