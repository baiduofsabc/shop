<?php
namespace app\miniapi\controller;
use think\Db;
use think\Request;
class Transport extends Common{
    public function _initialize(){
        parent::_initialize();
        //$this->checkToken();  
    }
    public function show(){
        if (request()->isPost()) {
            $info =  db('transport')->where('id',1)->find();
            $info['images'] = json_decode($info['images']);
            $info['img_url'] =  $this->imgUrl;
            $coupon =  db('coupon')->where('use_in',3)->select();
            $result = ['code' => 1, 'msg' => '获取成功!', 'data' => $info,'discount'=>$coupon];
            return json($result);
        }   
    }
    
    

}