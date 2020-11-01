<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:50:"E:\asd1028\fszb1027/app/home\view\login/index.html";i:1603874470;s:50:"E:\asd1028\fszb1027\app\home\view\common\head.html";i:1603874470;s:52:"E:\asd1028\fszb1027\app\home\view\common\footer.html";i:1603874470;}*/ ?>
<!DOCTYPE html>
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js lt-ie9 lt-ie8" lang="zh-cn"> <![endif]-->
<!--[if IE 8]>    <html class="no-js lt-ie9" lang="zh-cn"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="zh-cn"> <!--<![endif]-->

<head>
    <meta charset="utf-8" />
   <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />

    <title><?php echo $seo_title; ?></title>
    
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <!-- 引入样式 -->
    <link rel="stylesheet" href="/statics/home/css/css.css">
<link rel="stylesheet" href="//at.alicdn.com/t/font_1259219_pj7nkqaq8cf.css">

<link rel="stylesheet" href="statics/vue/index.css">

<!-- 引入组件 -->
<script src="/statics/vue/axios.min.js"></script>
<script src="/statics/vue/vue.min.js"></script>
<script src="statics/vue/vant.min.js"></script>

<link rel="stylesheet" href="/statics/home/js/swiper.min.css">
 
<script src="/statics/home/js/swiper.min.js"></script>
 <script src="/statics/home/js/clipboard.min.js"></script>
 <script src="/statics/home/js/jquery.min.js"></script>

    <script src="/statics/home/js/scroll.js"></script>
    <script src="https://cdn.staticfile.org/vue-router/2.7.0/vue-router.min.js"></script>
 <script src="https://cdn.bootcss.com/jquery-cookie/1.4.1/jquery.cookie.min.js"></script>
 <script src="/statics/home/js/exif.js"></script>

<script>
//640是原始设计果图大小
var fontSizeAuto = function(oriWidth){
    return function(){
        var viewportWidth = document.documentElement.clientWidth;
        if(viewportWidth > oriWidth){ viewportWidth = oriWidth; }
        if(viewportWidth < 320){ viewportWidth = 320; }
        document.documentElement.style.fontSize = viewportWidth/(oriWidth/100) +'px';   
    }
}
fontSizeAuto(750)();
window.onresize = fontSizeAuto(750);

var Vue = window.Vue;
var vant = window.vant;
// 注册组件
Vue.use(vant);
// 调用函数式组件
// vant.Toast('提示');
</script>

<!-- <script>
  $(function () {
    var str = window.location.href;
    str = str.substring(str.lastIndexOf("/") + 1);
    if ($.cookie(str)) {
        console.log(5555)
    $("html,body").animate({ scrollTop: $.cookie(str) }, 1000);
    }
    else {
        console.log(666)
    }
  })

  $(window).scroll(function () {
    console.log(888888)
    var str = window.location.href;
    str = str.substring(str.lastIndexOf("/") + 1);
    var top = $(document).scrollTop();
    $.cookie(str, top, { path: '/' });
    return $.cookie(str);
 })
</script> -->

</head>
<body>
 

<div id="app" xmlns="http://www.w3.org/1999/html">
<div class="login">
<van-field
    v-model="login.username"
    placeholder="请输入手机号"
    left-icon="a iconfont icon-shouji"
></van-field>
 
 <!-- <van-field
    v-model="login.vercode"
    center
    clearable
    placeholder="请输入短信验证码"
    left-icon="a iconfont icon-yanzhengma"
  >
    <van-button slot="button" @click="sendSms()" v-if="!isDisabled"  size="small" type="primary" style="font-size:14px;" class="btn_orange_deep">{{content}}</van-button>
    <van-button slot="button" v-if="isDisabled"  size="small" type="primary" style="font-size:14px;" class="btn_orange_deep">{{content}}</van-button>
</van-field> -->
<van-field
    v-model="login.password"
    placeholder="请输入密码"
    left-icon="a iconfont icon-mima54"
></van-field>
 
<div class="line_height"></div>
<van-button type="primary" size="large" style="font-size:0.55rem;" class="btn_orange_deep" @click="reg">登录</van-button>
 
<div style="font-size:0.32rem;" class="login_txt">密码由8-16位数字字母加密组成</div>

</div>
<div class="login_foot">
<van-row gutter="1">
      <van-col span="12"  >
         <span style="font-size:0.45rem;" @click="navTo('<?php echo url('reg'); ?>')">新用户注册</span>
      </van-col>
      <van-col span="12"  >
         <span style="font-size:0.45rem;" @click="navTo('<?php echo url('forget'); ?>')">忘记密码</span>
      </van-col>

 <span style="font-size:0.3rem">0.30忘记密码</span></br>
  <span style="font-size:0.33rem">0.33忘记密码</span></br>
    <span style="font-size:0.36rem">0.36忘记密码</span></br>
    <span style="font-size:0.4rem">0.40忘记密码</span></br>
    <span style="font-size:0.43rem">0.43忘记密码</span></br>
    <span style="font-size:0.46rem">0.46忘记密码</span></br>
    <span style="font-size:0.5rem">0.50忘记密码</span></br>
    <span style="font-size:0.53rem">0.53忘记密码</span></br>
 </van-row>
