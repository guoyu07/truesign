<?php
namespace Royal\App;

/**
 * Created by PhpStorm.
 * User: iamsee
 * Date: 16/8/13
 * Time: 上午7:23
 * 详细页面每一行的属性
 */
class DetailRow
{
    public $_data;

//    // 纯文本
//    public static function text() {
//        $s = new DetailRow();
//        $s->_data=array('type'=>'text');
//        return $s;
//    }

    //块
    public static function block($height,$v_align='',$align='') {
        $s = new DetailRow();
        $s->_data=array('type'=>'block','height'=>$height);
        if(!empty($v_align)) {
            $s->_data['v_align'] =$v_align;
        }

        if(!empty($align)) {
            $s->_data['align'] =$align;
        }
        return $s;
    }
    // 设置gap 块之间的空隙
    public function gap($gap='|',$gap_color='#b1b1b1',$gap_size=30) {
        $this->_data['gap']=$gap;
        $this->_data['gap_color']=self::setColor($gap_color);
        $this->_data['gap_size']=$gap_size;
        return $this;
    }

    // 一条线
    public static function line($height,$line_color='#dadada') {
        $s = new DetailRow();
        $s->_data=array('type'=>'line','height'=>$height,'line_color'=>$line_color);
        return $s;
    }

    // 空行
    public static function blank($height,&$input,$back_ground='') {
        $block = array();
        $block[]=array(
            array('text'=>'')
        );

        $data = array('type'=>'block','height'=>$height,'block'=>$block,);
        if(!empty($back_ground)) {
            $data['background_color'] = self::setColor($back_ground);
        }
        $input[]=$data;
    }

    // textArea
    public static function textArea($label,$text) {
        $s = new DetailRow();
        $s->_data=array('type'=>'textArea','label'=>$label,'textArea'=>$text);
        return $s;
    }

    // 单张图
    public static function icon($height,$width,$uri,$circular=false) {
        $s = new DetailRow();
        $s->_data=array('type'=>'icon','height'=>$height,'width'=>$width,'url'=>$uri,'circular'=>$circular);
        return $s;
    }

    // 单一行
    public static function row($height,$rows,&$input,$background_color='') {
        $block =array();
        $block[]=$rows;
        return self::block($height,'center')->unit($block)->backGround($background_color)->out($input);
    }
    public static function rowCenter($height,$rows,&$input) {
        $block =array();
        $block[]=$rows;
        return self::block($height,'center','center')->unit($block)->out($input);
    }

    public function outInput(&$input) {
        $input[]=$this->_data;
    }


    // 整行图
    public static function image($images) {
        $s = new DetailRow();
        $s->_data=array('type'=>'image','images'=>$images);
        return $s;
    }

    public static function imageButton($height,$button) {
        $s = new DetailRow();
        $s->_data=array('type'=>'image_button','button'=>$button,'height'=>$height);
        return $s;
    }


//    // 行高
//    public function height($h) {
//        $this->_data['height']=$h;
//        return $this;
//    }

    // 行背景
    public  function backGround($color) {
        if(empty($color)) {
            return $this;
        }
        if(strpos($color,'#')===0) {
        }
        else {
            $color='#'.$color;
        }
        $this->_data['background_color']=$color;
        return $this;
    }
    // 内容显示的位置
    public function align($align) {
        // top  center  bottom
        $this->_data['align']=$align;
        return $this;
    }

    // 块内容元素
    public function unit($unit) {
        $this->_data['block']=$unit;
        return $this;
    }
    // 输出返回
    public  function out(&$input) {
        $input[]=$this->_data;
    }

    public static function setColor($color) {
        if(strpos($color,'#')===0) {
        }
        else {
            $color='#'.$color;
        }
        return $color;
    }

    // 地图
    public function map($longitude,$latitude,$action,&$input) {
        $input[]=array('type'=>'map','longitude'=>$longitude,'latitude'=>$latitude,'action'=>$action);
    }

    // 二维码
    public static function qrCode($height,$text,&$input) {
        $input[]= array('type'=>'QrCode','width'=>$height,'height'=>$height,'text'=>$text);
    }
    // 字体颜色
    public  function color($color) {
        if(strpos($color,'#')===0) {
        }
        else {
            $color='#'.$color;
        }
        $this->_data['font_color']=$color;
        return $this;
    }
    // 字体大小
    public  function size($size) {
        $this->_data['font_size']=$size;
        return $this;
    }

    // 事件
    public function action($action) {
        $this->_data['action']=$action;
        return $this;
    }
}