<?php
/*

传递的参数$weight
   $weight = [
            [
                    'name' => '超级大奖',
                    'weight' => 0, // 0
            ],
            [
                    'name' => '特等奖',
                    'weight' => 1, // 1/10000
            ],
            [
                    'name' => '一等奖',
                    'weight' => 2, // 2/10000
            ],
            [
                    'name' => '二等奖',
                    'weight' => 7, // 7/10000
            ],
            [
                    'name' => '未中奖',
                    'weight' => 9990, // 9990/10000
            ],
    ];


$weight = [
        [
                'name' => '超级大奖',
                'weight' => [10010], // 0
        ],
        [
                'name' => '特等奖',
                'weight' => [10000, 10000], // 1/10000
        ],
        [
                'name' => '一等奖',
                'weight' => [9998, 9999], // 2/10000
        ],
        [
                'name' => '二等奖',
                'weight' => [9991, 9997], // 7/10000
        ],
        [
                'name' => '未中奖',
                'weight' => [1,9990], // 9990/10000
        ],
];

 */

namespace lib\system;
class Luck {
    private static $numBase = 100;
    private static $weight = [];
    private static $weightNum = [];
    
    public static function init( $weight, $numBase = 100 ){
       self::$numBase = $numBase;
       self::$weight= $weight;
       self::$weightNum = array_combine(array_keys(self::$weight), array_column(self::$weight,'weight'));
       self::initWeight();
    }
    
    private static function initWeight(){
        $i = 0;
        foreach (self::$weight as $k => &$v ){
            $preTotalNum = array_sum(array_slice(self::$weightNum, 0, $i));
            if( $v['weight'] == 0 ){
                $v['weight'] = [self::$numBase + 10];
            }else{
                $v['weight'] = [self::$numBase - $preTotalNum - $v['weight'] + 1,self::$numBase - $preTotalNum];
            }
            $i++;
        }
        unset($v);
    }
    
    //抽奖
    public static function run(){
        $res = [];
        $num = mt_rand(1,self::$numBase);
        foreach (self::$weight as $v){
            if( $num >= $v['weight'][0] && $num <= $v['weight'][1]){
                $res['num'] = $num;
                $res['name'] = $v['name'];
                $res['weight'] = $v['weight'];
            }
        }
        return $res;
    }
    
    //统计执行n次抽奖获奖情况
    public static function test($n = 10000){
        $res = [];
        for( $i=1;$i<=$n;$i++ ){
            $num = mt_rand(1,self::$numBase);
            foreach (self::$weight as $v){
                if( $num >= $v['weight'][0] && $num <= $v['weight'][1]){
                    $res[$i]['num'] = $num;
                    $res[$i]['name'] = $v['name'];
                    $res[$i]['weight'] = $v['weight'];
                }
            }
        }
        return array_count_values(array_combine(array_keys($res), array_column($res, 'name')));
    }
}