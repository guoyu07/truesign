<?php
/**
 * Created by PhpStorm.
 * User: liuwei
 * Date: 16/9/10
 * Time: 上午9:16
 */

namespace Royal\App;


use Eagle\Service\Department;

class ErpList
{
    public static function getAllParam() {
        $data =array();
        parse_str(file_get_contents("php://input"),$data);
        if(empty($data)) {
            $s = $_SERVER['REQUEST_URI'];
            if(strpos($s,'?')) {
                $s = substr($s,strpos($s,'?')+1);
            }
            parse_str($s,$data);
        }
        return $data;
    }

    public static function getPageParam($param) {
        $data =array();
        if(empty($param['page'])) {
            $data['page']=1;
        }
        else {
            $data['page']=$param['page'];
        }
        if(empty($param['page_size'])) {
            $data['page_size']=20;
        }
        else {
            $data['page_size']=$param['page_size'];
        }
        return $data;

    }

    public static function Col(&$col,$dataIndex,$title,$width) {
        $col[]=array(
            'title'=>$title,
            'dataIndex'=>$dataIndex,
            'width'=>$width,
        );
    }
    public static function ColHtml(&$col,$dataIndex,$title,$width) {
        $col[]=array(
            'title'=>$title,
            'dataIndex'=>'',
            'html'=>'{{{'.$dataIndex.'}}}',
            'width'=>$width,
        );
    }
    public static function ColAction(&$col,$dataIndex,$title,$width) {
        $col[]=array(
            'title'=>$title,
            'dataIndex'=>'',
            'html'=>'<a action='.$dataIndex.'>{{{'.$dataIndex.'}}}</a>',
            'width'=>$width,
        );
    }

    public static function setEqParam(&$search,$field,$param) {
        if(!empty($param[$field])) {
            $search[$field] =$param[$field];
        }
    }

    public static function setLikeParam(&$search,$field,$param) {
        if(!empty($param[$field])) {
            $search[$field] =array(
                'operation'=>'prefix',
                'value'=>$param[$field]
            );
        }
    }
    public static function setDepartmentParam(&$search,$field,$param) {
        if(!empty($param[$field])) {
            $d = new Department();
            $ids =$d->getDepartmentIds($param[$field]);
            $search[$field] =array(
                'operation'=>'in',
                'value'=>$ids
            );
        }
    }

    public static function setBoundParam(&$search,$field,$param) {
        if(!empty($param[$field])) {
            $data = json_decode($param[$field],1);
            if(strpos($field,'W')) {
                $s = explode('W',$field);
                if(!empty($data['min'])) {
                    $search[$s[1]] =array(
                        'operation'=>'ge',
                        'value'=>$data['min']
                    );
                }
                if(!empty($data['max'])) {
                    $search[$s[0]] =array(
                        'operation'=>'le',
                        'value'=>$data['max']
                    );
                }
                return;
            }
            $search[$field] =array(
                'operation'=>'range',
                'value'=>array($data['min'],$data['max'])
            );
        }
    }
    public static function setInParam(&$search,$field,$data) {
        if(!empty($data)) {
            $search[$field] =array(
                'operation'=>'in',
                'value'=>$data
            );
        }
    }
    public static function setDateParam(&$search,$field,$min,$max) {
        $data =array();
        if(!empty($min)) {
            $data['min']=strtotime($min.' 00:00:00');
        }
        if(!empty($max)) {
            $data['max']=strtotime($max.' 23:59:59');
        }
        if(!empty($min)&&!empty($max)) {
            $search[$field] =array(
                'operation'=>'range',
                'value'=>array($data['min'],$data['max'])
            );
        }
        else if(!empty($data['min'])) {
            $search[$field] =array(
                'operation'=>'ge',
                'value'=>$data['min']
            );
        }
        else if(!empty($data['max'])) {
            $search[$field] =array(
                'operation'=>'le',
                'value'=>$data['max']
            );
        }
    }

    public static function setDateSql(&$condition,$field,$min,$max) {
        $data =array();
        if(!empty($min)) {
            $data['min']=strtotime($min.' 00:00:00');
        }
        if(!empty($max)) {
            $data['max']=strtotime($max.' 23:59:59');
        }
        if(!empty($data['min'])) {
            $condition.=' and '.$field.'>='.$data['min'];
        }
        if(!empty($data['max'])) {
            $condition.=' and '.$field.'<='.$data['max'];
        }
    }
    public static function setEqSql(&$condition,$field,$param) {
        if(!empty($param[$field])) {
            $condition.=' and '.$field.'="'.$param[$field].'"';
        }
    }
    public static function setInSql(&$condition,$field,$data) {
        if(!empty($data)) {
            $ids = '';
            foreach($data as $d) {
                $ids.=',"'.$d.'"';
            }
            if(!empty($ids)) {
                $ids = substr($ids,1);
            }
            $condition.=' and '.$field.' in ('.$ids.')';
        }
    }
}