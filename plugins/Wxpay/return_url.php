<?php
	session_start();
	error_reporting ( E_ALL );
	error_reporting(E_ALL & ~E_DEPRECATED);
	error_reporting(0);
	ini_set('date.timezone','PRC');
	ini_set('date.timezone','Asia/Shanghai');
	$xml = $GLOBALS["HTTP_RAW_POST_DATA"];
	$postObj = simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA);
	if($postObj->return_code=='SUCCESS'){

		logs('订单号为：'.$postObj->out_trade_no.'的订单号已支付成功! 微信通知时间 : '.$data['update_time'].' ; return_code : '.$postObj->return_code);
		
			echo "<xml><return_code><![CDATA[SUCCESS]]></return_code>
		                      <return_msg><![CDATA[OK]]></return_msg>
		                 </xml>";
	}

	logs('报文：\n'.$xml);

	logs(json_encode($postObj));
	function logs( $logthis ){
		$myfile = fopen("return_log.txt", "a") or die("Unable to open file!");
		fwrite($myfile, date('Y-m-d H:i:s').$logthis."\r\n");
		fclose($myfile);
	}
	
?>