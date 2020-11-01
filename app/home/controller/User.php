<?php

namespace app\home\controller;

use think\Db;
use think\Request;
use app\home\model\Collect;//收藏
use clt\Imgcompress;
class User extends Common
{
    public function _initialize()
    {
        parent::_initialize();
        $this->assign('foot_active', 3);
        if (!session('user.id')) {
            $this->redirect('Login/index');
        }
        $this->webInfo = db('system')->where('id', 1)->find();
        $this->assign('head_title', "我的");
        $this->assign('webInfo', $this->webInfo);
    }

    public function clearTmp()
    {
        Db::name('fabu_tmp')->where('user_id', $this->userInfo['id'])->delete();
        return json(['code' => 1]);
    }

    public function getTmp()
    {
        if (request()->isPost()) {
            $info = Db::name('fabu_tmp')->where('user_id', $this->userInfo['id'])->find();
            $goodsInfo = input('post.');
            if ($info) {
                // 更新
                // $info['type'] = input('post.type');
                // $info['catid'] = input('post.catid');
                // $info['images'] = explode(',', $info['images']);
                // print_r($info);exit;
                // 更新
                if ($info['images'] == '') {
                    $goodsInfo['images'] = [];
                } else {
                    $goodsInfo['images'] = explode(',', $info['images']);
                }
                if ($info['thumb'] == '') {
                    $goodsInfo['thumb'] = '';
                } else {
                    $goodsInfo['thumb'] = $info['thumb'];
                }
                if ($info['info'] == '') {
                    $goodsInfo['info'] = '';
                } else {
                    $goodsInfo['info'] = $info['info'];
                }
                if ($info['price'] == '') {
                    $goodsInfo['price'] = '';
                } else {
                    $goodsInfo['price'] = $info['price'];
                }
                return json(['code' => 1, 'data' => $goodsInfo]);
            } else {
                // 新增
                // $info['type'] = input('post.type');
                // $info['catid'] = input('post.catid');
                return json(['code' => 1, 'data' => $goodsInfo]);
            }
        }
    }

    public function saveTmp()
    {
        if (request()->isPost()) {
            $data = input('post.');
            if ($data['images'] == "A,r,r,a,y") {
                $data['images'] = '';
            }
            // print_r($data);exit;
            $data['user_id'] = $this->userInfo['id'];
            unset($data['type']);
            unset($data['catid']);
            $info = Db::name('fabu_tmp')->where('user_id', $this->userInfo['id'])->find();
            // print_r($info);exit;
            if ($info) {
                // 更新
                Db::name('fabu_tmp')->where('user_id', $this->userInfo['id'])->update($data);
            } else {
                // 新增
                Db::name('fabu_tmp')->insert($data);
            }
        }
    }

    public function explain()
    {
        $bugao = db('bugao')->where('id', 1)->find();
        $this->assign('bugao', $bugao);
        return $this->fetch();
    }

    public function index()
    {
        if (request()->isPost()) {
            $res = db('users_card')->where('user_id', $this->userInfo['id'])->find();
            if ($res) {
                $this->userInfo['cardInfo'] = $res;
            } else {
                $data['card_sn'] = 100000 + $this->userInfo['id'];
                $data['user_id'] = $this->userInfo['id'];
                $data['integral'] = 50;
                $data['updatetime'] = time();
                $data['addtime'] = time();
                $this->userInfo['cardInfo'] = $data;
                db('users_card')->insert($data);
            }

            return json($this->userInfo);
        }
        $this->assign('head_title', "我的");
        return $this->fetch();
    }

    public function jssdkCon()
    {
        $wxjssdk = new Wxjssdk;
        $hello=$wxjssdk->getSecret();
        dump($hello);
        $this->assign("hello",$hello);
    }

