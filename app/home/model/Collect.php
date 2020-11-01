<?php
namespace app\home\model;
use think\Model;
class Collect extends Model
{
	public function getList($map,$page=1,$limit){
		$data=db('users_collect')->where($map)->find();
		$page = input('page') ? input('page') : 1;
        $pageSize = input('limit') ? input('limit') : config('pageSize');
        $list = Db::table(config('database.prefix') . 'goods')->alias('a')
                ->field('a.*')
                ->where($map)
                ->order('a.id desc')
                ->paginate(array('list_rows' => $pageSize, 'page' => $page))
                ->toArray();
        foreach ($list['data'] as $k => $v) {
                $list['data'][$k]['addtime'] = date('Y-m-d H:i', $v['addtime']);
        }
        $result = ['code' => 1, 'msg' => '获取成功!', 'data' => $list['data'], 'count' => $list['total'], 'rel' =>db('goods')->getLastSql() ];
		return $result;
	}

}
