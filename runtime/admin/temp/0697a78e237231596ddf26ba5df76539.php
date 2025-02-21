<?php /*a:1:{s:59:"/www/wwwroot/11.fanlipt.com/app/admin/view/login/index.html";i:1643094160;}*/ ?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8" />
<title><?php echo config('base_config.site_title'); ?></title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="/assets/css/icons.min.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="/assets/element/index.css">
<link rel="stylesheet" href="/assets/css/base.css">
<script src="/assets/element/vue.js"></script>
<script src="/assets/element/index.js"></script>
<script src="/assets/js/axios.min.js"></script>
<script src="/assets/js/js.cookie.min.js"></script>
<script>Vue.config.productionTip = false</script>
</head>


<div id="app" class="body-bg">
	<div class="login-main">
		<h2 class="site-title"><?php echo config('base_config.site_title'); ?></h2>
		<el-form ref="login" :model="login" size="medium" :rules="loginRules" class="login-form" label-position="left" >
			<el-form-item style="margin-bottom:18px;" prop="username">
				<el-input v-model="login.username"  type="text" placeholder="用户名" prefix-icon="el-icon-user" autocomplete="off" clearable />
			</el-form-item>
			<el-form-item style="margin-bottom:18px;" prop="password">
				<el-input v-model="login.password"  type="text" placeholder="密码" prefix-icon="el-icon-lock" @keyup.enter.native="handleLogin" autocomplete="off" clearable show-password/>
			</el-form-item>
			<el-form-item style="margin-bottom:18px;" prop="verify" v-if="verify_status">
				<el-col :span="13">
					<el-input v-model="login.verify"  type="text" placeholder="验证码" prefix-icon="el-icon-picture" @keyup.enter.native="handleLogin" autocomplete="off" clearable/>
				</el-col>
				<el-col :span="11">
					<el-image :src="verifySrc"  alt="验证码" title="点击刷新验证码" @click="refeshVerifySrc" style="width:108px;height:35px; margin-top:3px;float:right" />
				</el-col>
			</el-form-item>
		</el-form>
		<el-button :loading="loading" size="small"  type="primary" style="width:100%;" @click.native.prevent="handleLogin">
			<span v-if="!loading">登 录</span>
			<span v-else>登 录 中...</span>
		</el-button>
	</div>
</div>

<script>
base_url = '/admin'
new Vue({
	el: '#app',
	data: function() {
		return {
			loading: false,
			login:{
				username:'',
				password:'',
				rememberMe:false,
			},
			loginRules: {
				username: [{ required: true, message: '请输入用户名', trigger: 'blur' }],
				password: [{ required: true, message: '请输入密码', trigger: 'blur' }],
				verify: [{ required: true, message: '请输入验证码', trigger: 'blur' }]
			},
			verifySrc:'',
			verify_status:true,
			success_url:'',
			site_title:'',
		}
	},

	mounted(){
		this.captch()
	},
	methods:{
		captch(){
			axios('<?php echo url("admin/Login/verify"); ?>').then(res => {
				if(res.data.status == 200){
					this.verifySrc = res.data.data.img
					this.login.key = res.data.data.key
					this.success_url = res.data.success_url
					this.site_title = res.data.site_title
					this.verify_status = res.data.verify_status
				}else{
					this.$message.error('验证码获取失败!')
				}
			})
		},
		refeshVerifySrc() {
			this.captch()
		},
		handleLogin(){
			this.$refs['login'].validate(valid => {
				if(valid){
					this.loading = true
					axios.post('<?php echo url("admin/Login/index"); ?>',this.login).then(res => {
						if(res.data.status == 200){
							window.location.href = '<?php echo url("admin/Index/index"); ?>';
						}else{
							this.loading = false
							this.refeshVerifySrc()
							this.$message.error(res.data.msg)
						}
					})
				}
			})
		}
	},
})
</script>