    public function testImg()
    {
        $wxjssdk = new Wxjssdk;
        $hello=$wxjssdk->getSecret();
        // dump($hello);
        $xitong=get_device_type();
        // dump($xitong);
        if($xitong=="ios"){
         $moban="testImg2";
        }
        if($xitong=="android"){
          $moban="testImg";
        }
        $this->assign("hello",$hello);
        return $this->fetch($moban);
    }

    public function updateTestImg()
    {
        if(isset($_POST) && !empty($_POST)){

 

        $localData = $_POST['localData'];

        

        $res=base64_image_content($localData,$path);
      
        return json($res);

    }

    }

    // public function checkPassword(){
    //     if(request()->isPost()) {
    //         $password = input('post.password');
    //         $user =db('users')->where("id",$this->userInfo['id'])->find();
    //         if(md5($password) != $user['password']){
    //             return json(['code'=>0,'msg'=>'密码错误!','user'=>$user,'password'=>$password]);
    //         }
    //         else {
    //             return json(['code'=>1,'msg'=>'密码正确!']);
    //         }
    //     }
    // }
    public function updateUserInfo()
    {
        if (request()->isPost()) {
            $data = input('post.');
            $data['id'] = $this->userInfo['id'];
            $id = db('users')->update($data);
            return json(['code' => 1, 'msg' => '成功！']);
        }
    }

    public function noticeTimeUpdate()
    {
        if (request()->isPost()) {

            $data['id'] = $this->userInfo['id'];
            $data['read_notice_time'] = time();
            $id = db('users')->update($data);
            return json(['code' => 1, 'msg' => '成功！']);
        }
    }

    public function goods()
    {
        if (request()->isPost()) {
            $map['user_id'] = $this->userInfo['id'];

            if (input('catid')) {
                $map['catid'] = input('catid');
            }
            if (input('status')) {
                $map['status'] = input('status');
            } else {
                $map['status'] = array('neq', 3);
            }
            $page = input('page') ? input('page') : 1;
            $pageSize = input('limit') ? input('limit') : config('pageSize');
            $list = Db::table(config('database.prefix') . 'goods')->alias('a')
                ->field('a.id,a.thumb,a.price')
                ->where($map)
                ->order('a.id desc')
                ->paginate(array('list_rows' => $pageSize, 'page' => $page))
                ->toArray();
            foreach ($list['data'] as $k => $v) {
                $list['data'][$k]['addtime'] = date('Y-m-d H:i', $v['addtime']);
            }
            $result = ['code' => 1, 'msg' => '获取成功!', 'data' => $list['data'], 'count' => $list['total']];
            return json($result);
        }
        $this->assign('head_title', "我的发布");
        return $this->fetch();
    }

    public function goodsAdd()
    {
        
        $xitong=get_device_type();
        // dump($xitong);
        if($xitong=="ios"){
         $moban="testImg2";
        }
        if($xitong=="android"){
          $moban="androidImg";
        }
        if (request()->isPost()) {
            if (input('id')) {
                $goodsInfo = $this->goodsInfo(input('id'));
                if ($goodsInfo) {
                    $result = ['code' => 1, 'data' => $goodsInfo];
                } else {
                    $result = ['code' => 0, 'data' => "", 'map' => $map];
                }
            } else {
                $result = ['code' => 0, 'data' => ""];
            }
            return json($result);
        }
        $goodsInfo = input('get.');
        // $this->getTmp();
        $info = Db::name('fabu_tmp')->where('user_id', $this->userInfo['id'])->find();
        if ($info) {
            // 更新
            if ($info['images'] == '') {
                $goodsInfo['images'] = [];
            } else {
                $goodsInfo['images'] = explode(',', $info['images']);
            }
            if ($info['thumb'] == '') {
                $goodsInfo['thumb'] = '';
            } else {
                $goodsInfo['thumb'] = $info['thumb'];
            }
            if ($info['info'] == '') {
                $goodsInfo['info'] = '';
            } else {
                $goodsInfo['info'] = str_replace("\n","" ,$info['info']);
            }
            if ($info['price'] == '') {
                $goodsInfo['price'] = '';
            } else {
                $goodsInfo['price'] = $info['price'];
            }
            $this->assign('gettmp', 1);
        } else {
            $goodsInfo['price'] = '';
            $goodsInfo['thumb'] = '';
            $goodsInfo['images'] = [];
            $this->assign('gettmp', 0);
        }
        // print_r($goodsInfo);exit;
        if (!$goodsInfo['type'] && !$goodsInfo['catid']) {
            $this->redirect('User/index');
        }
        $info = input('get.type');
        if ($info == 1) {
            $this->assign('total_int', 1);
        } else if ($info == 2) {
            $this->assign('total_int', 50);
        }

        // print_r($info);exit;
        $this->assign('goodsTip', "请不要发布违法违规信息，发现立即封号，情节严重的，转交司法部门，请发布到对应类别，否则下架处理。");
        $this->assign('goodsInfo', $goodsInfo);
        $this->assign('head_title', "发布");
        return $this->fetch($moban);
    }

