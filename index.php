<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK 
// +----------------------------------------------------------------------
// [ 应用入口文件 ]
if (!defined('__PUBLIC__')) {
    $_public = rtrim(dirname(rtrim($_SERVER['SCRIPT_NAME'], '/')), '/');
    define('__PUBLIC__', (('/' == $_public || '\\' == $_public) ? '' : $_public).'/public');
}
// 定义应用目录
define('APP_PATH', __DIR__ . '/app/');
define('DATA_PATH',  __DIR__.'/runtime/Data/');
//插件目录
define('PLUGIN_PATH', __DIR__ . '/plugins/');
define('BIND_MODULE','home');//绑定前台模块
// 加载框架引导文件
require __DIR__ . '/think/start.php';
