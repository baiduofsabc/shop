<?php
namespace app\miniapi\controller;
use think\Db;
use think\Request;
use app\miniapi\controller\WechatPay;
class Order extends Common{
    public function _initialize(){
        parent::_initialize();
        $this->checkToken();
     //    $map['token'] = $this->token;
    	// $this->userInfo=db('users')
    	// ->field('id,username,email,sex,birthday,mobile,openid,unionid,avatar,province,city,district,level,is_lock,token')
    	// ->where($map)
    	// ->find(); 
    }
    public function index(){
        if(request()->isPost()) {
            $page =input('page')?input('page'):1;
            $pageSize =input('limit')?input('limit'):config('pageSize');
            $list = Db::table(config('database.prefix') . 'ad')->alias('a')
                ->join(config('database.prefix') . 'ad_type at', 'a.type_id = at.type_id', 'left')
                ->field('a.*,at.name as typename')
                ->where('a.name', 'like', "%" . $key . "%")
                ->order('a.sort')
                ->paginate(array('list_rows'=>$pageSize,'page'=>$page))
                ->toArray();
            foreach ($list['data'] as $k=>$v){
                $list['data'][$k]['addtime'] = date('Y-m-d H:i:s',$v['addtime']);
            }
            $result = ['code'=>1,'msg'=>'获取成功!','data'=>$list['data'],'count'=>$list['total'],'rel'=>1];
            return json($result);
        }
       
    }
    public function bill(){
        if(request()->isPost()) {
            $page =input('page')?input('page'):1;
            $pageSize =input('limit')?input('limit'):config('pageSize');
            $list = Db::table(config('database.prefix') . 'ad')->alias('a')
                ->join(config('database.prefix') . 'ad_type at', 'a.type_id = at.type_id', 'left')
                ->field('a.*,at.name as typename')
                ->where('a.name', 'like', "%" . $key . "%")
                ->order('a.sort')
                ->paginate(array('list_rows'=>$pageSize,'page'=>$page))
                ->toArray();
            foreach ($list['data'] as $k=>$v){
                $list['data'][$k]['addtime'] = date('Y-m-d H:i:s',$v['addtime']);
            }
            $result = ['code'=>1,'msg'=>'获取成功!','data'=>$list['data'],'count'=>$list['total'],'rel'=>1];
            return json($result);
        }

    }
    public function tend(){
        if(request()->isPost()) {
            $map['user_id']=$this->userInfo['id'];
            if(input('post.order_status')) {
               $map['order_status'] = input('post.order_status');
            }
            if(input('post.pay_status')) {
               $map['pay_status'] = input('post.pay_status');
            }
            $page =input('page')?input('page'):1;
            $pageSize =input('limit')?input('limit'):config('pageSize');
            $list = Db::table(config('database.prefix') . 'tend_order')->alias('a')
                ->field('a.*')
                ->where($map)
                ->order('a.id desc')
                ->paginate(array('list_rows'=>$pageSize,'page'=>$page))
                ->toArray();
            foreach ($list['data'] as $k=>$v){   
                $list['data'][$k]['addtime'] = date('Y-m-d H:i:s',$v['addtime']);
                $list['data'][$k]['outtime'] = date('Y-m-d H:i:s',$v['addtime']+60*30);
                $list['data'][$k]['selected_days'] = json_decode($v['selected_days']);           
            }
            $result = ['code'=>1,'msg'=>'获取成功!','data'=>$list['data'],'count'=>$list['total'],'rel'=>$map];
            return json($result);
        }
    }
    public function tendData(){
        if(request()->isPost()) {
            $map['tid'] = input('post.tend_id');
            $map['order_status']  = array('neq',3);
            $map['addtime']  = array('gt',strtotime('-1 days'));
            $list=db('tend_order')->where($map)->select(); 
            $select_days = array(); 
            foreach ($list as $k=>$v){   
                if($v['selected_days']) {
                    $data = $this->object_array(json_decode($v['selected_days']));
                    $select_days = array_merge($select_days,$data);
                }
            }      
            $result = ['code'=>1,'msg'=>'获取成功!','data'=>$select_days,'rel'=>db('tend_order')->getLastSql()];
            return json($result);
        }
    }
    public function tendAdd() {
        if (request()->isPost()) {
            $data = input('post.');
            $yCode = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J');
            $orderSn = $yCode[intval(date('Y')) - 2011] . strtoupper(dechex(date('m'))) . date('d') . substr(time(), -5) . substr(microtime(), 2, 5) . sprintf('%02d', rand(0, 99));
            $data['order_sn'] = $orderSn;
            $data['user_id'] = $this->userInfo['id'];
            $data['addtime'] = time();
            $id = db('tend_order')->insertGetId($data);
            if($id) {
               $result = ['code' => 1, 'msg' => '下单成功!', 'data' => $data,'id'=>$id]; 
            }
            else {
               $result = ['code' => 0, 'msg' => '下单失败!', 'data' => $data]; 
            }
            return json($result);
        }
    }
    public function tendDetail() {
        if (request()->isPost()) {
            $map['id'] = input('post.id');
            $orderInfo=db('tend_order')->where($map)->find(); 
            $orderInfo['addtime'] = date('Y-m-d H:i:s', $orderInfo['addtime']);
            $orderInfo['pay_time'] = $orderInfo['pay_time']?date('Y-m-d H:i:s', $orderInfo['pay_time']):"";
            $orderInfo['selected_days_formart_arr'] = explode(",", $orderInfo['selected_days_formart']);

            $map2['id'] = array('in',$orderInfo['service_ids']);

            $orderInfo['service_cate'] =db('service_cate')->select(); 
            foreach ($orderInfo['service_cate'] as $k=>$v){
                $map2['catid'] = $v['id'];
                $orderInfo['service_cate'][$k]['service'] =db('service')->where($map2)->select();
                foreach ($orderInfo['service_cate'][$k]['service'] as $k2 => $v2) {
                    if(empty($orderInfo['service_cate'][$k]['service'][$k2]['service_item'])) {
                        $orderInfo['service_cate'][$k]['service'][$k2]['service_item_arr'] = [];
                    }
                    else {
                      $orderInfo['service_cate'][$k]['service'][$k2]['service_item_arr'] = explode(",", $v2['service_item']);  
                    }   
                } 
            }
            $orderInfo['hospitalInfo'] =db('hospital')->where('id',$orderInfo['hospital_id'])->find();
            


            $result = ['code' => 1, 'msg' => '下单成功!', 'data' => $orderInfo]; 
            return json($result);
        }
    }
    public function tendUpdate() {
        if (request()->isPost()) {
            $data = input('post.');
            $data['updatetime'] = time();
            if($data['pay_status']) {
               $data['pay_time'] = time();
            }
            $update = db('tend_order')->update($data);
            if($update) {
               $result = ['code' => 1, 'msg' => '成功!'];  
            }
            else {
               $result = ['code' => 0, 'msg' => '失败!']; 
            } 
            return json($result);
        }
    }
    public function lease(){
        if(request()->isPost()) {
            $map['user_id']=$this->userInfo['id'];
            if(input('post.order_status')) {
               $map['order_status'] = input('post.order_status');
            }
            if(input('post.pay_status')) {
               $map['pay_status'] = input('post.pay_status');
            }
            $page =input('page')?input('page'):1;
            $pageSize =input('limit')?input('limit'):config('pageSize');
            $list = Db::table(config('database.prefix') . 'wheelchair_order')->alias('a')
                ->field('a.*')
                ->where($map)
                ->order('a.id desc')
                ->paginate(array('list_rows'=>$pageSize,'page'=>$page))
                ->toArray();
            foreach ($list['data'] as $k=>$v){   
                $list['data'][$k]['addtime'] = date('Y-m-d H:i:s',$v['addtime']);
            }
            $result = ['code'=>1,'msg'=>'获取成功!','data'=>$list['data'],'count'=>$list['total'],'rel'=>$map];
            return json($result);
        }
    }
    public function leaseAdd() {
        if (request()->isPost()) {
            $data = input('post.');
            $yCode = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J');
            $orderSn = $yCode[intval(date('Y')) - 2011] . strtoupper(dechex(date('m'))) . date('d') . substr(time(), -5) . substr(microtime(), 2, 5) . sprintf('%02d', rand(0, 99));
            $data['order_sn'] = $orderSn;
            $data['user_id'] = $this->userInfo['id'];
            $data['addtime'] = time();
            $update = db('wheelchair_order')->insertGetId($data);
            if($update) {
               $result = ['code' => 1, 'msg' => '下单成功!', 'data' => $data,'id'=>$update]; 
            }
            else {
               $result = ['code' => 0, 'msg' => '下单失败!', 'data' => $data]; 
            }
            return json($result);
        }
    }
    public function leaseDetail() {
        if (request()->isPost()) {
            $map['id'] = input('post.id');
            $orderInfo=db('wheelchair_order')->where($map)->find(); 
            $orderInfo['addtime'] = date('Y-m-d H:i:s', $orderInfo['addtime']);
            $orderInfo['pay_time'] = $orderInfo['pay_time']?date('Y-m-d H:i:s', $orderInfo['pay_time']):"";
            $result = ['code' => 1, 'msg' => '下单成功!', 'data' => $orderInfo]; 
            return json($result);
        }
    }
    public function leaseUpdate() {
        if (request()->isPost()) {
            $data = input('post.');
            $data['updatetime'] = time();
            if($data['pay_status']) {
               $data['pay_time'] = time();
            }
            $update = db('wheelchair_order')->update($data);
            if($update) {
               $result = ['code' => 1, 'msg' => '成功!'];  
            }
            else {
               $result = ['code' => 0, 'msg' => '失败!']; 
            } 
            return json($result);
        }
    }
    public function transport(){
        if(request()->isPost()) {
            $map['user_id']=$this->userInfo['id'];
            if(input('post.order_status')) {
               $map['order_status'] = input('post.order_status');
            }
            if(input('post.pay_status')) {
               $map['pay_status'] = input('post.pay_status');
            }
            $page =input('page')?input('page'):1;
            $pageSize =input('limit')?input('limit'):config('pageSize');
            $list = Db::table(config('database.prefix') . 'transport_order')->alias('a')
                ->field('a.*')
                ->where($map)
                ->order('a.id desc')
                ->paginate(array('list_rows'=>$pageSize,'page'=>$page))
                ->toArray();
            foreach ($list['data'] as $k=>$v){   
                $list['data'][$k]['addtime'] = date('Y-m-d H:i:s',$v['addtime']);
            }
            $result = ['code'=>1,'msg'=>'获取成功!','data'=>$list['data'],'count'=>$list['total'],'rel'=>$map];
            return json($result);
        }
    }
    public function addTransport() {
        if (request()->isPost()) {
            $data = input('post.');
            $yCode = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J');
            $orderSn = $yCode[intval(date('Y')) - 2011] . strtoupper(dechex(date('m'))) . date('d') . substr(time(), -5) . substr(microtime(), 2, 5) . sprintf('%02d', rand(0, 99));
            $data['order_sn'] = $orderSn;
            $data['user_id'] = $this->userInfo['id'];
            $data['addtime'] = time();
            $update = db('transport_order')->insertGetId($data);
            if($update) {
               $result = ['code' => 1, 'msg' => '下单成功!', 'data' => $data,'id'=>$update]; 
            }
            else {
               $result = ['code' => 0, 'msg' => '下单失败!', 'data' => $data]; 
            }
            return json($result);
        }
    }
    public function transportDetail() {
        if (request()->isPost()) {
            $map['id'] = input('post.id');
            $orderInfo=db('transport_order')->where($map)->find(); 
            $orderInfo['addtime'] = date('Y-m-d H:i:s', $orderInfo['addtime']);
            $orderInfo['pay_time'] = $orderInfo['pay_time']?date('Y-m-d H:i:s', $orderInfo['pay_time']):"";
            $result = ['code' => 1, 'msg' => '下单成功!', 'data' => $orderInfo]; 
            return json($result);
        }
    }
    public function transportUpdate() {
        if (request()->isPost()) {
            $data = input('post.');
            $data['updatetime'] = time();
            if($data['pay_status']) {
               $data['pay_time'] = time();
            }
            $update = db('transport_order')->update($data);
            if($update) {
               $result = ['code' => 1, 'msg' => '成功!'];  
            }
            else {
               $result = ['code' => 0, 'msg' => '失败!']; 
            } 
            return json($result);
        }
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
            $total_fee = input('post.total_fee');
            $openid = $this->user['openid'];
            $wechat = db('wechat')->where(array('id'=>1))->find();
            //$wechat['key'] = '8b9c6d6860ff97c17e386a5365faa4bc';
            $wechat['key'] = '3nzw2vx4e66govm24qnuxlkv3gehud3o';
            $wechat['notify_url'] = 'http://erwenxx.wkdns.cn';
            $wechat['trade_type'] = 'JSAPI';
         
            $wxpay=new WechatPay($wechat);
            return json($wxpay->unifiedorder($orders_sn,$total_fee,$openid,$body));
        }
    }
    public function getOrder() {
    	$map['user_id'] = $this->userInfo['id'];
    	$orderInfo=db('orders')->where($map)->find(); 
    	return json($orderInfo);
    }
    public function delOrder() {
    	$data['order_id'] = input('order_id');
    	$data['status'] = 200;
    	$upadte = db('orders')->update($data);
    	if($upadte) {
    		 $result = ['code'=>0,'msg'=>'删除成功!'];
    	}
    	else {
    		$result = ['code'=>1,'msg'=>'删除失败，该订单不存在!'];
    	}
    	return json($result);
    }


    public function commentInfo()
    {
        if (request()->isPost()) {
            $map=[];
            if(input('post.order_id')) {
              $map['order_id'] = input('post.order_id');
            }
            if(input('post.mod')) {
              $map['mod'] = input('post.mod');
            }
            $res = db('comment')->where($map)->find();
            if($res) {
               $result = ['code' => 1, 'msg' => '获取成功!', 'data' => $res,  'rel' => 1]; 
            }
            else {
               $result = ['code' => 0, 'msg' => '获取成功!', 'data' => $res,  'rel' => 1]; 
            }
            return json($result);
        }    
    }
    public function commentAdd()
    {
        if (request()->isPost()) {
            $data = input('post.');
            $data['user_id'] = $this->userInfo['id'];
            $data['addtime'] = time();
            $res = db('comment')->insert($data);
            if($res) {
                $map['id'] = $data['tid'];
                $tend = db('tend')->where($map)->find();
                $tendData['star_total'] = $tend['star_total'] + $data['star'];
                $tendData['comment_num'] = $tend['comment_num'] + 1;
                $star = round($tendData['star_total']/$tendData['comment_num']);
                $tendData['star'] = $star>5?5:$star;
                $tendData['id'] =  $data['tid'];
                db('tend')->update($tendData); 
            }
            $result = ['code' => 1, 'msg' => '提交成功!', 'data' => $res,  'rel' => 1];
            return json($result);
        }    
    }





    public function createdOrder() {
    	$yCode = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J');
        $orderSn = $yCode[intval(date('Y')) - 2011] . strtoupper(dechex(date('m'))) . date('d') . substr(time(), -5) . substr(microtime(), 2, 5) . sprintf('%02d', rand(0, 99));
        $data['order_sn'] = $orderSn;
    	$data['course_id'] = input('course_id');
        $data['price'] = input('price');
        $data['user_id'] = $this->userInfo['id'];
        
        $map['course_id'] = $data['course_id'];
        $map['user_id'] = $data['user_id'];
        $map['status'] = 1;
        $order = db('orders')->where($map)->select();
        if($order) {
        	$result = ['code'=>0,'msg'=>'该课程以购买!'];
        }
        else {
        	$upadte = db('orders')->insert($data);
        	if($upadte) {
    		 $result = ['code'=>0,'msg'=>'生成订单成功!'];
	    	}
	    	else {
	    		$result = ['code'=>1,'msg'=>'生成订单失败!'];
	    	}
        }
    	return json($result);
    }
     
    public function updateOrder() {
    	$data['order_id'] = input('order_id');
    	$data['status'] = 200;
    	$upadte = db('orders')->update($data);
    	if($upadte) {
    		 $result = ['code'=>0,'msg'=>'删除成功!'];
    	}
    	else {
    		$result = ['code'=>1,'msg'=>'删除失败，该订单不存在!'];
    	}
    	return json($result);
    }
    public function getPayUrl(){
        $resultData = [
            'body' => "{\"h5_info\": {\"type\":\"Wap\",\"wap_url\": \"https://pay.qq.com\",\"wap_name\": \"腾讯充值\"}} ", /**商品描述*/
            'out_trade_no' => time(), /**商户订单号*/
            'total_fee' => 1, /**标价金额(单位分)*/
            'notify_url' => url(), /**通知地址(WchatConfig::$notificationURL)*/
            'trade_type' => "NATIVE", /**交易类型  NATIVE  MWEB  */
            'product_id'=>22,
        ];
        $result = Wxpay::instance($this->$options)->unifiedOrder()->setParam($resultData)->getPayUrl();
        return json($result);
    }
    public function getMWEBPayUrl(){
        $resultData = [
            'body' => "{\"h5_info\": {\"type\":\"Wap\",\"wap_url\": \"https://pay.qq.com\",\"wap_name\": \"腾讯充值\"}} ", /**商品描述*/
            'out_trade_no' => time(), /**商户订单号*/
            'total_fee' => 1, /**标价金额(单位分)*/
            'notify_url' => url(), /**通知地址(WchatConfig::$notificationURL)*/
            'trade_type' => "MWEB", /**交易类型  NATIVE  MWEB  */
        ];
        
        $result = Wxpay::instance($this->$options)->unifiedOrder()->setParam($resultData)->getPayUrl();
        return json($result);
    }
    public function pay() {
        $appid='wxbedac84ff5711d5b';
        $openid= $this->userInfo['openid'];
        $mch_id='1489615132';
        $key='77hgFpGfZ1qH7GjIlRLJH7R2DPPzGCqO';
        $out_trade_no = $mch_id. time();
        $total_fee = $_GET['fee'];
        if(empty($total_fee)) //押金
        {
            $body = "充值押金";
            $total_fee = floatval(99*100);
        }
         else {
             $body = "充值余额";
             $total_fee = floatval($total_fee*100);
         }
        $weixinpay = new WeixinPay($appid,$openid,$mch_id,$key,$out_trade_no,$body,$total_fee);
        $return=$weixinpay->pay(); 
        echo json_encode($return);
    }
    public function notify() {
        $postXml = $GLOBALS["HTTP_RAW_POST_DATA"]; //接收微信参数
        if (empty($postXml)) {
            return false;
        } 
        $attr = $this->xmlToArray($postXml); 
        $total_fee = $attr[total_fee];
        $open_id = $attr[openid];
        $out_trade_no = $attr[out_trade_no];
        $time = $attr[time_end];
    }
    //将xml格式转换成数组
    public function xmlToArray($xml) {
        //禁止引用外部xml实体 
        libxml_disable_entity_loader(true);
        $xmlstring = simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA);
        $val = json_decode(json_encode($xmlstring), true);
        return $val;
    } 

}