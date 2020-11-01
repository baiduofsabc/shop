<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:54:"E:\asd1028\fszb1027/app/home\view\goods/list_list.html";i:1603874470;s:50:"E:\asd1028\fszb1027\app\home\view\common\head.html";i:1603874470;s:52:"E:\asd1028\fszb1027\app\home\view\common\footer.html";i:1603874470;}*/ ?>
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
 
<style>
    .van-icon.icon-jiantouarrow486 {
        font-size: 0.6rem
    }

    .head_list {
        line-height: 1rem;
        font-size: 0.4rem;
        height: 1rem;
        background: #fff;
        position: fixed;
        width: 100%;
        top: 0;
        z-index: 9;
        text-align: center
    }

    .head_list i {
        font-size: 0.6rem;
        margin-top: 0.05rem;
        display: inline-block;
        vertical-align: top;
    }

    /*.swiper-container {height:3.4rem!important;left: 0;right: 0;bottom: 0; position: absolute;}*/
    .swiper-container {
        width: 32.2%;
        height: 3.4rem;
        float: left;

    }

    .btn_green_deep {
        width: 100%;
        height: 60px;
        line-height: 58px;
        background: #07c160 !important;
        opacity: 1;
        top: 50%;
        z-index: 999;
        border: none !important;
    }

    .btn-bcg {
        padding: 0.2rem;
        left: 0;
        right: 0;
        bottom: 0rem;
        position: absolute;
        height: 2rem;
        background: rgb(255, 255, 255, 0.9);
        ;
        z-index: 999;
    }
</style>


