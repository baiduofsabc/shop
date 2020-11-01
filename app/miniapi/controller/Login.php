<?php
namespace app\miniapi\controller;
use think\Db;
use think\Request;
use clt\Form;
use think\Cache;
class Login extends Common{
    public function _initialize(){
        parent::_initialize();
    }
    public function index(){
        if(request()->isPost()) {
            
            $table = db('users');
            $postData = input('post.');
            $data = $this->object_array(json_decode($postData['rawData']));
            
            $openidAndToken = $this->getOpenIdAndToken($postData['code']);
            $data['openid'] = $openidAndToken['openid'];
            $data['token'] = $openidAndToken['token'];
           
            $user = db('users')->where("openid",$data['openid'])->find();
            $data['last_login']=time();
            if($user) {

                $update = db('users')->where("id",$user['id'])->update($data);
            }
            else {
                $data['reg_time']=time();
                $data['notice_time']=time();
                $update = db('users')->insert($data);
            }
            if($update) {
                $res = array('code'=>1,'message'=>'登录成功','token'=>$data['token'],'userInfo'=>$data);  
            }
            else {
                $res = array('code'=>0,'message'=>'登录失败','errorMsg'=>$openidAndToken);  
            }
            return json($res);   
     
        }

    }
    public function checkLogin(){
        if(request()->isPost()) {
            $map['token'] = $this->get_getallheaders()['Token'];  
            $userInfo = db('users')->where($map)->find();
            if($userInfo) {
                $res = array('code'=>1,'message'=>'登录成功','userInfo'=>$userInfo,'pass'=>0);  
            }
            else {
                $res = array('code'=>0,'message'=>'失败','pass'=>0);  
            }
            return json($res);
        }
      
    }

    public function checkCode($codeSn,$code){
        $a=Cache::get($codeSn);
        if($a==$code) {
            return true;
        }
        else {
            return false;
        }
    }
    public function reg(){
        if(request()->isPost()) {
            $data = input();
            // if(is_email($data['username'])){
            //     $is_validated = 1;
            //     $map['email_validated'] = 1;
            //     $map['email'] = $data['username']; //邮箱注册
            // }

             
            if(is_mobile_phone($data['username'])){
                $is_validated = 1;
                $map['mobile_validated'] = 1;
                $map['mobile'] = $data['username']; //手机注册
            }

            if($is_validated != 1){
                return json(['code'=>0,'message'=>'请用手机号注册']);
            }
            if(!$data['username'] || !$data['password']){
                return json(['code'=>-1,'message'=>'请输入昵称或密码']);
            }
            if(!$this->checkCode($data['codeSn'],$data['code'])) {
                return json(['code'=>-1,'message'=>'验证码不正确']);
            }
            //验证两次密码是否匹配
            // if($data['password'] != $data['password2']){
            //     return json(['code'=>-1,'message'=>'两次输入密码不一致']);
            // }
            //验证是否存在用户名
            if(get_user_info($map['email'],1)||get_user_info($map['mobile'],2)){
                return json(['code'=>-1,'message'=>'账号已存在']);
            }
            // $openidAndToken = $this->getOpenIdAndToken($data['code']);
            $map['username'] = $data['username'];
            $map['password'] = md5($data['password']);
            $map['reg_time'] = time();
            // $map['openid'] = $openidAndToken['openid'];
            // $map['token'] = $openidAndToken['token'];
            // $map['nickName'] =  input('nickName');
            // $map['avatar'] =  input('avatarUrl');
            // $map['country'] =  input('country');
            // $map['province'] =  input('province');
            // $map['city'] =  input('city');
            // $map['sex'] =  input('gender');
            // if($map['openid']) {
                $id = db('users')->insertGetId($map);
                if($id === false){
      
                    return json(['code'=>-1,'message'=>'注册失败']);
                }
                // $user = db('users')->field('id,username')->where("id", $id)->find();
                // session('user',$user);
                // 
                return json(['code'=>1,'message'=>'注册成功']); 
            // }
            // else {
            //     return json(['code'=>-1,'message'=>'注册失败','errorMsg'=>$openidAndToken]);
            // }
 
            
        } 
    }
    public function forget(){
        if(request()->isPost()) {
            $username = input('username');
            $password = input('password');
            $codeSn =input('codeSn');
            $code =input('code');
            
            $isFind = db('users')->where(['username'=>$username])->find();
            if(!$isFind) {
                return json(['code'=>-1,'message'=>'账号暂未注册']);
            }
            
            if($codeSn=='' || $code=='') {
                return json(['code'=>-1,'message'=>'请先取验证码']);
            }
            if(!$this->checkCode($codeSn,$code)) {
                return json(['code'=>-1,'message'=>'验证码不正确']);
            } 
            if(!$password){
                return json(['code'=>-1,'message'=>'请输入密码']);
            }
            else{
                db('users')->where(['username'=>$username])->update(['password'=>md5($password)]);
                return json(['code'=>1,'msg'=>'密码找回成功！']);
            }
        } 
    }

