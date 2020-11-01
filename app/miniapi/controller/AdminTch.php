<?php
namespace app\miniapi\controller;
use think\Db;
use think\Request;
use clt\Form;
class AdminTch extends Common{
    public function _initialize(){
        parent::_initialize();
    }
    public function index(){
    	$page =input('page')?input('page'):1;
        $pageSize =input('limit')?input('limit'):config('pageSize');
        $list = Db::table(config('database.prefix') . 'course_tch')->alias('a')
                ->field('a.*') 
                ->order('a.sort')
                ->paginate(array('list_rows'=>$pageSize,'page'=>$page))
                ->toArray();
        foreach ($list['data'] as $k=>$v){
                $list['data'][$k]['addtime'] = date('Y-m-d H:i',$v['addtime']);
        }
        $result = ['code'=>0,'msg'=>'获取成功!','data'=>$list['data'],'count'=>$list['total'],'rel'=>1];
        return json_encode($result,true);
    }
}