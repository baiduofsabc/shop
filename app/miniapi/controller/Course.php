<?php
namespace app\miniapi\controller;
use think\Db;
include_once './extend/aliyun-php-sdk-core/Config.php';
use vod\Request\V20170321 as vod;
use DefaultAcsClient;
use DefaultProfile;
class Course extends Common{
    public function _initialize(){
        parent::_initialize();
        $this->checkToken(); 
        $map['token'] = $this->token;
        $this->userInfo=db('users')
        ->field('id,username,email,sex,birthday,mobile,openid,unionid,avatar,province,city,district,level,is_lock,token')
        ->where($map)
        ->find(); 
        $this->system =  db('system')->field('is_mini_check')->where(array('id'=>1))->find();
        
    }
    public function index(){
     
    }
    public function listInfo(){
    	$map=array();
        
         if(input('name')) {
                $key = input('name');
                $keyArr = explode(" ",$key);
                if(count($keyArr)>1) {
                    $map['a.name'] = array();
                    for ($i=0; $i <count($keyArr) ; $i++) { 
                    array_push($map['a.name'],array('like','%'.$keyArr[$i].'%'));
                    }
                    array_push($map['a.name'],'or');

                }
                else {
                    $map['a.name'] = array('like','%'.$key.'%');
                }
        }
    	if(input('level_id')) {
    	  $map['a.level_id'] =input('level_id');	
    	}
    	if(input('subject_id')) {
    	  $map['a.subject_id'] =input('subject_id');	
    	}
    	if(input('level_id')) {
    	  $map['a.type_id'] =input('type_id');	
    	}
        if(input('is_svip')) {
          $map['a.is_svip'] =input('is_svip');  
        }
        $map['a.status'] =1;
    	$page =input('page')?input('page'):1;
        $pageSize =input('limit')?input('limit'):config('pageSize');
        $list = Db::table(config('database.prefix') . 'course')->alias('a')
                ->join(config('database.prefix') . 'cate a1', 'a.level_id = a1.id', 'left')
                ->join(config('database.prefix') . 'cate a2', 'a.subject_id = a2.id', 'left')
                ->join(config('database.prefix') . 'cate a3', 'a.type_id = a3.id', 'left')
                ->field('a.*,a.name,a1.name as level_name,a2.name as subject_name,a3.name as type_name,a3.is_free')
                ->where($map)
                ->order('a.course_id desc')
                ->paginate(array('list_rows'=>$pageSize,'page'=>$page))
                ->toArray();

    	foreach ($list['data'] as $k=>$v){
             $list['data'][$k]['addtime'] = date('Y-m-d H:i:s',$v['addtime']);
             $map2['course_id'] = $v['course_id'];
             $map2['user_id'] = $this->userInfo['id'];
             $isBuy =  db('users_course')->where($map2)->find();
            if($isBuy) {
               $list['data'][$k]['is_buy'] = 1;
               $list['data'][$k]['myCourseInfo'] = $isBuy;
            }
            else {
               $list['data'][$k]['is_buy'] = 2; 
               $list['data'][$k]['myCourseInfo'] = [];
            }


        }
        $list['data'] = $this->addImgUrl($list['data'],'thumb');
        return json($list);
    }
    public function info(){
    	$map['a.course_id'] =input('course_id');
    	$course = Db::table(config('database.prefix') . 'course')->alias('a')
                ->join(config('database.prefix') . 'cate a1', 'a.level_id = a1.id', 'left')
                ->join(config('database.prefix') . 'cate a2', 'a.subject_id = a2.id', 'left')
                ->join(config('database.prefix') . 'cate a3', 'a.type_id = a3.id', 'left')
                ->field('a.course_id,a.name,a1.name as level_name,a2.name as subject_name,a3.name as type_name,a3.is_free,a.thumb,a.price,a.section,a.description as content,a.sell_num_jia,a.sell_num,a.images')
                ->where($map)
                ->find();
        $map3['cate_type'] = 'year';
        $arr1=array();
        $arr2=array();
        $year =  db('cate')->where($map3)->select();
        foreach ($year as $k=>$v){
             $arr1[] = '"year_id":'.$v['id'];
             $arr2[] = '"year_id":'.$v['name'];
        }
        $course['section'] = str_replace($arr1,$arr2,$course['section']);        
        if($course) {
            $course['is_mini_check'] = $this->system['is_mini_check'];
            $course['thumb'] = $this->imgUrl.$course['thumb'];
            $course['section'] = json_decode($course['section']);
            $course['images'] = json_decode($course['images']);
            $map2['course_id'] = $course['course_id'];
            $map2['user_id'] = $this->userInfo['id'];
            $isBuy =  db('users_course')->where($map2)->find();
            if($isBuy) {
               $course['is_buy'] = 1;
               $course['myCourseInfo'] = $isBuy;
            }
            else {
               $course['is_buy'] = 2; 
               $course['myCourseInfo'] = [];
            }
            if($course['price'] == 0){
                $course['is_free'] = 1;
            }
            return json($course); 
        }   
        else {
            return json([]); 
        }     
        
    }
    public function svipListInfo(){
    	$map=array();
        $map['a.status'] =1;
    	$page =input('page')?input('page'):1;
        $pageSize =input('limit')?input('limit'):config('pageSize');
        $list = Db::table(config('database.prefix') . 'course_svip')->alias('a')
                ->field('a.*')
                ->where($map)
                ->order('a.sort asc')
                ->paginate(array('list_rows'=>$pageSize,'page'=>$page))
                ->toArray();
    	foreach ($list['data'] as $k=>$v){
             $list['data'][$k]['addtime'] = date('Y-m-d H:i:s',$v['addtime']);
        }
        return json($list);
    }
    public function svipinfo(){
    	$map['a.course_id'] =input('course_id');
    	$course = Db::table(config('database.prefix') . 'course_svip')->alias('a')
                ->field('a.*')
                ->where($map)
                ->find();
        $course['images'] = json_decode($course['images']);
        $course['section'] = json_decode($course['section']);
        $course['addtime'] = date('Y-m-d H:i:s',$course['addtime']);
        $course['is_mini_check'] = $this->system['is_mini_check'];
        return json($course);
    }