    public function goodsShow()
    {
        if (request()->isPost()) {


            if (input('id')) {
                $adv = db('ad_type')->select();
                foreach ($adv as $k => $v) {
                    $map['type_id'] = $v['type_id'];
                    $map['goods_id'] = input('id');
                    $adv[$k]['ad_info'] = db('ad')->where($map)->find();
                    if ($adv[$k]['ad_info']) {
                        $adv[$k]['ad_info']['lastDays'] = ($adv[$k]['ad_info']['endtime'] - time()) / (24 * 3600);
                    } else {
                        $adv[$k]['ad_info']['lastDays'] = 0;
                    }
                }
                $goodsInfo = $this->goodsInfo(input('id'));
                if ($goodsInfo) {
                    $goodsInfo['addtime'] = date('Y-m-d H:i', $goodsInfo['addtime']);
                    $result = ['code' => 1, 'data' => $goodsInfo, 'adv' => $adv];
                } else {
                    $result = ['code' => 0, 'data' => ""];
                }
            } else {
                $result = ['code' => 0, 'data' => ""];
            }
            return json($result);
        }
        $this->assign('head_title', "我的发布");
        return $this->fetch();
    }

    public function advList()
    {
        if (request()->isPost()) {
            $adv = db('ad_type')->select();
            foreach ($adv as $k => $v) {
                $map['type_id'] = $v['type_id'];

                $map['goods_id'] = input('id');
                $adv[$k]['ad_info'] = db('ad')->where($map)->find();
                if ($adv[$k]['ad_info']) {
                    $adv[$k]['ad_info']['lastDays'] = ($adv[$k]['ad_info']['endtime'] - time()) / (24 * 3600);
                } else {
                    $adv[$k]['ad_info']['lastDays'] = 0;
                }
            }

            $result = ['code' => 1, 'adv' => $adv];

            return json($result);
        }

    }

    public function checkInt()
    {
        if (request()->isPost()) {
            $post = input('post.');
            $res = db('users_card')->where('user_id', $this->userInfo['id'])->find();
            if ($res['integral'] < $post['total_int']) {
                return json(['code' => 0, 'msg' => '积分不足，请充值!']);
            } else {
                return json(['code' => 1, 'msg' => '']);
            }

        }
    }

