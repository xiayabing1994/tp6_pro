<?php

namespace  app\api\controller;
use think\Controller;
use app\common\service\Date;
use app\common\service\Math;
class Plan{

    private $min=300;
    private $max=900;
    private $pattern=3;                //刷3次还一次
    private $day_max_num=2;            //单日最大笔数
    private $rate=0.0075;
    private $float_rate=0.05;          //金额浮动率
//    private
    /**
     * @param $money               还款金额
     * @param $start_time          开始时间
     * @param $end_time            结束时间
     * return  预留金额，笔数,计划详情
     */
    public function  index(\think\Request $request){
        $money=$request->param('money');
        $start_time=$request->param('start_time');
        $end_time=$request->param('end_time');
        //1.判断金额与日期是否符合   先计算间隔天数,在判断金额
        if($start_time<date('Y-m-d')) $this->error('开始时间不能在今天之前');
        if($start_time>$end_time){
           $this->error('结束日期必须大于开始日期');
        }
        $interval_days=Date::getIntervalDays($start_time,$end_time);
        $curr_hour=date('H');
        if($start_time==$end_time && $curr_hour>18) $this->error('当日18点以后无法消费,请延长时间');
        $time_start=1;
        if($curr_hour<18){
            $interval_days++;
            $time_start=0;
        }
        //构造时间数组
        for($t=$time_start;$t<$interval_days;$t++){
            $date_arr[]=date('Y-m-d',strtotime('+'.$t.' days'));
        }
        $average=$money/$interval_days;
        dump($average);
        $total_plan_min=$this->min*$this->pattern*1*(1+$this->float_rate);     //计划单日最小值
        $total_plan_max=$this->max*$this->pattern*$this->day_max_num*(1-$this->float_rate); //计划单日最大值
        if($average>$total_plan_max  || $average<$total_plan_min){
            $this->error("金额过大或过小,请设置".$total_plan_min*$interval_days."-".$total_plan_max*$interval_days."之间的金额或调整计划时间");
        }
        //2.根据总额生成合适的笔数 单消费300-900,单笔900-2700  单日最大2笔 最小1笔 10000按最小需要(10000/900)/向下取整=11需要每日1-2笔 按(10000/2700)向上取整=5笔每日一笔
        $max_stroke=floor($money/($this->min*$this->pattern*(1+$this->float_rate*2))); //最大笔数,需要加上浮动率
        $min_stroke=ceil($money/($this->max*$this->pattern*(1-$this->float_rate*2)));  //最小笔数,需要减去浮动率
        if($max_stroke>$interval_days*$this->day_max_num) $max_stroke=$interval_days*$this->day_max_num;
        dump($min_stroke."最大".$max_stroke);
        //3.将计划打碎成单笔计划   将单笔计划打碎成$this->pattern次  最后分配每日笔数和时间
        $plans=$this->getPlan($money,$min_stroke,$max_stroke);

        dump($date_arr);
        dump($plans);
        //分别计算 $min-$max每一笔的分配方式
        foreach($plans as $plan){
            foreach($plan as $per_stroke){

            }
        }
        //3.根据金额与笔数生成计划

    }
    private function getPlan($money,$min_stroke,$max_stroke){
        $plans=[];
        for($i=$min_stroke;$i<=$max_stroke;$i++) {
            $single_plan = Math::getAvarageRand($money, $i);
            foreach ($single_plan as $k => $v) {
                $per_plan=Math::getAvarageRand($v, $this->pattern);
                if($per_plan[$this->pattern-1]<$this->min || $per_plan[0]>$this->max){
                    //如果存在不满足最小值或最大值的情况,则重新生成
                    dump('有不符合情况金额'.json_encode($per_plan));
                    dump($plans);
                    $plans=[];
                    return $this->getPlan($money,$min_stroke,$max_stroke);
                }else{
                    $plans[$i][] =$per_plan ;
                }
            }
        }
        return $plans;
    }
    private function error($msg,$data=null){
        echo  json_encode(['error'=>1,'msg'=>$msg,'data'=>$data],JSON_UNESCAPED_UNICODE);
        exit();
    }
    private function success($data,$msg){
        echo  json_encode(['error'=>0,'msg'=>$msg,'data'=>$data],JSON_UNESCAPED_UNICODE);
        exit();
    }
}