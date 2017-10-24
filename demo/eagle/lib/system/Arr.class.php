<?php

namespace lib\system;

class Arr {
	
	public static function createHugeArr( $num = 10000 ){
		$arr = range( 1, $num );
		shuffle( $arr );
		return $arr;
	}
	
	/**
	 * php内置排序
	 * @param array $arr
	 * @param string $sort
	 */
	public static function phpSort( $arr, $sort = 'ASC' ){
		if( strtoupper($sort) == 'ASC' ){
			sort( $arr );
		}else{
			arsort( $arr );
		}
		return $arr;
	}
	
	/**
	 * 冒泡排序
	 * @param array $arr
	 * @param string $sort
	 */
	public static function bubbleSort( $arr, $sort = 'ASC' ){
		$len = count( $arr );
		//此循环控制轮数
		for ( $i = 1; $i < $len; $i++ ){
			//此循环控制每轮比较次数
			for ( $j = 0; $j < $len - $i; $j++ ){
				$swop = false;
				switch ( strtoupper($sort) ) {
					case 'ASC':
						$arr[$j] > $arr[$j + 1] && $swop = true;
						break;
					default:
						$arr[$j] < $arr[$j + 1] && $swop = true;
						break;
				}
				if( $swop ){
					$tmp = $arr[$j];
					$arr[$j] = $arr[$j + 1];
					$arr[$j + 1] = $tmp;
				}
			}
		}
		return $arr;
	}
	
	public static function quickSort( $arr, $sort = 'ASC' ){
		 //先判断是否需要继续进行
	    $length = count($arr);
	    if($length <= 1) {
	        return $arr;
	    }
	    //选择第一个元素作为基准
	    $base_num = $arr[0];
	    //遍历除了标尺外的所有元素，按照大小关系放入两个数组内
	    //初始化两个数组
	    $left_array = array();  //小于基准的
	    $right_array = array();  //大于基准的
	    for($i=1; $i<$length; $i++) {
	        if($base_num > $arr[$i]) {
	            //放入左边数组
	            $left_array[] = $arr[$i];
	        } else {
	            //放入右边
	            $right_array[] = $arr[$i];
	        }
	    }
	    //再分别对左边和右边的数组进行相同的排序处理方式递归调用这个函数
	    $left_array = self::quickSort($left_array);
	    $right_array = self::quickSort($right_array);
	    return array_merge($left_array, array($base_num), $right_array);
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	public function __construct(){}
	public function __destruct(){}
}