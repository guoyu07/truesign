<?php
/**
 * Created by PhpStorm.
 * User: liuwei
 * Date: 16/9/10
 * Time: 下午7:49
 */

namespace Royal\App;


class ErpWidget
{
    public  $Widget_SingleSelect; // 单选
    public  $Widget_MultiSelect; // 多选
    public  $Widget_Input; // 录入框
    public  $Widget_Calendar;// 选择日期
    public  $Widget_DepartmentSelect;// 选择部门
    public  $Widget_UserSelect;// 选择用户
    public  $Widget_KeywordSelect;// 关键字搜索
    public  $Widget_Button;// 按钮
    public  $Widget_Text;// 只读输入框
    public  $Widget_Password;// 密码框
    public  $Widget_TextArea;// 多行输入框
    public  $Widget_MapLocation;// 地图坐标
    public  $Widget_Choose;// 选择资源
    public  $Widget_HourMinute;// 小时分钟
    public  $Widget_RegionSelect;// 片区选择
    public  $Widget_CommunitySelect;// 小区选择
    public  $Widget_Radio;// radio 单选
    public  $Widget_More;//更多
    public  $Widget_Bound;//区间
    public  $Widget_Hidden;//隐藏

    public $man_ico;
    public $woman_ico;
    public $house_fault;
    public $house_fault_big;
    public $default_url;

    public function __construct() {
        $this->Widget_SingleSelect='SingleSelect';
        $this->Widget_MultiSelect='MultiSelect';
        $this->Widget_Input='Input';
        $this->Widget_Calendar='Calendar';
        $this->Widget_UserSelect='UserSelect';
        $this->Widget_DepartmentSelect='DepartmentSelect';
        $this->Widget_KeywordSelect='KeywordSelect';
        $this->Widget_Button='Button';
        $this->Widget_Text='Text';
        $this->Widget_Password='Password';
        $this->Widget_TextArea='TextArea';
        $this->Widget_RoomCode='RoomCode';
        $this->Widget_MapLocation='MapLocation';
        $this->Widget_Choose='Choose';
        $this->Widget_HourMinute='HourMinute';
        $this->Widget_RegionSelect='RegionSelect';
        $this->Widget_CommunitySelect='CommunitySelect';
        $this->Widget_Radio='Radio';
        $this->Widget_More='More';
        $this->Widget_Bound='Bound';
        $this->Widget_Hidden='Hidden';

        $config = \Yaf_Registry::get('config');
        $default_url = $config->api->default_url;

        $this->default_url=$default_url;
        $this->man_ico='http://cdn.mse.meiliwu.com/avenger/online_head_man_0.png';
        $this->woman_ico='http://cdn.mse.meiliwu.com/avenger/online_head_women_1.png';
        $this->house_fault=$default_url.'/house_fault.png';
        $this->house_fault_big=$default_url.'/house_fault_big.png';
    }
    public function outData($title,$widget,$gap='') {
        return array(
            'title'=>$title,
            'gap'=>$gap,
            'widget'=>$widget,
        );
    }

    public function html($html,&$input) {
        $input[]=array(
            'widget'=>array(
                array(
                    'widget'=>'Html',
                    'html'=>$html
                )
            )
        );
    }

    public function hidden($id,$param,&$input) {
        $input[]=array(
            'widget'=>array(
                array(
                    'widget'=>'Hidden',
                    'id'=>$id,
                    'value'=>$param[$id]
                )
            )
        );
    }

    public function Widget($id,$widget,$data=array(),$param=array()) {
        $re  =  array(
            'id'=>$id,
            'widget'=>$widget,
            'rule'=>isset($data['rule'])?$data['rule']:'',
            'unit'=>isset($data['unit'])?$data['unit']:'',
            'placeholder'=>isset($data['placeholder'])?$data['placeholder']:'',
            'value'=>(isset($data['value'])?$data['value']:'').'',
            'is_must'=>isset($data['is_must'])?true:false,
            'select'=>isset($data['select'])?$data['select']:'',
        );
        if($param[$id]=='0') {
//            var_dump(12);
            $re['value']='0';
        }
        if(!empty($param[$id])) {
            if(strpos($param[$id],'%')===0) {
//                self::throwException($param[$id].'->'.urldecode($param[$id]));
                $param[$id] = urldecode($param[$id]);
            }

            if($widget==$this->Widget_Calendar && is_numeric($param[$id])) {
                $re['value']=date('Y-m-d',$param[$id]);
            }
            else {
                $re['value']=$param[$id].'';
            }

        }
        if($data['action']) {
            $re['action'] = $data['action'];
        }
        if($data['height']) {
            $re['height'] = $data['height'];
        }
        if($data['is_auto']) {
            $re['is_auto'] = true;
        }
        if($data['uri']) {
            $re['uri'] = $data['uri'];
        }
        if($data['uri_param']) {
            $re['uri_param'] = $data['uri_param'];
        }
        if($data['action']) {
            $re['action'] = $data['action'];
        }
        if($data['name']) {
            $re['name'] = $data['name'];
        }
        return $re;
    }
    // 控件取值
    public function widgetJson($value) {
        $data =array();
        if(!empty($value)) {
            if(strpos($value,'%')===0) {
                $value = urldecode($value);
            }
            $data =json_decode($value,1);
            if(isset($data['id'])) {
                $data['user_id']=$data['id'];
            }
            if(isset($data['department_id'])) {
                if($data['department_id']=='null') {
                    $data['department_id']='';
                }
            }
            return $data;
        }
        return $data;
    }
}