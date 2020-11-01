<?php
namespace app\admin\controller;
use think\Db;
use think\Request;
use think\Controller;
class Goods extends Common
{
    public function _initialize(){
        parent::_initialize();

    }
    
    public function index()
    {
        if (request()->isPost()) {
            if(input('post.name')) {
              $map['a.name'] = array('like','%'.input('post.name').'%');
            }
            if(input('post.status')) {
              $map['a.status'] = input('post.status');
            }
            if(input('post.catid')) {
              $map['a.catid'] = input('post.catid');
            }
            $page = input('page') ? input('page') : 1;
            $pageSize = input('limit') ? input('limit') : config('pageSize');
            $list = Db::table(config('database.prefix') . 'goods')->alias('a')
                ->join(config('database.prefix') . 'category at', 'a.catid = at.id', 'left')
                ->field('a.*,at.catname as cate')
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
     public function getLevel()
    {
        if (request()->isPost()) {
            $list = db('category')->where('parentid',0)->select();
            foreach ($list as $k => $v) {
                $result[$k]['label'] = $v['catname'];
                $list2 = db('category')->where('parentid',$v['id'])->select();
                foreach ($list2 as $k2 => $v2) {
                    $result[$k]['options'][$k2]['value'] = $v2['id'];
                    $result[$k]['options'][$k2]['label'] = $v2['catname'];
                }
//                 
            }

            return json($result);
        }     
    }
    public function getArea()
    {
        if (request()->isPost()) {
            $map['pid'] = input('post.pid');
            $result = db('regions')->where($map)->select();
            return json($result);
        }     
    }
    public function getHospital()
    {
        if (request()->isPost()) {
            if(input('post.province')) {
                $map['province'] = input('post.province');
            }
            if(input('post.city')) {
                $map['city'] = input('post.city');
            }
            if(input('post.city')) {
                $map['district'] = input('post.district');
            }
            if($this->adminInfo['hospital_id']&&session('aid')!=1) {
              $map['id'] = $this->adminInfo['hospital_id'];
            }
            $result = db('hospital')->where($map)->select();
            return json($result);
        }     
    }
    public function add() {
        if (request()->isPost()) {
            $data = input('post.');
            $data['updatetime'] = time();
            if($data['id']) {
               $res = db('goods')->update($data);   
            }
            else {
               $data['addtime'] = time(); 
               $res = db('goods')->insert($data); 
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
    public function info() {
        if (request()->isPost()) {
            $info = db('goods')
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

    public function del(){
        db('tend')->where(array('id'=>input('id')))->delete();
        return json(['code'=>1,'msg'=>'删除成功！']);
    }

    public function level() {
        if (request()->isPost()) {
            $res = db('tend_level')->order('sort asc')->select();
            return json($res);
        }
        else{
            return $this->fetch();
        }

    }
    public function levelAdd() {
        if (request()->isPost()) {
            $data = input('post.');
            $data['updatetime'] = time();
            if($data['level_id']) {
                $res = db('tend_level')->update($data);
            }
            else {
                $data['addtime'] = time();
                $res = db('tend_level')->insert($data);
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
    public function levelDel(){
        $isUse = db('tend')->where(array('level_id'=>input('level_id')))->find();
        if($isUse) {
            return json(['code'=>0,'msg'=>'等级已被使用，无法删除！']);
        }
        else{
            db('tend_level')->where(array('level_id'=>input('level_id')))->delete();
            return json(['code'=>1,'msg'=>'删除成功！']); 
        }
        
    }

    public function tag() {
        if (request()->isPost()) {
            $res = db('tend_tag')->order('sort asc')->select();
            return json($res);
        }
        else{
            return $this->fetch();
        }

    }
    public function tagList() {
        if (request()->isPost()) {
            $res = db('tend_tag')->order('sort asc')->select();
            return json($res);
        }
        else{
            return $this->fetch();
        }

    }
    public function tagAdd() {
        if (request()->isPost()) {
            $data = input('post.');
            $data['updatetime'] = time();
            if($data['tag_id']) {
                $res = db('tend_tag')->update($data);
            }
            else {
                $data['addtime'] = time();
                $res = db('tend_tag')->insert($data);
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
    public function tagDel(){
        $map[]=['exp','FIND_IN_SET('.input('tag_id').',tag_ids)'];
        $isUse = db('tend')->where($map)->find();
        if($isUse) {
            return json(['code'=>0,'msg'=>'标签已被使用，无法删除！']);
        }
        else{
            db('tend_tag')->where(array('tag_id'=>input('tag_id')))->delete();
            return json(['code'=>1,'msg'=>'删除成功！']); 
        }
        
    }



    public function tagCom() {
        if (request()->isPost()) {
            $res = db('tend_tag_com')->order('sort asc')->select();
            return json($res);
        }
        else{
            return $this->fetch();
        }
    }
    public function tagComList() {
        if (request()->isPost()) {
            $res = db('tend_tag_com')->order('sort asc')->select();
            return json($res);
        }
        else{
            return $this->fetch();
        }
    }
    public function tagComAdd() {
        if (request()->isPost()) {
            $data = input('post.');
            $data['updatetime'] = time();
            if($data['tag_id']) {
                $res = db('tend_tag_com')->update($data);
            }
            else {
                $data['addtime'] = time();
                $res = db('tend_tag_com')->insert($data);
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
    public function tagComDel(){
        $map[]=['exp','FIND_IN_SET('.input('tag_id').',tag_com_ids)'];
        $isUse = db('tend')->where($map)->find();
        if($isUse) {
            return json(['code'=>0,'msg'=>'标签已被使用，无法删除！']);
        }
        else{
            db('tend_tag_com')->where(array('tag_id'=>input('tag_id')))->delete();
            return json(['code'=>1,'msg'=>'删除成功！']); 
        }
        
    }


    

   

}