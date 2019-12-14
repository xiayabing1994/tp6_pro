<?php
/**
 * Created by Xiami.
 * User: beinianxiaoyao@163.com
 * Desc: 图片处理类
 * Date: 2019/12/13 0014
 * Time: 12:36
 */
namespace app\common\service;

class Image{
    /**
     * 大小图合成 例:海报图上放二维码
     * @param $big_img                  大图路径
     * @param $small_img                小图路径
     * @param $newname                  合成图片路径
     * @param int $thumb_width          小图压缩宽度
     * @param int $thumb_height         小图压缩高度
     * @param float $position_x         小图横向相对位置
     * @param float $position_y         小图纵向相对位置
     * @return bool                     返回true
     */
    public function combImage($big_img,$small_img,$newname,$thumb_width=150,$thumb_height=150,$position_x=0.5,$position_y=0.5){

        list($originWidth, $orginHight, $orginType) = getimagesize($small_img);
        //小图压缩
        $small_img = $this->thumb($small_img,$thumb_width,$thumb_height,'small_');
        //获取大图小图
        $bigImg = imagecreatefromstring(file_get_contents($big_img));
        $smallImg = imagecreatefromstring(file_get_contents($small_img));
        //获取大图小图宽高类型
        list($bigWidth, $bigHight, $bigType) = getimagesize($big_img);
        list($smallWidth, $smallHight, $smallType) = getimagesize($small_img);
        //获取放置位置的横纵坐标
        $start_x=($bigWidth-$smallWidth)*$position_x;
        $start_y=($bigHight-$smallHight)*$position_y;
        // imagecopymerge使用注解
        imagecopymerge($bigImg, $smallImg, $start_x, $start_y, 0, 0, $smallWidth, $smallHight, 100);
        switch ($bigType) {
            case 1: //gif
                header('Content-Type:image/gif');
                imagegif($bigImg,$newname.'.gif');
                break;
            case 2: //jpg
                header('Content-Type:image/jpg');
                imagejpeg($bigImg,$newname.'.jpg');
                break;
            case 3: //png
                 header('Content-Type:image/png');
                imagepng($bigImg,$newname.'.png');
                break;
            default:
                # code...
                break;
        }
        imagedestroy($bigImg);
        imagedestroy($smallImg);
        unlink($small_img);  //删除压缩的图片
        return true;
    }

    /**
     * 为图片添加文字水印
     * @param $word        水印文字
     * @param int $width   起始位置横坐标(以左上角为基础)
     * @param int $height  起始位置纵坐标(以左上角为基础)
     * @param string $bg_color   文字颜色
     * @param string $font  文字样式
     * @return bool         返回true
     */
    public function wordImage($word,$width=200,$height=100,$bg_color='255,255,255',$font = "arial.ttf"){
        header('content-type:image/png');
        $bg_color=explode(',',$bg_color);
        // var_dump($bg_color);die;
        $img = imagecreatetruecolor($width,$height);
        imagesavealpha($img,true);
        //创建颜色
        $color =imagecolorallocate($img,$bg_color[0],$bg_color[1],$bg_color[2]);
        $color = imagecolorallocatealpha($img, 255, 255, 255,127);
        //填充区域
        imagefill($img,0,0,$color);
        $color = imagecolorallocate ($img,0,0,0);
        //输出文字
        imagettftext($img, 20,0, 10, 50, $color, $font,$word);
        //输出画布
        imagepng($img);
        //销毁图像（释放占用的资源）
        imagedestroy($img);
        return true;
    }

    /**
     * 压缩图片
     * @param $file   源文件
     * @param $dw     压缩的宽度
     * @param $dh     压缩的高度
     * @param $pre    文件前缀
     * @return string 缩略图路径
     */
    public function  thumb($file,$dw,$dh,$pre)
    {
        $brr=pathinfo($file);
        $dir=$brr['dirname']; //图片保存路径
        $basename=$brr['basename'];//图片名字
        $dstname=$pre.$basename;  //目标图片名字
        $path=$dir.'/'.$dstname;//目标图片保存轮径
        $arr=getimagesize($file);  //获得图片信息
        $sw=$arr[0];  //原图宽
        $sh=$arr[1];  //原图的高
        $type=$arr[2];  //图片格式  1 = GIF，2 = JPG，3 = PNG
        $mime=$arr['mime'];  //MIME 类型

        switch ($type) {
            case 1:
                $imgcreate='imagecreatefromgif';
                $imgout='imagegif';
                break;
            case 2:
                $imgcreate='imagecreatefromjpeg';
                $imgout='imagejpeg';
                break;
            case 3:
                $imgcreate='imagecreatefrompng';
                $imgout='imagepng';
                break;
        }
        $src=$imgcreate($file);
        $b=$sw/$dw>$sh/$dh?$sw/$dw:$sh/$dh;
        $dw=floor($sw/$b);
        $dh=floor($sh/$b);
        $dst=imagecreatetruecolor($dw,$dh);
        $bool=imagecopyresampled($dst, $src, 0, 0 , 0 , 0, $dw , $dh , $sw, $sh);
        $imgout($dst,$path);
        return  $path;
    }
}