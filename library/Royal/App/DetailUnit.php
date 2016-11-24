<?php
namespace Royal\App;

/**
 * Created by PhpStorm.
 * User: liuwei
 * Date: 16/8/13
 * Time: 上午7:23
 * 详细页面每一单元的属性
 */
class DetailUnit
{
    public $_data;
    public static function text($text) {
        if(empty($text)) {
            $text ='';
        }
        $s = new DetailUnit();
        $s->_data=array('text'=>$text);
        return $s;
    }
    public static function blank($b=1,&$input) {
//        $s = new DetailUnit();
        $text ='';
        for($i=$b;$i>0;$i--) {
            $text.=' ';
        }
        $input[]=array('text'=>$text);
    }

    public static function blankText($b=1) {
//        $s = new DetailUnit();
        $text ='';
        for($i=$b;$i>0;$i--) {
            $text.=' ';
        }
        return array('text'=>$text);
    }

    public static function textIcon($text,$icon_width,$icon_uri) {
        $s = new DetailUnit();
        $s->_data=array('text'=>$text,'icon'=>true,'icon_width'=>$icon_width,'icon_uri'=>$icon_uri);
        return $s;
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
    // 内容显示的位置  不能使用
    public function align($align) {
//         left  center  right
        $this->_data['align']=$align;
        return $this;
    }

    // 字体变粗
    public function bold() {
        $this->_data['font_bold']=true;
        return $this;
    }

    // 是否为tag标签
    public function tag() {
        $this->_data['is_tag']=true;
        return $this;
    }

    // 事件
    public function action($action) {
        $this->_data['action']=$action;
        return $this;
    }

    // 输出返回
    public  function out() {
        return $this->_data;
    }
    public  function outInput(&$input) {
        $input[]=$this->_data;
    }
}