    public function advAdd()
    {
        if (request()->isPost()) {
            $data['user_id'] = $this->userInfo['id'];
            $post = input('post.');
            $res = db('users_card')->where('user_id', $this->userInfo['id'])->find();
            if ($res['integral'] < $post['total_int']) {
                return json(['code' => 0, 'msg' => '积分不足，请充值!']);
            } else {
                db('users_card')->where('user_id', $this->userInfo['id'])->setDec('integral', $post['total_int']);
            }
            foreach ($post['list'] as $k => $v) {
                $v['user_id'] = $this->userInfo['id'];
                $v['goods_id'] = $post['goods_id'];
                if ($v['days'] > 0) {
                    if ($v['ad_info']['ad_id']) {
                        $v['ad_id'] = $v['ad_info']['ad_id'];
                        if ($v['ad_info']['endtime'] < time()) {
                            $v['endtime'] = strtotime('+' . $v['days'] . 'day');
                        } else {
                            $v['endtime'] = strtotime('+' . $v['days'] . 'day', $v['ad_info']['endtime']);
                        }
                        $v['use_int'] = $v['ad_info']['use_int'] + $v['need_int'] * $v['days'];
                        db('ad')->update($v);


                    } else {
                        $v['addtime'] = time();
                        $v['endtime'] = strtotime('+' . $v['days'] . 'day');
                        $v['use_int'] = $v['need_int'] * $v['days'];
                        db('ad')->insert($v);
                    }
                    if ($v['type_id'] == 1) {
                        $dataG['id'] = $v['goods_id'];
                        $dataG['endtime'] = $v['endtime'];
                        db('goods')->update($dataG);
                    }
                    $data['integral'] = $v['need_int'] * $v['days'];
                    $data['type'] = 2;
                    $data['info'] = "发布至" . $v['name'] . $v['days'] . "天";
                    $this->addFollow($data);
                }
            }
            // mark
            Db::name('fabu_tmp')->where('user_id', $this->userInfo['id'])->update(['price' => '', 'info' => '', 'thumb' => '', 'images' => '']);
            return json(['code' => 1, 'msg' => '提交成功!']);
        }
    }

    public function goodsInfo($id)
    {
        $map['id'] = $id;
        $map['status'] = array('neq', 3);
        $map['user_id'] = $this->userInfo['id'];
        $goodsInfo = db('goods')->where($map)->find();
        if ($goodsInfo['images']) {
            $goodsInfo['images'] = explode(",", $goodsInfo['images']);
        } else {
            $goodsInfo['images'] = [];
        }
        return $goodsInfo;
    }

    public function goodsSubmit()
    {

        if (request()->isPost()) {
            $data = input('post.');
            // print_r($data);exit;
            $data['user_id'] = $this->userInfo['id'];
            if ($data['id']) {
                $data['updatetime'] = time();
                $info = db('goods')->update($data);
            } else {
                $data['addtime'] = time();
                $data['updatetime'] = time();
                $data['endtime'] = time();
                $info = db('goods')->insertGetID($data);
            }
            // print_r($info);exit;
            if ($info) {
                $data['integral'] = 1;
                $data['type'] = 2;
                $data['info'] = "发布至分类";
                $this->addFollow($data);
                // print_r($this->addFollow($data));exit;
                $result = ['code' => 1, 'msg' => '成功!', 'id' => $info];
            } else {
                $result = ['code' => 0, 'msg' => '失败!'];

            }
            return json($result);
        }

    }

    //base64 商品添加

    public function goodsSubmit2()
    {

        $xitong=get_device_type();
        // dump($xitong);
       

        if (request()->isPost()) {
            $data = input('post.');
            foreach ($data['images'] as $k => $v) {

                if($xitong=="ios"){
                    $path="./public/upload_baiduo/";
                    $source = base64_image_content($v,$path);  
                    $dst_img = "fszb".md5(mt_rand().time());  
                    $percent = 1;  #原图压缩，不缩放，但体积大大降低  
                    $image = (new Imgcompress($source,$percent))->compressImg($dst_img); 
                    $img_url[$k]=$image;
                 }
                if($xitong=="android"){
                  
                }
               
            }
            if(isset($img_url)){ $data['images']=implode(",", $img_url);}


            // dump('1111');exit;
            $data['user_id'] = $this->userInfo['id'];
            if ($data['id']) {
                $data['updatetime'] = time();
                $info = db('goods')->update($data);
            } else {
                $data['addtime'] = time();
                $data['updatetime'] = time();
                $data['endtime'] = time();
                $info = db('goods')->insertGetID($data);
            }
            // print_r($info);exit;
            if ($info) {
                $data['integral'] = 1;
                $data['type'] = 2;
                $data['info'] = "发布至分类";
                $this->addFollow($data);
                // print_r($this->addFollow($data));exit;
                $result = ['code' => 1, 'msg' => '成功!', 'id' => $info];
            } else {
                $result = ['code' => 0, 'msg' => '失败!'];

            }
            return json($result);
        }

    }

