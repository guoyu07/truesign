<?php

namespace Royal\Validator;


class ParamRule {
    public $name;
    public $title;
    public $min;
    public $max;
    public $type;
    public $required = false;
    public $delimiter = ',';

    public $regex = false;
    public $able_modify = true ;
    public $able_show = true ;
    public $issearch = false ;
    public $issorter = false ;
    public $widgetType = false;
    public $tag = false;

    public $enumValues = false;
    public $defaultValue = false;
    public $notEmpty = false;
    public $allowEmpty = true;

    /**
     * @return ParamRule default type str with length between (0, 1024).
     */
    static function rule($name) {
        $rule = new ParamRule();
        return $rule->name($name)->type('str')->min(0)->max(10240000);
//        return $rule->name($name)->type('str')->min(0)->max(65535);
    }

    /**
     * @param $name
     * @return ParamRule
     */
    public function name($name) {
        $this->name = $name;
        return $this;
    }

    /**
     * @param $title
     * @return ParamRule
     */
    public function title($title) {
        $this->title = $title;
        return $this;
    }

    /**
     * @param $enum1
     * @param null $_
     * @return ParamRule
     */
    public function enum($enum1, $_ = null) {
        $this->type('enum');
        $this->enumValues = func_get_args();
        return $this;
    }

    /**
     * @param $type
     * @return ParamRule
     */
    public function type($type) {
        $this->type = $type;
        return $this;
    }

    /**
     * @param $min
     * @return ParamRule
     */
    public function min($min) {
        $this->min = $min;
        return $this;
    }

    /**
     * @param $max
     * @return ParamRule
     */
    public function max($max) {
        $this->max = $max;
        return $this;
    }

    /**
     * @param $required
     * @return ParamRule
     */
    public function required($required) {
        $this->required = $required;
        return $this;
    }

    /**
     * @param $delimiter
     * @return ParamRule
     */
    public function delimiter($delimiter) {
        $this->delimiter = $delimiter;
        return $this;
    }

    /**
     * @param $regex
     * @return ParamRule
     */
    public function regex($regex) {
        $this->regex = $regex;
        return $this;
    }

    public function widgetType($type,$params)
    {
        $this->widgetType = array($type,$params);
        return $this;
    }
    public function tag($tag) {
        $this->tag = $tag;
        return $this;
    }
    /**
     * @param $able_modify
     * @return ParamRule
     */
    public function able_modify($able_modify) {
        $this->able_modify = $able_modify;
        return $this;
    }
    /**
     * @param $able_show
     * @return ParamRule
     */
    public function able_show($able_show) {
        $this->able_modify = $able_show;
        return $this;
    }
    /**
     * @param $issearch
     * @return ParamRule
     */
    public function issearch($issearch) {
        $this->issearch = $issearch;
        return $this;
    }
    /**
     * @param $issearch
     * @return ParamRule
     */
    public function issorter($issorter) {
        $this->issorter = $issorter;
        return $this;
    }

    /**
     * @return ParamRule
     */
    public function isPhone() {
//        $this->regex = '/^(\+?86)?1[3578]\d{9}$/';
//        $this->regex = '/(^(0{0,1}\d{3,}\-){0,1}\d{7,8}$)|(^[1][3,5,8,4,7][0-9]{9}$)/';
        $this->regex = '/(^(0[0-9]{2,3}\-)?([2-9][0-9]{6,7})+(\-[0-9]{1,4})?$)|(^0?[1][3578][0-9]{9}$)/';
        return $this;
    }
    /**
     * @return ParamRule
     */
    public function isEmail() {
        $this->regex = '/^[A-Za-z0-9\u4e00-\u9fa5]+@[a-zA-Z0-9_-]+(\.[a-zA-Z0-9_-]+)+$/';
        return $this;
    }

    public function isNum(){
        $this->regex = '/^(\+?86)?1[3578]\d{9}$/';
        return $this;
    }

    public function isMoney()
    {
        $this->regex = '/(^[1-9]([0-9]+)?(\.[0-9]{1,2})?$)|(^(0){1}$)|(^[0-9]\.[0-9]([0-9])?$)/';
        return $this;
    }

    public function isDayTime()
    {
        $this->regex = '/^[1-9]\d{3}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])\s+(20|21|22|23|[0-1]\d):[0-5]\d:[0-5]\d$/';
        return $this;
    }


    /**
     * @return ParamRule
     */
    public function isAutoId() {
//        $this->regex = $regex;
        return $this;
    }



    /**
     * @param $defaultValue
     * @return ParamRule
     */
    public function defaultValue($defaultValue) {
        $this->defaultValue = $defaultValue;
        return $this;
    }


    /**
     * @param $notEmpty
     * @return ParamRule
     */
    public function notEmpty($notEmpty) {
        $this->notEmpty = $notEmpty;
        return $this;
    }


    /**
     * @param $notEmpty
     * @return ParamRule
     */
    public function allowEmpty($allowEmpty) {
        $this->allowEmpty = $allowEmpty;
        return $this;
    }


    public function desc() {
        return empty($this->title) ? $this->name : $this->title;
    }
}