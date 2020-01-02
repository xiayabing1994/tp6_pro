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
use FFMpeg\FFProbe;
use FFMpeg\Coordinate\Point;
use FFMpeg\Format\Audio\Mp3;
use FFMpeg\Format\Video\Ogg;
use FFMpeg\Format\Video\WMV;
use FFMpeg\Format\Video\X264;
use FFMpeg\Format\Video\WebM;
use FFMpeg\Coordinate\TimeCode;
use FFMpeg\Coordinate\FrameRate;
use FFMpeg\Coordinate\Dimension;
use FFMpeg\Filters\Video\ResizeFilter;

class Video{
    private $conf=null;
    private $video=null;
    private $format=null;
    public  $handler=null;
    //所有方法测试 https://blog.csdn.net/a9925/article/details/80334700
    public function __construct($videopath=''){
        $this->conf=[
            'ffmpeg.binaries'  => 'C:\Users\Administrator\Desktop\ffmpeg\bin\ffmpeg.exe',
            //'ffmpeg.binaries' => '/usr/local/bin/ffmpeg',
            'ffprobe.binaries' => 'C:\Users\Administrator\Desktop\ffmpeg\bin\ffprobe.exe',
            //'ffprobe.binaries' => '/usr/local/bin/ffprobe',
        ];
        $this->handler= FFMpeg::create($this->conf);
        $this->format= new X264();
        if($videopath) $this->video=$this->handler->open($videopath);
    }

    /**
     * 拼接视频(暂不可用)
     * @param $video_arr
     * @param $newpath
     */
    public function  contactVideo($video_arr,$newpath){
        return $this->video->concat($video_arr)->saveFromSameCodecs($newpath, TRUE);
    }

    /**
     * 截取视频封面(可用)
     * @param $video          视频路径
     * @param string $path    封面保存路径
     * @param $seconds        截取的秒数
     * @return string         返回图片路径地址
     */
    public function thumb($path='thumb.jpg',$seconds){
        $this->video
            ->frame(TimeCode::fromSeconds($seconds))
            ->save($path);
        return $path;
    }

    /**
     * 视频转码(可用)  目前格式较少,可用下各个方法 transcodeFormat
     * @param $name          转码视频名字
     * @param string $format 转码格式
     * @param int $width     转码视频宽度
     * @param int $height    转码视频高度
     */
    public function transcode($newpath,$format='mp4',$width=0,$height=0){
        if($width && $height){
            $this->video->filters()->resize(new Dimension($width,$height))->synchronize();
        }
        switch($format){
            case 'wmv':
                $model=new  WMV();
            case 'webm':
                $model=new WebM();
            default:
                $model=new X264();
        }
        return $this->video->save($model,$newpath);
    }

    /**
     * 视频格式转化(可用)   首选
     * @param $newpath           视频地址 eg: t.avi t.flv
     * @param $bite_rate         视频比特率(kbps)
     * @param $audio_channels    声道 1单声道 2双声道 3立体声
     * @param $audio_bite_rate   音频比特率
     */
    public function transcodeFormat($newpath,$bite_rate=1000,$audio_bite_rate=100,$audio_channels=2){
        $this->format->setKiloBitrate($bite_rate)->setAudioChannels($audio_channels)->setAudioKiloBitrate($audio_bite_rate);
        $this->format->on('progress', function ($video, $format, $percentage) {
            echo "$percentage % 进度";
        });
        $this->video->save($this->format, $newpath);
    }
    /**
     * 视频提取音频(可用)
     * @param $videopath   视频路径
     * @param $audiopath   音频保存路径
     * @return \FFMpeg\Media\Audio|\FFMpeg\Media\Video
     */
    public function transferAudio($audiopath){
        $audio_format = new Mp3();
        $this->video->save($audio_format, $audiopath);
        return $audiopath;
    }
    /**
     * 为视频添加水印(可用)
     * @param $iamgepath  水印图片路径png,必须传相对路径否则报错
     * @param $newpath    视频保存路径
     * @param $x          距左上角横向距离:px
     * @param $y          距左上角纵向距离:px
     */
    public function waterMark($iamgepath,$newpath,$x=10,$y=10){
        $absolute = ['x' => $x,'y' => $y];
        $this->video->filters()->watermark($iamgepath, $absolute);
        return $this->video->save($this->format, $newpath);
    }
    /**
     * 调整视频大小(可用)  调整后大小变化不大
     * @param $newpath    新视频路径
     * @param $width      新视频宽度
     * @param $height     新视频高度
     * @return \FFMpeg\Media\Audio|\FFMpeg\Media\Video
     */
    public function resize($newpath,$width,$height){
        $this->video->filters()->resize(new Dimension($width,$height), ResizeFilter::RESIZEMODE_FIT, true);
        return $this->video->save($this->format, $newpath);
    }

    /**
     * 调整视频帧率(可用,时间较长)
     * @param $newpath  新视频路径
     * @param $rate     帧率  eg: 30帧/s
     * @param gop       两个I帧之间的间隔
     */
    public function framerate($newpath,$rate,$gop=100){
        $this->video->filters()->framerate(new FrameRate($rate), $gop);
        return $this->video->save($this->format, $newpath);

    }

    /**
     * 截取视频(可用)
     * @param $newpath       新视频路径
     * @param $from          截取开始秒数
     * @param null $length   截取长度必须要传
     */
    public function clipVideo($newpath,$start,$length=null){
        $this->video->filters()->clip(TimeCode::fromSeconds($start),TimeCode::fromSeconds($length));
        return $this->video->save($this->format, $newpath);
    }

    /**
     * 从视频中截取gif动图(可用)   宽高会自适应
     * @param $gifpath       图片保存地址
     * @param $start         开始截取的秒数
     * @param $length        截取的长度
     * @param $width         图片的宽度
     * @param $height        图片的高度
     * @return \FFMpeg\Media\Gif
     */
    public function videoGif($gifpath,$start,$length,$width=200,$height=400){
       $this->video->gif(TimeCode::fromSeconds($start), new Dimension($width, $height), $length)->save($gifpath);
       return $gifpath;
    }

    /**
     * 裁剪视频
     * @param $newpath  视频保存路径
     * @param $width    裁剪宽度
     * @param $height   裁剪高度
     */
    public function tailor($newpath,$width,$height){
        //$x 和 $y 为裁剪的起始坐标，$dynamic 为是否动态裁剪  100 为速度，值越大，移动速度越快
        $this->video->filters()->crop(new Point("t*100", 0, true), new \FFMpeg\Coordinate\Dimension($width, $height));
        return $this->video->save($this->format,$newpath);
    }
    /**
     * 获取视频信息(可用)
     * @param $videopath    视频路径
     * @return array        详细信息
     */
    public function getVideoInfo($videopath){
        $handler=FFProbe::create($this->conf);
        $videoinfo=$handler->format($videopath);
//        dump($videoinfo->toArray());
        return [
            'duration'=>$videoinfo->get('duration',100),
            'filename'=>$videoinfo->get('filename',100),
            'size'=>$videoinfo->get('size',100),
            'bit_rate'=>$videoinfo->get('bit_rate',100),  //比特率 b/s
            'tags'=>$videoinfo->get('tags'),
        ];
    }

    /**
     * 添加额外参数
     * @param $param  要添加的参数(ffmpeg 命令行参数)
     * @return bool
     */
    public function addParams($param){
        $this->format->setAdditionalParameters($param);
        return true;
    }

}