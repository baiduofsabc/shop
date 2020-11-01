<?php

namespace app\home\controller;

use think\Db;
use think\Request;
use app\home\controller\WechatPay;
use app\home\controller\AlipayService;

class Recharge extends Common
{
    public function _initialize()
    {
        parent::_initialize();
        $this->assign('foot_active', 3);
        if (!session('user.id')) {
            $this->redirect('Login/index');
        }
        $this->wechat = db('wechat')->where('id', 1)->find();

    }

    public function index()
    {
        if (request()->isPost()) {
            $res = db('recharge')->select();
            return json(['code' => 1, 'msg' => '成功', 'data' => $res]);
        }
        $this->assign('head_title', "我的");
        return $this->fetch();
    }

    public function Pay()
    {
        if (request()->isPost()) {
            if (input('order_sn')) {
                $order_sn = input('order_sn');
            } else {
                $order_sn = time();
            }

            if (input('body')) {
                $body = input('body');
            } else {
                $body = '充值';
            }

            $total_fee = input('money');
            $type = input('type');

            if ($type == 1) {
                // wxpay
                require 'Wxpay.php';

                $outTradeNo = uniqid() . session('user.id');     //你自己的商品订单号
                // $payAmount = 0.01;          //付款金额，单位:元
                $payAmount = input('money');          //付款金额，单位:元
                $orderName = '充值积分:' . input('jifen');    //订单标题
                session('money', input('money'));
                session('dingdanhao', $outTradeNo);
                // $info = Db::name('wxpay_tmp')->where('user_id', session('user.id'))->find();
                // if ($info) {
                //     Db::name('wxpay_tmp')->where('user_id', session('user.id'))->update(['dingdanhao' => $outTradeNo,'money' => $payAmount]);
                // }else{
                //     Db::name('wxpay_tmp')->insert(['user_id' => session('user.id'),'dingdanhao' => $outTradeNo,'money' => $payAmount]);
                // }
                $wxpay = new Wxpay();
                $wxpay->setTotalFee($payAmount);
                $wxpay->setOutTradeNo($outTradeNo);
                $wxpay->setOrderName($orderName);

                $url = $wxpay->createJsBizPackage($payAmount, $outTradeNo, $orderName);

                return json(['code' => 1, 'data' => $url]);
                // print_r($url);exit;

            } else if ($type == 2) {
                // alipay
                require 'Alipay.php';

                $outTradeNo = uniqid();     //你自己的商品订单号
                // $payAmount = 0.01;          //付款金额，单位:元
                $payAmount = input('money');          //付款金额，单位:元
                $orderName = '充值积分:' . input('jifen');    //订单标题

                $aliPay = new Alipay();

                $payConfigs = $aliPay->doPay($payAmount, $outTradeNo, $orderName);
                $queryStr = http_build_query($payConfigs);
                if (isWeixin()) {
                    echo '<script type="text/javascript" src="ap.js"></script>
                        <script>
                            var gotoUrl = "https://openapi.alipay.com/gateway.do?' . $queryStr . '";
                            _AP.pay(gotoUrl);
                        </script>';
                } else {
                    $url = 'https://openapi.alipay.com/gateway.do?' . $queryStr;
                    // header("Location:https://openapi.alipay.com/gateway.do?".$queryStr."");
                }
                return json(['code' => 1, 'data' => $url]);
                // print_r($url);exit;
            }

        }
    }

    public function wxpayreturn()
    {

        require 'orderquery.php';

    }

}