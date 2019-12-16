<?php
/**
 * Created by Xiami.
 * User: beinianxiaoyao@163.com
 * Desc: app\common\service 服务类测试
 * Date: 2019/12/15 0015
 * Time: 10:44
 */
namespace  app\api\controller;
use app\common\Service\Math;
use app\common\Service\Date;
use app\common\Service\File;
use app\common\Service\Image;
use app\common\Service\Video;
use app\common\Service\Random;
use app\common\Service\Str;
class Test{
    public function math(Math $math){
        dump($math->power(12,3));
        dump($math->powerRoot(5,2));
    }
    public function image(Image $image){

    }
    public function video(Video $video){
        $video_arr=[
           ROOT_PATH.'/uploads/video/001.mp4',
           ROOT_PATH.'/uploads/video/002.mp4',
        ];
        $video->transcode($video_arr[0],'new-mp41','wmv',240,480);
//        dump($video->contactVideo($video_arr,ROOT_PATH.'/new.m3u8'));
    }

}