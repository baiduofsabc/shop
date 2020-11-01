<?php
namespace app\home\controller;

class Wxpay
{
    const mchid = '1545738121';
    const appid = 'wx4b4f4e0b100c026d';
    const apiKey = '69ac4576eea9adf8fe362e613cda7986';
    const notifyUrl = 'http://fszbs.wanzhong666.com/home-Recharge-wxpaynotify.html';
    const returnUrl = 'http://fszbs.wanzhong666.com/home-Recharge-wxpayreturn.html';
    // const returnUrl = 'http://fszbs.wanzhong666.com/home-User-setting.html';
    const wapUrl = 'http://fszbs.wanzhong666.com';
    const wapName = '服饰专版';

    public function __construct()
    {
        $this->mchid = self::mchid;
        $this->appid = self::appid;
        $this->apiKey = self::apiKey;
        $this->notifyUrl = self::notifyUrl;
        $this->returnUrl = self::returnUrl;
        $this->wapUrl = self::wapUrl;
        $this->wapName = self::wapName;
    }

    public function setTotalFee($totalFee)
    {
        $this->totalFee = $totalFee;
    }
    public function setOutTradeNo($outTradeNo)
    {
        $this->outTradeNo = $outTradeNo;
    }
    public function setOrderName($orderName)
    {
        $this->orderName = $orderName;
    }
    // public function setWapUrl($wapUrl)
    // {
    //     $this->wapUrl = $wapUrl;
    // }
    // public function setWapName($wapName)
    // {
    //     $this->wapName = $wapName;
    // }
    // public function setNotifyUrl($notifyUrl)
    // {
    //     $this->notifyUrl = $notifyUrl;
    // }
    // public function setReturnUrl($returnUrl)
    // {
    //     $this->returnUrl = $returnUrl;
    // }

    /**
     * 发起订单
     * @return array
     */
    public function createJsBizPackage()
    {
        $config = array(
            'mch_id' => $this->mchid,
            'appid' => $this->appid,
            'key' => $this->apiKey,
        );
        $scene_info = array(
            'h5_info' =>array(
                'type'=>'Wap',
                'wap_url'=>$this->wapUrl,
                'wap_name'=>$this->wapName,
            )
        );
        $unified = array(
            'appid' => $config['appid'],
            'attach' => 'pay',             //商家数据包，原样返回，如果填写中文，请注意转换为utf-8
            'body' => $this->orderName,
            'mch_id' => $config['mch_id'],
            'nonce_str' => self::createNonceStr(),
            'notify_url' => $this->notifyUrl,
            'out_trade_no' => $this->outTradeNo,
            'spbill_create_ip' => $_SERVER['REMOTE_ADDR'],
            'total_fee' => intval($this->totalFee * 100),       //单位 转为分
            'trade_type' => 'MWEB',
            'scene_info'=>json_encode($scene_info)
        );
        $unified['sign'] = self::getSign($unified, $config['key']);

        // return $unified;
        $responseXml = self::curlPost('https://api.mch.weixin.qq.com/pay/unifiedorder', self::arrayToXml($unified));
        // return $responseXml;
        $unifiedOrder = simplexml_load_string($responseXml, 'SimpleXMLElement', LIBXML_NOCDATA);
        // return $unifiedOrder->mweb_url.'&redirect_url='.urlencode($this->returnUrl);
        if ($unifiedOrder->return_code != 'SUCCESS') {
            die($unifiedOrder->return_msg);
        }
        if($unifiedOrder->mweb_url){
            return $unifiedOrder->mweb_url.'&redirect_url='.urlencode($this->returnUrl);
        }
        exit('error');
    }
    public static function curlPost($url = '', $postData = '', $options = array())
    {
        if (is_array($postData)) {
            $postData = http_build_query($postData);
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30); //设置cURL允许执行的最长秒数
        if (!empty($options)) {
            curl_setopt_array($ch, $options);
        }
        //https请求 不验证证书和host
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }
    public static function createNonceStr($length = 16)
    {
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $str = '';
        for ($i = 0; $i < $length; $i++) {
            $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        }
        return $str;
    }
    public static function arrayToXml($arr)
    {
        $xml = "<xml>";
        foreach ($arr as $key => $val) {
            if (is_numeric($val)) {
                $xml .= "<" . $key . ">" . $val . "</" . $key . ">";
            } else
                $xml .= "<" . $key . "><![CDATA[" . $val . "]]></" . $key . ">";
        }
        $xml .= "</xml>";
        return $xml;
    }
    /**
     * 获取签名
     */
    public static function getSign($params, $key)
    {
        ksort($params, SORT_STRING);
        $unSignParaString = self::formatQueryParaMap($params, false);
        $signStr = strtoupper(md5($unSignParaString . "&key=" . $key));
        return $signStr;
    }
    protected static function formatQueryParaMap($paraMap, $urlEncode = false)
    {
        $buff = "";
        ksort($paraMap);
        foreach ($paraMap as $k => $v) {
            if (null != $v && "null" != $v) {
                if ($urlEncode) {
                    $v = urlencode($v);
                }
                $buff .= $k . "=" . $v . "&";
            }
        }
        $reqPar = '';
        if (strlen($buff) > 0) {
            $reqPar = substr($buff, 0, strlen($buff) - 1);
        }
        return $reqPar;
    }

    public function notify()
    {
        $config = array(
            'mch_id' => $this->mchid,
            'appid' => $this->appid,
            'key' => $this->apiKey,
        );
        
        // $postStr = file_get_contents('php://input');
        $xml = $GLOBALS["HTTP_RAW_POST_DATA"];
        // $postObj = simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA);
        // if($postObj->return_code=='SUCCESS'){

        //     logs('订单号为：'.$postObj->out_trade_no.'的订单号已支付成功! 微信通知时间 : '.$data['update_time'].' ; return_code : '.$postObj->return_code);
            
        //         echo "<xml><return_code><![CDATA[SUCCESS]]></return_code>
        //                           <return_msg><![CDATA[OK]]></return_msg>
        //                      </xml>";
        // }
        // return $config;
        //禁止引用外部xml实体
        // libxml_disable_entity_loader(true);        
        $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
        if ($postObj == false) {
            die('parse xml error');
        }
        if ($postObj->return_code != 'SUCCESS') {
            die($postObj->return_msg);
        }
        if ($postObj->result_code != 'SUCCESS') {
            die($postObj->err_code);
        }
        $arr = (array)$postObj;
        unset($arr['sign']);
        if (self::getSign($arr, $config['key']) == $postObj->sign) {
            echo '<xml><return_code><![CDATA[SUCCESS]]></return_code><return_msg><![CDATA[OK]]></return_msg></xml>';
            return $arr;
        }
    }

    public function ruku(){
        $jifentmp = db('recharge')->where('money', session('money'))->find();
            $jifentmp2 = db('users_card')->where('user_id', session('user.id'))->find();
            $jifen = $jifentmp['integral'] + $jifentmp2['integral'];
            db('users_card')->where('user_id', session('user.id'))->update(['integral' => $jifen, 'updatetime' => time()]);
            db('users_card_follow')->insert(['user_id' => session('user.id'), 'integral' => $jifentmp['integral'], 'type' => 1, 'info' => '充值积分', 'updatetime' => time(), 'addtime' => time()]);
            // echo '<pre>'; print_r($jifentmp);print_r($jifentmp2); exit;
            // echo '<script type="text/javascript">alert("充值成功");window.location.href="http://fszbs.wanzhong666.com/home-User-setting.html"</script>';
    }
    
}

