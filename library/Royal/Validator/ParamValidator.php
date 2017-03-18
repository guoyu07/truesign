<?php

namespace Royal\Validator;
use \Exception;

class ParamValidator {
    function __construct()
    {
//        $Yaf_Request = new \Yaf_Request_Simple;
////        $Yaf_Request = new \Yaf_Request_Simple;
    }

    static function getPairParam(\Yaf_Request_Simple &$request, ParamRule &$rule) {
        $value = $request->get($rule->name, $rule->defaultValue);
        if('0'==$value) {

        }
        else if ($value === false || empty($value)) {
            return false;
        }

        $param = array('operation' => 'eq');
        if (preg_match('/(.*):(.*)/', $value, $matched) && PairMap::exists($matched[1])) {
            $operation = $matched[1];
            $value = $matched[2];
            $param['operation'] = $operation;
        } else {
            $param['operation'] = 'eq';
        }
        if (strpos($value, ';')) {
            $value = explode(';', $value);
            $value = array_values(array_filter($value, function($e) { return '' !== $e; }));
            if (count($value) && $param['operation'] == 'eq') {
                $param['operation'] = 'in';
            }
        }
//        else if (strpos($value, ':')) {
//            $value = explode(':', $value);
//            $param['operation'] = 'in';
//
//            $value = array_values(array_filter($value, function($e) { return '' !== $e; }));
//            if (count($value) && $param['operation'] == 'eq') {
//            }
//        }

        switch ($rule->type) {
            case 'int':
                if (is_array($value)) {
                    $value = array_map(function($e){return intval($e);}, $value);
                } else {
                    $value = intval($value);
                }
                break;
            case 'enum':
                if (is_array($value)) {
                    $value = array_filter($value, function($e)  use ($rule)  { return in_array($e, $rule->enumValues); });
                    if (empty($value)) {
                        $value = false;
                    }
                } else {
                    if (!in_array($value, $rule->enumValues)) {
                        $value = false;
                    }
                }
                break;
            default:
                break;
        }

        if ($value === false) {
            return false;
        }

        $param['value'] = $value;

        return $param;
    }

    static function paramsFromRequest(\Yaf_Request_Simple &$request, $required, $optional) {
        $rules = array();
        foreach ((array)$required as $r) {
            $rules[$r] = ParamRule::rule($r)->type('raw')->required(true);
        }
        foreach ((array)$optional as $o) {
            $rules[$o] = ParamRule::rule($o)->type('raw');
        }

        return static::paramsFromRequestAndRules($request, $rules);
    }

    static function paramsFromRequestAndRules(\Yaf_Request_Simple &$request, $rules,$throw_code=-100) {
        $params = array();
        foreach ($rules as $name => $rule) {
            $param = static::getParam($request, $rule,$throw_code);
            if ($param === false) {

            } else {
                $params[$name] = $param;
            }
        }

        return $params;
    }

    static function pairParamsFromRequestAndRules(\Yaf_Request_Simple &$request, $rules) {
        $params = array();
        foreach ($rules as $name => $rule) {
            $param = static::getPairParam($request, $rule);
            if ($param === false) {

            } else {
                $params[$name] = $param;
            }
        }

        return $params;
    }

    /**
     * @param Http $request
     * @param ParamRule $rule
     * @return mixed
     * @throws Exception
     */
    static function unicode_decode($name){

        $json = '{"str":"'.$name.'"}';
        $arr = json_decode($json,true);
        if(empty($arr)) return '';
        return $arr['str'];
    }
    static function getParam(\Yaf_Request_Simple &$request, ParamRule &$rule,$throw_code=-100) {

        $param = $request->get($rule->name, $rule->defaultValue);

        if ($param === false) {
            if ($rule->required) {
                throw new Exception(ParamValidator::requiredErrorDesc($rule->desc()), $throw_code);
            }
            return false;
        }

        if ($rule->notEmpty && empty($param)) {
            throw new Exception(ParamValidator::emptyErrorDesc($rule->desc()), $throw_code);
        }

        if ($param === '') {
            if ($rule->type == 'enum') {
                return false;
            }
            if ($rule->type == 'str') {
                return $rule->allowEmpty ? '' : false;
            }
        }
        if(gettype($param) === 'array'){
            return $param;
        }
        switch ($rule->type) {
//            case 'int':
//                $param = intval($param);
//                if ($param < $rule->min || $param > $rule->max) {
//                    throw new Exception(ParamValidator::rangeErrorDesc($rule->desc(), $rule->min, $rule->max), $throw_code);
//                }
//                break;
            case 'enum':
                if (!in_array($param, $rule->enumValues)) {
                    throw new Exception(ParamValidator::valueErrorDesc($rule->desc()), $throw_code);
                }
                break;
            case 'array':
                if (!is_array($param)) {
                    $param = explode($rule->delimiter, $param);
                }
                break;
            case 'str':
                $param = strval($param);
                $strLen = mb_strlen($param, 'utf8');
                if ($strLen < $rule->min || $strLen > $rule->max) {
                    throw new Exception(ParamValidator::strLengthErrorDesc($rule->desc(), $strLen, $rule->min, $rule->max), $throw_code);
                }
                break;
            case 'varchar':
                $param = strval($param);
                $strLen = mb_strlen($param, 'utf8');
                if ($strLen < $rule->min || $strLen > $rule->max) {
                    throw new Exception(ParamValidator::strLengthErrorDesc($rule->desc(), $strLen, $rule->min, $rule->max), $throw_code);
                }
                break;
            default:
                if ($rule->regex && !preg_match($rule->regex, $param)) {
                    throw new Exception(ParamValidator::wrongFormatErrorDesc($rule->desc()), $throw_code);
                }
                break;
        }

        return $param;
    }

    static function requiredErrorDesc($title) {
        return sprintf('%s 是必填参数', $title);
    }

    static function valueErrorDesc($title) {
        return sprintf('%s 取值非法', $title);
    }

    static function rangeErrorDesc($title, $min, $max) {
        return sprintf('%s 不在可选范围 [%d:%d]', $title, $min, $max);
    }

    static function emptyErrorDesc($title) {
        return sprintf('%s 不能为空', $title);
    }

    static function strLengthErrorDesc($title, $length, $minLength, $maxLength) {
        if ($length < $minLength) {
            return sprintf('%s 最短长度为%d', $title, $minLength);
        }

        return sprintf('%s 最长不超过 %d', $title, $maxLength);
    }

    static function wrongFormatErrorDesc($title) {
        return sprintf('%s 格式不对', $title);
    }
}