    public function getOpenIdAndToken($code) {
        $url = "https://api.weixin.qq.com/sns/jscode2session";        
        // 参数
        $params['appid']= $this->wechat['appid'];        
        $params['secret']= $this->wechat['secret'];        
        $params['js_code']= $code;
        $params['grant_type']= 'authorization_code';        
        // $user_phone= $request -> param('user_phone');        
        // 微信API返回的session_key 和 openid
        $arr = $this->httpCurl($url, $params, 'POST');
        $data = json_decode($arr,true);   
        $data['token'] =  md5($data['openid'].$data['session_key']);
        return $data;

        //$map['token2'] = $this->get_getallheaders()['Token'];
        // $map['nickName'] =  input('nickName');
        // $map['avatar'] =  input('avatar');
        // $map['province'] =  input('province');
        // $map['city'] =  input('city');
        // $map['sex'] =  input('sex');
        // $updata = db('users')->where('openid', $map['openid'])->update($map);
        // if(!$updata)  {
        //     return json(['code'=>0,'message'=>'请先绑定账号','data'=>$map]);
        //     //db('users')->insert($map);
        // }
        // return json(['code'=>1,'message'=>'登录成功','data'=>$map]);
    }

    public function wxLogin(Request $request) {
        $url = "https://api.weixin.qq.com/sns/jscode2session";        
        // 参数
        $params['appid']= 'wx4ee87837a6e095e0';        
        $params['secret']= '65409c01696cfa187152b703a5ad7237';         
        $params['js_code']= $request -> param('code');
        $params['grant_type']= 'authorization_code';        
        $user_phone= $request -> param('user_phone');        
        // 微信API返回的session_key 和 openid
        $arr = $this->httpCurl($url, $params, 'POST');
        $map = json_decode($arr,true);   
        $map['token'] =  md5($map['openid'].$map['session_key']);
        //$map['token2'] = $this->get_getallheaders()['Token'];
        $map['nickName'] =  input('nickName');
        $map['avatar'] =  input('avatar');
        $map['province'] =  input('province');
        $map['city'] =  input('city');
        $map['sex'] =  input('sex');
        $updata = db('users')->where('openid', $map['openid'])->update($map);
        if(!$updata)  {
            return json(['code'=>0,'message'=>'请先绑定账号','data'=>$map]);
            //db('users')->insert($map);
        }
        return json(['code'=>1,'message'=>'登录成功','data'=>$map]);
    }
    public function login(Request $request){

        // 获取到前台传输的手机号
        $user_phone = $request -> param('user_phone');        
        // 判断数据库中该手机号是否存在
        $is_user_phone = Db::table('user_info')->where('user_phone',$user_phone)->find();        
        if(isset($is_user_phone) && !empty($is_user_phone)){            
        // 登录时，数据库中存在该手机号，则更新openid_time
            $update = Db::table('user_info')
                    ->where('user_phone', $user_phone)
                    ->update([                        
                    'openid_time' => time(),
                    ]);            
                    if($update){                
                    return json(['sendsure'=>'1','message'=>'登录成功',]);
            }
        }else{            
        $data = [                
        "user_phone" => $user_phone,                
        "pass" => '12345'
            ];            
            // 如果数据库中不存在该手机号，则进行添加
            Db::table('user_info')->insert($data);
        }        return json(['sendsure'=>'1','message'=>'登录成功',]);
    }

    

    function httpCurl($url, $params, $method = 'GET', $header = array(), $multi = false){
        date_default_timezone_set('PRC');        
        $opts = array(
            CURLOPT_TIMEOUT        => 30,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_HTTPHEADER     => $header,
            CURLOPT_COOKIESESSION  => true,
            //CURLOPT_FOLLOWLOCATION => 1,
            CURLOPT_COOKIE  =>session_name().'='.session_id(),
        );   
        if (ini_get('open_basedir') == '' && ini_get('safe_mode' == 'Off')) {
                curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
        }     
        /* 根据请求类型设置特定参数 */
        switch(strtoupper($method)){            
        case 'GET':                
        // $opts[CURLOPT_URL] = $url . '?' . http_build_query($params);
                // 链接后拼接参数  &  非？
                $opts[CURLOPT_URL] = $url . '?' . http_build_query($params);                
                break;           
        case 'POST':                //判断是否传输文件
                $params = $multi ? $params : http_build_query($params);                
                $opts[CURLOPT_URL] = $url;                
                $opts[CURLOPT_POST] = 1;                
                $opts[CURLOPT_POSTFIELDS] = $params;                
                break;            
        default:                
                throw new Exception('不支持的请求方式！');
        }        
        /* 初始化并执行curl请求 */
        $ch = curl_init();
        curl_setopt_array($ch, $opts);        
        $data  = curl_exec($ch);        
        $error = curl_error($ch);
        curl_close($ch);        
        if($error) throw new Exception('请求发生错误：' . $error);        
        return  $data;
    }
    






}