<?php
namespace app\admin\controller;
use think\Db;
use think\Request;
use clt\Leftnav;
use app\admin\model\System as SysModel;
class System extends Common
{
    /********************************站点管理*******************************/
    //站点设置
    public function system($sys_id=1){
        $table = db('system');
        if(request()->isPost()) {
            $datas = input('post.');
            if($table->where('id',1)->update($datas)!==false) {
                savecache('System');
                return json(['code' => 1, 'msg' => '站点设置保存成功!', 'url' => url('system/system')]);
            } else {
                return json(array('code' => 0, 'msg' =>'站点设置保存失败！'));
            }
        }else{
            $system = $table->find($sys_id);
            $this->assign('system', $system);
            return $this->fetch();
        }
    }
 
    public function bugao(){
        if (request()->isGet()) {
            $bugao = db('bugao')->where(array('id'=>1))->find();
            $this->assign('bugao', $bugao);
            return $this->fetch();
        }else if (request()->isPost()) {
            $data['text'] = input('bugao');
            db('bugao')->where(array('id'=>1))->update($data);
            $result = ['code'=>1,'msg'=>'提交成功','url' => url('system/bugao')];
            return $result;   
        }
    }

    public function banner(){
        if(request()->isPost()) {
        $banner =  db('config')->where(array('name'=>'banner'))->find();
        // print_r($banner);exit;
            return json($banner['value']);
        }else{
            return $this->fetch();
        }
    }
   
    public function bannerAdd(){
      if(request()->isPost()) {
         $data['value'] = input('value');
         db('config')->where(array('name'=>'banner'))->update($data);
         $result = ['code'=>1,'msg'=>'提交成功'];
         return json_encode($result,true);   
        } 
    }

    public function ad(){
        if(request()->isPost()) {
        $banner =  db('config')->where(array('name'=>'banner'))->find();
            return json($banner['value']);
        }else{
            return $this->fetch();
        }
    }
   
    public function adAdd(){
      if(request()->isPost()) {
         $data['value'] = input('value');
         db('config')->where(array('name'=>'banner'))->update($data);
         $result = ['code'=>1,'msg'=>'提交成功'];
         return json_encode($result,true);   
        } 
    }

    public function email(){
        $table = db('config');
        if(request()->isPost()) {
            $datas = input('post.');
            foreach ($datas as $k=>$v){
                $table->where(['name'=>$k,'inc_type'=>'smtp'])->update(['value'=>$v]);
            }
            return json(['code' => 1, 'msg' => '邮箱设置成功!', 'url' => url('system/email')]);
        }else{
            $smtp = $table->where(['inc_type'=>'smtp'])->select();
            $info = convert_arr_kv($smtp,'name','value');
            $this->assign('info', $info);
            return $this->fetch();
        }
    }
    public function trySend(){
        $sender = input('email');
        //检查是否邮箱格式
        if (!is_email($sender)) {
            return json(['code' => -1, 'msg' => '测试邮箱码格式有误']);
        }
        $send = send_email($sender, '测试邮件', '您好！这是一封来自'.$this->system['name'].'的测试邮件！');
        if ($send) {
            return json(['code' => 1, 'msg' => '邮件发送成功！']);
        } else {
            return json(['code' => -1, 'msg' => '邮件发送失败！']);
        }
    }

}
