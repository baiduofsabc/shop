<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:55:"E:\asd1028\fszb1027/app/home\view\goods/list_today.html";i:1603874470;s:50:"E:\asd1028\fszb1027\app\home\view\common\head.html";i:1603874470;s:52:"E:\asd1028\fszb1027\app\home\view\common\footer.html";i:1603874470;}*/ ?>
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
 

<div id="app">
  <van-list v-model="loading" :finished="finished" finished-text="没有更多了" @load="onLoad" :offset="1">
    <div class="index_content">
      <van-row gutter="1">
        <van-col span="8" v-for="(item,index) in list" key="index">
          <div class="img" :style="'background-image:  url('+(item.thumb)+') , url('+item.thumb+');'"
            @click="navTo('<?php echo url('Goods/show'); ?>?id='+item.id)"></div>
          <div class="title">￥{{item.price}}</div>
        </van-col>
      </van-row>
    </div>


  </van-list>
</div>
<script>
  new Vue({
    el: '#app',
    data: function data() {
      return {
        list: [],
        loading: false,
        finished: false,
        pageTotal: 0,
        filter: { page: 1, limit: 18, type_id: 3 }
      };
    },
    created: function () {
      let _this = this
      // if (window.sessionStorage.list_ul2 != 'undefined') {
      //   var top = parseInt(sessionStorage.getItem("top2"));
      //   top = top ? top : 0;
      //   var product_ul = JSON.parse(sessionStorage.getItem("list_ul2"));
      //   var page = parseInt(sessionStorage.getItem("page2"));
      //   var url = sessionStorage.getItem("url2");
      //   //判断返回后的页面和上一次的页面地址是否一致
      //   if (product_ul != null && url == window.location.href) {
      //     _this.list = product_ul;;
      //     document.body.scrollTop = top + parseInt($(window).height());
      //     _this.filter.page = Number(page);
      //   }
      // } else {
        console.log(111)
        // this.indexList();
      // }
    },
    methods: {
      indexList() {
        var _this = this
        axios({
          method: 'post',
          url: '<?php echo url("Index/indexList"); ?>',
          data: _this.filter,
        }).then(function (resp) {
          _this.loading = false;
          _this.list = _this.list.concat(resp.data.data);
          _this.pageTotal = resp.data.count
          _this.filter.page++
          if (_this.list.length >= _this.pageTotal) {
            _this.finished = true;
          }
        }).catch(resp => {
          console.log('请求失败：' + resp.status + ',' + resp.statusText);
        });
      },
      onLoad() {
        this.page++;
        this.indexList();
      },
      navTo: function (url) {
        let _this = this
        sessionStorage.clear()
        sessionStorage.setItem("list_ul2", `${JSON.stringify(_this.list)}`);
        sessionStorage.setItem("top2", `${window.pageYOffset}`);
        sessionStorage.setItem("page2", `${_this.filter.page}`);
        sessionStorage.setItem("url2", window.location.href);
        setTimeout(() => {
          window.location.href = url
        }, 200)
      }

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