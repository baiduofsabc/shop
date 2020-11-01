<?php

namespace app\home\controller;

use think\Db;
use think\Log;
use think\Request;

class Goods extends Common
{
    public function _initialize()
    {
        parent::_initialize();
        $this->assign('foot_active', 1);
    }

    public function showimg()
    {
        $this->assign('url', input('url'));
        return $this->fetch();
    }

    public function index()
    {
        if (request()->isPost()) {
            $info['category'] = db('category')->field('id,catname as text')->where('parentid', 0)->select();
            foreach ($info['category'] as $k => $v) {

                $info['category'][$k]['children'] = db('category')->field('id,catname as text,listorder')->where('parentid', $v['id'])->order('listorder asc')->select();
            }
            $info['banner'] = $this->banner;
            $result = ['code' => 1, 'msg' => '获取成功!', 'data' => $info];
            return json($result);
        }
        return $this->fetch();
    }

    public function listAd()
    {
        if (request()->isPost()) {
            $info['banner'] = $this->banner;
            $result = ['code' => 1, 'msg' => '获取成功!', 'data' => $info];
            return json($result);
        }
        return $this->fetch();
    }

    public function list_today()
    {
        if (request()->isPost()) {
            $map['a.type_id'] = 3;
            $page = input('page') ? input('page') : 1;
            $pageSize = input('limit') ? input('limit') : config('pageSize');
            $list = Db::table(config('database.prefix') . 'ad')->alias('a')
                ->join(config('database.prefix') . 'goods at', 'a.goods_id = at.id', 'left')
                ->field('at.*,a.endtime,a.addtime')
                ->where($map)
                ->order('a.addtime desc,at.id desc')
                ->paginate(array('list_rows' => $pageSize, 'page' => $page))
                ->toArray();
            foreach ($list['data'] as $k => $v) {
                $list['data'][$k]['addtime'] = date('Y-m-d H:i', $v['addtime']);
            }
            $result = ['code' => 1, 'msg' => '获取成功!', 'data' => $list['data'], 'count' => $list['total'], 'rel' => db('goods')->getLastSql()];
            return json($result);
        }
        $this->assign('foot_active', 2);
        $this->assign('head_title', '今日之星');
        return $this->fetch();
    }

    public function list_list()
    {

        if (request()->isPost()) {
            $adMap = [];
            if (input('catid')) {
                $map['catid'] = input('catid');
                $adMap['a.catid'] = input('catid');
                $map['status'] = 1;
                $adMap['a.status'] = 1;
            }
//            $map["a.endtime"] = [">=" , time()];

//            $map['at.type_id'] = 1;
            $page = input('page') ? input('page') : 1;
            $pageSize = input('limit') ;

            $adMap["at.type_id"] = 1;
            $adMap["at.endtime"] = [">=" , time()];
            $adMap["a.status"] = 1;
            $countList = Db::table(config('database.prefix') . 'goods')->alias('a')->join(config('database.prefix') . 'ad at', 'a.id = at.goods_id', 'left')->where($adMap)->column("goods_id");
            $countListOther = Db::table(config('database.prefix') . 'goods')->alias('a')->join(config('database.prefix') . 'ad at', 'a.id = at.goods_id', 'left')->where($map)->whereNotIn("a.id", $countList) ->group("a.id")->count();
            $count = count($countList);

                $list1 = Db::table(config('database.prefix') . 'goods')->alias('a')
                    ->join(config('database.prefix') . 'ad at', 'a.id = at.goods_id', 'left')
                    ->field('a.*')
                    ->where($adMap)
                    ->order('a.addtime asc')
                    ->group("a.id")
                    ->paginate(array('list_rows' => $pageSize, 'page' => $page))
                    ->toArray();
                foreach ($list1['data'] as $k => $v) {
                    $list1['data'][$k]['is_top'] = 1;
                    $list1['data'][$k]['addtime'] = date('Y-m-d H:i', $v['addtime']);
                }

                $list2 = Db::table(config('database.prefix') . 'goods')->alias('a')
                    ->distinct ( true )
                    ->join(config('database.prefix') . 'ad at', 'a.id = at.goods_id', 'left')
                    ->field('a.*')
                    ->where($map)
                    ->whereNotIn("a.id", $countList)
                    ->order('a.endtime desc')
                    ->group("a.id")
                    ->paginate(array('list_rows' => $pageSize, 'page' => $page))
                    ->toArray();
                foreach ($list2['data'] as $k => $v) {
                    $list2['data'][$k]['is_top'] = 0;
                    $list2['data'][$k]['addtime'] = date('Y-m-d H:i', $v['addtime']);
                }
                $list['data']=array_merge($list1['data'],$list2['data']);

            $result = ['code' => 1, 'msg' => '获取成功!', 'data' => $list['data'], 'count' => $countListOther + $count,'count1'=>$count,'count2'=>$countListOther, 'rel' => db('goods')->getLastSql()];
            return json($result);


        }

        if(input('catid') ) {
            $catInfo = db('category')->where('id', input('catid') ? input('catid') : 2)->find();
        }else{
            $catInfo = db('category')->order('listorder','desc')->find();
        }
        $head_title=$catInfo['catname'] ?  $catInfo['catname'] : "分类";
        $this->assign('head_title', $head_title);
        return $this->fetch();
    }

    public function show()
    {
        $map['id'] = input('id');
        $info = db('goods')->where('id', input('id'))->find();
        if (request()->isPost()) {
            $info['imagesArr'] = explode(",", $info['images']);
            $info['addtime'] = date('Y-m-d H:i:s', $info['addtime']);
            $map2['user_id'] = session('user.id');
            $map2['goods_id'] = input('id');

                $collect = db('users_collect')->where($map2)->find();
                $info['collect_status'] = $collect ? 1 : 0;
                $info['collect_id'] = $collect ? $collect['id'] : 0;


            $result = ['code' => 1, 'msg' => '获取成功!', 'data' => $info,'userid'=>$map2['user_id'], 'rel' => db('users_collect')->getLastSql()];
            return json($result);

        }
        if (session('user.id') == $info["user_id"]) {
            $this->assign('showdel', 1);
        } else {
            $this->assign('showdel', 0);
        }
        $this->assign('user_id', $info["user_id"]);
        $this->assign('head_title', $info['name']);
        return $this->fetch();
    }

    public function delshow()
    {
        // echo '<pre>'; print_r($this->userInfo['id']); exit;
        $map2['user_id'] = $this->userInfo['id'];
        $map2['id'] = input('id');
        db('goods')->where($map2)->update(['status' => '3']);
        $result = ['code' => 1];
        return json($result);
    }

    public function showimgs(){
        //获取要下载的文件名
        $filename = input("filename");
        header('Content-Disposition:attachment;filename=' . basename($filename));
    }
    public function listorder()
    {
        if (request()->isPost()) {
            $parentid=db('category')->where('parentid','<>','0')->min('parentid');
          $info=db('category')->where('parentid',$parentid)->order('listorder asc')->select();
            $result = ['code' => 1, 'msg' => '获取成功!', 'data' => $info];
            return json($result);
        }
        return $this->fetch();
    }


}