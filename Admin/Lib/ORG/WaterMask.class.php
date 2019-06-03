<?php
 
/**
 * 图片加水印类，支持文字水印、透明度设置、自定义水印位置等。
 * 使用示例：
 *      $obj = new WaterMask($imgFileName);              //实例化对象
 *      $obj->$waterType = 1;                     //类型：0为文字水印、1为图片水印
 *      $obj->$transparent = 45;                  //水印透明度
 *      $obj->$waterStr = 'icp.niufee.com';               //水印文字
 *      $obj->$fontSize = 18;                     //文字字体大小
 *      $obj->$fontColor = array(255,255,255);                //水印文字颜色（RGB）
 *      $obj->$fontFile = 'AHGBold.ttf';              //字体文件
 *      ……
 *      $obj->output();                            //输出水印图片文件覆盖到输入的图片文件
 * @modify liuzp111
 */
class WaterMask{
    public  $waterTypeImage     = false;                                //水印类型：启用图片水印
    public  $waterTypeStr       = false;                   //水印类型：启用文字水印
    public  $pos                = 0;                   //水印位置
    public  $transparent        = 45;                  //水印透明度(0---100)数值越大越不透明
    
    public  $waterStr           = 'icp.niufee.com';            //水印文字
    public  $fontSize           = 14;                  //文字字体大小
    public  $fontColor          = array(0,0,0);                    //水印文字颜色（RGB） 默认黑色
    public  $fontFile           = './font/simfang.ttf';            //字体文件
    
    public  $waterImg           = 'logo.png';              //水印图片
    
    private $srcImg             = '';                  //需要添加水印的图片
    private $im                 = '';                  //图片句柄
    private $water_im           = '';                  //水印图片句柄
    private $srcImg_info        = '';                  //图片信息
    private $waterImg_info      = '';                  //水印图片信息
    private $str_w              = '';                  //水印文字宽度
    private $str_h              = '';                  //水印文字高度
    private $x                  = '';                  //水印X坐标
    private $y                  = '';                  //水印y坐标
    public  $output_img         = '';                                    //存储输出图片到哪里
    public  $is_draw_rectangle  = false;                                    //是否绘制矩形区域  （暂不支持自定义位置）
    //public $rectange_color      = '';                                    //绘制矩形区域的颜色
    private $result_array       =  array();                              //结果数组
    public  function __construct($img) {        //析构函数
        //$this->srcImg = file_exists($img) ? $img : die('"'.$img.'" 源文件不存在！');
        if(file_exists($img)){
            $this->srcImg = $img;
        }else{
            return array('data'=>'','info'=>'源文件不存在!','status'=>0);
        }
    }
    
    private function imginfo() {    //获取需要添加水印的图片的信息，并载入图片。
        $this->srcImg_info = getimagesize($this->srcImg);
        switch ($this->srcImg_info[2]) {
            case 3:
                $this->im = imagecreatefrompng($this->srcImg);
                break 1;
            case 2:
                $this->im = imagecreatefromjpeg($this->srcImg);
                break 1;
            case 1:
                $this->im = imagecreatefromgif($this->srcImg);
                break 1;
            default:
                //die('原图片（'.$this->srcImg.'）格式不对，只支持PNG、JPEG、GIF。');
                return array('data'=>'','info'=>'原图片（'.$this->srcImg.'）格式不对，只支持PNG、JPEG、GIF。','status'=>0);
        }
    }
    
    private function waterimginfo() {   //获取水印图片的信息，并载入图片。
        $this->waterImg_info = getimagesize($this->waterImg);
        switch ($this->waterImg_info[2]) {
            case 3:
                $this->water_im = imagecreatefrompng($this->waterImg);
                break 1;
            case 2:
                $this->water_im = imagecreatefromjpeg($this->waterImg);
                break 1;
            case 1:
                $this->water_im = imagecreatefromgif($this->waterImg);
                break 1;
            default:
                //die('水印图片（'.$this->srcImg.'）格式不对，只支持PNG、JPEG、GIF。');
                return array('data'=>'','info'=>'水印图片（'.$this->srcImg.'）格式不对，只支持PNG、JPEG、GIF。','status'=>0);
        }
    }
    private function waterpos() {   //水印位置算法
        switch ($this->pos) {
            case 0:     //随机位置
                $this->x = rand(0,$this->srcImg_info[0]-$this->waterImg_info[0]);
                $this->y = rand(0,$this->srcImg_info[1]-$this->waterImg_info[1]);
                break 1;
            case 1:     //上左
                $this->x = 0;
                $this->y = 0;
                break 1;
            case 2:     //上中
                $this->x = ($this->srcImg_info[0]-$this->waterImg_info[0])/2;
                $this->y = 0;
                break 1;
            case 3:     //上右
                $this->x = $this->srcImg_info[0]-$this->waterImg_info[0];
                $this->y = 0;
                break 1;
            case 4:     //中左
                $this->x = 0;
                $this->y = ($this->srcImg_info[1]-$this->waterImg_info[1])/2;
                break 1;
            case 5:     //中中
                $this->x = ($this->srcImg_info[0]-$this->waterImg_info[0])/2;
                $this->y = ($this->srcImg_info[1]-$this->waterImg_info[1])/2;
                break 1;
            case 6:     //中右
                $this->x = $this->srcImg_info[0]-$this->waterImg_info[0];
                $this->y = ($this->srcImg_info[1]-$this->waterImg_info[1])/2;
                break 1;
            case 7:     //下左
                $this->x = 0;
                $this->y = $this->srcImg_info[1]-$this->waterImg_info[1];
                break 1;
            case 8:     //下中
                $this->x = ($this->srcImg_info[0]-$this->waterImg_info[0])/2;
                $this->y = $this->srcImg_info[1]-$this->waterImg_info[1];
                break 1;
            case 9:     //下中偏上100px
                $this->x = ($this->srcImg_info[0]-$this->waterImg_info[0])/2;
                $this->y = $this->srcImg_info[1]-$this->waterImg_info[1] - 100;
                break 1;
            default:    //下右
                $this->x = $this->srcImg_info[0]-$this->waterImg_info[0];
                $this->y = $this->srcImg_info[1]-$this->waterImg_info[1];
                break 1;
        }
    }
    /**
     * 水印文字图片位置，根据需求调整
     */
    private function waterposStr() {
        $this->x = ($this->srcImg_info[0]-$this->waterImg_info[0])/2;
        $this->y = $this->srcImg_info[1]-$this->waterImg_info[1] - 3;
    }
    private function waterimg($type='') {
        if ($this->srcImg_info[0] <= $this->waterImg_info[0] || $this->srcImg_info[1] <= $this->waterImg_info[1]){
            //die('水印比原图大！');
            return array('data'=>'','info'=>'水印比原图大!','status'=>0);
        }
        if($type == 'waterstr'){
            $this->waterposStr();
        }else{
            $this->waterpos();
        }
        $cut = imagecreatetruecolor($this->waterImg_info[0],$this->waterImg_info[1]);
        imagecopy($cut,$this->im,0,0,$this->x,$this->y,$this->waterImg_info[0],$this->waterImg_info[1]);
        $pct = $this->transparent;
        imagecopy($cut,$this->water_im,0,0,0,0,$this->waterImg_info[0],$this->waterImg_info[1]);
        imagecopymerge($this->im,$cut,$this->x,$this->y,0,0,$this->waterImg_info[0],$this->waterImg_info[1],$pct);
    }
    
