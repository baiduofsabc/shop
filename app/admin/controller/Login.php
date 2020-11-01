<?php

namespace app\admin\controller;

use think\Controller;
use app\admin\model\Admin;
use think\Request;

class Login extends Controller
{
    public function _initialize()
    {
        if (session('aid')) {
            $this->redirect('index/index');
        }
    }

    private $cache_model, $system;

    public function index()
    {
        if (request()->isPost()) {
            $admin = new Admin();
            $data = input('post.');
            if (!$this->check($data['captcha'])) {
                return json(array('code' => 0, 'msg' => '验证码错误'));
            }
            $num = $admin->login($data);
            if ($num == 1) {
                $data2['admin_id'] = session('aid');
                $data2['ip'] = request()->ip();
                db("admin")->update($data2);
                return json(['code' => 1, 'msg' => '登录成功!', 'url' => url('index/index')]);
            } else if ($num == 2) {
                return json(array('code' => 0, 'msg' => '账号异常，请联系管理员!'));
            } else {
                return json(array('code' => 0, 'msg' => '用户名或者密码错误，重新输入!'));
            }
        } else {
            $this->cache_model = array('Module', 'Role', 'Category', 'Posid', 'Field', 'System');
            $this->system = F('System');
            if (empty($this->system)) {
                foreach ($this->cache_model as $r) {
                    savecache($r);
                }
            }
            return $this->fetch();
        }
    }

    public function check($code)
    {
        return captcha_check($code);
    }
}