<?php
/**
 * User: heyi
 * Date: 14/11/4
 * Time: PM2:47
 */

namespace Royal\Prof;


use Royal\Logger\Logger;
use Royal\Misc\TreeNode;
use Royal\Util\TimeUtil;

class TimeStack {

    /**
     * @var TreeNode
     */
    static $current = null;

    /**
     * @var TreeNode
     */
    static $lastNotNull = null;

    const  TAG_ALL = 'ALL';
    const  TAG_SQL = 'SQL';
    const  TAG_HTTP = 'HTTP';
    const  TAG_ES = 'ES';
    const  TAG_CONNECTION = 'CONNECTION';

    static function start($tag='ALL', $info=array()) {
        $trace = debug_backtrace(0, 2);
        array_shift($trace);
        $nodeValue = array('tag'=>$tag, 'start'=>TimeUtil::msTimestamp(), 'function'=>$trace[0]['function']);
        if (!empty($info)) {
            $nodeValue['info'] = $info;
        }

        $node = new TreeNode($nodeValue);
        if (static::$current == null) {
            static::$current = $node;
        } else {
            static::$current->addChild($node);
            static::$current = $node;
        }
    }

    static function end() {
        $value = static::$current->getValue();
        $value['spent'] = TimeUtil::msTimestamp() - $value['start'];
        unset($value['start']);
        static::$current->setValue($value);
        $parent = static::$current->getParent();
        static::$lastNotNull = $parent ?: static::$current;
        static::$current = $parent;

        if (!$parent) {
            $spent = TimeStack::timeSpent();
            $conf = \Yaf_Registry::get('config');
            if ($spent >= $conf->application->timeout) {
                Logger::alert(TimeStack::getStackText());
            }
            Logger::info(sprintf('Take:%dms', $spent));
        }
    }

    static function getStackText() {
        $str = "TimeStack:\r\n";
        $treeStr = TreeNode::treeToString(static::$lastNotNull, function($value) {
            if (isset($value['info'])) {
                if (is_scalar($value['info'])) {
                    $info = $value['info'];
                } else {
                    $info = json_encode($value['info']);
                }
                return sprintf("%s:%s:%s:%s", $value['tag'], $value['spent'], $value['function'], $info);
            } else {
                return sprintf("%s:%s", $value['tag'], $value['spent'], $value['function']);
            }
        });
        return $str.$treeStr;
    }

    static function timeSpent() {
        $value = static::$lastNotNull->getValue();
        return $value['spent'];
    }
}