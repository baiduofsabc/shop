<?php
namespace app\miniapi\controller;
use think\Db;
use sms\SignatureHelper;
use think\Cache;
class Sms extends Common{
    public function _initialize(){
        parent::_initialize(); 
    }
    function sendSms() {
            $yCode = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J');
            $codeSn = $yCode[intval(date('Y')) - 2011] . strtoupper(dechex(date('m'))) . date('d') . substr(time(), -5) . substr(microtime(), 2, 5) . sprintf('%02d', rand(0, 99));
            $xcode = $this->getCode();
            Cache::set($codeSn,$xcode,1800);
            $params = array ();
            // *** 需用户填写部分 ***
            // fixme 必填：是否启用https
            $security = false;
            // fixme 必填: 请参阅 https://ak-console.aliyun.com/ 取得您的AK信息
            $accessKeyId = "LTAIxCy6kPH8ZxgU";
            $accessKeySecret = "JXFU9fLeJZay4yzq19XS2XtETevKM7";
            // fixme 必填: 短信接收号码
            $params["PhoneNumbers"] = input('tel');
            // fixme 必填: 短信签名，应严格按"签名名称"填写，请参考: https://dysms.console.aliyun.com/dysms.htm#/develop/sign
            $params["SignName"] = "五牛网校";
            // fixme 必填: 短信模板Code，应严格按"模板CODE"填写, 请参考: https://dysms.console.aliyun.com/dysms.htm#/develop/template
            $params["TemplateCode"] = "SMS_150766382";
            // fixme 可选: 设置模板参数, 假如模板中存在变量需要替换则为必填项
            $params['TemplateParam'] = Array (
                "code" => $xcode,
            );
            // fixme 可选: 设置发送短信流水号
            //$params['OutId'] = "12345";
            // fixme 可选: 上行短信扩展码, 扩展码字段控制在7位或以下，无特殊需求用户请忽略此字段
            //$params['SmsUpExtendCode'] = "1234567";
            // *** 需用户填写部分结束, 以下代码若无必要无需更改 ***
            if(!empty($params["TemplateParam"]) && is_array($params["TemplateParam"])) {
                $params["TemplateParam"] = json_encode($params["TemplateParam"], JSON_UNESCAPED_UNICODE);
            }
            // 初始化SignatureHelper实例用于设置参数，签名以及发送请求
            $helper = new SignatureHelper();

            // 此处可能会抛出异常，注意catch
            $content = $helper->request(
                $accessKeyId,
                $accessKeySecret,
                "dysmsapi.aliyuncs.com",
                array_merge($params, array(
                    "RegionId" => "cn-hangzhou",
                    "Action" => "SendSms",
                    "Version" => "2017-05-25",
                )),
                $security
            );
            if(empty($content) || $content->Message!='OK') {
                $message = $content->Message?$content->Message:'短信发送失败，请稍后重试';
                return  json(['code'=>0,'message'=>$message]);
            }else{
                return  json(['code'=>0,'message'=>'短信验证码已发送您手机，请注意查收','codeSn'=>$codeSn]);
            }
          
    }
    public function index(){
        
        
        
     
    }
    public function getCode() {

        $arr=array_merge(range('0','9'));
        shuffle($arr);
        $arr=array_flip($arr);
        $arr=array_rand($arr,4);
        $res='';
        foreach ($arr as $v){
            $res.=$v;
        }
        return $res;
 
    }
    
     
}