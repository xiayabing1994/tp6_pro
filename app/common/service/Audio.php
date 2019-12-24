<?php
namespace app\common\service;
/**
 * Created by Xiami.
 * User: beinianxiaoyao@163.com
 * Desc: 这是文件说明
 * Date: 2019/12/22 0022
 * Time: 16:10
 */
use FFMpeg\FFMpeg;
use Ratchet\Wamp\Exception;

class Audio{

    public $handler;
    public $audio;
    public function __construct($audiopath){
        $this->handler= FFMpeg::create([
            'ffmpeg.binaries'  => 'C:\Users\Administrator\Desktop\ffmpeg\bin\ffmpeg.exe',
            //'ffmpeg.binaries' => '/usr/local/bin/ffmpeg',
            'ffprobe.binaries' => 'C:\Users\Administrator\Desktop\ffmpeg\bin\ffprobe.exe',
            //'ffprobe.binaries' => '/usr/local/bin/ffprobe',
        ]);
        $this->audio=$this->handler->open($audiopath);
    }
    public function test(){
        $audio_arr=[
            ROOT_PATH.'\uploads\video\001.mp3',
            ROOT_PATH.'/uploads/video/002.mp3',
        ];
        $newFile =  ROOT_PATH.'/uploads/video/new.mp3';
        $audio = $this->handler->open($audio_arr[0]);
        //1、拼接音频
        $audio->concat($audio_arr)->saveFromSameCodecs($newFile, TRUE);
        //2、生成音频波形
        $waveform = $audio->waveform(640, 120, array('#00FF00'));
        $waveform->save('waveform.png');//必须保存为 png 格式
        //3、截取音频
        $audio->filters()->clip(\FFMpeg\Coordinate\TimeCode::fromSeconds(10));
        $audio->save(new \FFMpeg\Format\Video\X264('libfdk_aac'), $newFile);
        //4、音频转换 Flac 为无损压缩格式  setAudioChannels 声道设置，1单声道，2双声道，3立体声    setAudioKiloBitrate 比特率
        $format = new \FFMpeg\Format\Audio\Flac();
        $format->on('progress', function ($audio, $format, $percentage) {
            echo "$percentage % 进度";
        });
        $format->setAudioChannels(2)->setAudioKiloBitrate(256);
        $audio->save($format, ROOT_PATH.'/uploads/video/new.flac');
        //5.音频添加元素  title(标题)，artist(艺术家)，album(专辑)，  artwork(艺术作品)
        $audio->filters()->addMetadata([
            "title" => "Test Title",
            "artist" => "Jam00 artist",
            "album" => "Test album", //专辑
            "composer" => "Jam00",   //作曲家
            "track" => 1,             //轨道
            "year" => 2017,           //年份
            "description" => "jam00 test description",  //描述
        ]);
        $audio->save(new \FFMpeg\Format\Audio\Mp3, $newFile);

    }
    public function  Contact($audio_arr,$newfile){
        return $this->audio->concat($audio_arr)->saveFromSameCodecs($newfile, TRUE);
    }
    public function wavePng($pngfile){
        $waveform = $this->audio->waveform(640, 120, array('#00FF00'));
        $waveform->save($pngfile);//必须保存为 png 格式
    }
    public function clip($newfile,$start,$length=0){
        $this->audio->filters()->clip(\FFMpeg\Coordinate\TimeCode::fromSeconds($start));
        $this->audio->save(new \FFMpeg\Format\Video\X264('libfdk_aac'), $newfile);
    }


}

