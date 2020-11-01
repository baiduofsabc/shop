<?php
namespace app\miniapi\controller;
use think\Input;
use think\Db;
use clt\Leftnav;
use think\Request;
use think\Controller;
class Common extends Controller{
    protected $pagesize;
    public function _initialize(){
         $this->userInfo = [];
         $this->wechat = db('wechat')->where('id',1)->find();
         $this->imgUrl = "https://api.jscyph.com";
    }
    public function addImgUrl($arr,$field) //定义方法
    { 
         foreach ($arr as $k=>$v){
             $arr[$k][$field] = $this->imgUrl.$arr[$k][$field];
         }
         return $arr;
    }

    public function get_getallheaders() //定义方法
    { 
         foreach ($_SERVER as $name => $value) //循环_SERVER数组
         { 
             if (substr($name, 0, 5) == 'HTTP_') //前5个字符是HTTP_的进入循环
             { 
               $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value; 
             } 
         } 
         return $headers;
    }
    public function checkToken() //定义方法
    { 
        $map['token'] = $this->get_getallheaders()['Token'];
        if($map['token']) {
            $find = db('users')->where($map)->find();
            if(!$find) {
                $this->userInfo = [];
                return ['code'=>0,'msg'=>'请先登录'];//token错误
                exit;
            }
            else {
                $this->userInfo = $find;
                $this->token = $map['token'];
                return ['code'=>1,'msg'=>'验证成功'];//token不能为空  
            }
        }
        else {
            $this->userInfo = [];
            return ['code'=>0,'msg'=>'请先登录'];//token不能为空
            exit;
        }    
    }
    public function object_array($array)
    {
       if(is_object($array))
       {
        $array = (array)$array;
       }
       if(is_array($array))
       {
        foreach($array as $key=>$value)
        {
         $array[$key] = $this->object_array($value);
        }
       }
       return $array;
    }
    public function _empty(){
        return $this->error('空操作，返回上次访问页面中...');
    }
}