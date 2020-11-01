<?php
namespace app\miniapi\controller;
use think\Db;
use think\Request;
class Users extends Common{
    public function _initialize(){
        parent::_initialize();

        $this->check = $this->checkToken();

        $map['token'] = $this->token;
    }
    public function index(){
        if($this->userInfo) {
          $result = ['code' => 1, 'msg' => '成功!', 'data' => $this->userInfo];  
        }
        else {
            $result = ['code' => 0, 'msg' => '失败!', 'data' => $this->userInfo];  
        }
        return json($result);
        
       
    }
    public function settingInfo(){
        if($this->userInfo) {
          $result = ['code' => 1, 'msg' => '成功!', 'data' => 1];  
        }
        return json($result); 
    }
    public function recharge(){
        if (request()->isPost()) {
            $map['id'] = $this->userInfo['id'];
            $list = db('users')->where($map)->setInc('money',input('post.money'));
            if($list) {
                $result = ['code' => 1, 'msg' => '充值成功!', 'data' => $list];  
            }
            else {
                $result = ['code' => 0, 'msg' => '充值失败!', 'data' => $info];  
            }
            return json($result);
       }
       
    }

    public function addressList(){
       if (request()->isPost()) {
            $map['user_id'] = $this->userInfo['id'];
            $list = db('users_address')->where($map)->select();
            if($list) {
                $result = ['code' => 1, 'msg' => '获取成功!', 'data' => $list];  
            }
            else {
                $result = ['code' => 0, 'msg' => '获取失败!', 'data' => $info];  
            }
            return json($result);
       }
    }

    public function addressDel(){
       if (request()->isPost()) {
            $map['id'] = input('post.id');
            $list = db('users_address')->where($map)->delete();
            if($list) {
                $result = ['code' => 1, 'msg' => '删除成功!', 'data' => $list];  
            }
            else {
                $result = ['code' => 0, 'msg' => '删除失败!', 'data' => $info];  
            }
            return json($result);
       }
    }
    public function addressAdd(){
       if (request()->isPost()) {
            $data=input('post.');
            $data['updatetime'] = time();
            $data['user_id'] = $this->userInfo['id'];
            if($data['id']) {
              $info =  db('users_address')->update($data);  
            }
            else {
                $data['addtime'] = time();
              $info =  db('users_address')->insert($data);
            }
            if($info) {
                $result = ['code' => 1, 'msg' => '提交成功!', 'data' => $info];  
            }
            else {
                $result = ['code' => 0, 'msg' => '提交失败!', 'data' => $info];  
            }
            return json($result);
       }
    }
    public function linkList(){
         
       if (request()->isPost()) {
            $map['user_id'] = $this->userInfo['id'];
            $list = db('users_link')->where($map)->select();
            if($list) {
                $result = ['code' => 1, 'msg' => '获取成功!', 'data' => $list];  
            }
            else {
                $result = ['code' => 0, 'msg' => '获取失败!', 'data' => $info];  
            }
            return json($result);
       }
    }

    public function linkDel(){
       if (request()->isPost()) {
            $map['id'] = input('post.id');
            $list = db('users_link')->where($map)->delete();
            if($list) {
                $result = ['code' => 1, 'msg' => '删除成功!', 'data' => $list];  
            }
            else {
                $result = ['code' => 0, 'msg' => '删除失败!', 'data' => $info];  
            }
            return json($result);
       }
    }
    public function linkAdd(){
       if(!$this->userInfo['id']) {
            return json(['code' => 0, 'msg' => '请先登录!']);
        }  
       if (request()->isPost()) {
            $data=input('post.');
            $data['updatetime'] = time();
            $data['user_id'] = $this->userInfo['id'];
            if($data['id']) {
              $info =  db('users_link')->update($data);  
            }
            else {
                $data['addtime'] = time();
              $info =  db('users_link')->insert($data); 
            }
            if($info) {
                $result = ['code' => 1, 'msg' => '提交成功!', 'data' => $info];  
            }
            else {
                $result = ['code' => 0, 'msg' => '提交失败!', 'data' => $info];  
            }
            return json($result);
       }
    }

