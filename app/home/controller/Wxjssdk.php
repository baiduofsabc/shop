<?php
//微信基础配置
namespace app\home\controller;
use think\Controller;
use think\Db;
use app\home\controller\Wxjssdk;
class Wxjssdk extends Controller {
       private  $appid;//私有的自己用
       private  $appsecret;
       private  $token;

      //实例化触发构造函数
      public function __construct(){
         $this->appid = 'wx4b4f4e0b100c026d';
         $this->appsecret = 'aa22b9ac6413b6f977e9c21fcc1bdc60';
         $this->token ='baiduo';
      }

      //封装request curl方法
      public function request($url,$https=true,$method='get',$data=null){
          //1.初始化url
          $ch = curl_init($url);
          //2.设置请求参数
          //把数据以文件流形式保存，而不是直接输出
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
          //支持http和https协议
          //https协议  ssl证书
          //绕过证书验证
          if($https === true){
              curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
              curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
          }
          //支持post请求
          if($method === 'post'){
              curl_setopt($ch, CURLOPT_POST, true);
              //发送的post数据
              curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

          }
          //3.发送请求
          $content = curl_exec($ch);
          //4.关闭请求
          curl_close($ch);
          return $content;
      }
        
      //封装请求方法
      public function newrequest($url,$https=true,$method='get',$data=null){
          //协议区分 http https(ssl加密)
          //请求方式区分  get(url传值)  post(对应传输数据)
          //使用curl封装
          //1.curl初识化
          $ch = curl_init($url);
          //2.设置相关选项参数
          //接收到的数据以文件流的形式保存,不进行输出
          //设置基本上都是开关状态  true和false
          curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
          //支持https请求
          if($https === true){
              //绕过ssl验证
              curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);
              curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,false);
          }
          //支持post请求
          if($method === 'post'){
              //开启post请求
              curl_setopt($ch,CURLOPT_POST,true);
              //发送的post数据
              curl_setopt($ch,CURLOPT_POSTFIELDS,$data);
          }
          //3.发送请求
          $content = curl_exec($ch);
          //4.关闭请求链接
          curl_close($ch);
          //返回给调用者
          return $content;
      }
    
      //获取access_token
      public function getAccessToken(){
        //1.url地址
        $url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.$this->appid.'&secret='.$this->appsecret;
        //2.请求方式
        //3.发送请求
        $content = $this->request($url);
        //4.处理返回值
          //dump($content);exit;
        //返回值为json
        $content = json_decode($content);
        //dump($content);
        	//输出access_token
        return  $content->access_token;
      }

       //用户微信登录
       public function getcode()
       {
           //1获取code
            $redirect_uri =urlencode("https://ad.yz360.net/index.php/Home/wei/getuseropenid");
            $url = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid='.$this->appid.'&redirect_uri='.$redirect_uri.'&response_type=code&scope=snsapi_userinfo&state=123#wechat_redirect';
            header('location:'.$url);
       }
       public function getuseropenid(){
             //2获取网页授权自己的access_token
                $code = $_GET['code'];
                $url ='https://api.weixin.qq.com/sns/oauth2/access_token?appid='.$this->appid.'&secret='.$this->appsecret.'&code='.$code.'&grant_type=authorization_code ';
                     $access_token1=$this->request($url);
                     //dump($code );
                     $access_token1 = json_decode($access_token1);//转化为对象
                     //dump( $access_token1);exit;
                    
                     $access_token11=  $access_token1->access_token;
                     $openid = $access_token1->openid;
                     session('openid',$openid);
                     //3拉取用户openid详细信息
                     $url = 'https://api.weixin.qq.com/sns/userinfo?access_token='.$access_token11.'&openid='.$openid.'&lang=zh_CN';

                     $xinxi=$this->request($url);
                     //转数据为对象格式
                     $xinxi = json_decode($xinxi);

                     //dump($xinxi);exit;
                      $openid1 = $xinxi->openid;     //用户的唯一标识
                      $nickname = $xinxi->nickname;   //用户昵称
                      $headimgurl= $xinxi->headimgurl;  //用户头像，
                      $sex= $xinxi->sex;//用户性别
                          //var_dump($xinxi);exit;
                          
                          
                    //查询数据此用户是否存下
                      $open = db('users')->where(array('openid'=>$openid1,'is_teacher'=>0))->find();
                             	 
                         
                        if($open){
                                //存在的,ID
                              $uss=M('users')->where(array('openid'=>$openid1,'is_teacher'=>0))->find();
                              $is_teacher=M('users')->where(array('openid'=>$openid1,'is_teacher'=>0))->getField("is_teacher");
                               
                             if($is_teacher==0){
                             	 
                              session('user',$uss);
                              header('location:https://ad.yz360.net/index.php/Home/User/index');
                             
                             }
                             
                        }else{
                            $data['openid']=$openid1;
                        	$data['nickname']=$nickname;
                        	$data['name']=$nickname;
                        	$data['image']=$headimgurl;
                        	$data['sex']=$sex;
                        	$data['is_teacher']=0;
                        	$info=M('users')->add($data);
                        	if($info){
                        		$info1=M('users')->where(array('user_id'=>$info))->find();
                        		session('user',$info1);
                                header('location:https://ad.yz360.net/index.php/Home/User/index');
                        	}
                        	 //header("Content-type: text/html; charset=utf-8");
                           
                             //echo "<script>alert('请先用手机号登录,然后在个人中心进行微信绑定')</script>
                             
                                 //<script> window.location.href='https://ad.yz360.net/Home/Login/user_login'</script>";
                           

                        }

                    //dump($xinxi);exit;
             
       }
    
      //通过openID列表获取用户基本信息
      public function getUserInfo($openid){
        //1.url
        $url = 'https://api.weixin.qq.com/cgi-bin/user/info?access_token='.$this->getAccessToken().'&openid='.$openid.'&lang=zh_CN';
        //2.请求方式
        //3.发送请求
        $content = $this->request($url);
        //转数据为对象格式
        $content = json_decode($content);

        //dump($content);exit;
          //返回查询到的信息
          return  $content;


        // //4.处理返回值
        // switch ($content->sex) {
        //   case '1':
        //     $sex = '男';
        //     break;
        //   case '2':
        //     $sex = '女';
        //     break;
        //   default:
        //     $sex = '未知';
        //     break;
        // }
        // echo '昵称:'.$content->nickname.'<br />';
        // echo '性别:'.$sex.'<br />';
        // echo '省份:'.$content->province.'<br />';
        // echo '<img src="'.$content->headimgurl.'" />';
      } 

      
      public function getSecret()
      {
        $url=input('post.url');
        $appid=$this->appid;

        $jsapi_ticket=$this->getJsapiTicket();

        // dump($jsapi_ticket);

        $timestamp=time();
        $noncestr=$this->getRandCode();
        // return 2;
        // $url="http://baiduofs.wanzhong666.com/user-testImg";
        $signature="jsapi_ticket=".$jsapi_ticket."&noncestr=".$noncestr."&timestamp=".$timestamp."&url=".$url;
        $signature=sha1($signature);
        $array=array("appid"=>$appid,"jsapi_ticket"=>$jsapi_ticket,"timestamp"=>$timestamp,"noncestr"=>$noncestr,"signature"=>$signature);
        return json($array); 
      }
    
      //获取jsapi_ticket

       function getJsapiTicket1()
      {
          if(session('jsapi_ticket_expire_time')>time()&&session('jsapi_ticket')){

              $jsapi_ticket=session('jsapi_ticket');
              dump($jsapi_ticket);
          }else{
             $url="https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token=".$this->getAccessToken()."&type=jsapi";
              $content = $this->request($url);
              // dump($content);exit;
              //返回值为json
              $content = json_decode($content,true);
              dump($content);
              $ticket=$content['ticket'];
              session('jsapi_ticket',$ticket);
              session('jsapi_ticket_expire_time',time()+7000);
              // dump('222');
          }
          return $jsapi_ticket;
      }

      function getJsapiTicket()
      {
          if(session('jsapi_ticket_expire_time')>time()&&session('jsapi_ticket')){

              $jsapi_ticket=session('jsapi_ticket');
              // dump($jsapi_ticket);
          }else{
             $url="https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token=".$this->getAccessToken()."&type=jsapi";
              $content = $this->request($url);
              // dump($content);exit;
              //返回值为json
              $content = json_decode($content,true);
              //dump($content);
              $jsapi_ticket=$content['ticket'];
              session('jsapi_ticket',$jsapi_ticket);
              session('jsapi_ticket_expire_time',time()+7000);
              // dump('222');
          }
          return $jsapi_ticket;
      }

      function getRandCode($num='16')
      {
         $array=array(
            'A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z',
            'a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z',
            '0','1','2','3','4','5','6','7','8','9'
          );
         $tmpstr="";
         $max=count($array);
         for ($i=1;$i<=$num;$i++) { 
            $key=rand(0,$max-1);//A->$array['0']
            $tmpstr.=$array[$key];
         }
         return $tmpstr;
      }

      ////////////////////开始/////////////////验证服务器
      ///url地址
       public  function index(){
            //判断是验证还是消息处理的
            if($_GET["echostr"]){
              //调用验证方法
              $this->valid();
            }else{
              //调用消息管理方法
              $this->responseMsg();
            }
        }
        //验证方法
        public function valid()
        {
            $echoStr = $_GET["echostr"];
            //valid signature , option
            if($this->checkSignature()){
              echo $echoStr;
              exit;
            }
        }
        
       //校验签名的方法
        public function checkSignature(){
          // you must define TOKEN by yourself
          if (!defined("TOKEN")) {
              //throw new Exception('TOKEN is not defined!');
            }

                  $signature = $_GET["signature"];
                  $timestamp = $_GET["timestamp"];
                  $nonce = $_GET["nonce"];

              $token = $this->token;
              $tmpArr = array($token, $timestamp, $nonce);
                  // use SORT_STRING rule
              sort($tmpArr, SORT_STRING);
              $tmpStr = implode( $tmpArr );
              $tmpStr = sha1( $tmpStr );

              if( $tmpStr == $signature ){
                return true;
              }else{
                return false;
              }
        }
       ////////////////////结束/////////////////验证服务器


 

       // 获取图片地址
		public function getmedia($media_id){
		    $url="http://file.api.weixin.qq.com/cgi-bin/media/get?access_token=".$this->getAccessToken()."&media_id=".$media_id;
		    $path="./public/upload_baiduo/".date('Ymd');
		    if (!file_exists($path)) {
		      mkdir($path, 0777, true);
		    }
		    $targetName = $path.'/'.date('YmdHis').rand(1000,9999).'.jpg';
		    $ch = curl_init($url); // 初始化
		    $fp = fopen($targetName, 'wb'); // 打开写入
		    curl_setopt($ch, CURLOPT_FILE, $fp); // 设置输出文件的位置，值是一个资源类型
		    curl_setopt($ch, CURLOPT_HEADER, 0);
		    curl_exec($ch);
		    curl_close($ch);
		    fclose($fp);
		    return $targetName;
		}
      
    }