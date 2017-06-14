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
    public $modifiable = true ;
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
    /**
     * @param $modifiable
     * @return ParamRule
     */
    public function modifiable($modifiable) {
        $this->modifiable = $modifiable;
        return $this;
    }

    /**
     * @return ParamRule
     */
    public function isPhone() {
        $this->regex = '/^(\+?86)?1[3578]\d{9}$/';
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
     * @return ParamRule
     */
    public function isEmail() {
        $this->regex = '/.+@[^@]+\.[^@]*[^\.]$/';
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