    public function tmanList(){
       if (request()->isPost()) {
            $map['user_id'] = $this->userInfo['id'];
            $list = db('users_tman')->where($map)->select();
            if($list) {
                $result = ['code' => 1, 'msg' => '获取成功!', 'data' => $list];  
            }
            else {
                $result = ['code' => 0, 'msg' => '获取失败!', 'data' => $info];  
            }
            return json($result);
       }
    }

    public function tmanDel(){
       if (request()->isPost()) {
            $map['id'] = input('post.id');
            $list = db('users_tman')->where($map)->delete();
            if($list) {
                $result = ['code' => 1, 'msg' => '删除成功!', 'data' => $list];  
            }
            else {
                $result = ['code' => 0, 'msg' => '删除失败!', 'data' => $info];  
            }
            return json($result);
       }
    }
    public function tmanAdd(){
       if (request()->isPost()) {
            $data=input('post.');
            $data['updatetime'] = time();
            $data['user_id'] = $this->userInfo['id'];
            if($data['id']) {
              $info =  db('users_tman')->update($data);  
            }
            else {
                $data['addtime'] = time();
              $info =  db('users_tman')->insert($data); 
            }
            if($info) {
                $result = ['code' => 1, 'msg' => '提交成功!', 'data' => $info];  
            }
            else {
                $result = ['code' => 0, 'msg' => '提交失败!', 'data' => $info];  
            }
            return json($result);
       }
    }


    public function getUserInfo() {
        return json($this->userInfo);        
    }
    public function couponList() {
        if (request()->isPost()) {
            $map=[];
            $map['start_time'] = array('lt',time()*1000);
            $map['end_time'] = array('gt',time()*1000);
            $list = db('coupon')->where($map)->select();
            foreach ($list as $k => $v) {
                $list[$k]['start_time'] = date('Y-m-d', $v['start_time']/1000);
                $list[$k]['end_time'] = date('Y-m-d', $v['end_time']/1000);
                $list[$k]['cut_price'] = round($v['cut_price'],0);;
            }
            if($list) {
                $result = ['code' => 1, 'msg' => '获取成功!', 'data' => $list];
            }
            else {
                $result = ['code' => 0, 'msg' => '获取失败!', 'data' => $info];
            }
            return json($result);
        }

    }


    
    //到此






    public function orderList() {
        $map['a.user_id'] = $this->userInfo['id'];
        $status = input('status');
        if(is_numeric($status)) {
           $map['a.status'] = array(array('eq',$status),array('neq',200));
        }
        else {
           $map['a.status'] = array('neq',200);
        }
        $page =input('page')?input('page'):1;
        $pageSize =input('limit')?input('limit'):config('pageSize');
        $list = Db::table(config('database.prefix') . 'orders')->alias('a')
                    ->join(config('database.prefix') . 'course at', 'a.course_id = at.course_id', 'left')
                    ->field('a.*,at.name as course_name,at.thumb as course_thumb')
                    ->where($map)
                    ->order('a.id desc')
                    ->paginate(array('list_rows'=>$pageSize,'page'=>$page))
                    ->toArray();
        foreach ($list['data'] as $k=>$v){
             $list['data'][$k]['updatetime'] = date('Y-m-d H:i:s',$v['updatetime']);
             $list['data'][$k]['addtime'] = date('Y-m-d H:i:s',$v['addtime']);
        } 
        $list['data'] = $this->addImgUrl($list['data'],'course_thumb');           
        return json($list);
    }
    public function getOrder() {
    	$map['a.user_id'] = $this->userInfo['id'];
        $map['a.id'] = input('id');
        $orderInfo = Db::table(config('database.prefix') . 'orders')->alias('a')
                    ->join(config('database.prefix') . 'course at', 'a.course_id = at.course_id', 'left')
                    ->field('a.*,at.name as course_name,at.thumb as course_thumb')
                    ->where($map)
                    ->order('a.id desc')
                    ->find();
        $orderInfo['updatetime'] = date('Y-m-d H:i:s',$orderInfo['updatetime']);
        $orderInfo['addtime'] = date('Y-m-d H:i:s',$orderInfo['addtime']);
    	return json($orderInfo);
    }
    public function delOrder() {
    	$data['id'] = input('id');
    	$data['status'] = 200;
    	$upadte = db('orders')->update($data);
    	if($upadte) {
    		 $result = ['code'=>1,'msg'=>'删除成功!'];
    	}
    	else {
    		$result = ['code'=>0,'msg'=>'删除失败，该订单不存在!'];
    	}
    	return json($result);
    }
    public function createdOrder() {
    	$yCode = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J');
        $orderSn = $yCode[intval(date('Y')) - 2011] . strtoupper(dechex(date('m'))) . date('d') . substr(time(), -5) . substr(microtime(), 2, 5) . sprintf('%02d', rand(0, 99));
        $data['order_id'] = $orderSn;
    	$data['course_id'] = input('course_id');
        $data['price'] = input('price');
        $data['user_id'] = $this->userInfo['id'];
        $data['pay_from'] = input('pay_from');
        $data['upadtetime'] = time();
        $data['addtime'] = time();
        $map['course_id'] = $data['course_id'];
        $map['user_id'] = $data['user_id'];
        $map['status'] = 1;
        $order = db('orders')->where($map)->select();
        if($order) {
        	$result = ['code'=>0,'msg'=>'该课程以购买!'];
        }
        else {
        	//$upadte = db('orders')->insert($data);
            $order_id = Db::name('orders')->insertGetId($data);

        	if($order_id) {
    		    $result = ['code'=>1,'msg'=>'生成订单成功!','order_id'=>$order_id];
	    	}
	    	else {
	    		$result = ['code'=>0,'msg'=>'生成订单失败!'];
	    	}
        }
    	return json($result);
    }


