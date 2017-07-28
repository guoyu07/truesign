<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/7/3
 * Time: 17:37
 */

namespace Royal\Prof;


class TrueSignConst
{
    static function SUCCESS($info=''){return array('code' => 0, 'desc' => empty($info)?'代码逻辑正常':$info);   }
    static function WARNING($info=''){return array('code' => 100, 'desc' => empty($info)?'系统逻辑警告':$info);   }
    static function ERROR($info=''){return array('code' => 200, 'desc' => empty($info)?'系统运行异常':$info);   }
    static function ERROR_DATA_FORMAT($info=''){return array('code' => 210, 'desc' => empty($info)?'数据格式不正确':$info);   }
    static function EMPTY_PARAMS($info=''){return array('code' => 220, 'desc' => empty($info)?'参数为空':$info);   }
    static function FETCH_DATA_ERR($info=''){return array('code' => 300, 'desc' => empty($info)?'远程获取数据错误':$info);   }
    static function FILE_NOT_FOUND($info=''){return array('code' => 404, 'desc' => empty($info)?'文件无法获取':$info);   }
    static function CODE_LOGIC_ERR($info=''){return array('code' => 500, 'desc' => empty($info)?'代码逻辑错误':$info);   }
    static function OPERATION_lOGIC_ERR($info=''){return array('code' => 510, 'desc' => empty($info)?'操作逻辑错误':$info);   } //客户端操作错误
    static function AUTH_ERROR($info=''){return array('code' => 700, 'desc' => empty($info)?'账户认证失败':$info);   }
    static function ACCESS_DENIED($info=''){return array('code' => 755, 'desc' => empty($info)?'权限禁止':$info);   }
    static function ACCESS_DENIED_READ($info=''){return array('code' => 756, 'desc' => empty($info)?'权限禁止读取':$info);   }
    static function ACCESS_DENIED_WRITE($info=''){return array('code' => 757, 'desc' => empty($info)?'权限禁止写入':$info);   }
    static function PDO_ERR($info=''){return array('code' => 1900, 'desc' => empty($info)?'连接数据库错误':$info);   }
    static function SQL_ERR($info=''){return array('code' => 2000, 'desc' => empty($info)?'数据交互出错':$info);   }
    static function REDIS_ERR($info=''){return array('code' => 2100, 'desc' => empty($info)?'REDIS错误':$info);   }
    static function DEBUG($info=''){return array('code' => 6000, 'desc' => empty($info)?'调试':$info);   }

    static function REQUIRE_AUTH($info=''){return array('code' => 10000, 'desc' => empty($info)?'需要登录认证':$info);   }

    static function GET_DEBUG_BACKTRACE(){
        $trace = debug_backtrace();
        $i = 0;
        $skipClasses = array('Royal\\Prof\\TrueSignConst');
        foreach ($trace as $k=>$v){
            if(in_array($v['class'],$skipClasses)){
                unset($trace[$k]);
            }
        }
        return $trace;
    }
}