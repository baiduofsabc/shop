<?php
namespace app\miniapi\controller;
use think\Db;
use think\Request;
use app\miniapi\controller\WechatPay;

class Pay extends Common{
    public function _initialize(){
        parent::_initialize();
        $this->checkToken();
        $map['token'] = $this->token;
        $this->wechat=db('wechat')->where('id',1)->find(); 
 

    }
    public function index(){
 
    }
    public function wxPay() {
        if (request()->isPost()) {


            if(input('post.order_sn')) {
              $order_sn = input('post.order_sn');
            }
            else {
               $order_sn = time(); 
            }
            if(input('post.body')) {
              $body = input('post.body');
            }
            else {
              $body = '充值'; 
            }
            $total_fee = input('post.pay_price');
            $openid = $this->userInfo['openid'];
            $wechat = $this->wechat;
            $wechat['notify_url'] = 'https://api.jscyph.com/home';
          
            $wxpay=new WechatPay($wechat);
            // return json($wechat);
            // exit;
            return json($wxpay->unifiedorder($order_sn,$total_fee,$openid,$body));
        }
    }

}