    public function createdSvipOrder() {
        $yCode = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J');
        $orderSn = $yCode[intval(date('Y')) - 2011] . strtoupper(dechex(date('m'))) . date('d') . substr(time(), -5) . substr(microtime(), 2, 5) . sprintf('%02d', rand(0, 99));
        $data['order_id'] = $orderSn;
        $data['course_id'] = input('course_id');
        $data['price'] = input('price');
        $data['user_id'] = $this->userInfo['id'];
        $data['level_id'] = input('level_id');
        $data['subject_ids'] = input('subject_ids');
        $data['upadtetime'] = time();
        $data['addtime'] = time();
        $map['course_id'] = $data['course_id'];
        $map['level_id'] = $data['level_id'];
        $map['subject_ids'] = $data['subject_ids'];
        $map['user_id'] = $data['user_id'];
        $map['status'] = 1;
        $order = db('orders_svip')->where($map)->select();
        if($order) {
            $result = ['code'=>0,'msg'=>'该课程已购买!'];
        }
        else {
            //$upadte = db('orders')->insert($data);
            $order_id = Db::name('orders_svip')->insertGetId($data);
            if($order_id) {
                $result = ['code'=>1,'msg'=>'生成订单成功!','order_id'=>$data['order_id']];
            }
            else {
                $result = ['code'=>0,'msg'=>'生成订单失败!'];
            }
        }
        return json($result);
    }
    public function svipOrderList() {
        $map['a.user_id'] = $this->userInfo['id'];
        $status = input('status');
        if(is_numeric($status)) {
           $map['a.status'] = array(array('eq',$status),array('neq',200));
        }
        else {
           $map['a.status'] = array('neq',200);
        }
        $page =input('page')?input('page'):1;
        $pageSize =input('limit')?input('limit'):config('pageSize');
        $list = Db::table(config('database.prefix') . 'orders_svip')->alias('a')
                    ->join(config('database.prefix') . 'course_svip at', 'a.course_id = at.course_id', 'left')
                    ->field('a.*,at.name as course_name,at.thumb as course_thumb')
                    ->where($map)
                    ->order('a.id desc')
                    ->paginate(array('list_rows'=>$pageSize,'page'=>$page))
                    ->toArray();
        foreach ($list['data'] as $k=>$v){
             $list['data'][$k]['updatetime'] = date('Y-m-d H:i:s',$v['updatetime']);
             $list['data'][$k]['addtime'] = date('Y-m-d H:i:s',$v['addtime']);
             $map2['id'] = array('in',$v['subject_ids']);
             $list['data'][$k]['subject']=db('cate')->field('id,name')->where($map2)->select();

        } 
        $list['data'] = $this->addImgUrl($list['data'],'course_thumb');           
        return json($list);
    }
    public function getSvipOrder() {
        $map['a.user_id'] = $this->userInfo['id'];
        $map['a.id'] = input('id');
        $orderInfo = Db::table(config('database.prefix') . 'orders_svip')->alias('a')
                    ->join(config('database.prefix') . 'course_svip at', 'a.course_id = at.course_id', 'left')
                    ->field('a.*,at.name as course_name,at.thumb as course_thumb')
                    ->where($map)
                    ->order('a.id desc')
                    ->find();
        $orderInfo['updatetime'] = date('Y-m-d H:i:s',$orderInfo['updatetime']);
        $orderInfo['addtime'] = date('Y-m-d H:i:s',$orderInfo['addtime']);
        return json($orderInfo);
    }
    public function delSvipOrder() {
        $data['id'] = input('id');
        $data['status'] = 200;
        $upadte = db('orders_svip')->update($data);
        if($upadte) {
             $result = ['code'=>1,'msg'=>'删除成功!'];
        }
        else {
            $result = ['code'=>0,'msg'=>'删除失败，该订单不存在!'];
        }
        return json($result);
    }