<div id="app" v-cloak>


    <div class="head_list" @click="onClickRight">


        <?php echo $head_title; ?> <i class="iconfont icon-jiantouarrow486"></i>

    </div>
    <div style="height: 1rem"></div>
    <!-- <van-nav-bar
      title="<?php echo $head_title; ?>"
      left-text="返回"

      left-arrow
      @click-left="onClickLeft"
      @click-right="onClickRight"
    />
    <van-icon name="descending" slot="right" />
    </van-nav-bar>
    <van-nav-bar
      title="<?php echo $head_title; ?>"

      @click-left="onClickLeft"
      @click-right="onClickRight"
      style="position:fixed; width: 100%;top: 0;z-index: 9999999"
    />
    <van-icon name="a iconfont icon-jiantouarrow486" slot="right" style="font-size: 0.6rem" />
    </van-nav-bar> -->


    <van-popup v-model="popupShow" position="top" :style="{ height: '100%' }">

        <div class="btn-bcg">
            <van-button size="" type="primary" class="btn_green_deep" @click="popupShow=false">收起</van-button>
        </div>
        <van-tree-select :items="items" :main-active-index="mainActiveIndex" :active-id="activeId"
            @navclick="onNavClick" @itemclick="onItemClick" :style="{height:'100%'}" />
        </van-tree-select>

        <!--        <div class="index_center"-->
        <!--             style="background: #fff;padding: 0;margin:0; left: 0;right: 0;bottom: 0; position: absolute;"-->
        <!--             v-if="sortBannerInfo.length>0">-->
        <!--            <div class="swiper-container" style="margin-right: 0.425%;margin-left: 0.85%;">-->
        <!--                <div class="swiper-wrapper" style="height:3.4rem;">-->
        <!--                    <div class="swiper-slide" v-for="(vo,index) in sortBannerInfo"-->
        <!--                         :style="'background-image:  url('+(vo.thumb).replace('.','_150_150.')+') , url('+vo.thumb+');'"-->
        <!--                         @click="viewPic(index)" v-if="index < 9">-->

        <!--                    </div>-->
        <!--                    <div class="swiper-slide" v-for="(vo,index) in sortBannerInfo"-->
        <!--                         :style="'background-image:  url('+(vo.thumb).replace('.','_150_150.')+') , url('+vo.thumb+');'"-->
        <!--                         @click="viewPic(index)" v-if="index < 9">-->

        <!--                    </div>-->
        <!--                </div>-->
        <!--            </div>-->
        <!--            <div class="swiper-container" style="margin-right: 0.425%;margin-left: 0.425%;">-->
        <!--                <div class="swiper-wrapper" style="height:3.4rem;">-->
        <!--                    <div class="swiper-slide" v-for="(vo,index) in sortBannerInfo"-->
        <!--                         :style="'background-image:  url('+(vo.thumb).replace('.','_150_150.')+') , url('+vo.thumb+');'"-->
        <!--                         @click="viewPic(index)" v-if="index < 18 && index >= 9">-->

        <!--                    </div>-->
        <!--                    <div class="swiper-slide" v-for="(vo,index) in sortBannerInfo"-->
        <!--                         :style="'background-image:  url('+(vo.thumb).replace('.','_150_150.')+') , url('+vo.thumb+');'"-->
        <!--                         @click="viewPic(index)" v-if="index < 18 && index >= 9">-->

        <!--                    </div>-->
        <!--                </div>-->
        <!--            </div>-->
        <!--            <div class="swiper-container" style="margin-right: 0.85%;margin-left: 0.425%;">-->
        <!--                <div class="swiper-wrapper" style="height:3.4rem;">-->
        <!--                    <div class="swiper-slide" v-for="(vo,index) in sortBannerInfo"-->
        <!--                         :style="'background-image:  url('+(vo.thumb).replace('.','_150_150.')+') , url('+vo.thumb+');'"-->
        <!--                         @click="viewPic(index)" v-if="index < 27 && index >= 18">-->

        <!--                    </div>-->
        <!--                    <div class="swiper-slide" v-for="(vo,index) in sortBannerInfo"-->
        <!--                         :style="'background-image:  url('+(vo.thumb).replace('.','_150_150.')+') , url('+vo.thumb+');'"-->
        <!--                         @click="viewPic(index)" v-if="index < 27 && index >= 18">-->

        <!--                    </div>-->
        <!--                </div>-->
        <!--            </div>-->
        <!--        </div>-->

    </van-popup>



            <van-list id="a" v-model="loading" :finished="finished"  finished-text="没有更多了" @load="onLoad" :offset="1"  >
                <div class="list_content">
                    <van-row gutter="1">
                        <van-col span="8" v-for="(item,index) in list" :key="index">
                            <div class="img" :style="'background-image:  url('+(item.thumb)+') , url('+item.thumb+');'"
                                 @click="navTo('<?php echo url('show'); ?>?id='+item.id)">
                                <i class="iconfont icon-zhiding" v-if="item.is_top==1" :style="{top:0 , right:'0.0rem'}"></i>
                                <div class="title">￥{{item.price}}</div>
                            </div>

                        </van-col>

                    </van-row>
                </div>

            </van-list>




    <van-popup v-model="popupImgShow" position="right" :overlay="false" :style="{ height: '100%' }">
        <van-nav-bar left-text="返回" left-arrow @click-left="onImgLeft" />
        </van-nav-bar>

        <van-image width="7.5rem" height="90%" fit="contain" :src="thisImage" />
    </van-popup>

</div>
<style>
    .icon-zhiding {
        width: 0.45rem;
        background: red;
        height: 0.45rem;
        line-height: 0.45rem;
        text-align: center;
    }

    .icon-zhiding:before {
        content: "顶";
        /*font-weight: bold;*/
        background: red;
        color: white;
        /*font-size: 0.4rem;*/
    }
</style>

