<?php
/**
 * 微信支付类 小程序 公众号都可使用
 */
namespace app\miniapi\controller;
class WechatPay{

    private $config;//微信配置

    public function __construct($config)
    {
        $this->config = $config;
    }

    /**
     * 统一下单接口
     * @param $orders_sn 订单号
     * @param $total_fee 支付金额
     * @param $openid 用户openid
     * @return mixed
     */

    public function unifiedorder($orders_sn,$total_fee,$openid,$body = '微信支付',$trade_type="JSAPI")
    {

        $url = 'https://api.mch.weixin.qq.com/pay/unifiedorder';
        $post['appid'] = $this->config['appid'];
        $post['body'] = $body;
        $post['mch_id'] = $this->config['mch_id'];
        $post['nonce_str'] = $this->createNoncestr();//随机字符串
        $post['notify_url'] = $this->config['notify_url'];
        $post['openid'] = $openid;
        $post['out_trade_no'] = $orders_sn;
        $post['spbill_create_ip'] = request()->ip();//服务器终端的ip
        $post['total_fee'] = $total_fee*100; //总金额 最低为一分钱 必须是整数
        $post['trade_type'] = $trade_type;
        $sign = $this->makeSign($post);
        $post['sign']   =   $sign;
        $result = $this->request($url,$post);
        if ($result['return_code'] === "SUCCESS" && $result['return_msg'] === 'OK'){
            $return = [
                'appId'     => $this->config['appid'],
                'timeStamp' => time().'',
                'nonceStr'  => $post['nonce_str'],
                'package'   => 'prepay_id='.$result['prepay_id'],
                //'code_url'   => $result['code_url'],
                'signType'  => 'MD5',
            ];
            //二次验签
            $return['paySign'] = $this->twoSign($return);
            return $return;
        }
        return $result;
    }

    /**
     * 微信退款接口
     * @param $out_trade_no 商户系统内部订单号
     * @param $out_refund_no 商户系统内部的退款单号
     * @param $total_fee 订单总金额，单位为分，只能为整数
     * @param $refund_fee 退款总金额，订单总金额，单位为分，
     * @return mixed
     */
    public function refund($out_trade_no,$out_refund_no,$total_fee,$refund_fee)
    {
        $url = 'https://api.mch.weixin.qq.com/secapi/pay/refund';
     

        $data = [
            'appid'             =>  $this->config['appid'],
            'mch_id'            =>  $this->config['mch_id'],
            'nonce_str'         =>  $this->createNoncestr(),
            'out_trade_no'      =>  $out_trade_no,
            'out_refund_no'     =>  $out_refund_no,
            'total_fee'         =>  $total_fee*100,
            'refund_fee'        =>  $refund_fee*100,
            'notify_url'        =>  $this->config['notify_url']
        ];
        

        $data['sign']   =   $this->makeSign($data);


        $result = $this->request($url,$data,true);

        return $result;

    }

    /**
     * 二次验签
     * @return [type] [description]
     */
    private function twoSign($result)
    {
        return strtoupper(md5('appId='.$this->config['appid'].'&nonceStr='.$result['nonceStr'].'&package='.$result['package'].'&signType=MD5&timeStamp='.$result['timeStamp'].'&key='.$this->config['key']));
    }

    /**
     * 发起一个post请求
     * @param $url 请求 url
     * @param $array 请求参数
     * @param $setCert 是否是要证书 证书需要绝对路径
     * @return mixed
     */
    private function request($url,$array,$setCert = false)
    {
        $curl = curl_init(); // 启动一个CURL会话
        curl_setopt($curl, CURLOPT_URL, $url); // 要访问的地址
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        //设置header
        curl_setopt($curl, CURLOPT_HEADER, FALSE);
        if ($setCert == true) {
      
            //网站VENDOR目录
            $getRootPath = VENDOR_PATH;
            // 设置证书
            curl_setopt($curl, CURLOPT_SSLCERTTYPE, 'pem');
            curl_setopt($curl, CURLOPT_SSLCERT, $getRootPath . 'wechatPay/cert/apiclient_cert.pem');
            curl_setopt($curl, CURLOPT_SSLCERTTYPE, 'pem');
            curl_setopt($curl, CURLOPT_SSLKEY, $getRootPath . 'wechatPay/cert/apiclient_key.pem');
            curl_setopt($curl, CURLOPT_SSLCERTTYPE, 'pem');
            curl_setopt($curl, CURLOPT_CAINFO, $getRootPath . 'wechatPay/cert/rootca.pem');
        }
        //要求结果为字符串且输出到屏幕上
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($curl, CURLOPT_POST, TRUE);       //发送一个常规的Post请求
        curl_setopt($curl, CURLOPT_POSTFIELDS, $this->ToXml($array)); // Post提交的数据包
        curl_setopt($curl, CURLOPT_TIMEOUT, 30);      // 设置超时限制防止死循环
        $tmpInfo = curl_exec($curl); // 执行操作
        curl_close($curl); //关闭CURL会话
        return $this->FromXml($tmpInfo);
    }
    /**
     * 生成随机数
     * @param int $len 随机数长度
     * @return string
     */
    private function createNoncestr($length = 32)
    {
        $str = null;
        $strPol = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz";
        $max = strlen($strPol)-1;

        for($i=0;$i<$length;$i++){
            $str.=$strPol[rand(0,$max)];//rand($min,$max)生成介于min和max两个数之间的一个随机整数
        }

        return $str;
    }

    /**
     * 将 xml 转为数组
     * @param $xml
     * @return mixed
     */
    private function FromXml($xml)
    {
        //将XML转为array
        return json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)),true);
    }

    /**
     * 将 数组 转为 xml
     * @param $arr
     * @return string
     */
    private function ToXml($arr)
    {
        $xml = "<xml>";
        foreach ($arr as $key => $val) {
            if (is_numeric($val)) {
                $xml .= "<" . $key . ">" . $val . "</" . $key . ">";
            } else {
                $xml .= "<" . $key . "><![CDATA[" . $val . "]]></" . $key . ">";
            }
        }
        $xml .= "</xml>";
        return $xml;
    }

    /**
     * 生成支付签名
     * @param $data
     * @return string
     */
    private function makeSign($paramArray,$isencode = false){
        $paramStr = '';
        ksort($paramArray);
        $i = 0;
        foreach ($paramArray as $key => $value)
        {
            if ($key == 'Signature'){
                continue;
            }
            if ($i == 0){
                $paramStr .= '';
            }else{
                $paramStr .= '&';
            }
            $paramStr .= $key . '=' . ($isencode?urlencode($value):$value);
            ++$i;
        }
        $stringSignTemp=$paramStr."&key=".$this->config['key'];
        $sign=strtoupper(md5($stringSignTemp));
        return $sign;
    }
}
