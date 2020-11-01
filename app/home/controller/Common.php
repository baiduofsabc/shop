<?php
namespace app\home\controller;
use think\Input;
use think\Db;
use clt\Leftnav;
use think\Request;
use think\Controller;
class Common extends Controller{
    protected $pagesize;
    public function _initialize(){
        $this->assign('head_title',"服饰专版");
        $this->assign('seo_title',"服饰专版");
        $url_path=$_SERVER['REQUEST_URI'];
        
        // if(strlen($url_path)>1&&!strpos($url_path,"-")){
        //     return $this->error('访问地址不存在，返回上次访问页面中...');
        // }
        if(session('user.id')) {
           $this->userInfo=db('users')->where(['id'=>session('user.id')])->find();   
        }
        $this->getData= input('get.');
        $this->banner = db('config')->where('id',90)->find()['value'];
        $this->goodsTip = db('config')->where('id',91)->find()['value'];
   
        
            
    }

    //退出登陆
    public function logout(){
        session('user',null);
        $this->redirect('Login/index');
    }
    public function _empty(){
        return $this->error('空操作，返回上次访问页面中...');
    }
}