    public function updateOrder() {
        $data['id'] = input('id');
        if(input('status')) {
          $data['status'] = input('status');  
        }
        if($data['status']==1) {
            $order = db('orders')->where(array('id'=>$data['id']))->find();
            $this->addMyCourse($order['course_id']);
            db('course')->where(array('course_id'=>$order['course_id']))->setInc('sell_num');
        }
        if(input('pay_type')) {
          $data['pay_type'] = input('pay_type');  
        }
        $data['upadtetime'] = time();
        $upadte = db('orders')->update($data);
        if($upadte) {
             $result = ['code'=>1,'msg'=>'授权成功!'];
        }
        else {
            $result = ['code'=>0,'msg'=>'授权失败!'];
        }
        return json($result);
    }
    public function addMyCourse($course_id) {
        $map['course_id'] = $course_id;
        $map['user_id'] =  $this->userInfo['id'];
        $my=db('users_course')->where($map)->find();
        if(!$my) {
             $map['addtime'] = time();
             $map['validity'] = strtotime("+1 year");
             db('users_course')->insert($map);  
        }
    }
    public function object_array($array) {  
        if(is_object($array)) {  
            $array = (array)$array;  
         } if(is_array($array)) {  
             foreach($array as $key=>$value) {  
                 $array[$key] = $this->object_array($value);  
                 }  
         }  
         return $array;  
    }
    public function myCourse() {
        $map['a.user_id'] = $this->userInfo['id'];
        if(input('level_id')) {
               $map['at.level_id'] = input('level_id'); 
        }
        if(input('subject_id')) {
               $map['at.subject_id'] = input('subject_id'); 
        }
        if(input('type_id')) {
               $map['at.type_id'] = input('type_id'); 
        }
        $page =input('page')?input('page'):1;
        $pageSize =input('limit')?input('limit'):config('pageSize');
        $list = Db::table(config('database.prefix') . 'users_course')->alias('a')
                    ->join(config('database.prefix') . 'course at', 'a.course_id = at.course_id', 'left')
                    ->field('a.last_video_id,a.study_time,a.study_time,a.updatetime as last_study_time,at.*')
                    ->where($map)
                    ->order('at.course_id desc')
                    ->paginate(array('list_rows'=>$pageSize,'page'=>$page))
                    ->toArray();
        foreach ($list['data'] as $k=>$v){
            if(empty($list['data'][$k]['last_video_id'])) {
                $list['data'][$k]['section'] = $this->object_array(json_decode($v['section']));
                $list['data'][$k]['last_video_id'] = $list['data'][$k]['section'][0]['videoInfo'][0]['video_id'];
            }
            $list['data'][$k]['updatetime'] = date('Y-m-d H:i:s',$v['updatetime']);
            $list['data'][$k]['addtime'] = date('Y-m-d H:i:s',$v['addtime']);
            $list['data'][$k]['last_study_time'] = empty($list['data'][$k]['last_study_time'])?"未学习":date('Y-m-d H:i:s',$v['last_study_time']);
            $map2['id'] = $v['type_id'];
            $typeInfo =  db('cate')->where($map2)->find();
            $list['data'][$k]['is_free'] = $typeInfo['is_free'];


        } 
        $list['data'] = $this->addImgUrl($list['data'],'thumb');   
    	return json($list);
    }
    public function myCourseClassify() {
        $map['a.user_id'] = $this->userInfo['id'];
 
        $list = Db::table(config('database.prefix') . 'users_course')->alias('a')
                    ->join(config('database.prefix') . 'course at', 'a.course_id = at.course_id', 'left')
                    ->join(config('database.prefix') . 'cate a1', 'at.level_id = a1.id', 'left')
                    ->join(config('database.prefix') . 'cate a2', 'at.subject_id = a2.id', 'left')
                    ->field('a.last_video_id,a.study_time,a.study_time,at.*,a1.name as level_name,a2.name as subject_name')
                    ->where($map)
                    ->order('a1.sort asc,a2.sort asc')
                    ->select();
        $classify=array(); 
        foreach ($list as $k=>$v){
            $thisClassify['level_id'] = $v['level_id'];
            $thisClassify['subject_id'] = $v['subject_id'];
            $thisClassify['level_name'] = $v['level_name'];
            $thisClassify['subject_name'] = $v['subject_name'];
            if (!in_array($thisClassify, $classify)) {
               array_push($classify,$thisClassify);
            }            
        }  
        return json($classify);
    }

