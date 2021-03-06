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
    public function __construct()
    {
        set_time_limit(0);
    }

    public function math(Math $math){
        dump($math->power(12,3));
        dump($math->powerRoot(5,2));
    }
    public function image(Image $image){
        dump($image->test('thumb.png','watermark.png','bar.png'));
    }

    /**
     * 视频处理类相关测试
     */
    public function video(){
        $thumbpath=ROOT_PATH."/uploads/video/thumb.png";
        $gifpath=ROOT_PATH."/uploads/video/gif.gif";
        $newpath=ROOT_PATH."/uploads/video/new.mp4";
        $video_arr=[
            ROOT_PATH.'/uploads/video/001.mp4',
            ROOT_PATH.'/uploads/video/002.mp4',
        ];
        $video_arr=[
            ROOT_PATH.'./uploads/video/001.mp4',
            ROOT_PATH.'./uploads/video/002.mp4',
        ];
        $contact_video_arr=[
            './uploads/video/001.mp4',
            './uploads/video/002.mp4',
        ];
        $video=new Video($video_arr[0]);
//        dump($video->getVideoInfo($video_arr[0]));
//        dump($video->transcodeFormat(ROOT_PATH."/uploads/video/transcodeFormat.avi",900,80));
//        dump($video->tailor(ROOT_PATH."/uploads/video/tailor.mp4",200,300));
//        dump($video->contactVideo($contact_video_arr,ROOT_PATH."/uploads/video/contact.mp4"));
//        dump($video->thumb(ROOT_PATH."/uploads/video/thumb.png",9));
//        dump($video->transcode(ROOT_PATH."/uploads/video/transcode.wmv",'wmv',544,960));
//        dump($video->transferAudio(ROOT_PATH."/uploads/video/transferaudio.mp3"));
//        dump($video->waterMark("./uploads/video/water.png",ROOT_PATH."/uploads/video/water.mp4",10,10));
//        dump($video->resize(ROOT_PATH."/uploads/video/resize.mp4",200,300));
//        dump($video->videoGif(ROOT_PATH."/uploads/video/gif.gif",12,3,200,300));
//        dump($video->framerate(ROOT_PATH."/uploads/video/framerate60.mp4",120));
//        dump($video->clipVideo(ROOT_PATH."/uploads/video/clip.mp4",15,6));
    }
    public function audio(){
        $audio_arr=[
            ROOT_PATH.'/uploads/audio/001.mp3',
           ROOT_PATH.'/uploads/audio/002.mp3',
//           ROOT_PATH.'/uploads/audio/transfer.aac',
        ];
        $contact_audio_arr=[
            './uploads/audio/001.mp3',
            './uploads/audio/002.mp3',
//           ROOT_PATH.'/uploads/audio/transfer.aac',
        ];
        $audio=new Audio($audio_arr[0]);
//        dump($audio->addMetadata(ROOT_PATH.'/uploads/audio/elements.aac',[
//            'title'=>'piano of xia',
//            "artist" => "虾米",
//            "album" => "第几个2020", //专辑
//            "composer" => "xiami",   //作曲家
//            "track" => 1,             //轨道
//            "year" => 2020,           //年份
//            "description" => "我的2020专辑",  //描述
//        ]));
//        dump($audio->clip(ROOT_PATH.'/uploads/audio/clip.mp3',10,10));
//        dump($audio->transfer(ROOT_PATH.'/uploads/audio/transfer.aac',300,3));
//        dump($audio->transfer(ROOT_PATH.'/uploads/audio/transfer.flac',500,2));
//        dump($audio->transfer(ROOT_PATH.'/uploads/audio/transfer.wav',150,1));
//        dump($audio->Contact($contact_audio_arr,ROOT_PATH.'/uploads/audio/contact.mp3'));
    }
    public function socket(Socket $socket){
        $socket->getConn();
    }

}