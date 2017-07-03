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
    static function SUCCESS(){return array('code' => 0, 'desc' => '代码逻辑正常');   }
    static function WARNING(){return array('code' => 100, 'desc' => '系统逻辑警告');   }
    static function ERROR(){return array('code' => 200, 'desc' => '系统运行异常');   }
    static function FETCH_DATA_ERR(){return array('code' => 300, 'desc' => '远程获取数据错误');   }
    static function FILE_NOT_FOUND(){return array('code' => 404, 'desc' => '文件无法获取');   }
    static function CODE_LOGIC_ERR(){return array('code' => 500, 'desc' => '代码逻辑错误');   }
    static function ACCESS_DENIED(){return array('code' => 755, 'desc' => '权限禁止');   }
    static function ACCESS_DENIED_READ(){return array('code' => 756, 'desc' => '权限禁止读取');   }
    static function ACCESS_DENIED_WRITE(){return array('code' => 757, 'desc' => '权限禁止写入');   }
    static function PDO_ERR(){return array('code' => 1900, 'desc' => '连接数据库错误');   }
    static function SQL_ERR(){return array('code' => 2000, 'desc' => '数据交互出错');   }
    static function REDIS_ERR(){return array('code' => 2100, 'desc' => 'REDIS错误');   }
}