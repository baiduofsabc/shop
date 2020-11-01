<?php
namespace app\admin\controller;
use think\Db;
use think\Request;
use think\Controller;
use app\miniapi\controller\WechatPay;
class Order extends Common
{
    public function _initialize(){
        parent::_initialize();
        $this->wechat=db('wechat')->where('id',1)->find(); 
    }

    public function lease()
    {
        if (request()->isPost()) {
            if(input('post.order_sn')) {
              $map['a.order_sn'] = input('post.order_sn');
            }
            if(input('post.order_status')) {
              $map['a.order_status'] = input('post.order_status');
            }
            if(input('post.pay_status')) {
              $map['a.pay_status'] = input('post.pay_status');
            }
            if(input('post.express_status')) {
              $map['a.express_status'] = input('post.express_status');
            }

            $page = input('page') ? input('page') : 1;
            $pageSize = input('limit') ? input('limit') : config('pageSize');
            $list = Db::table(config('database.prefix') . 'wheelchair_order')->alias('a')
                ->join(config('database.prefix') . 'users at', 'a.user_id = at.id', 'left')
                ->field('a.*,at.nickName')
                ->where($map)
                ->order('a.id desc')
                ->paginate(array('list_rows' => $pageSize, 'page' => $page))
                ->toArray();
            foreach ($list['data'] as $k => $v) {
                $list['data'][$k]['add_time'] = date('Y-m-d H:i:s', $v['addtime']);
            }
            $result = ['code' => 1, 'msg' => '获取成功!', 'data' => $list['data'], 'count' => $list['total'], 'rel' => 1];
            return json($result);
        }
        else {
            return $this->fetch();
        }
    }
    public function leaseInfo() {
        if (request()->isPost()) {
            $info = db('wheelchair_order')->where('id',input('post.id'))->find();
            $info['coupon'] = db('coupon')->where('id',$info['cid'])->find();
            if($info['express_name']) {
                $url = "http://www.kuaidi100.com/query?type=yuantong&postid=805380382048784001";
                $info['express_log'] = json_decode(file_get_contents($url));
                
            }
            $result = ['code' => 1, 'msg' => '获取成功!', 'data' => $info];
            return json($result);
        }
    }
    public function leaseEdit() {
        if (request()->isPost()) {
            $data = input('post.');
            $data['updatetime'] = time();
            if($data['id']) {
               $res = db('wheelchair_order')->update($data);   
            }
            else {
                $result = ['code' => 0, 'msg' => '提交失败!']; 
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
    public function tend()
    {
        if (request()->isPost()) {
            if(input('post.order_sn')) {
              $map['a.order_sn'] = input('post.order_sn');
            }
            if(input('post.order_status')) {
              $map['a.order_status'] = input('post.order_status');
            }
            if(input('post.pay_status')) {
              $map['a.pay_status'] = input('post.pay_status');
            }
            if($this->adminInfo['hospital_id']&&session('aid')!=1) {
              $map['a.is_house'] = 2;
              $map['a.hospital_id'] = $this->adminInfo['hospital_id'];
            }
            $page = input('page') ? input('page') : 1;
            $pageSize = input('limit') ? input('limit') : config('pageSize');
            $list = Db::table(config('database.prefix') . 'tend_order')->alias('a')
                ->join(config('database.prefix') . 'users at', 'a.user_id = at.id', 'left')
                ->field('a.*,at.nickName')
                ->where($map)
                ->order('a.id desc')
                ->paginate(array('list_rows' => $pageSize, 'page' => $page))
                ->toArray();
            foreach ($list['data'] as $k => $v) {
                $list['data'][$k]['add_time'] = date('Y-m-d H:i:s', $v['addtime']);
            }
            $result = ['code' => 1, 'msg' => '获取成功!', 'data' => $list['data'], 'count' => $list['total'], 'rel' => 1];
            return json($result);
        }
        else {
            return $this->fetch();
        }
    }
    public function tendInfo() {
        if (request()->isPost()) {
            $info = db('tend_order')->where('id',input('post.id'))->find();
            $info['coupon'] = db('coupon')->where('id',$info['cid'])->find();
            $info['user'] = db('users')->where('id',$info['user_id'])->find();
            $info['service'] = db('service')->where('id',array('in',$info['service_ids']))->select();
            $info['link_man'] = db('users_link')->where('id',$info['link_id'])->find();
            $info['t_man'] = db('users_tman')->where('id',$info['tman_id'])->find();
            $info['selected_days'] = json_decode($info['selected_days']);
            $info['hospital'] = db('hospital')->where('id',$info['hospital_id'])->find();
            $result = ['code' => 1, 'msg' => '获取成功!', 'data' => $info];
            return json($result);
        }
    }
    public function tendEdit() {
        if (request()->isPost()) {
            $data = input('post.');
            $data['updatetime'] = time();
            if($data['id']) {
               $res = db('tend_order')->update($data);
            }
            else {
                $result = ['code' => 0, 'msg' => '提交失败!']; 
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
    public function transport()
    {
        if (request()->isPost()) {
            if(input('post.order_sn')) {
              $map['a.order_sn'] = input('post.order_sn');
            }
            if(input('post.order_status')) {
              $map['a.order_status'] = input('post.order_status');
            }
            if(input('post.pay_status')) {
              $map['a.pay_status'] = input('post.pay_status');
            }
       
            $page = input('page') ? input('page') : 1;
            $pageSize = input('limit') ? input('limit') : config('pageSize');
            $list = Db::table(config('database.prefix') . 'transport_order')->alias('a')
                ->join(config('database.prefix') . 'users at', 'a.user_id = at.id', 'left')
                ->field('a.*,at.nickName')
                ->where($map)
                ->order('a.id desc')
                ->paginate(array('list_rows' => $pageSize, 'page' => $page))
                ->toArray();
            foreach ($list['data'] as $k => $v) {
                $list['data'][$k]['add_time'] = date('Y-m-d H:i:s', $v['addtime']);
            }
            $result = ['code' => 1, 'msg' => '获取成功!', 'data' => $list['data'], 'count' => $list['total'], 'rel' => 1];
            return json($result);
        }
        else {
            return $this->fetch();
        }
    }
    public function transportInfo() {
        if (request()->isPost()) {
            $info = db('transport_order')->where('id',input('post.id'))->find();
            $info['coupon'] = db('coupon')->where('id',$info['cid'])->find();
            $info['user'] = db('users')->where('id',$info['user_id'])->find();
            $info['service'] = db('service')->where('id',array('in',$info['service_ids']))->select();
            $info['link_man'] = db('users_link')->where('id',$info['link_id'])->find();
            $info['t_man'] = db('users_tman')->where('id',$info['tman_id'])->find();
            $info['selected_days'] = json_decode($info['selected_days']);
            $result = ['code' => 1, 'msg' => '获取成功!', 'data' => $info];
            return json($result);
        }
    }
    public function transportEdit() {
        if (request()->isPost()) {
            $data = input('post.');
            $data['updatetime'] = time();
            if($data['id']) {
               $res = db('transport_order')->update($data);
            }
            else {
                $result = ['code' => 0, 'msg' => '提交失败!']; 
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
    public function wxRefund() {
        if (request()->isPost()) {
            $wechat = $this->wechat;
            $wechat['notify_url'] = 'https://api.jscyph.com/home/Notify/wxRefund';
            $wxpay=new WechatPay($wechat);

            $yCode = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J');
            $out_trade_no = input('post.out_trade_no');
            $out_refund_no = 'TK'.input('post.out_trade_no');
            // $out_refund_no = $yCode[intval(date('Y')) - 2011] . strtoupper(dechex(date('m'))) . date('d') . substr(time(), -5) . substr(microtime(), 2, 5) . sprintf('%02d', rand(0, 99));
            $total_fee = input('post.total_fee');
            $refund_fee = input('post.refund_fee');
          
            return json($wxpay->refund($out_trade_no,$out_refund_no,$total_fee,$refund_fee));
        }
    }

    

   

}