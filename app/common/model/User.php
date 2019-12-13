<?php

namespace app\common\model;

use think\Model;

/**
 * 会员模型
 */
class User extends Model
{

    // 设置字段信息,减小字段类型查询开销
    protected $schema = [
        'id'          => 'int',
        'name'        => 'string',
        'status'      => 'int',
        'score'       => 'float',
        'create_time' => 'datetime',
        'update_time' => 'datetime',
    ];
    // 设置废弃字段
    protected $disuse = [ 'status', 'type' ];
    // 开启自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';
    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';
    // 追加属性
    protected $append = [
        'url',
    ];
    public function  setPasswordAttr($value){
        if(strlen($value)==32) return $value;
        return md5($value);
    }

}
