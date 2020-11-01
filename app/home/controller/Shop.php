<?php
namespace app\home\controller;
use think\Db;
use think\Request;
 
class Shop extends Common{
    public function _initialize(){
        parent::_initialize();
        $this->assign('foot_active',3);
    }
 
    public function index(){
    	if(request()->isPost()) {
            $res = db('users')->where('id',$this->getData['id'])->find();
            $res['cardInfo'] = db('users_card')->where('user_id',$this->getData['id'])->find();
            $res['shopInfo'] = db('shop')->where('user_id',$this->getData['id'])->find();
            if ( !$res['shopInfo'] ) {
                db("shop")->insert(['user_id'=> $this->getData['id']]);
                $res['shopInfo'] = db('shop')->where('user_id',$this->getData['id'])->find();
            }
            return json($res);
        }
        $this->assign('getData',$this->getData);

        $this->assign('head_title',"店铺");
        return $this->fetch();
    }


    // public function updateUserInfo(){
    //     if(request()->isPost()) {
    //         $data = input('post.');
    //         $data['id'] = $this->userInfo['id'];
    //         $id = db('users')->update($data); 
    //         return json(['code'=>1,'msg'=>'成功！']);
    //     }
    // }

    public function goods(){
        if(request()->isPost()) {
            $map['user_id'] = $this->getData['id'];
            
            if(input('catid')) {
               $map['catid'] = input('catid'); 
            }
            if(input('status')) {
               $map['status'] = input('status'); 
            }
            else {
               $map['status']= array('neq',3); 
            }
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
        $this->assign('head_title',"我的发布");
        return $this->fetch();
    }
    // public function goodsAdd(){
    //     if(request()->isPost()) {
    //         if(input('id')) { 
    //           $goodsInfo = $this->goodsInfo(input('id'));
    //           if($goodsInfo) {
    //             $result = ['code' => 1, 'data' => $goodsInfo];
    //           }
    //           else {
    //             $result = ['code' => 0, 'data' => "",'map'=>$map];
    //           }
    //         }
    //         else {
    //           $result = ['code' => 0, 'data' => ""];
    //         }            
    //         return json($result);    
    //     }
    //     $this->assign('head_title',"发布");
    //     return $this->fetch();
    // }
    public function goodsShow(){
        if(request()->isPost()) {
            

            if(input('id')) {
                $adv = db('ad_type')->select();
                foreach ($adv as $k => $v) {
                   $map['type_id'] = $v['type_id'];
                   $map['goods_id'] = input('id');
                   $adv[$k]['ad_info'] = db('ad')->where($map)->find();
                   if($adv[$k]['ad_info']) {
                      $adv[$k]['ad_info']['lastDays'] = ($adv[$k]['ad_info']['endtime']-time())/(24*3600);
                   }
                   else {
                      $adv[$k]['ad_info']['lastDays']=0;
                   }
                }
                $goodsInfo = $this->goodsInfo(input('id'));
                if($goodsInfo) {
                    $goodsInfo['addtime'] = date('Y-m-d H:i', $goodsInfo['addtime']);
                    $result = ['code' => 1, 'data' => $goodsInfo,'adv'=>$adv];
                }
                else {
                $result = ['code' => 0, 'data' => ""];
                }
            }
            else {
              $result = ['code' => 0, 'data' => ""];
            }
            return json($result);    
        }
        $this->assign('head_title',"我的发布");
        return $this->fetch();
    }
     public function advAdd() {
        if(request()->isPost()) {
            $data['user_id'] = $this->userInfo['id'];
            $post = input('post.');
            $res = db('users_card')->where('user_id',$this->userInfo['id'])->find();
            if($res['integral']<$post['total_int']) {
                return json(['code' => 0, 'msg' => '积分不足，请充值!']) ;
            }
            else {
                db('users_card')->where('user_id',$this->userInfo['id'])->setDec('integral',$post['total_int']);
            }
            foreach ($post['list'] as $k => $v) {
                $v['user_id'] = $this->userInfo['id'];
                $v['goods_id'] = $post['goods_id'];
                if($v['days']>0) {
                    if($v['ad_info']['ad_id']) {
                        $v['ad_id'] = $v['ad_info']['ad_id'];
                        if($v['ad_info']['endtime']<time()) {
                           $v['endtime'] = strtotime('+'.$v['days'].'day');
                        }
                        else {
                           $v['endtime'] = strtotime('+'.$v['days'].'day',$v['ad_info']['endtime']);
                        }
                        $v['use_int'] = $v['ad_info']['use_int'] + $v['need_int']*$v['days'];
                        db('ad')->update($v);
                    }
                    else {
                        $v['addtime'] = time();
                        $v['endtime'] = strtotime('+'.$v['days'].'day');
                        $v['use_int'] = $v['need_int']*$v['days'];
                        db('ad')->insert($v);
                    }
                    $data['integral']= $v['need_int']*$v['days'];
                    $data['type']= 2;
                    $data['info']= "发布至".$v['name'].$v['days']."天";
                    $this->addFollow($data);
                }
            }
            
            return json(['code' => 1, 'msg' => '提交成功!']) ;
        }
    }
    public function goodsInfo($id){
        $map['id'] = $id;
        $map['status']= array('neq',3);
        $map['user_id'] = $this->userInfo['id'];
        $goodsInfo = db('goods')->where($map)->find();
        if($goodsInfo['images']) {
           $goodsInfo['images'] = explode(",",$goodsInfo['images']);
        }
        else {
            $goodsInfo['images'] = [];
        }
        return $goodsInfo;     
    }

    public function goodsSubmit(){
        if(request()->isPost()) {
            $data = input('post.');
            $data['user_id'] = $this->userInfo['id'];
            if($data['id']) {
              
              $data['updatetime']=time(); 
              $info = db('goods')->update($data);
            }
            else {
              $data['addtime']=time();
              $data['updatetime']=time(); 
              $info = db('goods')->insertGetID($data); 
            }
            if($info) {
                $result = ['code' => 1, 'msg' => '成功!'];

            }
            else {
                $result = ['code' => 0, 'msg' => '失败!'];

            }
            return json($result);   
        }
        
    }

    public function user_info(){
        if(request()->isPost()) {
            $res = db('users_card')->where('user_id',$this->userInfo['id'])->find();
            if($res) {
                $this->userInfo['cardInfo'] = $res;
            }
            else {
                $data['card_sn']=100000+$this->userInfo['id'];
                $data['user_id']=$this->userInfo['id'];
                $data['integral']=0;
                $data['updatetime']=time();
                $data['addtime']=time();
                $this->userInfo['cardInfo'] = $data;
                db('users_card')->insert($data);
            }
            $this->assign('head_title',"收藏");
            return json($this->userInfo);
        }
        return $this->fetch();
    }
    public function getUserGoods(){
        if(request()->isPost()) {
            $map['user_id'] = $this->getData['id'];
            $map['status']=1;
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
            $result = ['code' => 1, 'msg' => '获取成功!', 'data' => $list['data'], 'count' => $list['total'],'res'=>$map ];
            return json($result);
    
        }
        
    }
     
    


    

}