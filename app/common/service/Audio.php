<?php
namespace app\common\service;
/**
 * Created by Xiami.
 * User: beinianxiaoyao@163.com
 * Desc: 音频服务类
 * Date: 2019/12/22 0022
 * Time: 16:10
 */
use FFMpeg\FFMpeg;
use Ratchet\Wamp\Exception;
use FFMpeg\Format\Audio\Mp3;
use FFMpeg\Format\Audio\Aac;
use FFMpeg\Format\Audio\Wav;
use FFMpeg\Format\Audio\Flac;
use FFMpeg\Coordinate\TimeCode;

class Audio{

    public $handler;
    public $audio;
    public function __construct($audiopath){
        $this->handler= FFMpeg::create([
            'ffmpeg.binaries'  => 'C:\Users\Administrator\Desktop\ffmpeg\bin\ffmpeg.exe',
//            'ffmpeg.binaries'  => 'C:\Users\Administrator\Desktop\ffmpeg\bin\ffplay.exe',
            //'ffmpeg.binaries' => '/usr/local/bin/ffmpeg',
            'ffprobe.binaries' => 'C:\Users\Administrator\Desktop\ffmpeg\bin\ffprobe.exe',
            //'ffprobe.binaries' => '/usr/local/bin/ffprobe',
        ]);
        $this->audio=$this->handler->open($audiopath);

    }
    /**
     * 合并音频(可用)
     * @param $audio_arr       音频数组必须传相对路径
     * @param $newfile         保存路径
     * @return \FFMpeg\Media\Concat
     */
    public function  Contact($audio_arr,$newfile){
        return $this->audio->concat($audio_arr)->saveFromSameCodecs($newfile, TRUE);
    }

    /**
     * 生成音频波谱图(可用)
     * @param $pngfile         图片路径
     * @param int $width       图片宽度
     * @param int $height      图片高度
     * @param array $color     波谱颜色 ps:暂时没用
     * @return mixed
     */
    public function wavePng($pngfile,$width=800,$height=200,$color=['#00FF00']){
        $waveform = $this->audio->waveform($width, $height, $color);
        $waveform->save($pngfile);//必须保存为 png 格式
        return $pngfile;
    }

    /**
     * 音频裁剪(可用)           MP3格式的好像不支持,会调用video类,其他格式可以
     * @param $newfile        音频保存路径
     * @param $start          截取开始秒数
     * @param int $length     截取长度(s)
     */
    public function clip($newfile,$start,$length=null){
        $this->audio->filters()->clip(TimeCode::fromSeconds($start),TimeCode::fromSeconds($length));
        dump($this->audio);
        $this->audio->save(new Mp3(), $newfile);
    }

    /**
     * 音频格式转化(可用)
     * @param $newfile            保存路径
     * @param $audio_bite_rate    音频比特率 kbps
     * @param int $audio_channel  声道 1单声道 2双声道 3立体声
     */
    public function transfer($newfile,$audio_bite_rate,$audio_channel=2){
        $format=strtolower(pathinfo($newfile, PATHINFO_EXTENSION));
        switch($format){
            case 'aac' :
                $audioModel=new Aac();break;
            case 'wav' :
                $audioModel=new Wav();break;
            case 'flac':
                $audioModel=new Flac();break;
            default:
                $audioModel=new Mp3();
        }
        $audioModel->on('progress', function ($audio, $format, $percentage) {
            echo "$percentage % 进度";
        });
        $audioModel->setAudioChannels($audio_channel)->setAudioKiloBitrate($audio_bite_rate);
        $this->audio->save($audioModel, $newfile);
    }

    /**
     * 为音频添加元素信息(可用,但测试没啥效果)
     * @param $newfile
     * @param $elements
     * @return bool
     */
    public function addMetadata($newfile,$elements){
        $example_data=[
            "title" => "Test Title",
            "artist" => "Jam00 artist",
            "album" => "Test album", //专辑
            "composer" => "Jam00",   //作曲家
            "track" => 1,             //轨道
            "year" => 2017,           //年份
            "description" => "jam00 test description",  //描述
        ];
        $format=strtolower(pathinfo($newfile, PATHINFO_EXTENSION));
        switch($format){
            case 'aac' :
                $audioModel=new Aac();break;
            case 'wav' :
                $audioModel=new Wav();break;
            case 'flac':
                $audioModel=new Flac();break;
            default:
                $audioModel=new Mp3();
        }

        $this->audio->filters()->addMetadata($elements);
        $this->audio->save($audioModel, $newfile);
        return true;
    }


}