    //Android端  添加商品

    public function androidGoodsSubmit()
    {

        $xitong=get_device_type();
        // dump($xitong);
        $wxjssdk = new Wxjssdk;

		$data = input('post.');

		foreach ($data['images'] as $k => $v) {

		        $img=$wxjssdk->getmedia($v);
		        $source =$img;  
		        $dst_img = "fszb".md5(mt_rand().time());  
		        $percent = 1;  #原图压缩，不缩放，但体积大大降低  
		        $image = (new Imgcompress($source,$percent))->compressImg($dst_img); 
		        $img_url[$k]=$image;
		   
		   
		}
        if(isset($img_url)){
            $data['images']=implode(",", $img_url);
        }

		// dump('1111');exit;
		$data['user_id'] = $this->userInfo['id'];
		if ($data['id']) {
		    $data['updatetime'] = time();
		    $info = db('goods')->update($data);
		} else {
		    $data['addtime'] = time();
		    $data['updatetime'] = time();
		    $data['endtime'] = time();
		    $info = db('goods')->insertGetID($data);
		}
		// print_r($info);exit;
		if ($info) {
		    $data['integral'] = 1;
		    $data['type'] = 2;
		    $data['info'] = "发布至分类";
		    $this->addFollow($data);
		    // print_r($this->addFollow($data));exit;
		    $result = ['code' => 1, 'msg' => '成功!', 'id' => $info];
		} else {
		    $result = ['code' => 0, 'msg' => '失败!'];

		}
		return json($result);

    }

    public function user_info()
    {
        if (request()->isPost()) {
            $res = db('users_card')->where('user_id', $this->userInfo['id'])->find();
            if ($res) {
                $this->userInfo['cardInfo'] = $res;
            } else {
                $data['card_sn'] = 100000 + $this->userInfo['id'];
                $data['user_id'] = $this->userInfo['id'];
                $data['integral'] = 0;
                $data['updatetime'] = time();
                $data['addtime'] = time();
                $this->userInfo['cardInfo'] = $data;
                db('users_card')->insert($data);
            }
            $this->assign('head_title', "收藏");
            return json($this->userInfo);
        }
        return $this->fetch();
    }

    public function getUserGoods()
    {
        if (request()->isPost()) {
            $map['user_id'] = $this->userInfo['id'];
            $page = input('page') ? input('page') : 1;
            $pageSize = input('limit') ? input('limit') : config('pageSize');
            $list = Db::table(config('database.prefix') . 'goods')->alias('a')
                ->field('a.*')
                ->where($map)
                ->order('a.id desc')
                ->paginate(array('list_rows' => $pageSize, 'page' => $page))
                ->toArray();
            foreach ($list['data'] as $k => $v) {
                $list['data'][$k]['addtime'] = date('Y-m-d H:i', $v['addtime']);
            }
            $result = ['code' => 1, 'msg' => '获取成功!', 'data' => $list['data'], 'count' => $list['total']];
            return json($result);

        }

    }

    public function showbeizhu()
    {
        $userid = session('user.id');
        if (request()->isGet()) {
            $beizhu = db('beizhu')->where(['goods_id'=>input('id'),'userid'=>$userid])->find();
            if ($beizhu == '') {
                $beizhu['id'] = 0;
                $beizhu['goods_id'] = input('id');
                $beizhu['beizhu'] = '';
            }
            $this->assign('beizhu', $beizhu);
            return $this->fetch();
        } else if (request()->isPost()) {
            if (input('id') != 0) {
                $id = input('id');
                $data['goods_id'] = input('goods_id');
                $data['beizhu'] = input('beizhu');
                $info = Db::name('beizhu')->where(['userid'=>$userid,'id'=>$id])->update($data);
            } else {
                $info = Db::name('beizhu')->insertGetId(['goods_id' => input('goods_id'), 'beizhu' => input('beizhu'),'userid'=>$userid]);
            }
            // echo '<pre>';print_r($data);exit;
            $result = ['code' => 1];
            return json($result);
        }
    }