</div> 

<!--<div style="line-height:0.5rem; padding: 0.2rem; text-align: center;font-size:0.32rem;"> 客户专员行为规则，用户协议<br>-->
<!--  百多信息科技有限公司</div>-->

</div>
<div class="van-hairline--top-bottom van-tabbar van-tabbar--fixed" style="bottom: 0.7rem ;background: none; text-align: center ;left: 0.5rem;">Baiduo(xuzhou)ITCo.,Ltd 苏ICP备19027979号</div>
<style>

    #app {
        height: 80%;
    }
    .van-field__control {
        font-size: 0.55rem;
    }
</style>

<script>
    var appIndex = new Vue({
        el: '#app',
        data: function data() {
            return {
                tableData: {list:[]},
                login:{},
                isDisabled: false, //控制按钮是否可以点击（false:可以点击，true:不可点击）
                content: '获取短信验证码', // 发送验证码按钮的初始显示文字
                timer: null,
                count: '',
                model: {},
                code:'',
                username:''

            };
        },
        created:function(){
         
        },
        methods: {
          reg(){
                var _this = this
                axios({
                    method:'post',
                    url:'',
                    data:_this.login,
                }).then(function(resp){
                    if(resp.data.code==1) {
                        vant.Toast.success(resp.data.msg);
                        console.log(resp)
                        window.location.href=resp.data.url;
                    }
                    else {
                        vant.Toast.fail(resp.data.msg);
                    }  
                }).catch(resp => {
                    console.log('请求失败：'+resp.status+','+resp.statusText);
                });
          },
          navTo:function(url) {
              window.location.href=url
          },
          
        //   sendSms () {
        //     // let vm = this
        //     // 校验手机号
        //     if (!this.login.username || this.login.username == '') {
        //         vant.Dialog.alert({
        //         message: '请输入手机号'
        //         }).then(() => {
        //         // on close
        //         });
        //     }else if (!(/^1[34578]\d{9}$/.test(this.login.username))) {
        //         vant.Dialog.alert({
        //         message: '请输入正确的手机号'
        //         }).then(() => {
        //         // on close
        //         });
        //     }else{
        //         var _this = this
        //         axios({
        //             method:'post',
        //             url:'/home/login/code',
        //             data: {phone:_this.login.username},
        //         }).then(function(resp){
        //             if(resp.data.code==1) {
        //                 vant.Toast.success(resp.data.msg);
        //                 // window.location.href="<?php echo url('user/index'); ?>"
        //             }
        //             else {
        //                 // vant.Toast.fail(resp.data.msg);
        //             }  
        //         }).catch(resp => {
        //             console.log('请求失败：'+resp.status+','+resp.statusText);
        //         });
        //         // 控制倒计时及按钮是否可以点击
        //         const TIME_COUNT = 5
        //         this.count = TIME_COUNT
        //         this.timer = window.setInterval(() => {
        //         if (this.count > 0 && this.count <= TIME_COUNT){
        //             // 倒计时时不可点击
        //             this.isDisabled = true
        //             // 计时秒数
        //             this.count--
        //             // 更新按钮的文字内容
        //             this.content = this.count + 's后重新获取'
                    
        //         } else {
        //             // 倒计时完，可点击
        //             this.isDisabled = false
        //             // 更新按钮文字内容
        //             this.content = '获取短信验证码'
        //             // 清空定时器!!!
        //             clearInterval(this.timer)
        //             this.timer = null
        //         }
        //         }, 1000)
                
        //     }
            
            
        // }

        },
        mounted() {
 
        }

    });
</script>
<div style="height: 1rem"></div>
<style type="text/css" media="screen">
  #footer .van-tabbar-item__text {font-size: 0.35rem}
</style>
<div id="footer">
<van-tabbar
  v-model="active"
  active-color="#ff0000"
  inactive-color="#000"
>
  <van-tabbar-item @click="navTo('<?php echo url('Index/index_hot'); ?>')" >热门商品</van-tabbar-item>
  <van-tabbar-item @click="navTo1">分类</van-tabbar-item>
  <van-tabbar-item @click="navTo('<?php echo url('Goods/list_today'); ?>')"  >今日之星</van-tabbar-item>
  <van-tabbar-item @click="navTo('<?php echo url('User/index'); ?>')">我的</van-tabbar-item>
</van-tabbar>
</div>
<script>
    var footer = new Vue({
        el: '#footer',
        data: function data() {
            return {
             active: <?php echo $foot_active; ?>,
              listorder:'',
            };
        },
        created:function(){
          // if(<?php echo $foot_active; ?>==1) {
          //   this.active = 0
          // }
          // if(<?php echo $foot_active; ?>==3) {
          //   this.active = 2
          // }
        },

        methods: {
          navTo: function (url) {
            window.location.href = url
          },
          navTo1() {
            var _this = this
            axios({
              method: 'post',
              url: '<?php echo url("goods/listorder"); ?>',
              data: '',
            }).then(function (resp) {
console.log(resp.data.data[0])
              window.location.href = "<?php echo url('Goods/list_list'); ?>?catid=" + resp.data.data[0].id;
            })


          },
        },
        mounted() {
 
        }

    });
</script>
</body>
</html>

