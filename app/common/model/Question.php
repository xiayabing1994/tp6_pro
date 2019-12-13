<?php
namespace app\common\model;

use think\Model;

class Question extends Model{

    protected $pk='q_id';

    public function comment(){
        return $this->hasMany('Comment','c_question_id');
    }


}