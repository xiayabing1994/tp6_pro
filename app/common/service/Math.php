<?php
/**
 * Created by Xiami.
 * User: beinianxiaoyao@163.com
 * Desc: 数学类
 * Date: 2019/11/04 0014
 * Time: 08:31
 */
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

    /**
     * 求指数运算
     * @param $num      底数
     * @param int $pow  指数
     * @return int      结果
     */
    public function power($num,$pow=0){
        $res= $num==0 ? 0 : 1;
        for($i=0;$i<$pow;$i++){
            $res=$res*$num;
        }
        return $res;
    }

    /**
     * 二分法求一个数次方根
     * @param $num                要求的数
     * @param $power              次数
     * @param float $precision    精度
     * @param int $start          开始值默认0,待优化
     * @param int $end            结束值
     * @param int $dgree          结果保留位数
     * @return float|int
     */
    public function powerRoot($num,$power,$precision=0.01,$start=0,$end=0,$dgree=6){
        if(!$end) $end=$num;
        while($start<$end){
            $mid=($start+$end)/2;
            if($this->power($mid,$power)<$num-$precision){
                $start=$mid;
            }elseif($this->power($mid,$power)>$num+$precision){
                $end=$mid;
            }else{
                $res=$mid;
            }
        }
        return $res;
        if(!$end) $end=$num;
        $half=round(($end+$start)/2,$dgree);
        $half_power=$this->power($half,$power);
        if(($end-$half)<$precision && ($half-$start)<$precision){
            return $half;
        }else{
            dump($start.'-'.$half.'-'.$end);
            if($half_power>$num){
                $this->powerRoot($num,$power,$precision,$dgree,$start,$half);
            }
            $this->powerRoot($num,$power,$precision,$dgree,$half,$end);
        }
    }
    /**
     * 求阶乘
     * @param int $num  要求的数
     * @return int      结果
     */
    public function fact($num=1){
        $res=$num==0 ? 0 : 1;
        for($i=$num;$i>0;$i--){
            $res=$res*$i;
        }
        return $res;
    }















}