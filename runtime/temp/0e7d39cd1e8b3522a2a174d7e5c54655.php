<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:51:"E:\asd1028\fszb1027/app/admin\view\goods\index.html";i:1603874470;s:52:"E:\asd1028\fszb1027\app\admin\view\common\head2.html";i:1603874470;}*/ ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title><?php echo config('sys_name'); ?>后台管理</title>
    <link rel="stylesheet" href="/public/static/plugins/layui/css/layui.css" media="all" />

    <link rel="stylesheet" href="/statics/admin/css/css.css">

    <script src="/statics/admin/js/axios.min.js"></script>
    <!-- 引入Vue -->
    <script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.min.js"></script>
    <!-- 引入样式 -->
    <link rel="stylesheet" href="/statics/admin/element/index.css">
    <!-- 引入组件库 -->
    <script src="/statics/admin/element/index.js"></script>
</head>
<body>
<style>
.avatar-uploader {display: inline-block;}
</style>
<script type="text/javascript" src="/statics/UE/ueditor.config.js"></script>
<!-- 编辑器源码文件 -->
<script type="text/javascript" src="/statics/UE/ueditor.all.js"></script>
<div id="appIndex">
    <fieldset class="layui-elem-field layui-field-title">
        <legend>商品列表</legend>
    </fieldset>
    <el-form :inline="true" :model="filter">
      <el-form-item label="商品名称">
        <el-input v-model="filter.name" placeholder="商品名称"></el-input>
      </el-form-item>
      <el-form-item label="所属分类" >
           
                  <el-select v-model="filter.catid" placeholder="请选择">
                    <el-option-group
                      v-for="group in level"
                      :key="group.label"
                      :label="group.label">
                      <el-option
                        v-for="item in group.options"
                        :key="item.value"
                        :label="item.label"
                        :value="item.value">
                      </el-option>
                    </el-option-group>
                  </el-select>
      </el-form-item>
 
      <el-form-item label="状态" >
                 <el-select v-model="filter.status" placeholder="请选择">
                    <el-option :key="index" :label="vo" :value="index" v-for="(vo,index) in status" v-if="index>0"></el-option>
       
                  </el-select>
      </el-form-item>

      <el-form-item>
        <el-button type="primary" @click="setData">查询</el-button>
        <el-button plain style="margin-bottom: 20px;" @click="add">添加</el-button>
      </el-form-item>
    </el-form>
    

    <el-table
            :data="tableData"
            border
            style="width: 100%">
        <el-table-column
                fixed
                prop="id"
                label="序号"
                width="150">
            <template slot-scope="scope">
                 {{scope.$index+1}}
             </template>
        </el-table-column>
        <el-table-column
                prop="name"
                label="商品名称"
                width="120">
        </el-table-column>
        <el-table-column
                prop="cate"
                label="所属分类"
                width="120">
        </el-table-column>
        <el-table-column
                prop="price"
                label="价格（元）"
                width="120">
        </el-table-column>
 
         <el-table-column
                prop="status"
                label="状态"
                width="120">
             <template slot-scope="scope">
                 <span>{{status[scope.row.status]}}</span>
             </template>
        </el-table-column>
        <el-table-column
                prop="add_time"
                label="添加时间"
                width="200">
        </el-table-column>
   
        <el-table-column
                label="操作"
                width="300">
            <template slot-scope="scope">
                <el-button size="mini" @click="edit(scope.$index)">查看</el-button>
                <el-button size="mini"  @click="setStatus(scope.$index)" v-if="scope.row.status!=3">{{scope.row.status==1?"下架":"上架" }}</el-button>       
                <el-button size="mini" type="danger" @click="del(scope.$index)" v-if="scope.row.status!=3">删除</el-button>
                <el-button size="mini"  @click="del(scope.$index)" v-else>恢复</el-button>
            </template>
        </el-table-column>
    </el-table>

    <el-pagination
      background
      @current-change="handleCurrentChange"
      layout="prev, pager, next"
      :page-size="15"
      :total="pageTotal">
    </el-pagination>

    <el-dialog
            :title="thisTitle+'家护师'"
            :visible.sync="dialogVisible"


            width="70%"
            :close-on-click-modal="false"
            :before-close="handleClose">


        <el-form :model="ruleForm" :rules="rules" ref="ruleForm" label-width="100px" class="demo-ruleForm">
         
            <el-form-item label="商品名称" prop="name">
                {{ruleForm.name}}
            </el-form-item>
            
         
            <el-form-item label="价格" prop="exp">
                {{ruleForm.price}} 元
            </el-form-item>

            <el-form-item label="详情" prop="content">
                 {{ruleForm.info}}
            </el-form-item> 
            <el-form-item label="详情图" prop="thumb" v-if="ruleForm.thumb">
                 <img :src="ruleForm.thumb" style="height: 500px;">
            </el-form-item>
            <el-form-item label="缩略图"  >
                 <img :src="vo" v-for="(vo,index) in ruleForm.images" style="height: 500px; margin:5px;">
            </el-form-item>
      
 
        
        </el-form>



    </el-dialog>

        




