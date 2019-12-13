<?php
namespace app\index\job;
use think\queue\Job;
class Dojob {


	public function fire(Job $job,$data){

		file_put_contents('./job.txt', json_encode($data)."\r\n",FILE_APPEND);
		$job->delete();
		echo 111;
	}
	public function test(){
        $data=['name'=>111];
		file_put_contents('./job.txt', json_encode($data),FILE_APPEND);
		// $job->delete();
		echo 111;
	}
}