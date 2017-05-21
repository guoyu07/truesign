<?php
/**
 * Created by PhpStorm.
 * User: iamsee
 * Date: 16/8/26
 * Time: 上午10:23
 */

namespace Royal\App;


use Eagle\Service\BaseRedis;
use Eagle\Service\Department;
use Eagle\Service\Report\ReportData;
use Erp\Service\AppCore\Base;

class ReportSet extends Base
{
    public static function title($test,$width,$rom_num,&$data) {
        $d = array(
            'rom_num'=>$rom_num,
            'text'=>$test,
            'width'=>$width,
            'background_color'=>'#385E96',
            'font_color'=>'#FFFFFF'
        );
        $data['title'][]=$d;
    }

    public static function colUnit($id,$text,$width,&$unit) {
        if(empty($id)) {
            return;
        }
        if(empty($text)) {
            $text =$id;
        }
        $d = array(
            'id'=>$id,
            'text'=>$text,
            'width'=>$width,
            'background_color'=>'#385E96',
            'font_color'=>'#FFFFFF',
        );
        if($id=='HeJi') {
            $d['background_color'] ='#385E20';
        }
        $unit[]=$d;
    }

    public static function col($title,$units,&$data) {
        if(\Yaf_Registry::get('report_he')){
            ReportSet::colUnit('HeJi','合计',100,$units);
        }
        $s = array(
            'units'=>$units
        );
        if($title) {
            $s['title']=$title;
            $s['background_color']='#385E96';
            $s['font_color']='#FFFFFF';
        }
        $data['cols'][]=$s;
    }

    public static function department($department,$users,&$data) {
        $data['rows'][]=array(
            'department'=>$department,
            'background_color'=>'#685E20',
            'font_color'=>'#FFFFFF',
            'users'=>$users
        );
    }

    public static function rowUser($text,$data,&$users) {
        $s = array(
            'text'=>$text,
            'data'=>$data,
            'background_color'=>'#385E96',
            'font_color'=>'#FFFFFF',
        );
        if($text=='总量') {
            $s['background_color'] ='#387E00';
            $s['font_color'] ='#FFFFFF';
        }
        else if($text=='平均') {
            $s['background_color'] ='#385E20';
            $s['font_color'] ='#FFFFFF';
        }
        else if($text=='全部') {
            $s['background_color'] ='#185E20';
            $s['font_color'] ='#FFFFFF';
        }
        $users[]=$s;
    }

    public static function userData($key,$text,$user_id,&$rowData) {
        $actionData =array(
            'col'=>$key,
            'row'=>$user_id
        );
        $rowData[$key]=array(
            'text'=>$text.'',
            'actionData'=>$actionData
        );
//        $action = self::getAction($key,$user_id);
//        if(!empty($action)) {
//            $rowData[$key]['action']=$action;
//        }
    }

    public static function action($uri,$type,$uri_param=array(),$post=true) { // 打开列表页面
        $data =array();
        $data['type']=$type;
        $data['uri']=$uri;
        if(empty($uri_param)) {
            $uri_param = (Object)array();
        }
        $data['uri_param']=$uri_param;
        if($post) {
            $data['post']=$post;
        }
        return $data;
    }

    public static function getAction($key,$user_id) {
        if(empty($user_id)) {
            return array();
        }
        $actions = \Yaf_Registry::get('report_action');
        $report_range = \Yaf_Registry::get('report_range');
        if(isset($actions[$key])) {
            $data = $actions[$key];
            $param = \Yaf_Registry::get('report_condition');
            $param[$data['col']] = $key;
            if($report_range=='店' || $report_range =='组') {
                $param[$data['row_user']] = $user_id;
            }
            else {
                $param[$data['row_department']] = $user_id;
            }
            return self::action($data['uri'],$data['type'],$param);
        }
        return array();
    }

    // 将报表数据转换成app 报表结构
    public static function revertData($report_data,&$data) {
        foreach($report_data as $departmentData) {
            $users =array();
            $total = $departmentData['total'];

            \Yaf_Registry::set('report_range',$departmentData['range']);


            self::rowUser('总量',self::dataToJson($total,'0'),$users);
            self::rowUser('平均',self::dataToAvf($total,count($departmentData['data'])),$users);

            foreach($departmentData['data'] as $user_data) {
//                self::throwException($user_data['id'],-1);
                if(isset($user_data['user_id'])) {
                    \Yaf_Registry::set('report_range','组');

                    self::rowUser($user_data['name'],self::dataToJson($user_data['data'],$user_data['user_id']),$users);
                }
                else {
                    self::rowUser($user_data['name'],self::dataToJson($user_data['data'],$user_data['id']),$users);
                }
            }

            self::department($departmentData['name'],$users,$data);

        }
//        self::throwException(json_encode($data));
        self::setTotal($data);
//        BaseRedis::set('test',json_encode($data));

    }

    public static function dataToJson($data,$user_id) {
        if(empty($data)) {
            return (Object)array();
        }
        $s =array();
        foreach($data as $k=>$v) {
            self::userData($k,$v,$user_id,$s);
        }
        if(empty($s)) {
            return (Object)$s;
        }
        return $s;
    }

