<?php
namespace app\miniapi\controller;
use think\Db;
use think\Request;
class Lease extends Common{
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
            if(input('post.is_home')) {
              $map['a.is_home'] = input('post.is_home');
            }
            if(input('post.level_id')) {
              $map['a.level_id'] = input('post.level_id');
            }
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
    //获取等级
    public function show(){
        if (request()->isPost()) {
            $lease =  db('wheelchair')->where('id',1)->find();
            $info['lease'] = $lease;
            $info['img_url'] = $this->imgUrl;
            $info['lease']['images'] = json_decode($info['lease']['images']);
        
           

            $info['spec'] = db('wheelchair_spec')->select();
            foreach ($info['spec'] as $k => $v) {
               $info['spec'][$k]['value'] = explode(",", $v['value']);
            }
            $result = ['code' => 1, 'msg' => '获取成功!', 'data' => $info];
            return json($result);
        }
        
    }
    public function getComment() {
        if (request()->isPost()) {
            $map['a.tend_id'] = input('post.id');
            $page = input('page') ? input('page') : 1;
            $pageSize = input('limit') ? input('limit') : config('pageSize');
            $list = Db::table(config('database.prefix') . 'tend_comment')->alias('a')
                    ->join(config('database.prefix') . 'users at', 'a.user_id = at.id', 'left')
                    ->field('a.*,at.nickName,at.avatarUrl')
                    ->where($map)
                    ->order('a.id')
                    ->paginate(array('list_rows' => $pageSize, 'page' => $page))
                    ->toArray();
            foreach ($list['data'] as $k => $v) {
                $list['data'][$k]['addtime'] = date('Y-m-d H:i:s', $v['addtime']);  
            }     

            $result = ['code' => 1, 'msg' => '获取成功!', 'data' => $list['data'], 'count' => $list['total'], 'rel' => 1];
            return json($result);
        }

    }

}