    public function collect()
    {
        if (request()->isPost()) {
            $map['a.user_id'] = $this->userInfo['id'];
            $userid=$this->userInfo['id'];
            $page = input('page') ? input('page') : 1;
            $pageSize = input('limit') ? input('limit') : config('pageSize');
            $list = Db::table(config('database.prefix') . 'users_collect')->alias('a')
                ->join(config('database.prefix') . 'goods ul', 'a.goods_id = ul.id', 'left')
                ->field('ul.*,a.id as collect_id,a.addtime as add_time')
                ->where($map)
                ->order('a.id desc')
                ->paginate(array('list_rows' => $pageSize, 'page' => $page))
                ->toArray();
            foreach ($list['data'] as $k => $v) {
                $list['data'][$k]['addtime'] = date('Y-m-d H:i', $v['add_time']);
                $beizhu = db('beizhu')->where(['goods_id'=>$v['id'],'userid'=>$userid])->find();
                $list['data'][$k]['beizhu'] = $beizhu['beizhu'];
//                if(mb_strlen($list['data'][$k]['beizhu'],'utf-8') > 44) {
//                    $list['data'][$k]['beizhu'] = mb_substr($list['data'][$k]['beizhu'],0,44,'utf-8')."...";
//                }
            }
            // echo '<pre>';print_r($list);exit;
            $result = ['code' => 1, 'msg' => '获取成功!', 'data' => $list['data'], 'count' => $list['total'], 'rel' => 1];
            return json($result);
        }
        $this->assign('head_title', "收藏");
        return $this->fetch();
    }

    public function collectDel()
    {
        if (request()->isPost()) {
            $info = db('users_collect')->where(array('id' => input('id')))->find();
            db('users_collect')->where(array('id' => input('id')))->delete();
            db('beizhu')->where(['userid'=>$info['user_id'],'goods_id' => $info['goods_id']])->delete();
            return json(['code' => 1, 'msg' => '删除成功！']);
        }
    }

    public function collectAdd()
    {
        if (request()->isPost()) {
            $data['user_id'] = $this->userInfo['id'];
            $data['goods_id'] = input('post.id');
            $exist = db('users_collect')->where($data)->find();
            if($exist) {
                return json(['code' => 2, 'msg' => '您已收藏，请勿重复操作']);
            }
            $id = db('users_collect')->insertGetID($data);
            return json(['code' => 1, 'msg' => '收藏成功！', 'id' => $id]);
        }
    }

    public function notice()
    {
        if (request()->isPost()) {
            $del = db('notice_del')->where(array('user_id' => $this->userInfo['id']))->find();
            $map['id'] = array('not in', $del['notice_id']);
            $page = input('page') ? input('page') : 1;
            $pageSize = input('limit') ? input('limit') : config('pageSize');
            $list = Db::table(config('database.prefix') . 'notice')
                ->field('*')
                ->where($map)
                ->order('id desc')
                ->paginate(array('list_rows' => $pageSize, 'page' => $page))
                ->toArray();
            foreach ($list['data'] as $k => $v) {
                $list['data'][$k]['addtime'] = date('Y-m-d H:i', $v['addtime']);
                $list['data'][$k]['imagesArr'] = $v['images'] ? explode(",", $v['images']) : [];
                $list['data'][$k]['packUp'] = true;
            }
            $result = ['code' => 1, 'msg' => '获取成功!', 'data' => $list['data'], 'count' => $list['total'], 'rel' => 1];
            return json($result);
        }
        $this->assign('head_title', "通知");
        return $this->fetch();
    }