</div>
<script>
    var appIndex = new Vue({
        el: '#appIndex',
        data() {
            return {
                tableData: [],
                status:['','上架中','已下架','已删除'],
                dialogVisible: false,
                ruleForm: {tag_com_ids:[]},
                filter:{page:1},
                rules: {
                    hospital_id: [
                        {required: true, message: '请选择所属医院', trigger: 'change' }
                    ],
                    level_id: [
                        {required: true, message: '请选择照护师等级', trigger: 'change' }
                    ],
                    name: [
                        {required: true, message: '必填', trigger: 'blur' }
                    ],
                    sex: [
                        {required: true, message: '必填', trigger: 'blur' }
                    ],
                    age: [
                        {required: true, message: '必填', trigger: 'blur' }
                    ],
                    exp: [
                        {required: true, message: '必填', trigger: 'blur' }
                    ],
                    tag_ids: [
                        { type: 'array', required: true, message: '请至少选择一个特长标签', trigger: 'change' }
                    ],
                    thumb: [
                        {required: true, message: '请上传',  }
                    ],
                },
                thisTitle:"添加",
                pageTotal:0,
                editor:'',
                level:[],
                regions:[[],[],[]],
                tag:[],
                tagCom:[],
                hospital:[],
                comfilter:{page:1},
                comData: [],
                commentVisible:false,
                comTotal:0,
                info_ext_arr:[{key:"",value:""}],

            };
        },
        created:function(){
               this.setData();
       
               var _this = this;
               axios({
                    method:'post',
                    url:'<?php echo url("getLevel"); ?>',
                    data:{},
                }).then(function(resp){
                    _this.level = resp.data;
                    console.log(_this.level)
                }).catch(resp => {
                    console.log('请求失败：'+resp.status+','+resp.statusText);
                });

        },
        methods: {
            resSetKey(key,value) {
              this.$set(this.ruleForm,key,value); 
            },
            setData(){
                var _this = this
                axios({
                    method:'post',
                    url:'<?php echo url("index"); ?>',
                    data:_this.filter,
                }).then(function(resp){
                    console.log(resp.data);
                    _this.tableData = resp.data.data;
                    _this.pageTotal = resp.data.count;
                }).catch(resp => {
                    console.log('请求失败：'+resp.status+','+resp.statusText);
                });
            },
            getHospital(is_show=0) {
                if(is_show==0) {
                    this.$set(this.ruleForm,'hospital_id','')
                }
                let filter = {}
                filter.province = this.ruleForm.province
                filter.city = this.ruleForm.city
                filter.district = this.ruleForm.district
                let _this = this
                axios({
                    method:'post',
                    url:'<?php echo url("getHospital"); ?>',
                    data:filter
                }).then(function(resp){
                    console.log(resp.data)
                    _this.hospital = resp.data
                }).catch(resp => {
                    console.log('请求失败：'+resp.status+','+resp.statusText);
                });
            },
            getArea(pid,index,val=1) {
                if(index==1&&val==1) {
            
                    this.$set(this.ruleForm,'city','')
                    this.$set(this.ruleForm,'district','')
                }
                if(index==2&&val==1) {

                    this.$set(this.ruleForm,'district','');
                    //this.getHospital()
                }

                var _this = this
                axios({
                    method:'post',
                    url:'<?php echo url("getArea"); ?>',
                    data:{pid:pid},
                }).then(function(resp){
                     _this.$set(_this.regions,index,resp.data)
                }).catch(resp => {
                    console.log('请求失败：'+resp.status+','+resp.statusText);
                });

            },
            handleCurrentChange(val) {
                this.$set(this.filter,'page',val);
                this.setData()
            },


            add() {
                var _this =this;
                this.thisTitle="添加";
                this.dialogVisible=true;
                this.info_ext_arr=[''];
                this.ruleForm = {tag_ids:[],tag_com_ids:[],info_ext:[{key:"",value:""}]};
                this.editor = UE.getEditor('container');

                this.editor.ready(function() {
                    _this.editor.setContent('');
                });

                // this.editor.addListener("ready", function () {
                //     // editor准备好之后才可以使用
                //     _this.editor.setContent('');
                //
                // });
            },
            edit(index) {
                var _this =this;
                _this.thisTitle="编辑";

                if(_this.tableData[index].province) {
                    _this.getArea(_this.tableData[index].province,1,2)
                }
                if(_this.tableData[index].city) {
                    _this.getArea(_this.tableData[index].city,2,2)
                }
                axios({
                    method:'post',
                    url:'<?php echo url("info"); ?>',
                    data:{id:_this.tableData[index].id},
                }).then(function(resp){
                    _this.ruleForm = resp.data.data;
                    _this.dialogVisible=true;
                    if(resp.data.data.info_ext) {
                       _this.$set(_this.ruleForm,'info_ext',JSON.parse(resp.data.data.info_ext)); 
                    }
                    else {
                      _this.$set(_this.ruleForm,'info_ext',[{key:"",value:""}]); 
                    }
                    if(resp.data.data.tag_ids) {
                       _this.$set(_this.ruleForm,'tag_ids',resp.data.data.tag_ids.split(',').map(Number)); 
                    }
                    else {
                        _this.$set(_this.ruleForm,'tag_ids',[]); 
                    }
                    if(resp.data.data.tag_com_ids) {
                       _this.$set(_this.ruleForm,'tag_com_ids',resp.data.data.tag_com_ids.split(',').map(Number)); 
                    }
                    else {
                        _this.$set(_this.ruleForm,'tag_com_ids',[]); 
                    }
                    _this.editor = UE.getEditor('container');
                    _this.editor.ready(function() {
                        if (_this.tableData[index].info) {
                          _this.editor.setContent(_this.tableData[index].info);  
                        }
                        else{
                            _this.editor.setContent('');
                        }
                        
                    });
                    _this.getHospital(1);
                }).catch(resp => {
                    console.log('请求失败：'+resp.status+','+resp.statusText);
                });



                //this.editor.setContent(_this.tableData[index].content);



            },
            addValue(){
                this.ruleForm.info_ext.push({key:"",value:""});
            },
            delValue(index){
                this.ruleForm.info_ext.splice(index,1);
            },
            handleClick(row) {
                console.log(row);
            },
            handleClose(done) {
                this.$confirm('确认关闭？')
                    .then(_ => {
                        done();
                    })
                    .catch(_ => {});
            },
            submitForm(formName) {
                var _this = this;
                this.$refs[formName].validate((valid) => {
                    if (valid) {
                        _this.save()
                    } else {
                        console.log('error submit!!');
                        return false;
                    }
                });
            },
            save() {
                let ueditordata = UE.getEditor('container').getContent();

                this.ruleForm.info = ueditordata;

                let ruleForm = JSON.parse(JSON.stringify(this.ruleForm));
                this.$set(ruleForm,'tag_ids',this.ruleForm.tag_ids.join(',')); 
                this.$set(ruleForm,'tag_com_ids',this.ruleForm.tag_com_ids.join(','));
                this.$set(ruleForm,'info_ext',JSON.stringify(this.ruleForm.info_ext)); 
                var _this = this
                axios({
                    method:'post',
                    url:'<?php echo url("add"); ?>',
                    data:ruleForm,
                }).then(function(resp){
                    console.log(resp.data);
                    if(resp.data.code==1) {
                        _this.$notify({title: '成功',message:resp.data.msg,type: 'success'});
                        _this.dialogVisible=false;
                    }
                    else {
                        _this.$notify({title: '失败',message:resp.data.msg,type: 'error'});
                    }
                    this.dialogVisible=false;
                    _this.setData();
                }).catch(resp => {
                    console.log('请求失败：'+resp.status+','+resp.statusText);
                });
            },
            resetForm(formName) {
                this.$refs[formName].resetFields();
            },
            handleAvatarSuccess(res, file) {
                console.log(res)
               this.$set(this.ruleForm,'thumb',res.url)
             
            },
            health_certSuccess(res, file) {
                console.log(res)
               this.$set(this.ruleForm,'health_cert',res.url)
             
            },
            ylhly_certSuccess(res, file) {
                console.log(res)
               this.$set(this.ruleForm,'ylhly_cert',res.url)
             
            },
            ylzh_certSuccess(res, file) {
                console.log(res)
               this.$set(this.ruleForm,'ylzh_cert',res.url)
             
            },

            beforeAvatarUpload(file) {
           
                const isLt2M = file.size / 1024 / 1024 < 2;

               
                if (!isLt2M) {
                  this.$message.error('上传头像图片大小不能超过 2MB!');
                }
                return  isLt2M;
            },
            del(index){
                let status = this.tableData[index].status==3?"恢复":"删除"
                var _this = this
                this.$confirm('确认'+status+'？')
                    .then(_ => {
                        _this.del2(index);
                    })
                    .catch(_ => {});

            },
            del2(index){
                let status = this.tableData[index].status!=3?3:2
                var id = this.tableData[index].id;
                var _this = this;
                axios({
                    method:'post',
                    url:'<?php echo url("add"); ?>',
                    data:{id:id,status:status},
                }).then(function(resp){
        
                    if(resp.data.code==1) {
                        _this.$notify({title: '成功',message:resp.data.msg,type: 'success'});
                        _this.setData();

                    }
                    else {
                        _this.$notify({title: '失败',message:resp.data.msg,type: 'error'});
                    }
 
                }).catch(resp => {
                    console.log('请求失败：'+resp.status+','+resp.statusText);
                });

            },
            setStatus(index){
                let status = this.tableData[index].status==2?1:2
                let id = this.tableData[index].id;
                let _this = this
                axios({
                    method:'post',
                    url:'<?php echo url("add"); ?>',
                    data:{id:id,status:status},
                }).then(function(resp){
        
                    if(resp.data.code==1) {
             
                        _this.$notify({title: '设置成功',message:resp.data.msg,type: 'success'});
                    }
                    else {
                        _this.$notify({title: '设置失败',message:resp.data.msg,type: 'error'});
                    }
                    _this.setData();
                }).catch(resp => {
                    console.log('请求失败：'+resp.status+','+resp.statusText);
                });
            },
            delCom(index){
                var _this = this
                this.$confirm('确认删除？')
                    .then(_ => {
                        _this.delCom2(index);
                    })
                    .catch(_ => {});

            },
            delCom2(index){
                var id = this.comData[index].id;
                var _this = this
                axios({
                    method:'post',
                    url:'<?php echo url("Comment/del"); ?>',
                    data:{id:id},
                }).then(function(resp){
                    console.log(resp.data);
                    if(resp.data.code==1) {
                        _this.getCom();
                        _this.$notify({title: '成功',message:resp.data.msg,type: 'success'});
                    }
                    else {
                        _this.$notify({title: '失败',message:resp.data.msg,type: 'error'});
                    }

                    _this.getCom();
                }).catch(resp => {
                    console.log('请求失败：'+resp.status+','+resp.statusText);
                });
            },
            
            

            getTag() {
                var _this = this;
                axios({
                    method:'post',
                    url:'<?php echo url("tagList"); ?>',
                    data:{},
                }).then(function(resp){
                    _this.tag=resp.data
              
                }).catch(resp => {
                    console.log('请求失败：'+resp.status+','+resp.statusText);
                });

                axios({
                    method:'post',
                    url:'<?php echo url("tagComList"); ?>',
                    data:{},
                }).then(function(resp){
                    _this.tagCom=resp.data
              
                }).catch(resp => {
                    console.log('请求失败：'+resp.status+','+resp.statusText);
                });

            },
            getCom(tend_id=0) {
                var _this = this
                if(tend_id) {
                  this.$set(this.comfilter,'tend_id',tend_id) 
                }
                this.$set(this.comfilter,'mod','tend') 
                axios({
                    method:'post',
                    url:'<?php echo url("Comment/index"); ?>',
                    data:_this.comfilter,
                }).then(function(resp){
                    _this.comData = resp.data.data;
                    _this.comTotal = resp.data.count;
                    _this.commentVisible = true;
                }).catch(resp => {
                    console.log('请求失败：'+resp.status+','+resp.statusText);
                });
            },
            comCurrentChange(val) {
                this.$set(this.comfilter,'page',val);
                this.getCom()
            },
        },
        mounted() {
             this.getTag();
        }

    });
</script>
</body>
</html>