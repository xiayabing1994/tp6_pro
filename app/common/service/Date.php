<?php

namespace app\common\service;

class Date{
    private $zone;
    public function __construct(){
        $this->zone='Asia/shanghai';
    }

    /**
     * 格式化时间
     * @param int $timestamp
     * @param string $format
     * @return bool|false|string
     */
    public function date_format($timestamp=0,$format='Y-m-d H:i:s'){
        if($timestamp==0) return false;
        return date($format,$timestamp);
    }

    /**
     * 获取当月的第一天或者最后一天时间
     * @param string $day
     * @return false|string
     */
    public static function getCurrenMonthDay($day='first'){
        $firstday=date('Y-m-01', strtotime(date("Y-m-d")));
        if($day=='first'){
            return $firstday;
        }elseif($day=='last'){
            return date('Y-m-d', strtotime("$firstday +1 month -1 day"));
        }
        return false;
    }

    /**
     * 获取上月的第一天或者最后一天时间
     * @param string $day
     * @return bool|false|string
     */
    public static function getPreMonthDay($day='first'){
        $firstday=date('Y-m-01', strtotime('-1 month'));
        if($day=='first'){
            return $firstday;
        }elseif($day=='last'){
            return date('Y-m-t', strtotime('-1 month'));
        }
        return false;
    }
    /**
     * 获取下月的第一天或者最后一天时间
     * @param string $day
     * @return bool|false|string
     */
    public static function getNextMonthDay($day='first'){
        $firstday=date('Y-m-01', strtotime('+1 month'));
        if($day=='first'){
            return $firstday;
        }elseif($day=='last'){
            return date('Y-m-t', strtotime('+1 month'));
        }
        return false;
    }

    /**
     * 获取两个日期间间隔天数
     * @param $startday  开始日期 2019-12-10
     * @param $endday    结束日期 2019-12-13
     * @return float|int 3
     */
    public static function getIntervalDays($startday,$endday){
        return (strtotime($endday)-strtotime($startday))/86400;
    }

    /**
     * 获取某日 开始hour到结束hour之间随机时间戳
     * @param $day              日期
     * @param $startHour        开始时刻(h)
     * @param $endHour          结束时刻(h)
     * @param int $ext_min      最小间隔(s)
     * @param int $ext_max      最大间隔(s)
     * @param int $num          要生成的总数量
     * @return array
     */
    public function randomTime($day,$startHour,$endHour,$ext_min=1200,$ext_max=3600,$num=4){
        $startTimestamp=strtotime("$day $startHour");
        $endTimestamp=strtotime("$day $endHour");
        $num=$startTimestamp;
        for($i=0;$i<$num;$i++){
            $num=$num+rand($ext_min,$ext_max);
            $res[]=$num;
        }
        return $res;
    }

}