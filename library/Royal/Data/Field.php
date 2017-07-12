<?php
namespace Royal\Data;
use Royal\Validator\ParamRule;

class Field {
    protected $_rule = array();
    protected $_map = array();
    protected $_desFieldc = array();
    protected $_key = array();
    protected $_traceBlock = array();
    protected $_index;
    protected $_default;

    public static function start() {
        return new Field();
    }
    public function getDesc()
    {
        return $this->_desc;
    }
    public function getMap()
    {
        return $this->_map;
    }
    public function getTraceBlock() {
        return $this->_traceBlock;
    }
    public function getKey() {
        return $this->_key;
    }
    public function getRule()
    {

        return $this->_rule;
    }
    public function noTrace() {
        $this->_traceBlock[$this->_index] = true;
        return $this;
    }
    public function def($name) {
        $this->_index = $name;
        $this->rule(ParamRule::rule($name));
        return $this;
    }
    public function rule($rule) {
        $this->_rule[$this->_index] = $rule;
        return $this;
    }
    public function map($map) {
        $this->_map[$this->_index] = $map;
        return $this;
    }
    public function desc($desc) {
        $this->_desc[$this->_index] = $desc;
        $this->_rule[$this->_index]->title($desc);
        return $this;
    }
    public function notEmpty($notEmpty=false) {
        $this->_rule[$this->_index]->notEmpty($notEmpty);
        return $this;
    }
    public function able_modify($able_modify=true) {
        $this->_rule[$this->_index]->able_modify($able_modify);
        return $this;
    }
    public function able_show($able_show=true) {
        $this->_rule[$this->_index]->able_show($able_show);
        return $this;
    }
    public function tag($tag=true) {
        $this->_rule[$this->_index]->tag($tag);
        return $this;
    }
    
    public function issearch($issearch=false) {
        $this->_rule[$this->_index]->issearch($issearch);
        return $this;
    }
    public function issorter($issorter=false) {
        $this->_rule[$this->_index]->issorter($issorter);
        return $this;
    }
    public function regex($regex) {
        $this->_rule[$this->_index]->regex($regex);
        return $this;
    }



    public function widgetType($type='str',$params=array())
    {
        $this->_rule[$this->_index]->widgetType($type,$params);
        return $this;
    }
    public function widgetStyle($params=array())
    {
        $this->_rule[$this->_index]->widgetStyle($params);
        return $this;
    }
    /**
     * @return ParamRule
     */
    public function isPhone() {
        $this->_rule[$this->_index]->isPhone();
        return $this;
    }

    public function isEmail()
    {
        $this->_rule[$this->_index]->isEmail();
        return $this;
    }
    public function isMoney()
    {
        $this->_rule[$this->_index]->isMoney();
        return $this;
    }
    public function isDayTime()
    {
        $this->_rule[$this->_index]->isDayTime();
        return $this;
    }
    public function key() {
        $fields = func_get_args();
        array_unshift($fields, $this->_index);
        sort($fields);
        $this->_key[] = array(
            'type' => 'normal',
            'field' => $fields,
        );
        return $this;
    }
    public function unique() {
        $fields = func_get_args();
        array_unshift($fields, $this->_index);
        sort($fields);
        $this->_key[] = array(
            'type' => 'unique',
            'field' => $fields,
        );
        return $this;
    }
    public function int() {
        $this->_rule[$this->_index]->type('int')->max(0x7fffffff);
        return $this;
    }

    public function bigint() {
        $this->_rule[$this->_index]->type('bigint')->max(0x7fffffff);
        return $this;
    }

    public function double() {
        $this->_rule[$this->_index]->type('double');
        return $this;
    }

    public function text() {
        $this->_rule[$this->_index]->type('text')->max(65535);
        return $this;
    }
    public function longtext() {
        $this->_rule[$this->_index]->type('longtext')->max(655350);
        return $this;
    }
    public function blob() {
        $this->_rule[$this->_index]->type('blob')->max(65535);
        return $this;
    }

    public function varchar($len) {
        $this->_rule[$this->_index]->type('varchar')->max($len);
        return $this;
    }

    public function datetime($nowtime)
    {
        $nowtime = '0000-00-00 00:00:00';
        $this->_rule[$this->_index]->type('datetime')->max($nowtime?$nowtime:date('y-m-d h:i:s',time()));
        return $this;
    }

    public function default($def)
    {   
        $this->_default = $def;
        return this;
    }

    public function end() {
        return $this;
    }

}
