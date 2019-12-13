<?php

namespace app\index\controller;
use app\facade\User;
use app\common\model\Exam;
use app\common\model\Test;
class Index{
     

     // protected $middleware=['crossdomain'];

     public function  index(Exam $exam){
     	//1.facade门面调用  user->app\index\controller\index  注册地址:app\facade\User    输出:1111
     	///echo User::test();
     	$exam=Exam::find(1);
     	 dump($exam->questions());
     	 foreach($exam->questions as $k=>$v ){
     	     dump(json_encode($v->comment));
         }
         dump($exam->questions->toArray());
     	// dump(Exam::get(1));
     }

     public function test(){
     	return 111;
     }
     public function testModel(){
         $data=['name'=>'xiaoming','age'=>'123','password'=>'123456'];
         $test=new Test();
         //会触发修改器
         //$res=$test->save(['name'=>'xiaoming','age'=>'123','password'=>md5('123456')]);
         //不会触发修改器
         $res=$test->data($data);
         $test=new Test($data);
         $res=$test->save();



         dump($res);

     }
}