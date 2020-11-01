<?php
namespace app\admin\controller;
use think\Db;
use think\Request;
use think\Controller;
class Service extends Common
{
    public function _initialize(){
        parent::_initialize();
    }
 
    public function index()
    {
       
        
    }

    public function service()
    {
        if (request()->isPost()) {

            if(input('post.name')) {
                $map['a.name'] = array('like','%'.input('post.name').'%');
            }
            if(input('post.catid')) {
                $map['a.catid'] = input('post.catid');
            }
            $pageSize = input('limit') ? input('limit') : config('pageSize');
            $list = Db::table(config('database.prefix') . 'service')->alias('a')
                ->join(config('database.prefix') . 'service_cate at', 'a.catid = at.id', 'left')
                ->field('a.*,at.name as catname')
                ->where($map)
                ->order('at.sort asc, a.sort asc')
                ->paginate(array('list_rows' => $pageSize, 'page' => $page))
                ->toArray();
            foreach ($list['data'] as $k => $v) {
                $list['data'][$k]['add_time'] = date('Y-m-d H:i', $v['addtime']);
            }
            $result = ['code' => 1, 'msg' => '获取成功!', 'data' => $list['data'], 'count' => $list['total'], 'rel' => 1];
            return json($result);
        }
        else {
            return $this->fetch();
        }

    }
    public function serviceCate()
    {
        if (request()->isPost()) {
            $list = db('service_cate')->order('sort asc')->select();
            $result = ['code' => 1, 'msg' => '获取成功!', 'data' => $list, 'rel' => 1];
            return json($result);
        }
        else {
            return $this->fetch();
        }

    }


    public function serviceAdd() {
        if (request()->isPost()) {
            $data = input('post.');
            if($data['id']) {
                $map['id']  = array('neq',$data['id']);
            }
            $map['name'] = input('post.name');
            $info = db('service')->where($map)->find();
            if($info) {
                $result = ['code' => 0, 'msg' => '服务已存在!'];
                return json($result);
            }
            $data['updatetime'] = time();
            if($data['id']) {
                $res = db('service')->update($data);
            }
            else {
                $data['addtime'] = time();
                $res = db('service')->insert($data);
            }
            if($res) {
                $result = ['code' => 1, 'msg' => '提交成功!'];
            }
            else{
                $result = ['code' => 0, 'msg' => '提交失败!'];
            }
            return json($result);
        }

    }
    public function serviceInfo() {
        if (request()->isPost()) {

            $info = db('service')
                ->field('*')
                ->where('id',input('post.id'))
                ->find();
            $result = ['code' => 1, 'msg' => '获取成功!', 'data' => $info];
            return json($result);
        }

    }

    public function serviceDel(){
        db('service')->where(array('id'=>input('id')))->delete();
        return json(['code'=>1,'msg'=>'删除成功！']);
    }



    public function test()
    {
        if (request()->isPost()) {

            if(input('post.name')) {
                $map['a.name'] = array('like','%'.input('post.name').'%');
            }
            if(input('post.catid')) {
                $map['a.catid'] = input('post.catid');
            }
            $pageSize = input('limit') ? input('limit') : config('pageSize');
            $list = Db::table(config('database.prefix') . 'test')->alias('a')
                ->join(config('database.prefix') . 'test_cate at', 'a.catid = at.id', 'left')
                ->field('a.*,at.name as catname')
                ->where($map)
                ->order('at.sort asc, a.sort asc')
                ->paginate(array('list_rows' => $pageSize, 'page' => $page))
                ->toArray();
            foreach ($list['data'] as $k => $v) {
                $list['data'][$k]['add_time'] = date('Y-m-d H:i', $v['addtime']);
            }
            $result = ['code' => 1, 'msg' => '获取成功!', 'data' => $list['data'], 'count' => $list['total'], 'rel' => 1];
            return json($result);
        }
        else {
            return $this->fetch();
        }

    }
    public function testCate()
    {
        if (request()->isPost()) {
            $list = db('test_cate')->order('sort asc')->select();
            $result = ['code' => 1, 'msg' => '获取成功!', 'data' => $list, 'rel' => 1];
            return json($result);
        }
        else {
            return $this->fetch();
        }

    }
    public function serviceCateInfo()
    {
        if (request()->isPost()) {
            $list = db('service_cate')->order('sort asc')->select();
            foreach ($list as $k => $v) {
                $list[$k]['service'] = db('service')->where('catid',$v['id'])->order('sort asc')->select();
            }
            $result = ['code' => 1, 'msg' => '获取成功!', 'data' => $list, 'rel' => 1];
            return json($result);
        }
        else {
            return $this->fetch();
        }

    }

    

