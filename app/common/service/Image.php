<?php
/**
 * Created by Xiami.
 * User: beinianxiaoyao@163.com
 * Desc: 图片处理类
 * Date: 2019/12/13 0014
 * Time: 12:36
 */
namespace app\common\service;
use Intervention\Image\ImageManagerStatic as  Img;
class Image{






    public function test($imgpath,$waterpath,$newpath){
        //创建一个空图片资源
        $img = Image::canvas(32, 32, '#ff0000');
        //图片备份
        $img->backup();
        //图片返回到备份状态
        $img->reset();
        //图像填充
        $img->fill('tile.png');
        $img->fill('#ff00ff', 0, 0);
        //在指定位置画圆
        $img->circle(10, 100, 100, function ($draw) {
            $draw->background('#0000ff');
            $draw->border(1, '#f00');
        });
        //在指定位置画椭圆
        $img->ellipse(60, 120, 100, 100, function ($draw) {
            $draw->background('#0000ff');
            $draw->border(1, '#ff0000');
        });
        //在指定位置绘制矩形
        $img->rectangle(5, 5, 195, 195, function ($draw) {
            $draw->background('rgba(255, 255, 255, 0.5)');
            $draw->border(2, '#000');
        });
        //多点绘制多边形
        $points = array(
            40,  50,  // Point 1 (x, y)
            20,  240, // Point 2 (x, y)
            60,  60,  // Point 3 (x, y)
            240, 20,  // Point 4 (x, y)
            50,  40,  // Point 5 (x, y)
            10,  10   // Point 6 (x, y)
        );
        $img->polygon($points, function ($draw) {
            $draw->background('#0000ff');
            $draw->border(1, '#ff0000');
        });
        //将文字写入图像
        $img->text('foo', 0, 0, function($font) {
            $font->file('foo/bar.ttf');
            $font->size(24);
            $font->color('#fdf6e3');
            $font->align('center');
            $font->valign('top');
            $font->angle(45);
        });
        //在指定两点之间连线
        $img->line(10, 10, 100, 10, function ($draw) {
            $draw->color('#0000ff');
        });
        //在指定点绘色
        $img->pixel('#0000ff', 32, 32);
        //将图像像素化
        $img->pixelate(12);
        //打开一账图片
        $img = Img::make($imgpath);
        //隔行扫描  true-打开  false-关闭
        $img->interlace(true);
        //获取图片大小
        $size=$img->filesize();
        //获取图片mine类型
        $img->mime();
        //获取图片高度
        $img->height();
        //获取图片宽度
        $img->width();
        //获取图片某一点颜色
        $hexcolor = $img->pickColor(100, 100, 'hex');
        //图像模糊 0-100默认1
        $img->blur(15);
        //图像亮度 -100 100  默认0
        $img->brightness(35);
        //图像对比度 -100 100  默认0
        $img->contrast(65);
        //图像颜色调整 介于-100 100 之间
        $img->colorize(-100, 0, 100);
        //设置图像不透明度 100-0
        $img->opacity(12);
        //图片锐化处理
        $img->sharpen(15);
        //将图片转化为灰度版本
        $img->greyscale();
        //反转当前图像的所有颜色。
        $img->invert();
        //自动调整图像方向
        $img->orientate();
        //修改图片尺寸
        $img->resize(320, 240);
        //调整图像边界大小
        $img->resizeCanvas(1280, 720, 'center', false, 'ff00ff');
        //裁剪图片
        $img->crop(100, 100, 25, 25);
        //合并裁剪并调整大小，最佳比例裁剪
        $img->fit(800, 600, function ($constraint) {
            $constraint->upsize();
        });
        //修剪掉图片的一部分
        trim('top-left', null, 40);
        //图片增高
        $img->heighten(100, function ($constraint) {
            $constraint->upsize();
        });
        //图片增宽
        $img->widen(300, function ($constraint) {
            $constraint->upsize();
        });
        //图片逆时针度数旋转，未覆盖区域填充背景色
        $img->rotate(-45,'#ffffff');
        //图片翻转 v-垂直 h-水平
        $img->flip('v');
        //添加水印
        $img->insert($waterpath,'bottom-right',10,10);
        //图片质量编码转码
        $img->encode('jpg', 75);
        //图片缓存
        Image::cache(function($img) {$img->resize(300, 200)->greyscale();}, 10, true);
        //保存图片
        $img->save($newpath,100,'jpg');
        //销毁实例
        $img->destroy();
    }
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