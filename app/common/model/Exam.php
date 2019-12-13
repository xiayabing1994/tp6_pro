<?php
namespace app\common\model;

use think\Model;



class Exam extends Model{


    protected $pk='e_id';
	public function questions(){
		return $this->hasMany('Question','q_exam_id');
	}
}