<script >


    new Vue({

        el: '#app',
        data: function data() {
            return {
                tableData: [],
                list: [],//图片数组
                loading: false,
                finished: false,
                pageTotal: 0,
                filter: { page: 1, limit: 18 },
                items: [],
                // 左侧高亮元素的index
                mainActiveIndex: 0,
                // 被选中元素的id
                activeId: 1,
                popupShow: false,
                bannerInfo: [],
                bannerImages: [],
                popupImgShow: false,
                thisImage: '',
                scrollY:0
            };
        },
        created: function () {
            let _this = this

            if (window.sessionStorage.list_ul != undefined) {
                // console.log(JSON.parse(window.sessionStorage.list_ul))

                var top = parseInt(sessionStorage.getItem("top"));
                top = top ? top : 0;
                var product_ul = JSON.parse(sessionStorage.getItem("list_ul"));
                console.log(product_ul)
                var page = parseInt(sessionStorage.getItem("page"));

                var url = sessionStorage.getItem("url");
                //判断返回后的页面和上一次的页面地址是否一致
                if (product_ul != null && url == window.location.href) {
                     _this.list = product_ul;
                    _this.filter.page = page;
                     console.log(sessionStorage)
                    window.scrollTop=top
                    document.scrollingElement.scrollTop = top + parseInt($(window).height());
                    _this.filter.page = Number(page+1);
                }
               this.setNav();

            } else {
                this.setNav();
                this.getBanner();
            }
        },


        methods: {

            onClickLeft() {
                if (window.history.length > 1) {
                    window.history.go(-1);
                } else {
                    window.location.href = "/"
                }
            },
            onClickRight() {
                this.popupShow = true
                setTimeout(function () {
                    var swiper = new Swiper('.swiper-container', {
                        slidesPerView: 1,
                        spaceBetween: 2,
                        paginationClickable: true,
                        autoplay: {
                            delay: 3000,
                            stopOnLastSlide: false,
                            disableOnInteraction: true,
                        },
                    });

                }, 500);
            },

            onNavClick(index) {
                this.mainActiveIndex = index;
            },
            onItemClick(data) {

    window.location.href = "<?php echo url('list_list'); ?>?catid=" + data.id
            },

            setData() {

                var _this = this
                axios({
                    method: 'post',
                    url: '',
                    data: _this.filter,
                }).then(function (resp) {
                    _this.loading = false;
                    _this.list = _this.list.concat(resp.data.data);
                    console.log(resp)
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
                    this.setData();
            },
            setNav() {
                var _this = this
                axios({
                    method: 'post',
                    url: '<?php echo url("index"); ?>',
                    data: _this.filter,
                }).then(function (resp) {
                    console.log(resp.data.data.category)
                    _this.items = resp.data.data.category
                }).catch(resp => {
                    console.log('请求失败：' + resp.status + ',' + resp.statusText);
                });
            },
            navTo: function (url) {
                let _this = this
                sessionStorage.clear()
                sessionStorage.setItem("list_ul", `${JSON.stringify(_this.list)}`);
                sessionStorage.setItem("top", `${window.pageYOffset}`);
                sessionStorage.setItem("page", `${_this.filter.page - 1}`);
                sessionStorage.setItem("url", window.location.href);

                // console.log(sessionStorage)
                setTimeout(() => {
                    window.location.href = url
                }, 200)
            },
            getBanner() {
                var _this = this
                axios({
                    method: 'post',
                    url: '<?php echo url("Goods/index"); ?>',
                    data: _this.filter,
                }).then(function (resp) {

                    _this.bannerInfo = JSON.parse(resp.data.data.banner)
                    console.log( _this.bannerInfo)

                    for (var i = 0; i < _this.sortBannerInfo.length; i++) {
                        _this.bannerImages.push(_this.sortBannerInfo[i].thumb)
                    }

                }).catch(resp => {
                    console.log('请求失败：' + resp.status + ',' + resp.statusText);
                });
            },
            viewPic(index) {
                // this.picIndex = index;
                // this.show = true
                this.thisImage = this.bannerImages[index]
                this.popupImgShow = true;

            },
            onImgLeft: function () {

                this.popupImgShow = false;
            },

            sortByKey: function (array, key) { //(数组、排序的列)
                return array.sort(function (a, b) {
                    var x = a[key];
                    var y = b[key];
                    return ((x < y) ? 1 : ((x > y) ? -1 : 0));
                })
            },

        },
        mounted() {

        },
        computed: {
            sortBannerInfo: function () {
                return this.sortByKey(this.bannerInfo, 'order_num');
            }
        },

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