    private function waterstr() {
        $rect = imagettfbbox($this->fontSize,0,$this->fontFile,$this->waterStr);
        $w = abs($rect[2]-$rect[6]);
        $h = abs($rect[3]-$rect[7]);
        $fontHeight = $this->fontSize;
        $this->water_im = imagecreatetruecolor($w, $h);
        imagealphablending($this->water_im,false);
        imagesavealpha($this->water_im,true);
        $white_alpha = imagecolorallocatealpha($this->water_im,255,255,255,127);
        imagefill($this->water_im,0,0,$white_alpha);
        $color = imagecolorallocate($this->water_im,$this->fontColor[0],$this->fontColor[1],$this->fontColor[2]);
        imagettftext($this->water_im,$this->fontSize,0,0,$this->fontSize,$color,$this->fontFile,$this->waterStr);
        $this->waterImg_info = array(0=>$w,1=>$h);
        $this->waterimg($type='waterstr');
    }
    /**
     * 绘制矩形区
     * bool imagefilledrectangle ( resource $image , int $x1 , int $y1 , int $x2 , int $y2 , int $color )
     * bool imagerectangle ( resource $image , int $x1 , int $y1 , int $x2 , int $y2 , int $col )
     * @author liuzp111
     */
    public function drawRectangle()
    {
        //imagefill($im,0,0,$gray);//填充资源，填充的坐标（类似PS魔棒），颜色
        /*
         *    1--------------画长方形--------------
         *    bool imagerectangle ( resource $image , int $x1 , int $y1 , int $x2 , int $y2 , int $col )
         *    参数: 画布资源, 左上角x坐标,左上y坐标,右下x坐标,右下y坐标,颜色
         */
        $color =  imagecolorallocate($this->im,255,255,255);//创建矩形边框颜色和填充颜色
        //=========================================================================
        //绘制矩形区域并填充
        // 参数说明：
        //bool imagefilledrectangle ( resource $image , int $x1 , int $y1 , int $x2 , int $y2 , int $color )
        // im:为将图像载入为图像资源
        // $x1:表示矩形左上角的X坐标
        // $y1:表示矩形左上角的Y坐标
        // $x2:表示矩形右下角的X坐标
        // $y2:表示矩形右下角的Y坐标
        // $color:为填充的RGB颜色
        //
        imagefilledrectangle($this->im,3,$this->srcImg_info[1] - 20,$this->srcImg_info[0]-3,$this->srcImg_info[1]-3,$color);
        //不要使用下方的函数填充，下方填充函数为魔棒填充，容易导致填充不完整
        //imagefill($this->im,$this->srcImg_info[0]/2,$this->srcImg_info[1]-8,$color);//填充资源，填充的坐标（魔棒），颜色
        
    }
    function output() {
        $this->imginfo();
        //是否创建矩形区域
        if($this->is_draw_rectangle){
            $this->drawRectangle();
        }
        if ($this->waterTypeStr ) {
            $this->waterstr();
        }
        if($this->waterTypeImage )
        {
            $this->waterimginfo();
            $this->waterimg();
        }
        switch ($this->srcImg_info[2]) {
            case 3:
                $res_output = imagepng($this->im,$this->output_img);
                break 1;
            case 2:
                $res_output =  imagejpeg($this->im,$this->output_img);
                break 1;
            case 1:
                $res_output = imagegif($this->im,$this->output_img);
                break 1;
            default:
                // die('添加水印失败！');
                return array('data'=>'','info'=>'添加水印失败!','status'=>0);
                break;
        }
        imagedestroy($this->im);
        imagedestroy($this->water_im);
        return array('data'=>$res_output,'info'=>'添加水印成功!','status'=>1);
    }
}
