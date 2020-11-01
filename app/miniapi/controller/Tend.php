<?php
namespace app\miniapi\controller;
use think\Db;
use think\Request;
class Tend extends Common{
    public function _initialize(){
        parent::_initialize();
        //$this->checkToken();  
    }
    public function index(){
        if (request()->isPost()) {
   
            if(input('post.name')) {
              $map['a.name'] = array('like','%'.input('post.name').'%');
            }
            if(input('post.sex')) {
              $map['a.sex'] = input('post.sex');
            }
            if(input('post.is_house')) {
              $map['a.is_house'] = input('post.is_house');
            }
            if(input('post.level_id')) {
              $map['a.level_id'] = input('post.level_id');
            }
           if(input('post.star')) {
              $map['a.star'] = input('post.star');
            }
            if(input('post.hospital_id')) {
              $map['a.hospital_id'] = input('post.hospital_id');
            }

            $map['a.status'] = 1;
            $page = input('page') ? input('page') : 1;
            $pageSize = input('limit') ? input('limit') : config('pageSize');
            $list = Db::table(config('database.prefix') . 'tend')->alias('a')
                ->join(config('database.prefix') . 'tend_level at', 'a.level_id = at.level_id', 'left')
                ->field('a.*,at.level_name')
                ->where($map)
                ->order('a.id')
                ->paginate(array('list_rows' => $pageSize, 'page' => $page))
                ->toArray();
            $list['data'] =  $this->addImgUrl($list['data'],'thumb');    
            foreach ($list['data'] as $k => $v) {
                $list['data'][$k]['add_time'] = date('Y-m-d H:i', $v['addtime']);
                $map2['tag_id'] = array('in',$v['tag_ids']);
                $list['data'][$k]['tag_name'] = db('tend_tag')->where($map2)->order('sort ASC')->select();
            }
            $result = ['code' => 1, 'msg' => '获取成功!', 'data' => $list['data'], 'count' => $list['total'], 'rel' => 1];
            return json($result);
        }  
    }
 
    public function show(){
        if (request()->isPost()) {
            $info =  db('tend')->where('id',input('post.id'))->find();
            $info['tag_name'] = db('tend_tag')->where('tag_id',array('in',$info['tag_ids']))->order('sort ASC')->select();
            $info['tag_name_com'] = db('tend_tag_com')->where('tag_id',array('in',$info['tag_com_ids']))->order('sort ASC')->select();
            $info['level'] = db('tend_level')->where('level_id',$info['level_id'])->find();
            $info['level_name'] = $info['level']['level_name'];
            $info['hospital'] = db('hospital')->where('id',$info['hospital_id'])->find();
            $info['person_id'] = substr($info['person_id'],0,4)."******".substr($info['person_id'],-4,4);
            $info['hospital_name'] = $info['hospital']['name'];
            $info['thumb'] = $this->imgUrl.$info['thumb'];
            $info['birthday']  = date('Y-m-d', $info['birthday']/1000);    
            $result = ['code' => 1, 'msg' => '获取成功!', 'data' => $info];
            return json($result);
        }
        
    }
    // public function getComment() {
    //     if (request()->isPost()) {
    //         $map['a.tid'] = input('post.id');
    //         $map['a.comment_status'] = 1;
    //         $page = input('page') ? input('page') : 1;
    //         $pageSize = input('limit') ? input('limit') : config('pageSize');
    //         $list = Db::table(config('database.prefix') . 'tend_order')->alias('a')
    //                 ->join(config('database.prefix') . 'users at', 'a.user_id = at.id', 'left')
    //                 ->join(config('database.prefix') . 'comment b', 'a.id = b.order_id', 'left')
    //                 ->field('a.*,at.nickName,at.avatarUrl,b.content,b.star,b.addtime as comment_addtime')
    //                 ->where($map)
    //                 ->order('a.id')
    //                 ->paginate(array('list_rows' => $pageSize, 'page' => $page))
    //                 ->toArray();
    //         foreach ($list['data'] as $k => $v) {
          
    //             $list['data'][$k]['comment_addtime']  = date('Y-m-d H:i:s', $list['data'][$k]['comment_addtime']); 
                
    //         }     
    //         $result = ['code' => 1, 'msg' => '获取成功!', 'data' => $list['data'], 'count' => $list['total'], 'rel' => 1];
    //         return json($result);
    //     }

    // }
    public function getComment() {
        if (request()->isPost()) {
            $map['a.tid'] = input('post.id');
            $map['a.mod'] = "tend";
            $map['a.status'] = 1;
            $page = input('page') ? input('page') : 1;
            $pageSize = input('limit') ? input('limit') : config('pageSize');
            $list = Db::table(config('database.prefix') . 'comment')->alias('a')
                    ->join(config('database.prefix') . 'users at', 'a.user_id = at.id', 'left')
                    ->field('a.*,at.nickName,at.avatarUrl')
                    ->where($map)
                    ->order('a.id')
                    ->paginate(array('list_rows' => $pageSize, 'page' => $page))
                    ->toArray();
            foreach ($list['data'] as $k => $v) {
                $list['data'][$k]['comment_addtime']  = date('Y-m-d H:i:s', $list['data'][$k]['addtime']);    
            }
            $result = ['code' => 1, 'msg' => '获取成功!', 'data' => $list['data'], 'count' => $list['total'], 'rel' => 1];
            return json($result);
        }
    }
    public function service(){
        if (request()->isPost()) {

            $service_cate =  db('service_cate')->order('sort asc')->select();
            $map=[];
            foreach ($service_cate as $k => $v) {
                $map['catid']=$v['id'];
                $map['status']=1;
                $service_cate[$k]['service']=  db('service')->where($map)->order('sort asc')->select();
                foreach ($service_cate[$k]['service'] as $k2 => $v2) {
                    if(empty($service_cate[$k]['service'][$k2]['service_item'])) {
                        $service_cate[$k]['service'][$k2]['service_item_arr'] = [];
                    }
                    else {
                      $service_cate[$k]['service'][$k2]['service_item_arr'] = explode(",", $v2['service_item']);  
                    }
                    
                } 
            } 
            $result = ['code' => 1, 'msg' => '获取成功!', 'data' => $service_cate];
            return json($result);
        }
    }
    public function test(){
        if (request()->isPost()) {
            $test_cate =  db('test_cate')->order('sort asc')->select();
            $map=[];
            foreach ($test_cate as $k => $v) {
                $map['catid']=$v['id'];
                $map['status']=1;
                $test_cate[$k]['test']=  db('test')->where($map)->order('sort asc')->select();
            } 
            $result = ['code' => 1, 'msg' => '获取成功!', 'data' => $test_cate];
            return json($result);
        }
    }

    public function hospitalList(){
        if (request()->isPost()) {
            $map = [];
            if(input('post.name')) {
              $map['name'] = array('like','%'.input('post.name').'%');
            }
            $hospital =  db('hospital')->where($map)->order('id desc')->select();
            $result = ['code' => 1, 'msg' => '获取成功!', 'data' => $hospital];
            return json($result);
        }
    }


}