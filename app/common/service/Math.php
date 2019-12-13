<?php
namespace app\common\service;

class Math{

    /**
     * 把一个数分成若干个随机数
     * @param $total 需要分开的数字
     * @param $num   要分开的数量
     * @param int $float  保留位数,默认2位
     * @param float $flow_rate 随机比例,默认5%
     */
    public static function getAvarageRand($total,$num,$float=2,$flow_rate=0.05){
        $res=[];
        $beishu=1;
        if($num<1) return $res;
        for($pow=0;$pow<$float;$pow++){
            $beishu=$beishu*10;
        }
        $avarage=round($total/$num,$float);
        $flow_num=round($avarage*$flow_rate,$float);
        for($i=0;$i<$num-1;$i++){
            $res[$i]=rand(($avarage-$flow_num)*$beishu,($avarage+$flow_num)*$beishu)/$beishu;
        }
        $res[$num-1]=$total-array_sum($res);
        rsort($res);
        return $res;
    }
}