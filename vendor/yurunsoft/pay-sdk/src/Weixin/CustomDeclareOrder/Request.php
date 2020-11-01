<?php
namespace Yurun\PaySDK\Weixin\CustomDeclareOrder;

use \Yurun\PaySDK\WeixinRequestBase;

/**
 * 微信支付-海关报关提交请求类
 */
class Request extends WeixinRequestBase
{
	/**
	 * 接口名称
	 * @var string
	 */
	public $_apiMethod = 'cgi-bin/mch/customs/customdeclareorder';

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
	 * @var string
	 */
	public $customs;

	/**
	 * @var string
	 */
	public $mch_customs_no;

	/**
	 * @var int
	 */
	public $duty;

	/**
	 * 商户子订单号，如有拆单则必传
	 * @var string
	 */
	public $sub_order_no;

	/**
	 * 币种，微信支付订单支付时使用的币种，暂只支持人民币CNY,如有拆单则必传。
	 * @var string
	 */
	public $fee_type;

	/**
	 * 应付金额
	 * 子订单金额，以分为单位，不能超过原订单金额，order_fee=transport_fee+product_fee（应付金额=物流费+商品价格），如有拆单则必传。
	 * @var int
	 */
	public $order_fee;

	/**
	 * 物流费用，以分为单位，如有拆单则必传。
	 * @var int
	 */
	public $transport_fee;

	/**
	 * 商品费用，以分为单位，如有拆单则必传。
	 * @var int
	 */
	public $product_fee;

	/**
	 * 证件类型
	 * @var string
	 */
	public $cert_type;

	/**
	 * 证件号码
	 * 身份证号，尾号为字母X的身份证号，请大写字母X。该参数是指用户信息，商户若有用户信息，可上送，系统将以商户上传的数据为准，进行海关通关报备；
	 * @var string
	 */
	public $cert_id;

	/**
	 * @var string
	 */
	public $name;

	public function __construct()
	{
		$this->needNonceStr = $this->needSignType = false;
		$this->signType = 'MD5';
		parent::__construct();
	}
}