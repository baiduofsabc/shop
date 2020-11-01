<?php
namespace app\miniapi\controller;
use think\Db;
class Pictures extends Common{
    public function _initialize(){
        parent::_initialize();
        $this->checkToken();       
    }
    public function index(){
     
    }
    public function getAlbum() {
    	$map['a.cate_type'] = 'photo';
    	$list = Db::table(config('database.prefix') . 'cate')->alias('a')
                ->join(config('database.prefix') . 'cate a1', 'a.childid = a1.id', 'left')
                ->field('a.id,a.name,a1.name as year')
                ->where($map)
                ->order('a1.sort asc')
                ->select();
        foreach ($list as $k=>$v){
             $map2['catid'] = $v['id'];
             $list2 =  db('pictures')->where($map2)->limit(6)->select();
             $list2 = $this->addImgUrl($list2,'pic_url');
             if(count($list2)>0) {
               $list[$k]['list'] = $list2; 
             }
             
        }         
        return json($list);        
    }
     
    public function listInfo(){
    	$map=array();
    	if(input('catid')) {
    	   $map['a.catid'] =input('catid');	
    	}
    	$page =input('page')?input('page'):1;
        $pageSize =input('limit')?input('limit'):25;
        $list = Db::table(config('database.prefix') . 'pictures')->alias('a')
                ->field('a.id,a.name,a.pic_url')
                ->where($map)
                ->order('a.id desc')
                ->paginate(array('list_rows'=>$pageSize,'page'=>$page))
                ->toArray();
        $list['data'] = $this->addImgUrl($list['data'],'pic_url');

        return json($list);
    }
    public function info(){
    	$map['a.id'] =input('id');
    	$pictures = Db::table(config('database.prefix') . 'pictures')->alias('a')
                ->join(config('database.prefix') . 'cate a1', 'a.catid = a1.id', 'left')
                ->field('a.id,a.name,a1.name as album_name,a.pic_url')
                ->where($map)
                ->find();
 
        $pictures['pic_url'] = $this->imgUrl.$pictures['pic_url'];
        return json($pictures);
    }
    public function svipListInfo(){
    	$map=array();
    	$page =input('page')?input('page'):1;
        $pageSize =input('limit')?input('limit'):config('pageSize');
        $list = Db::table(config('database.prefix') . 'course_svip')->alias('a')
                ->field('a.*')
                ->where($map)
                ->order('a.course_id desc')
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
    
        $course['section'] = json_decode($course['section']);
    
        return json($course);
    }


}