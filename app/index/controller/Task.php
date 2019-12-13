<?php
namespace app\index\controller;
use app\BaseController;
use think\facade\Queue;


class Task extends BaseController	{



	public function add(){

		$job_queue_name="sms_queue";
		$job_handler_classname = "\app\index\job\Dojob";
		$model=new \app\index\job\Dojob();

		for($i=0;$i<100;$i++){
			$job_data=['time'=>time(),'num'=>rand(10000,99999)];
			// $is_pushed = Queue::later($i*2,$job_handler_classname, $job_data, $job_queue_name);
			$is_pushed = Queue::push($job_handler_classname, $job_data, $job_queue_name);
			dump($is_pushed);
			$model->fire(new \think\queue\Job,$job_data);
		}
		echo 'ok';

	}
}