    public function noticeAdd()
    {
        if (request()->isPost()) {
            $data['user_id'] = $this->userInfo['id'];
            $id = db('notice')->insertGetID($data);
            return json(['code' => 1, 'msg' => '成功！', 'id' => $id]);
        }
    }

    public function noticeDel()
    {
        if (request()->isPost()) {
            $del = db('notice_del')->where(array('user_id' => $this->userInfo['id']))->find();
            $data['user_id'] = $this->userInfo['id'];
            if ($del) {
                if ($del['notice_id']) {
                    $notice_id = explode(",", $del['notice_id']);
                } else {
                    $notice_id = [];
                }


                array_push($notice_id, input('id'));
                $data['notice_id'] = implode(",", $notice_id);
                $data['user_id'] = $this->userInfo['id'];
                $data['id'] = $del['id'];
                db('notice_del')->update($data);
            } else {
                $data['notice_id'] = input('id');
                db('notice_del')->insert($data);
            }
            // db('notice_del')->where(array('id'=>input('id')))->delete();
            return json(['code' => 1, 'msg' => '删除成功！']);
        }
    }

    public function getReadNotice()
    {
        if (request()->isPost()) {
            $del = db('notice_del')->where(array('user_id' => $this->userInfo['id']))->find();
            $map['id'] = array('not in', $del['notice_id']);
            $map['addtime'] = array('gt', $this->userInfo['read_notice_time']);
            $count = db('notice')->where($map)->count();
            return json(['code' => 1, 'data' => $count]);
        }

    }

    public function setting()
    {
        $this->assign('head_title', "设置");
        return $this->fetch();
    }

    public function getShop()
    {
        if (request()->isPost()) {
            $info = db('shop')->where('user_id', $this->userInfo['id'])->find();
            if (!$info) {
                $data['user_id'] = $this->userInfo['id'];
                $info['id'] = db('shop')->insertGetID($data);
            }
            return json(['code' => 1, 'msg' => '成功！', 'data' => $info]);
        }
    }

    public function updateShop()
    {
        if (request()->isPost()) {
            $data = input('post.');
            $data['user_id'] = $this->userInfo['id'];
            $id = db('shop')->update($data);
            return json(['code' => 1, 'msg' => '成功！']);
        }
    }

    public function getFollow()
    {
        if (request()->isPost()) {
            $map['user_id'] = $this->userInfo['id'];
            $page = input('page') ? input('page') : 1;
            $pageSize = input('limit') ? input('limit') : config('pageSize');
            $list = Db::table(config('database.prefix') . 'users_card_follow')
                ->field('*')
                ->where($map)
                ->order('id desc')
                ->paginate(array('list_rows' => $pageSize, 'page' => $page))
                ->toArray();
            foreach ($list['data'] as $k => $v) {
                $list['data'][$k]['addtime'] = date('Y-m-d H:i', $v['addtime']);
            }
            $result = ['code' => 1, 'msg' => '获取成功!', 'data' => $list['data'], 'count' => $list['total'], 'rel' => 1];
            return json($result);
        }
    }

    public function addFollow($data)
    {
        unset($data['id']);
        $data['addtime'] = time();
        // print_r($data);exit;
        db('users_card_follow')->insert($data);

    }

    public function feedback()
    {
        if (request()->isPost()) {
            $data = input('post.');
            $data['user_id'] = $this->userInfo['id'];
            if ($data['id']) {

                $data['updatetime'] = time();
                $info = db('feedback')->update($data);
            } else {
                $data['addtime'] = time();
                $data['updatetime'] = time();
                $info = db('feedback')->insertGetID($data);
            }
            if ($info) {
                $result = ['code' => 1, 'msg' => '成功!'];

            } else {
                $result = ['code' => 0, 'msg' => '失败!'];

            }
            return json($result);
        }
        return $this->fetch();
    }


}
