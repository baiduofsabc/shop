<?php
namespace app\home\controller;


class Alipay extends Common
{
    // 沙箱
    const appid = '2019082666422727';  //https://open.alipay.com 账户中心->密钥管理->开放平台密钥，填写添加了电脑网站支付的应用的APPID
    const returnUrl = '/alipay-alipayreturn.html';     //付款成功后的同步回调地址
    const notifyUrl = '/alipay-alipaynotify.html';     //付款成功后的异步回调地址
    const charset = 'utf-8';
    //私钥值
    const rsaPrivateKey = 'MIIEvgIBADANBgkqhkiG9w0BAQEFAASCBKgwggSkAgEAAoIBAQCDMboCd3r2ziuEezr9xK3CcBXcY7NQ0C+NrA/r1QR/qtSYDD9F9g7adoBhzB2QXN0lF/pdrBceJhcK7Y4sQ353PYnbotdgtqirMdhSNalbMp2ieQOZudXDH245bStDU1HI1++xzi1zGq3rZQxm4KLCwQVrXQgKIvqPoFNK5L6wEZmZ/OoCdMVLIZvwhMfY0vyFfGtvuJ8RT3x66u18n4iQKbRSPg69pZxz9L+b38gcHDXWlXeNYrlIXSoIWLpej6XRT763jkKPjXNNiiduZ0sTzc0Ict3ci8c1n6qpwXg5ww9TwaF16xQk4S+cwVGdZaFTrc/RsUClRbSyy2Vho/SBAgMBAAECggEAC/2xiKQvAvvZEmTJAYTGdO0Vsm0SaKJ+li5RzjZMEPcmMzd7WQOiW+lU1pt3YEh8lfVZFtKx0jmdBYys3/LTTka7oO2DTf2j8PG/WdQ5xHId4km5QIWxugHXazxFJgQph3flGpVDpi+cEdXMimKhvbcyrZt1Lqf/yrzWoBPEMgdmjNcNTZdX/N+05oYxbXnvio8xlCI3PQOUQTTSrcUxVFMF0dHZcuMQrm9XLXqiuRiR3Z0WvoozLQLrpKS0lAao/ItbVLKgtC5hhBiYQVxcbc3QY2Mwv3EI2UPTTYojaUn0huCU20FWVlwbfzT8Vu8sWT7CLNE2WTjf7CjnQe+OIQKBgQD8chUI7SbWQgfpq/UPBJ96KGg9Yj+Wd39jDddzbmmV016cfR7TP3myVu8sXrNKgUaKBZ2FWpnLGcEM3kwmb6i6bgvC4IN2EDGF5iw1WLWOTNyfa8YtASA9FhPYstJs/lnQmT86jCQtdrNzjdsHL0ZC6/sokf8s8s6e7DjVTKgPfQKBgQCFCprGIX/0fyfPO/eTvx2rXu2J98VOCkObvjtmHUKNNn7tkktIn+majoheouXhBk7VsQuXGNhp5jUsT+ZnSJlDof14t8KDCgo5tpkqVGg8ujIhrfVsypc0jJWgKMo/pzkhSg+0clvnHub3fkD/+/KcL6n1pfNqhZ+jG8GvVIsQVQKBgQCQyY2u9PWVZYHMT8fZuTkOgyZmLndyAU+arFGp7uW3/OcCsfCAEKPn2VyMAjSqwsKGXvodhf03M6o5izX5xYjshDlSqBo+GMOOnJk2b46R8xuGg/XB0id/Ya44VzfBM5Cnx30wQzhsquv5lp7M3BxcPaRrfHDygsBm/e0KyVCKsQKBgAxIGFWmDgviGSi2XnqTMAf78W3FI7+6jtc3zn/0v4oNt9XypWSE7qnH8qrZ2uLVFD4B5BIExyWvjMUUcy6hsxRdRer4+8nhCWSeiLExrZW9kpctWfS7Fw5Q1Bc+7LzHkSyTi7OawwUpAyVh0FpRRepJNCVrNndk8oG6ND0dTuEpAoGBAJNGEYF1w+zp5CQn6V/bJuY0cm5BGuEaIbIv4OC8h+tYAjmjnMWJAAP3Ai48ofw2ag6EU8ijDNP8+zbQhyIu2J+piaBaN7DDZVMs7FAs0Z7HBRTogv3++VHJQL0rI6x0xSNY82Q54wIQ2BZYJs3V1CAURq9KKAC7dKQoQb4UHBS1';

