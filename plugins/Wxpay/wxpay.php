<?php
header("Content-type: text/html; charset=utf-8");
require_once ("wechatAppPay.class.php");

//以下参数是微信公众号参数，登录微信公众平台和商户平台就可以拿到
//微信公众号需要设置网页授权域名，添加支付目录，业务域名，js安全域名
define('APPID','wx4b4f4e0b100c026d');
//define('APPSECRET','SECRET ID');
define('APIKEY','69ac4576eea9adf8fe362e613cda7986');  
define('MCH_ID','1545738121');  
define('NOTIFY_URL','http://fszbs.wanzhong666.com/return_url.php');  
	
//生成随机字符串
function getNonceNum($numLen=16){
    $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
    $str = "";
    for ($i = 0; $i < $numLen; $i++) {
      $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
    }
    return $str;
}

//获取用户ip地址
function getClientIp(){
    $cip = "unknown";
    if($_SERVER['REMOTE_ADDR']){
        $cip = $_SERVER['REMOTE_ADDR'];
    }elseif(getenv("REMOTE_ADDR")){
        $cip = getenv("REMOTE_ADDR");
    }
    return $cip;
}


$getNonceNumstring = getNonceNum();//随机数
$out_trade_no =  date("Ymd").getNonceNum();//生成订单号
$ip = getClientIp();//取客户端IP


//统一下单参数
$param['appid']             = APPID;
$param['mch_id']            = MCH_ID;
$param['nonce_str']         = $getNonceNumstring;
$param['body']              = "测试商品";
$param['out_trade_no']      = $out_trade_no;
$param['total_fee']         = 1;
$param['spbill_create_ip']  = $ip;
$param['notify_url']        = NOTIFY_URL;
$param['trade_type']        = "MWEB"; //H5 支付类型



$wechatAppPay = new wechatAppPay(APPID, MCH_ID, NOTIFY_URL, APIKEY);//实例化微信支付类对象

$sign =  $wechatAppPay->MakeSign($param);//生成支付签名

$result = $wechatAppPay->unifiedOrder( $param );; // result中就是返回的各种信息

//如果下单成功会返回如下信息,这里已经转成了数组，按数组的方式操作就可以了。

/*
<xml><return_code><![CDATA[SUCCESS]]></return_code>
<return_msg><![CDATA[OK]]></return_msg>
<appid><![CDATA[wx9e5e2b4d093ece66]]></appid>
<mch_id><![CDATA[1341555201]]></mch_id>
<nonce_str><![CDATA[bBSmFakx2FOU2VaQ]]></nonce_str>
<sign><![CDATA[A2B0780BD235CA98266253CB63653130]]></sign>
<result_code><![CDATA[SUCCESS]]></result_code>
<prepay_id><![CDATA[wx24174533303925311f2ac1403843762497]]></prepay_id>
<trade_type><![CDATA[MWEB]]></trade_type>
<mweb_url><![CDATA[https://wx.tenpay.com/cgi-bin/mmpayweb-bin/checkmweb?prepay_id=wx24174533303925311f2ac1403843762497&package=4006479655]]></mweb_url>
</xml>
*/

$tiaozhuanurl = $result['mweb_url']."&redirect_url=http://fszbs.wanzhong666.com/success.php";
//redirect_url 为支付成功或取消支付之后跳转的地址
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
	<title>微信H5网页支付</title>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="initial-scale=1, maximum-scale=1">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="format-detection" content="telephone=no" />
	<link type="text/css" rel="stylesheet" href="Global_traffic_style.css"/>
</head>
<body>

<script>
	function pay(){
		window.location.href='<?php echo $tiaozhuanurl;?>'	
	}
</script>

<div class="container">
    <H1>微信H5网页支付</H1>
    <p>
    	请打开手机自带的浏览器，后然再点H5支付连接
    </p>
    <div class="btn-fixed">
        <div class="btn-box">
            <button type="button" class="btn-big" id="submitBtn" onClick="pay()">H5支付</button>
        </div>
    </div>
</div>
</body>
</html>



