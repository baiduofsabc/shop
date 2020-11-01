<?php
namespace Yurun\PaySDK\Weixin\CustomDeclareQuery;

use \Yurun\PaySDK\WeixinRequestBase;

/**
 * 微信支付-海关报关查询请求类
 */
class Request extends WeixinRequestBase
{
	/**
	 * 接口名称
	 * @var string
	 */
	public $_apiMethod = 'cgi-bin/mch/customs/customdeclarequery';

	/**
	 * 商户系统内部订单号，要求32个字符内，只能是数字、大小写字母_-|*@ ，且在同一个商户号下唯一。
	 * @var string
	 */
	public $out_trade_no;

	/**
	 * 微信支付返回的订单号
	 * @var string
	 */
	public $transaction_id;

	/**
	 * 商户子订单号
	 * @var string
	 */
	public $sub_order_no;

	/**
	 * 微信子订单号
	 * @var string
	 */
	public $sub_order_id;

	/**
	 * @var string
	 */
	public $customs;

	public function __construct()
	{
		$this->needNonceStr = $this->needSignType = false;
		$this->signType = 'MD5';
		parent::__construct();
	}
}