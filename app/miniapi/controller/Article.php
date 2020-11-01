<?php
namespace app\miniapi\controller;
use think\Db;
use think\Request;
class Article extends Common{
    public function _initialize(){
        parent::_initialize();
        //$this->checkToken();  
    }
    public function index(){
        if (request()->isPost()) {
            if(input('post.parentid')) {
              $map['parentid'] = input('post.parentid');
            }
            $cate = db('category')->where($map)->order('listorder ASC')->select();
            foreach ($cate as $k => $v) {
                $cate[$k]['createtime'] = date('Y-m-d H:i', $v['createtime']);
                $cate[$k]['image'] =$this->imgUrl.'/public'.$v['image'];
            }
            $result = ['code' => 1, 'msg' => '获取成功!', 'data' => $cate];
            return json($result);
        }  
    }
     public function getList(){
        if (request()->isPost()) {
            if(input('post.id')) {
              $map['catid'] = input('post.id');
            }
            $page = input('page') ? input('page') : 1;
            $pageSize = input('limit') ? input('limit') : config('pageSize');
            $list = Db::table(config('database.prefix') . 'article')->alias('a')
                ->field('a.*')
                ->where($map)
                ->order('a.id')
                ->paginate(array('list_rows' => $pageSize, 'page' => $page))
                ->toArray();
            foreach ($list['data'] as $k => $v) {
                $list['data'][$k]['createtime'] = date('Y-m-d H:i', $v['createtime']);
                $list['data'][$k]['thumb'] =$this->imgUrl.'/public'.$v['thumb'];
            }
            $result = ['code' => 1, 'msg' => '获取成功!', 'data' => $list['data'], 'count' => $list['total'], 'rel' => 1];
            return json($result);
        }  
    }
 
    public function show(){
        if (request()->isPost()) {
            if(input('post.mod')) {
                $mod = input('post.mod');
            }
            else {
                $mod = 'article';
            }
            $info =  db($mod)->where('id',input('post.id'))->find();
            $info['catname'] = db('category')->where('id',$info['catid'])->find()['catname'];
            $info['createtime'] = date('Y-m-d H:i', $info['createtime']);
            $result = ['code' => 1, 'msg' => '获取成功!', 'data' => $info];
            return json($result);
        }
        
    }
    public function notice(){
        if (request()->isPost()) {
            $this->checkToken();
            $map['catid'] = 18;
            $map['createtime'] = array('gt',$this->userInfo['notice_time']);
            $page = input('page') ? input('page') : 1;
            $pageSize = input('limit') ? input('limit') : config('pageSize');
            $list = Db::table(config('database.prefix') . 'article')->alias('a')
                ->field('a.*')
                ->where($map)
                ->order('a.id desc')
                ->paginate(array('list_rows' => $pageSize, 'page' => $page))
                ->toArray();
        
            foreach ($list['data'] as $k => $v) {
                $list['data'][$k]['createtime'] = date('Y-m-d H:i', $v['createtime']);
                $list['data'][$k]['thumb'] =$this->imgUrl.'/public'.$v['thumb'];
            }
            $result = ['code' => 1, 'msg' => '获取成功!', 'data' => $list['data'], 'count' => $list['total'], 'rel' => $map];
            return json($result);
        }
        
    }
    public function active(){
        if (request()->isPost()) {
            $map['catid'] = 19;
            $page = input('page') ? input('page') : 1;
            $pageSize = input('limit') ? input('limit') : config('pageSize');
            $list = Db::table(config('database.prefix') . 'article')->alias('a')
                ->field('a.*')
                ->where($map)
                ->order('a.id desc')
                ->paginate(array('list_rows' => $pageSize, 'page' => $page))
                ->toArray();
        
            foreach ($list['data'] as $k => $v) {
                $list['data'][$k]['createtime'] = date('Y-m-d H:i', $v['createtime']);
                $list['data'][$k]['thumb'] =$this->imgUrl.'/public'.$v['thumb'];
            }
            $result = ['code' => 1, 'msg' => '获取成功!', 'data' => $list['data'], 'count' => $list['total'], 'rel' => $map];
            return json($result);
        }    
    }
    public function ask(){
        if (request()->isPost()) {
            $map['catid'] = 20;
            $page = input('page') ? input('page') : 1;
            $pageSize = input('limit') ? input('limit') : config('pageSize');
            $list = Db::table(config('database.prefix') . 'article')->alias('a')
                ->field('a.*')
                ->where($map)
                ->order('a.id desc')
                ->paginate(array('list_rows' => $pageSize, 'page' => $page))
                ->toArray();

            foreach ($list['data'] as $k => $v) {
                $list['data'][$k]['createtime'] = date('Y-m-d H:i', $v['createtime']);
                $list['data'][$k]['thumb'] =$this->imgUrl.'/public'.$v['thumb'];
            }
            $result = ['code' => 1, 'msg' => '获取成功!', 'data' => $list['data'], 'count' => $list['total'], 'rel' => $map];
            return json($result);
        }
    }
    public function askV1(){
        if (request()->isPost()) {
            $map['parentid'] = 20;
            $category = db('category')->where($map)->select();
            foreach ($category as $k => $v) {
                $category[$k]['list'] =  db('article')->where('catid',$v['id'])->select();
            }
            $result = ['code' => 1, 'msg' => '获取成功!', 'data' => $category];
            return json($result);
        }
    }
    
    public function setting(){
        if (request()->isPost()) {
            $map['catid'] = 21;
            $page = input('page') ? input('page') : 1;
            $pageSize = input('limit') ? input('limit') : config('pageSize');
            $list = Db::table(config('database.prefix') . 'article')->alias('a')
                ->field('a.*')
                ->where($map)
                ->order('a.id desc')
                ->paginate(array('list_rows' => $pageSize, 'page' => $page))
                ->toArray();

            foreach ($list['data'] as $k => $v) {
                $list['data'][$k]['createtime'] = date('Y-m-d H:i', $v['createtime']);
                $list['data'][$k]['thumb'] =$this->imgUrl.'/public'.$v['thumb'];
            }
            $result = ['code' => 1, 'msg' => '获取成功!', 'data' => $list['data'], 'count' => $list['total'], 'rel' => $map];
            return json($result);
        }
    }

 

}