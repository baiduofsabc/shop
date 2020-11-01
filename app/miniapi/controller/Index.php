<?php
namespace app\miniapi\controller;
use think\Db;
use think\Request;
class Index extends Common{
    public function _initialize(){
        parent::_initialize();
        //$this->checkToken();  
    }
    public function object_array($array) {  
        if(is_object($array)) {  
            $array = (array)$array;  
         } if(is_array($array)) {  
             foreach($array as $key=>$value) {  
                 $array[$key] = $this->object_array($value);  
                 }  
         }  
         return $array;  
    }
    public function index(){
        $indexInfo['gy_pic'] =  db('category')->where('parentid',3)->column('image');
        foreach ($indexInfo['gy_pic'] as $k => $v) {
            $indexInfo['gy_pic'][$k] = $this->imgUrl.'/public'.$v;
        }
        $bannerInfo =   db('config')->where(array('name'=>'banner'))->find()['value'];
        $indexInfo['bannerInfo'] =  json_decode($bannerInfo);
        $tend_map['pay_status'] = 1;
        $indexInfo['orderInfo'] = db('tend_order')->where($tend_map)->order('id desc')->limit(6)->select();
        foreach ($indexInfo['orderInfo'] as $k2 => $k2) {
            $indexInfo['orderInfo'][$k2]['tman_name'] = mb_substr($indexInfo['orderInfo'][$k2]['tman_name'],0,1,'utf-8')."**";
        }
        $indexInfo['recommendInfo'] =  db('recommend')->select();
        $indexInfo['adInfo'] =   db('page')->where(array('id'=>35))->find();
        $indexInfo['adInfo']['thumb'] = $this->imgUrl.'/public'.$indexInfo['adInfo']['thumb'];
        $result = ['code' => 1, 'msg' => '获取成功!', 'data' => $indexInfo,'imgUrl'=>$this->imgUrl];
    	return json($result);
    }
    public function systemInfo(){
        $systemInfo =   db('system')->where(array('id'=>1))->find();
        $systemInfo['logo'] =   $this->imgUrl.'/public'.$systemInfo['logo'];
        //$systemInfo['shareInfo']['title'] = $systemInfo['mini_name'];
        $systemInfo['shareInfo']['imageUrl'] = $this->imgUrl.'/public'.$systemInfo['share_img'];




        $result = ['code' => 1, 'msg' => '获取成功!', 'data' => $systemInfo,'imgUrl'=>$this->imgUrl];
        return json($result);
    }
    
   
}