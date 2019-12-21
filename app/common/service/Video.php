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
            //'ffmpeg.binaries' => '/usr/local/bin/ffmpeg',
            'ffprobe.binaries' => 'C:\Users\Administrator\Desktop\ffmpeg\bin\ffprobe.exe',
            //'ffprobe.binaries' => '/usr/local/bin/ffprobe',
        ]);
    }
    //所有方法测试 https://blog.csdn.net/a9925/article/details/80334700
    public function test(){
        $video_arr=[
            ROOT_PATH.'/uploads/video/001.mp4',
            ROOT_PATH.'/uploads/video/002.mp4',
        ];
        //打开视频操作
        $video = $this->handler->open($video_arr[0]);
        //拼接视频
        $video->concat($video_arr)->saveFromSameCodecs('new.mp4', TRUE);
        //提取图片或封面
        $frame = $video->frame(\FFMpeg\Coordinate\TimeCode::fromSeconds(2))->save('image.jpg');
        //调整视频大小
        $video->filters()
            ->resize(new \FFMpeg\Coordinate\Dimension(200,400), \FFMpeg\Filters\Video\ResizeFilter::RESIZEMODE_FIT, true);
            ->save(new \FFMpeg\Format\Video\X264('libfdk_aac'), '/mnt/hgfs/www/test/v1080_new.mp4');
        //添加视频水印
        $watermarkPath = '/mnt/hgfs/www/test/water.png';
        $absolute = ['x' => 50,'y' => 100];
        $relative = [
            'position' => 'relative',
            'bottom' => 50,
            'right' => 50
        ];
        $video->filters()->watermark($watermarkPath, $absolute);
        $video->save(new \FFMpeg\Format\Video\X264('libfdk_aac'), '/mnt/hgfs/www/test/1080_new.mp4');
        //调整视频帧率  http://blog.csdn.net/xiangjai/article/details/44238005
        $video->filters()->framerate(new \FFMpeg\Coordinate\FrameRate(3000), 120);
        $video->save(new \FFMpeg\Format\Video\X264('libfdk_aac'), '/mnt/hgfs/www/test/1080_new.mp4');
        //截取视频音频
        $video->filters()->clip(\FFMpeg\Coordinate\TimeCode::fromSeconds(10));
        $video->save(new \FFMpeg\Format\Video\X264('libfdk_aac'), '/mnt/hgfs/www/test/1080_new.mp4');
        //裁剪视频 https://www.bilibili.com/video/av17244824/
        $video->filters()->crop(new \FFMpeg\Coordinate\Point("t*100", 0, true), new \FFMpeg\Coordinate\Dimension(960, 540));
        $video->save(new \FFMpeg\Format\Video\X264('libfdk_aac'), '/mnt/hgfs/www/test/1080_new.mp4');
        //提取动图
        $video->gif(\FFMpeg\Coordinate\TimeCode::fromSeconds(10), new \FFMpeg\Coordinate\Dimension(400, 200), 3)->save('1080.gif');
        //格式转化
        $format = new \FFMpeg\Format\Video\X264('libfdk_aac');
        $format->setKiloBitrate(1000)->setAudioChannels(2)->setAudioKiloBitrate(256);
        $format->on('progress', function ($video, $format, $percentage) {
            echo "$percentage % 进度";
        });
        $video->save($format, '/mnt/hgfs/www/test/video.avi');
        //提取图像
        $path='fram.jpg';
        $frame = new \FFMpeg\Media\Frame($video, \FFMpeg\Driver\FFMpegDriver::load($path), \FFMpeg\FFProbe::create($path), \FFMpeg\Coordinate\TimeCode::fromSeconds(10));
        $frame->save('frame.jpg');
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