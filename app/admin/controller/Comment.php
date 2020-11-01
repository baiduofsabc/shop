<?php
namespace app\admin\controller;
use think\Db;
use think\Request;
use think\Controller;
class Comment extends Common
{
    public function _initialize(){
        parent::_initialize();
    }
    public function index()
    {
        if (request()->isPost()) {
            $map=[];
            if(input('post.tend_id')) {
              $map['tid'] = input('post.tend_id');
            }
            if(input('post.mod')) {
              $map['mod'] = input('post.mod');
            }
            $page = input('page') ? input('page') : 1;
           
            $pageSize = input('limit') ? input('limit') : config('pageSize');
            $list = Db::table(config('database.prefix') . 'comment')->alias('a')
                ->field('a.*')
                ->where($map)
                ->order('a.id')
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
    public function setStatus(){
        if (request()->isPost()) {
            $map=[];
            if(input('post.id')) {
              $map['id'] = input('post.id');
            }
            else {
              return json(['code'=>0,'msg'=>'设置失败！']); 
            }
            if(input('post.status')) {
              $map['status'] = input('post.status');
            }
            else {
              return json(['code'=>0,'msg'=>'设置失败！']); 
            }
            db('comment')->update($map);
            return json(['code'=>1,'msg'=>'设置成功！']); 
        }
    }
    public function del(){
            db('comment')->where(array('id'=>input('id')))->delete();
            return json(['code'=>1,'msg'=>'删除成功！']); 
    } 

}