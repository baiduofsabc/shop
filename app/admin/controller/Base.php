<?php
namespace app\admin\controller;
use think\Db;
use think\Request;
use think\Controller;
class Base extends Common
{
    public function _initialize(){
        parent::_initialize();
    }
 
    public function index()
    {
       
        
    }
    public function recharge()
    {
        if (request()->isPost()) {
       
            $pageSize = input('limit') ? input('limit') : config('pageSize');
            $list = Db::table(config('database.prefix') . 'recharge')->alias('a')
                ->field('a.*')
                ->where($map)
                ->order('a.id')
                ->paginate(array('list_rows' => $pageSize, 'page' => $page))
                ->toArray();
        
            $result = ['code' => 1, 'msg' => '获取成功!', 'data' => $list['data'], 'count' => $list['total'], 'rel' => 1,'add'=>$address];
            return json($result);
        }
        else {
            return $this->fetch();
        }
        
    }
 
 
    public function rechargeAdd() {
        if (request()->isPost()) {
            $data = input('post.');
            $data['updatetime'] = time();
            if($data['id']) {
               $res = db('recharge')->update($data);   
            }
            else {
               $data['addtime'] = time(); 
               $res = db('recharge')->insert($data); 
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
    public function hospitalInfo() {
        if (request()->isPost()) {

            $info = db('recharge')
                ->field('*')
                ->where('id',input('post.id'))
                ->find();
            $result = ['code' => 1, 'msg' => '获取成功!', 'data' => $info];
            return json($result);
        }
    }
    public function feedback()
    {
        if (request()->isPost()) {
            if(input('post.name')) {
              $map['a.name'] = array('like','%'.input('post.name').'%');
            }
            $page = input('page') ? input('page') : 1;
            $pageSize = input('limit') ? input('limit') : config('pageSize');
            $list = Db::table(config('database.prefix') . 'feedback')->alias('a')
                ->join(config('database.prefix') . 'users at', 'a.user_id = at.id', 'left')
                ->field('a.*,at.username,at.mobile')
                ->where($map)
                ->order('a.id')
                ->paginate(array('list_rows' => $pageSize, 'page' => $page))
                ->toArray();
            foreach ($list['data'] as $k => $v) {
                

                $list['data'][$k]['add_time'] = date('Y-m-d H:i', $v['addtime']);
//                $list['data'][$k]['status'] = $v['status']==1?"上架中":"已下架";
            }
            $result = ['code' => 1, 'msg' => '获取成功!', 'data' => $list['data'], 'count' => $list['total'], 'rel' =>1];
            return json($result);
        }
        else {
            return $this->fetch();
        }
        
    }
 
    public function feedbackInfo() {
        if (request()->isPost()) {
            $info = db('feedback')
                ->field('*')
                ->where('id',input('post.id'))
                ->find();
            if($info['images']) {
                   $info['images'] = explode(",",$info['images']);
                }
                else {
                   $info['images']  = [];
                }    
            $result = ['code' => 1, 'msg' => '获取成功!', 'data' => $info];
            return json($result);
        }

    }
    public function hospitalDel(){
        db('recharge')->where(array('id'=>input('id')))->delete();
        return json(['code'=>1,'msg'=>'删除成功！']);
    }

    public function disease()
    {
        if (request()->isPost()) {
           
            if(input('post.name')) {
              $map['a.name'] = array('like','%'.input('post.name').'%');
            }
            $pageSize = input('limit') ? input('limit') : config('pageSize');
            $list = Db::table(config('database.prefix') . 'disease')->alias('a')
                ->join(config('database.prefix') . 'department at', 'a.department_id = at.id', 'left')
                ->field('a.*,at.name as department_name')
                ->where($map)
                ->order('a.id')
                ->paginate(array('list_rows' => $pageSize, 'page' => $page))
                ->toArray();
            foreach ($list['data'] as $k => $v) {
                $address = $v['province'].','.$v['city'].','.$v['district'];
                $map2['id'] = array('in','(0,'.$address.')');
                $address_info = db('regions')->where($map2)->column('name');
                $list['data'][$k]['address'] =  implode("", $address_info).$v['address'];
                $list['data'][$k]['add_time'] = date('Y-m-d H:i', $v['addtime']);
            }
            $result = ['code' => 1, 'msg' => '获取成功!', 'data' => $list['data'], 'count' => $list['total'], 'rel' => 1,'add'=>$address];
            return json($result);
        }
        else {
            return $this->fetch();
        }
        
    }
 
 
    public function diseaseAdd() {
        if (request()->isPost()) {

            $data = input('post.');

            if($data['id']) {
                $map['id']  = array('neq',$data['id']);
            }
            $map['name'] = input('post.name');

            $info = db('disease')->where($map)->find();
            if($info) {
                $result = ['code' => 0, 'msg' => '疾病已存在!'];
                return json($result); 
            }
            $data['updatetime'] = time();
            if($data['id']) {
               $res = db('disease')->update($data);   
            }
            else {
               $data['addtime'] = time(); 
               $res = db('disease')->insert($data); 
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
    public function diseaseInfo() {
        if (request()->isPost()) {

            $info = db('disease')
                ->field('*')
                ->where('id',input('post.id'))
                ->find();
            $result = ['code' => 1, 'msg' => '获取成功!', 'data' => $info];
            return json($result);
        }

    }
    public function getDepartment() {
        $info = db('department')->select();
        return json($info);
    }

    public function diseaseDel(){
        db('disease')->where(array('id'=>input('id')))->delete();
        return json(['code'=>1,'msg'=>'删除成功！']);
    }

    public function department()
    {
        if (request()->isPost()) {
           
            if(input('post.name')) {
              $map['a.name'] = array('like','%'.input('post.name').'%');
            }
            $pageSize = input('limit') ? input('limit') : config('pageSize');
            $list = Db::table(config('database.prefix') . 'department')->alias('a')
                ->field('a.*')
                ->where($map)
                ->order('a.id')
                ->paginate(array('list_rows' => $pageSize, 'page' => $page))
                ->toArray();
            foreach ($list['data'] as $k => $v) {
                $address = $v['province'].','.$v['city'].','.$v['district'];
                $map2['id'] = array('in','(0,'.$address.')');
                $address_info = db('regions')->where($map2)->column('name');
                $list['data'][$k]['address'] =  implode("", $address_info).$v['address'];
                $list['data'][$k]['add_time'] = date('Y-m-d H:i', $v['addtime']);
            }
            $result = ['code' => 1, 'msg' => '获取成功!', 'data' => $list['data'], 'count' => $list['total'], 'rel' => 1,'add'=>$address];
            return json($result);
        }
        else {
            return $this->fetch();
        }
        
    }
 
 
    public function departmentAdd() {
        if (request()->isPost()) {
            $data = input('post.');
            if($data['id']) {
                $map['id']  = array('neq',$data['id']);
            }
            $map['name'] = input('post.name');
            $info = db('department')->where($map)->find();
            if($info) {
                $result = ['code' => 0, 'msg' => '科室已存在!'];
                return json($result); 
            } 
            $data['updatetime'] = time();
            if($data['id']) {
               $res = db('department')->update($data);   
            }
            else {
               $data['addtime'] = time(); 
               $res = db('department')->insert($data); 
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
    public function departmentInfo() {
        if (request()->isPost()) {

            $info = db('department')
                ->field('*')
                ->where('id',input('post.id'))
                ->find();
            $result = ['code' => 1, 'msg' => '获取成功!', 'data' => $info];
            return json($result);
        }

    }

    public function departmentDel(){
        db('department')->where(array('id'=>input('id')))->delete();
        return json(['code'=>1,'msg'=>'删除成功！']);
    }


    public function notice()
    {
        if (request()->isPost()) {
           
            if(input('post.title')) {
              $map['a.title'] = array('like','%'.input('post.title').'%');
            }
            if(input('post.info')) {
              $map['a.info'] = array('like','%'.input('post.info').'%');
            }
            
            $pageSize = input('limit') ? input('limit') : config('pageSize');
            $list = Db::table(config('database.prefix') . 'notice')->alias('a')
                ->field('a.*')
                ->where($map)
                ->order('a.id desc')
                ->paginate(array('list_rows' => $pageSize, 'page' => $page))
                ->toArray();
            foreach ($list['data'] as $k => $v) {
                $address = $v['province'].','.$v['city'].','.$v['district'];
                $map2['id'] = array('in','(0,'.$address.')');
                $address_info = db('regions')->where($map2)->column('name');
                $list['data'][$k]['address'] =  implode("", $address_info).$v['address'];
                $list['data'][$k]['add_time'] = date('Y-m-d H:i', $v['addtime']);
            }
            $result = ['code' => 1, 'msg' => '获取成功!', 'data' => $list['data'], 'count' => $list['total'], 'rel' => 1,'add'=>$address];
            return json($result);
        }
        else {
            return $this->fetch();
        }
        
    }
 
 
    public function noticeAdd() {
        if (request()->isPost()) {
            $data = input('post.');
            if($data['id']) {
                $map['id']  = array('neq',$data['id']);
            }
 
            $data['updatetime'] = time();
            if($data['id']) {
               $res = db('notice')->update($data);   
            }
            else {
               $data['addtime'] = time(); 
               $res = db('notice')->insert($data); 
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
    public function noticeInfo() {
        if (request()->isPost()) {
            $info = db('notice')
                ->field('*')
                ->where('id',input('post.id'))
                ->find();
            $result = ['code' => 1, 'msg' => '获取成功!', 'data' => $info];
            return json($result);
        }

    }

    public function noticeDel(){
        db('notice')->where(array('id'=>input('id')))->delete();
        return json(['code'=>1,'msg'=>'删除成功！']);
    }



     

}