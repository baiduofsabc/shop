<?php

namespace app\home\controller;

use think\Db;
use think\Request;

class Index extends Common
{
    public function _initialize()
    {
        parent::_initialize();
        $this->assign('foot_active', 0);
    }

    public function index()
    {
        $parentid=db('category')->where('parentid','<>','0')->min('parentid');
        $info=db('category')->where('parentid',$parentid)->order('listorder asc')->find();
        $catid = $info['id'];
        return $this->redirect(url('Goods/list_list',['catid' => $catid]));
    }
    public function index_hot()
    {
        if (request()->isPost()) {
            $info['list'] = db('goods')->field('id,price,thumb')->order('addtime asc , id desc')->select();
            $info['banner'] = $this->banner;
            $result = ['code' => 0, 'msg' => '获取成功!', 'data' => $info];
            return json($result);
        }
        return $this->fetch('index');
    }
    public function indexList()
    {
        if (request()->isPost()) {
            $map['a.type_id'] = input('post.type_id');
            $map['a.endtime'] = array('egt', time());
            $page = input('page') ? input('page') : 1;
            $pageSize = input('limit') ? input('limit') : config('pageSize');
            $list = Db::table(config('database.prefix') . 'ad')->alias('a')
                ->join(config('database.prefix') . 'goods at', 'a.goods_id = at.id')
                ->field('at.*,a.endtime,a.addtime')
                ->where($map)
                ->order('a.addtime asc,at.id desc')
                ->paginate(array('list_rows' => $pageSize, 'page' => $page))
                ->toArray();
            foreach ($list['data'] as $k => $v) {
                $list['data'][$k]['addtime'] = date('Y-m-d H:i', $v['addtime']);
            }
            // echo '<pre>';print_r($list);exit;
            $result = ['code' => 1, 'msg' => '获取成功!', 'data' => $list['data'], 'count' => $list['total'], 'rel' => db('goods')->getLastSql()];
            return json($result);
        }

    }
    //退出登陆
    public function logout(){
        $_SESSION = array(); //清除SESSION值.
        if(isset($_COOKIE[session_name()])){  //判断客户端的cookie文件是否存在,存在的话将其设置为过期.
            setcookie(session_name(),'',time()-1,'/');
        }
        session_destroy();  //清除服务器的sesion文件
        $this->redirect('login/index');
    }

}