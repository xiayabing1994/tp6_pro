<?php
/**
 * Created by Xiami.
 * User: beinianxiaoyao@163.com
 * Desc: 视频处理类
 * Date: 2019/12/14 0014
 * Time: 18:32
 */
namespace  app\common\service;
use FFMpeg\FFMpeg;

class Video{

    public $handler;
    public function __construct(){
        $this->handler= FFMpeg::create([
            'ffmpeg.binaries'  => 'C:\Users\Administrator\Desktop\ffmpeg\bin\ffmpeg.exe',
//            'ffmpeg.binaries' => '/usr/local/bin/ffmpeg',
            'ffprobe.binaries' => 'C:\Users\Administrator\Desktop\ffmpeg\bin\ffprobe.exe',
//            'ffprobe.binaries' => '/usr/local/bin/ffprobe',
        ]);
    }

    /**
     * 拼接视频
     * @param $video_arr
     * @param $newpath
     */
    public function  contactVideo($video_arr,$newpath){
        $video = $this->handler->open($video_arr[0]);
        $video->concat($video_arr)->saveFromSameCodecs($newpath, TRUE);
    }

    /**
     * 截取视频封面
     * @param $video          视频路径
     * @param string $path    封面保存路径
     * @param $seconds        截取的秒数
     * @return string         返回图片路径地址
     */
    public function thumb($video,$path='thumb.jpg',$seconds){
        $video=$this->handler->open($video);

        $video
            ->frame(\FFMpeg\Coordinate\TimeCode::fromSeconds($seconds))
            ->save($path);
        return $path;

    }

    /**
     * 视频转码
     * @param $video         原视频地址
     * @param $name          转码视频名字
     * @param string $format 转码格式
     * @param int $width     转码视频宽度
     * @param int $height    转码视频高度
     */
    public function transcode($video,$name,$format='mp4',$width=0,$height=0){
        $video=$this->handler->open($video);
        if($width && $height){
            $video->filters()->resize(new \FFMpeg\Coordinate\Dimension($width,$height))->synchronize();
        }
        switch($format){
            case 'wmv':
                $model=new  \FFMpeg\Format\Video\WMV();
            case 'webm':
                $model=new \FFMpeg\Format\Video\WebM();
            default:
                $model=new \FFMpeg\Format\Video\X264();
        }
        $video->save($model,$name.'.'.$format);
    }

}