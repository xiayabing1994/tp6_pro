<?php
/**
 * Created by Xiami.
 * User: beinianxiaoyao@163.com
 * Desc: app\common\service 服务类测试
 * Date: 2019/12/15 0015
 * Time: 10:44
 */
namespace  app\api\controller;
use app\common\service\Audio;
use app\common\Service\Math;
use app\common\Service\Date;
use app\common\Service\File;
use app\common\Service\Image;
use app\common\Service\Video;
use app\common\Service\Random;
use app\common\Service\Socket;
use app\common\Service\Str;
class Test{
    public function math(Math $math){
        dump($math->power(12,3));
        dump($math->powerRoot(5,2));
    }
    public function image(Image $image){
        dump($image->test('thumb.png','watermark.png','bar.png'));
    }
    public function video(Video $video){
        $video_arr=[
            ROOT_PATH.'/uploads/video/001.mp4',
            ROOT_PATH.'/uploads/video/002.mp4',
        ];
        $video->thumb($video_arr[0],'thumb.png',2);
        $video->transcode($video_arr[0],'new-mp41','wmv',240,480);
//        dump($video->contactVideo($video_arr,ROOT_PATH.'/new.m3u8'));
    }
    public function audio(){
        $audio_arr=[
            ROOT_PATH.'/uploads/video/001.mp3',
           ROOT_PATH.'/uploads/video/002.mp3',
        ];
        $newfile =  ROOT_PATH.'/uploads/video/new'.rand(1000,9999).'.mp3';
        $audio=new Audio($audio_arr[0]);
        dump($audio->wavePng(ROOT_PATH.'/uploads/video/nwave.png'));
        dump($audio->clip($newfile,10,10));
        dump($audio->Contact($audio_arr,$newfile));
    }
    public function socket(Socket $socket){
        $socket->getConn();
    }

}