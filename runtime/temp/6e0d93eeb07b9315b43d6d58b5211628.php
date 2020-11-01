<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:53:"E:\asd1028\fszb1027/app/admin\view\base\recharge.html";i:1603874470;s:52:"E:\asd1028\fszb1027\app\admin\view\common\head2.html";i:1603874470;}*/ ?>
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
<script type="text/javascript" src="/statics/UE/ueditor.config.js"></script>
<!-- 编辑器源码文件 -->
<script type="text/javascript" src="/statics/UE/ueditor.all.js"></script>
<div id="appIndex">
    <fieldset class="layui-elem-field layui-field-title">
        <legend>充值金额管理</legend>
    </fieldset>
    <!--  <el-form :inline="true" :model="filter">
      <el-form-item label="医院名称">
        <el-input v-model="filter.name" placeholder="医院名称"></el-input>
      </el-form-item>
      <el-form-item>
        <el-button type="primary" @click="setData">查询</el-button>
        <el-button plain style="margin-bottom: 20px;" @click="add">添加</el-button>
      </el-form-item>
    </el-form> -->
 
    <el-table
            :data="tableData"
            border
            style="width: 100%">
        <el-table-column
                fixed
                prop="id"
                label="序号"
                width="100">
        </el-table-column>
        <el-table-column
                prop="money"
                label="金额"
                width="200">
        </el-table-column>
        <el-table-column
                prop="integral"
                label="获得   积分"
                width="320">
        </el-table-column>
 
        <el-table-column
                label="操作"
                width="160">
            <template slot-scope="scope">
                <el-button
                        size="mini" @click="edit(scope.$index)"
                         >编辑</el-button>
               <!--  <el-button
                        size="mini"
                        type="danger" @click="del(scope.$index)"
                         >删除</el-button> -->
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
            :title="thisTitle"
            :visible.sync="dialogVisible"
            width="800px"
            :close-on-click-modal="false"
            :before-close="handleClose">


        <el-form :model="ruleForm" :rules="rules" ref="ruleForm" label-width="100px" class="demo-ruleForm">
         
            <el-form-item label="金额" prop="money">
                <el-input v-model="ruleForm.money"></el-input>
            </el-form-item>
            <el-form-item label="积分" prop="integral">
                <el-input v-model="ruleForm.integral"></el-input>
            </el-form-item>
           
 
            <el-form-item>
                <el-button type="primary" @click="submitForm('ruleForm')">提交</el-button>
                <el-button @click="resetForm('ruleForm')">重置</el-button>
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
                dialogVisible: false,
                ruleForm: {department_ids:[]},
                filter:{page:1},
                rules: {
                    money: [
                        { required: true, message: '必填', trigger: 'blur' },
                        
                    ],
                    integral: [
                        { required: true, message: '必填', trigger: 'change' }
                    ],
                    department_ids: [
                        { type: 'array', required: true, message: '请至少选择一个科室', trigger: 'change' }
                    ],
                    date1: [
                        { type: 'date', required: true, message: '请选择日期', trigger: 'change' }
                    ],
                    date2: [
                        { type: 'date', required: true, message: '请选择时间', trigger: 'change' }
                    ],
                    type: [
                        { type: 'array', required: true, message: '请至少选择一个活动性质', trigger: 'change' }
                    ],
                    resource: [
                        { required: true, message: '请选择活动资源', trigger: 'change' }
                    ],
                    desc: [
                        { required: true, message: '请填写活动形式', trigger: 'blur' }
                    ]
                },
                thisTitle:"添加",
                pageTotal:0,
                editor:'',
                level:[],
                regions:[[],[],[]],
                department:[],

            };
        },
        created:function(){
           this.setData();
      
 

        },
        methods: {
            setData(){
                var _this = this
                axios({
                    method:'post',
                    url:'',
                    data:_this.filter,
                }).then(function(resp){
                    console.log(resp.data);
                    _this.tableData = resp.data.data;
                    _this.pageTotal = resp.data.count;
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
                    this.$set(this.ruleForm,'district','')
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
                this.ruleForm = {department_ids:[]};
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
                    url:'<?php echo url("hospitalInfo"); ?>',
                    data:{id:_this.tableData[index].id},
                }).then(function(resp){
                    _this.ruleForm = JSON.parse(JSON.stringify(resp.data.data));
                    if(resp.data.data.department_ids) {
                       _this.$set(_this.ruleForm,'department_ids',resp.data.data.department_ids.split(',').map(Number)); 
                    }
                    else {
                        _this.$set(_this.ruleForm,'department_ids',[]); 
                    }
  
                    _this.dialogVisible=true;
                    _this.editor = UE.getEditor('container');
                    _this.editor.ready(function() {
                        if (_this.tableData[index].info) {
                          _this.editor.setContent(_this.tableData[index].info);  
                        }
                        else{
                            _this.editor.setContent('');
                        }
                        
                    });
                }).catch(resp => {

                    console.log(resp);
                });



                //this.editor.setContent(_this.tableData[index].content);



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
                this.$set(ruleForm,'department_ids',this.ruleForm.department_ids.join(',')); 
                var _this = this
                axios({
                    method:'post',
                    url:'<?php echo url("hospitalAdd"); ?>',
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
            beforeAvatarUpload(file) {
           
                const isLt2M = file.size / 1024 / 1024 < 2;
                if (!isLt2M) {
                  this.$message.error('上传头像图片大小不能超过 2MB!');
                }
                return  isLt2M;
            },
            del(index){
                var _this = this
                this.$confirm('确认删除？')
                    .then(_ => {
                        _this.del2(index);
                    })
                    .catch(_ => {});

            },
            del2(index) {
                var _this = this;
                var id = this.tableData[index].id;
                axios({
                    method:'post',
                    url:'<?php echo url("hospitalDel"); ?>',
                    data:{id:id},
                }).then(function(resp){
                    console.log(resp.data);
                    if(resp.data.code==1) {
                        _this.$notify({title: '成功',message:resp.data.msg,type: 'success'});
                    }
                    else {
                        _this.$notify({title: '失败',message:resp.data.msg,type: 'error'});
                    }
                    _this.setData();
                }).catch(resp => {
                    console.log('请求失败：'+resp.status+','+resp.statusText);
                });
            },
            getDepartment() {
                var _this = this;
                axios({
                    method:'post',
                    url:'<?php echo url("getDepartment"); ?>',
                    data:{},
                }).then(function(resp){
                    _this.department=resp.data
              
                }).catch(resp => {
                    console.log('请求失败：'+resp.status+','+resp.statusText);
                });

            }


        },
        mounted() {
            this.getDepartment();


        }

    });
</script>
</body>
</html>