    public function __construct()
    {
        $this->appId = self::appid;
        $this->returnUrl = self::returnUrl;
        $this->notifyUrl = self::notifyUrl;
        $this->charset = self::charset;
        $this->rsaPrivateKey = self::rsaPrivateKey;
    }
    /**
     * 发起订单
     * @param float $totalFee 收款总费用 单位元
     * @param string $outTradeNo 唯一的订单号
     * @param string $orderName 订单名称
     * @param string $notifyUrl 支付结果通知url 不要有问号
     * @param string $timestamp 订单发起时间
     * @return array
     */
    public function doPay($totalFee, $outTradeNo, $orderName)
    {
        //请求参数
        $requestConfigs = array(
            'out_trade_no'=>$outTradeNo,
            'product_code'=>'QUICK_WAP_WAY',
            'total_amount'=>$totalFee, //单位 元
            'subject'=>$orderName,  //订单标题
        );
        $commonConfigs = array(
            //公共参数
            'app_id' => $this->appId,
            'method' => 'alipay.trade.wap.pay',             //接口名称
            'format' => 'JSON',
            'return_url' => DOMAIN.self::returnUrl,
            'charset'=>$this->charset,
            'sign_type'=>'RSA2',
            'timestamp'=>date('Y-m-d H:i:s'),
            'version'=>'1.0',
            'notify_url' => DOMAIN.self::notifyUrl,
            'biz_content'=>json_encode($requestConfigs),
        );
        $commonConfigs["sign"] = $this->generateSign($commonConfigs, $commonConfigs['sign_type']);
        return $commonConfigs;
    }
    public function generateSign($params, $signType = "RSA") {
        return $this->sign($this->getSignContent($params), $signType);
    }
    protected function sign($data, $signType = "RSA") {
        $priKey=$this->rsaPrivateKey;
        $res = "-----BEGIN RSA PRIVATE KEY-----\n" .
            wordwrap($priKey, 64, "\n", true) .
            "\n-----END RSA PRIVATE KEY-----";
        ($res) or die('您使用的私钥格式错误，请检查RSA私钥配置');
        if ("RSA2" == $signType) {
            openssl_sign($data, $sign, $res, version_compare(PHP_VERSION,'5.4.0', '<') ? SHA256 : OPENSSL_ALGO_SHA256); //OPENSSL_ALGO_SHA256是php5.4.8以上版本才支持
        } else {
            openssl_sign($data, $sign, $res);
        }
        $sign = base64_encode($sign);
        return $sign;
    }
    /**
     * 校验$value是否非空
     *  if not set ,return true;
     *    if is null , return true;
     **/
    protected function checkEmpty($value) {
        if (!isset($value))
            return true;
        if ($value === null)
            return true;
        if (trim($value) === "")
            return true;
        return false;
    }
    public function getSignContent($params) {
        ksort($params);
        $stringToBeSigned = "";
        $i = 0;
        foreach ($params as $k => $v) {
            if (false === $this->checkEmpty($v) && "@" != substr($v, 0, 1)) {
                // 转换成目标字符集
                $v = $this->characet($v, $this->charset);
                if ($i == 0) {
                    $stringToBeSigned .= "$k" . "=" . "$v";
                } else {
                    $stringToBeSigned .= "&" . "$k" . "=" . "$v";
                }
                $i++;
            }
        }
        unset ($k, $v);
        return $stringToBeSigned;
    }
    /**
     * 转换字符集编码
     * @param $data
     * @param $targetCharset
     * @return string
     */
    function characet($data, $targetCharset) {
        if (!empty($data)) {
            $fileType = $this->charset;
            if (strcasecmp($fileType, $targetCharset) != 0) {
                $data = mb_convert_encoding($data, $targetCharset, $fileType);
            }
        }
        return $data;
    }

    public function alipayreturn(){
        $jifentmp = db('recharge')->where('money', $_GET['total_amount'])->find();
        $jifentmp2 = db('users_card')->where('user_id', session('user.id'))->find();
        $jifen = $jifentmp['integral'] + $jifentmp2['integral'];
        db('users_card')->where('user_id', session('user.id'))->update(['integral' => $jifen, 'updatetime' => time()]);
        db('users_card_follow')->insert(['user_id' => session('user.id'), 'integral' => $jifentmp['integral'], 'type' => 1, 'info' => '充值积分', 'updatetime' => time(), 'addtime' => time()]);
        // echo '<pre>'; print_r($_GET);print_r($jifen); exit;
        echo '<script type="text/javascript">alert("充值成功");window.location.href="'.DOMAIN.'/User-setting.html"</script>';
    }

    public function alipaynotify(){
        if (request()->isPost()) {
            $data =  input('post.');
            if($data['trade_status'] == 'TRADE_SUCCESS' || $data['trade_status'] == 'TRADE_FINISHED') {
                exit('success');
            }
            exit('fail');
        }
        exit('fail');
    }
}