    public function init_vod_client($accessKeyId, $accessKeySecret) {
          $regionId = 'cn-shanghai';  // 点播服务所在的Region，国内请填cn-shanghai，不要填写别的区域
          $profile = DefaultProfile::getProfile($regionId, $accessKeyId, $accessKeySecret);
          return new DefaultAcsClient($profile);
    }
    public function get_play_info($client, $videoId) {
      $request = new vod\GetPlayInfoRequest();
      $request->setVideoId($videoId);
      $request->setAcceptFormat('JSON');
      return $client->getAcsResponse($request);
    }

    public function get_play_auth($client, $videoId) {
        $request = new vod\GetVideoPlayAuthRequest();
        $request->setVideoId($videoId);
        //$request->setAuthInfoTimeout(3600);  // 播放凭证过期时间，默认为100秒，取值范围100~3600；注意：播放凭证用来传给播放器自动换取播放地址，凭证过期时间不是播放地址的过期时间
        //$request->setAcceptFormat('JSON');
        $response = $client->getAcsResponse($request);
        return $response;
    }
    public function getPlayAuth() {
       $videoId =input('vid');
       $is_url =input('is_url');

       // try {
            $client = $this->init_vod_client('LTAIxCy6kPH8ZxgU', 'JXFU9fLeJZay4yzq19XS2XtETevKM7');
            if($is_url==1) {
               $playAuth = $this->get_play_info($client, $videoId); 
            }
            else {
                $playAuth = $this->get_play_auth($client, $videoId); 
            }
            $playAuthArr = $this->object_array($playAuth);
          
            for ($i=0; $i < count($playAuthArr['PlayInfoList']['PlayInfo']); $i++) { 
                $playAuthArr['PlayInfoList']['PlayInfo'][$i]['PlayURL'] = str_replace("outin-42277fa1f18111e8ad9400163e1a625e.oss-cn-shanghai.aliyuncs.com","vodapi.wuniuwang.cn",$playAuthArr['PlayInfoList']['PlayInfo'][$i]['PlayURL']);
            }
            return json($playAuthArr);
        // } catch (Exception $e) {
        //     return $e->getMessage();
        // }
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
    public function addMyMyCourse($course_id) {
        $map['course_id'] = $course_id;
        $map['user_id'] =  $this->userInfo['id'];
        $my=db('users_course')->where($map)->find();
        if(!$my) {
             db('users_course')->insert($map);  
        } 
    }
    public function addMyCourse() {
        $map['course_id'] =input('course_id');
        $map['user_id'] =  $this->userInfo['id'];
        $my=db('users_course')->where($map)->find();
        if(!$my) {
            $map['addtime'] = time();
            $map['validity'] = strtotime("+1 year");
            if(db('users_course')->insert($map)) {
              return json(['code'=>1,'message'=>'添加成功']); 
            }
            else {
              return json(['code'=>0,'message'=>'添加失败']); 
            }
        }
        else {
            return json(['code'=>0,'message'=>'课程已存在']);
        }
    }
    public function updateMyCourse() {
        $map['course_id'] =input('course_id');
        $map['user_id'] =  $this->userInfo['id'];
        $data['updatetime'] = time();
        $data['study_time'] = input('study_time');
        $data['last_video_id'] = input('last_video_id');

        $updata = db('users_course')->where($map)->update($data);
        if(!$updata) {
            return json(['code'=>1,'message'=>'更新失败']); 
        }
        else {
            return json(['code'=>0,'message'=>'更新成功']);
        }
    }
    


}