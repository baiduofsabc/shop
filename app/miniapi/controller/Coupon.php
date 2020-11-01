<?php
namespace app\miniapi\controller;
use think\Db;
use think\Request;
class Coupon extends Common{
     public function _initialize(){
        parent::_initialize();
        $this->checkToken();
    }
    public function index(){
        if (request()->isPost()) {
            if(input('post.use_in')) {
                $map['use_in']=input('post.use_in');
            }
            if(input('post.total_price')) {
                $map['full_price'] = array('elt',input('post.total_price'));
            }
            if(!$this->isNew()) {
                $map['target'] = array('neq',2);
            }
            $map['start_time'] = array('lt',time()*1000);
            $map['end_time'] = array('gt',time()*1000);
            $list =  db('coupon')->where($map)->select();
            foreach ($list as $k => $v) {
                $list[$k]['start_time'] = date('Y-m-d', $v['start_time']/1000);
                $list[$k]['end_time'] = date('Y-m-d', $v['end_time']/1000);
                $list[$k]['cut_price'] = round($v['cut_price'],0);;
            }
            $result = ['code' => 1, 'msg' => '获取成功!',  'data'=>$list];
            return json($result);
        }   
    }
    protected function isNew(){
        $map['user_id']=$this->userInfo['id'];
        $map['order_status'] = array('neq',3);
        $map['order_status'] = array('neq',3);
        $tend_order =  db('tend_order')->where($map)->limit(1)->select();
        $wheelchair_order =  db('wheelchair_order')->where($map)->limit(1)->select();
        $transport_order =  db('transport_order')->where($map)->limit(1)->select();
        if(count($tend_order)>0 || count($wheelchair_order)>0||count($transport_order)>0) {
            return false;
        }
        else {
            return ture;
        }   
    }

}