    public function useCode() {
        
         

        $data['code'] = input('code');
        $code = db('activation_code')->where($data)->find();
     
        if(!$code) {
            $result = ['code'=>0,'msg'=>'激活码不存在!'];
        }
        elseif($code['status']!=1) {
            $result = ['code'=>0,'msg'=>'激活码已被禁用!'];

        }
        elseif($code['level_id']!=input('level_id')) {
            $map1['id'] = input('level_id');
            $level_name =  db('cate')->where($map1)->find()['name'];
            $result = ['code'=>0,'msg'=>'激活码只能激活'.$level_name.'课程!'];
        }
        else {
            $map2['code_id'] = $code['id'];
            $map2['user_id'] = $this->userInfo['id'];
            $ishas =  db('activation_code_user')->where($map2)->find();
            if($ishas) {
                $result = ['code'=>0,'msg'=>'该课程已激活!'];
            }
            else{
                $data['code_id'] = $code['id'];
                $data['updatetime'] = time();
                $data['user_id'] = $this->userInfo['id'];
                //$data['status'] = 2;
                //$upadte = db('activation_code')->update($data);
                $upadte = db('activation_code_user')->insert($data);
                $result = ['code'=>1,'msg'=>'激活成功!']; 
            }
        }
        if($result['code']==1) {
            $map['level_id'] = input('level_id');
            // $subject_ids = explode(",", input('subject_ids'));
            // if(count($subject_ids)>1) {
            //         $map['subject_id'] = array();
            //         for ($i=0; $i <count($subject_ids) ; $i++) { 
            //         array_push($map['subject_id'],array('eq',$subject_ids[$i]));
            //         }
            //         array_push($map['subject_id'],'or');

            // }
            // else {
            //     if(count($subject_ids)) {
            //        $map['subject_id'] = array('eq',$subject_ids[0]); 
            //     } 
            // }
            if(input('subject_ids')) {
                $map['subject_id'] = array('in',input('subject_ids'));
            }

            $type_ids =  db('config')->where(array('name'=>'active_type'))->find()['value'];
            if($type_ids) {
                $map['type_id'] = array('in',$type_ids);
            }
            // $type_ids = explode(",", input('type_ids'));
            // if(count($type_ids)>1) {
            //         $map['type_id'] = array();
            //         for ($i=0; $i <count($type_ids) ; $i++) { 
            //         array_push($map['type_id'],array('eq',$type_ids[$i]));
            //         }
            //         array_push($map['type_id'],'or');

            // }
            // else {
            //     if(count($type_ids)) {
            //        $map['type_id'] = array('eq',$type_ids[0]); 
            //     }   
            // }

            $course=db('course')->field('course_id')->where($map)->select();
            foreach ($course as $k=>$v){
                $this->addMyCourse($v['course_id']);
            }

        }
        return json($result);
    }


   


}