    public function testAdd() {
        if (request()->isPost()) {
            $data = input('post.');
            if($data['id']) {
                $map['id']  = array('neq',$data['id']);
            }
            $map['name'] = input('post.name');
            $info = db('test')->where($map)->find();
            if($info) {
                $result = ['code' => 0, 'msg' => '服务已存在!'];
                return json($result);
            }
            $data['updatetime'] = time();
            if($data['id']) {
                $res = db('test')->update($data);
            }
            else {
                $data['addtime'] = time();
                $res = db('test')->insert($data);
            }
            if($res) {
                $result = ['code' => 1, 'msg' => '提交成功!'];
            }
            else{
                $result = ['code' => 0, 'msg' => '提交失败!'];
            }
            return json($result);
        }

    }
    public function testInfo() {
        if (request()->isPost()) {

            $info = db('test')
                ->field('*')
                ->where('id',input('post.id'))
                ->find();
            $result = ['code' => 1, 'msg' => '获取成功!', 'data' => $info];
            return json($result);
        }

    }

    public function testDel(){
        db('test')->where(array('id'=>input('id')))->delete();
        return json(['code'=>1,'msg'=>'删除成功！']);
    }

    
    public function recommend()
    {
        if (request()->isPost()) {

            if(input('post.name')) {
                $map['a.name'] = array('like','%'.input('post.name').'%');
            }
            if(input('post.catid')) {
                $map['a.catid'] = input('post.catid');
            }
            $pageSize = input('limit') ? input('limit') : config('pageSize');
            $list = Db::table(config('database.prefix') . 'recommend')->alias('a')
                ->field('a.*')
                ->where($map)
                ->order('a.sort asc')
                ->paginate(array('list_rows' => $pageSize, 'page' => $page))
                ->toArray();
            foreach ($list['data'] as $k => $v) {
                $list['data'][$k]['add_time'] = date('Y-m-d H:i', $v['addtime']);
            }
            $result = ['code' => 1, 'msg' => '获取成功!', 'data' => $list['data'], 'count' => $list['total'], 'rel' => 1];
            return json($result);
        }
        else {
            return $this->fetch();
        }

    }
    public function recommendAdd() {
        if (request()->isPost()) {
            $data = input('post.');
            if($data['id']) {
                $map['id']  = array('neq',$data['id']);
            }
            $map['name'] = input('post.name');
            $info = db('recommend')->where($map)->find();
            if($info) {
                $result = ['code' => 0, 'msg' => '服务已存在!'];
                return json($result);
            }
            $data['updatetime'] = time();
            if($data['id']) {
                $res = db('recommend')->update($data);
            }
            else {
                $data['addtime'] = time();
                $res = db('recommend')->insert($data);
            }
            if($res) {
                $result = ['code' => 1, 'msg' => '提交成功!'];
            }
            else{
                $result = ['code' => 0, 'msg' => '提交失败!'];
            }
            return json($result);
        }

    }
    public function recommendInfo() {
        if (request()->isPost()) {

            $info = db('recommend')
                ->field('*')
                ->where('id',input('post.id'))
                ->find();
            $result = ['code' => 1, 'msg' => '获取成功!', 'data' => $info];
            return json($result);
        }

    }

    public function recommendDel(){
        db('recommend')->where(array('id'=>input('id')))->delete();
        return json(['code'=>1,'msg'=>'删除成功！']);
    }

     

}