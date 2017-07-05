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

    static function FETCH_DATA_ERR($info=''){return array('code' => 300, 'desc' => empty($info)?'远程获取数据错误':$info);   }

    static function FILE_NOT_FOUND($info=''){return array('code' => 404, 'desc' => empty($info)?'文件无法获取':$info);   }

    static function CODE_LOGIC_ERR($info=''){return array('code' => 500, 'desc' => empty($info)?'代码逻辑错误':$info);   }
    static function OPERATION_lOGIC_ERR($info=''){return array('code' => 510, 'desc' => empty($info)?'操作逻辑错误':$info);   } //客户端操作错误

    static function ACCESS_DENIED($info=''){return array('code' => 755, 'desc' => empty($info)?'权限禁止':$info);   }
    static function ACCESS_DENIED_READ($info=''){return array('code' => 756, 'desc' => empty($info)?'权限禁止读取':$info);   }
    static function ACCESS_DENIED_WRITE($info=''){return array('code' => 757, 'desc' => empty($info)?'权限禁止写入':$info);   }
    static function PDO_ERR($info=''){return array('code' => 1900, 'desc' => empty($info)?'连接数据库错误':$info);   }
    static function SQL_ERR($info=''){return array('code' => 2000, 'desc' => empty($info)?'数据交互出错':$info);   }
    static function REDIS_ERR($info=''){return array('code' => 2100, 'desc' => empty($info)?'REDIS错误':$info);   }
}