    public static function dataToAvf($data,$total_num) {
        if(empty($total_num)) {
            $total_num=1;
        }

        $s =array();
        foreach($data as $k=>$v) {
            self::userData($k,sprintf("%.2f",$v/$total_num),'',$s);
        }
        if(empty($s)) {
            return (Object)$s;
        }
        return $s;
    }

    // 设置全部总量数据
    public static function setTotal(&$data) {
        $cols = array();
        foreach($data['cols'] as $q) {
            foreach($q['units'] as $w) {
                $cols[]=$w['id'];
            }
        }

        $total =array();
        $avg =0;
        if($data['rows']) {
            foreach($data['rows'] as $row) {
                foreach($row['users'] as $u) {
                    if($u['text'] =='总量' || $u['text'] =='平均') {

                    }
                    else {
                        $d = $u['data'];
                        $avg++;
                        if(!empty($d)) {
                            foreach($cols as $col) {
                                if(!is_object($d)) {
                                    $total[$col]+=$d[$col]['text'];
                                }
                            }
                        }

                    }
                }
            }
        }


        $users =array();
        self::rowUser('总量',self::dataToJson($total,''),$users);
        self::rowUser('平均',self::dataToAvf($total,$avg),$users);
        self::department('全部',$users,$data);
    }

    public static function setTitle(&$data,$row_num=1) {
        unset($data['title']);
        if(!empty($data['reportName'])) {
            ReportSet::title($data['reportName'],120,$row_num,$data);
            ReportSet::title($data['leftTitle'],120,$row_num,$data);
            return;
        }

        $range = \Yaf_Registry::get('report_range');
        if($range=='店' || $range=='组') {
            ReportSet::title('门店',120,$row_num,$data);
            ReportSet::title('员工',120,$row_num,$data);
        }
        else {
            ReportSet::title('区',120,$row_num,$data);
            ReportSet::title('门店',120,$row_num,$data);
        }
    }

    public static function setSearch(&$data,$action) {
        $data['action']=$action;
    }

    public static function RevertSqlData($sql_data) {
        $sqlData=array();
        foreach($sql_data as $s) {
            if(!empty($s['row_col'])) {
                if(empty($s['col_col'])) {
                    continue;
                }
                $sqlData[$s['row_col']][$s['col_col']]=$s['num'];
                $sqlData[$s['row_col']]['HeJi']+=$s['num'];
            }
        }
        return $sqlData;
    }

    // 新的报表数据整理
    public static function newRevertData(&$data,$sqlData,$type) {
        $row_data = ReportData::data($type);

        $data['result']=$sqlData;

        if($type=='大区' || $type=='区' || $type=='店') {
            // 整理数据
            $newData =array();
            foreach($sqlData as $d_id=>$row) {
                foreach($row_data as $user_data) {
                    $ids = $user_data['ids'];
                    if(in_array($d_id,$ids)) {
                        if(!empty($row)) {
                            foreach($row as $k=>$v) {
                                $newData[$user_data['id']][$k]+=$v;
                            }
                        }
                    }
                }
            }
            $data['result']=$newData;
            $data['row_data']=$row_data;

            $users =array();
            foreach($row_data as $user_data) {
                if(empty($newData[$user_data['id']])) {
                    continue;
                }
                self::rowUser($user_data['department'],self::dataToJson($newData[$user_data['id']],$user_data['id']),$users);
            }
            if(!empty($users)) {
                $data['rows'][]=array(
                    'department'=>$type,
                    'background_color'=>'#685E20',
                    'font_color'=>'#FFFFFF',
                    'users'=>$users
                );
            }
            self::setTotal($data);

        }
        else if($type=='员工') {
            $dian = ReportData::data('店');
            foreach($dian as $department) {
                $total =array();
                $ids =$department['ids'];
                $users =array();
                foreach($row_data as $user_data) {
                    if(in_array($user_data['department_id'],$ids)) {
                        if(empty($sqlData[$user_data['id']])) {
                            continue;
                        }
                        $sql_data = $sqlData[$user_data['id']];
                        self::rowUser($user_data['username'],self::dataToJson($sql_data,$user_data['id']),$users);
                        if(!empty($sql_data)) {
                            foreach($sql_data as $k=>$v) {
                                $total[$k]+=$v;
                            }
                        }
                    }
                }
                if(empty($users)) {
                    continue;
                }
                $userNum = count($users);
                self::rowUser('总量',self::dataToJson($total,'0'),$users);
                self::rowUser('平均',self::dataToAvf($total,$userNum),$users);
                $data['rows'][]=array(
                    'department'=>$department['department'],
                    'background_color'=>'#685E20',
                    'font_color'=>'#FFFFFF',
                    'users'=>$users
                );
            }
            self::setTotal($data);
        }
        else {
            $users =array();
            foreach($row_data as $user_data) {
                if(empty($sqlData[$user_data])) {
                    continue;
                }
                self::rowUser($user_data,self::dataToJson($sqlData[$user_data],$user_data),$users);
            }
            if(!empty($users)) {
                $data['rows'][]=array(
                    'department'=>$type,
                    'background_color'=>'#685E20',
                    'font_color'=>'#FFFFFF',
                    'users'=>$users
                );
            }
            self::setTotal($data);


        }
    }
}