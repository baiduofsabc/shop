<?php
namespace app\home\controller;
class Srticle extends Common{
    public function _initialize(){
        parent::_initialize();
    }
    public function index(){
        return $this->fetch();
    }
}