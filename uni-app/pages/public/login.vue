<template>
	<view class="container">
		<view class="left-bottom-sign"></view>
		<view class="back-btn yticon icon-zuojiantou-up" @click="navBack"></view>
		<view class="right-top-sign"></view>
		<!-- 设置白色背景防止软键盘把下部绝对定位元素顶上来盖住输入框等 -->
		<view class="wrapper">
			<view class="left-top-sign">LOGIN</view>
			<view class="welcome">
				欢迎回来！
			</view>
			<view class="input-content">
				<view class="input-item">
					<text class="tit">用户名</text>
					<input type="text" value="" placeholder="请输入用户名" maxlength="11" data-key="mobile" @input="inputChange" />
				</view>
				<view class="input-item">
					<text class="tit">密码</text>
					<input type="mobile" value="" placeholder="8-18位不含特殊字符的数字、字母组合" placeholder-class="input-empty" maxlength="20"
					 password data-key="password" @input="inputChange" @confirm="toLogin" />
				</view>
			</view>
			<button class="confirm-btn" @click="toLogin" :disabled="logining">登录</button>
			<view class="forget-section">
				忘记密码?
			</view>
		</view>
		<view class="register-section">
			还没有账号?
			<text @click="toRegist">马上注册</text>
		</view>
	</view>
</template>

<script>
	import common from '../../common/common.js'
	import request from '../../common/request.js'
	import {
		mapMutations
	} from 'vuex';
	import {
		mapState
	} from 'vuex';
	export default {
		data() {
			return {
				mobile: 'admin',
				password: '1',
				logining: false
			}
		},
		onLoad() {
			console.log(this.hasLogin)
			if (this.hasLogin) {
				let userInfo = uni.getStorageSync('userInfo') || '';
				if(userInfo.id){
					 this.mobile=userInfo.user_id;
					 this.password=userInfo.pwd1;
				}
				this.toLogin()
			}


		},
		onBackPress() {

			let that = this;
			if (that.userInfo == undefined) { 
			console.log(that.userInfo)
				if (this.showMask) {
					this.showMask = false;
					return true;
				} else {
					uni.showModal({
						title: '提示',
						content: '是否退出uni-app？',
						success: function(res) {
							if (res.confirm) {
								// 退出当前应用，改方法只在App中生效
								plus.runtime.quit();
							} else if (res.cancel) {
								console.log('用户点击取消');
							}
						}
					});
					return true;
				}
			}
		},
		computed: {
			...mapState(['hasLogin', 'userInfo', 'bi'])
		},
		methods: {
			...mapMutations(['login']),
			inputChange(e) {
				const key = e.currentTarget.dataset.key;
				this[key] = e.detail.value;
			},
			navBack() {
				uni.navigateBack();
			},navTo(url) {
				 
				uni.navigateTo({
					url 
				})
			},
			toRegist() {
				this.navTo('/user/user/register')
				 
			},
			async toLogin() {
				this.logining = true;
				const {
					mobile,
					password
				} = this;
				/* 数据验证模块
				if(!this.$api.match({
					mobile,
					password
				})){
					this.logining = false;
					return;
				}
				*/
				const sendData = {
					mobile,
					password
				};

				let that = this;
				console.log(that.userInfo)
				uni.request({
					url: common.checkLoginUrl, //仅为示例，并非真实接口地址。
					data: {
						is_mobile: 1,
						username: mobile,
						password: password
					},
					method: 'POST',
					header: {
						'content-type': 'application/x-www-form-urlencoded'
					},
					success: function(res) {
						console.log(res.data.status)
						if (res.data.status == 1) {
							uni.request({
								url: common.userInfoUrl, //仅为示例，并非真实接口地址。
								data: {
									is_mobile: 1,
									id: res.data.data.id
								},
								method: 'POST',
								header: {
									'content-type': 'application/x-www-form-urlencoded'
								},
								success: function(res) {
									console.log(res.data.status)
									if (res.data.status == 1) {
										that.login(res.data);
										var pages = getCurrentPages() //获取页面栈
										var prePage = pages[pages.length - 2];
										if (prePage.route == 'pages/product/product') {

											that.$api.prePage().refresh();
										}
										if (prePage.route == 'pages/cart/cart') {

											that.$api.prePage().refresh();
										}
										uni.navigateBack();
									} else {


										that.$api.msg(res.data.info);
										that.logining = false;

									}



								}
							});



						} else {


							that.$api.msg(res.data.info);
							that.logining = false;

						}



					}
				});


				// const result = await this.$api.json('userInfo');
				// if(result.status === 1){
				// 	this.login(result.data);
				//                 uni.navigateBack();  
				// }else{
				// }
			}
		},

	}
</script>

<style lang='scss'>
	page {
		background: #fff;
	}

	.container {
		padding-top: 115px;
		position: relative;
		width: 100vw;
		height: 100vh;
		overflow: hidden;
	}

	.wrapper {
		position: relative;
		z-index: 90;
		background: #fff;
		padding-bottom: 40upx;
	}

	.back-btn {
		position: absolute;
		left: 40upx;
		z-index: 9999;
		padding-top: var(--status-bar-height);
		top: 40upx;
		font-size: 40upx;
		color: $font-color-dark;
	}

	.left-top-sign {
		font-size: 120upx;
		color: $page-color-base;
		position: relative;
		left: -16upx;
	}

	.right-top-sign {
		position: absolute;
		top: 80upx;
		right: -30upx;
		z-index: 95;

		&:before,
		&:after {
			display: block;
			content: "";
			width: 400upx;
			height: 80upx;
			background: #b4f3e2;
		}

		&:before {
			transform: rotate(50deg);
			border-radius: 0 50px 0 0;
		}

		&:after {
			position: absolute;
			right: -198upx;
			top: 0;
			transform: rotate(-50deg);
			border-radius: 50px 0 0 0;
			/* background: pink; */
		}
	}

	.left-bottom-sign {
		position: absolute;
		left: -270upx;
		bottom: -320upx;
		border: 100upx solid #d0d1fd;
		border-radius: 50%;
		padding: 180upx;
	}

	.welcome {
		position: relative;
		left: 50upx;
		top: -90upx;
		font-size: 46upx;
		color: #555;
		text-shadow: 1px 0px 1px rgba(0, 0, 0, .3);
	}

	.input-content {
		padding: 0 60upx;
	}

	.input-item {
		display: flex;
		flex-direction: column;
		align-items: flex-start;
		justify-content: center;
		padding: 0 30upx;
		background: $page-color-light;
		height: 120upx;
		border-radius: 4px;
		margin-bottom: 50upx;

		&:last-child {
			margin-bottom: 0;
		}

		.tit {
			height: 50upx;
			line-height: 56upx;
			font-size: $font-sm+2upx;
			color: $font-color-base;
		}

		input {
			height: 60upx;
			font-size: $font-base + 2upx;
			color: $font-color-dark;
			width: 100%;
		}
	}

	.confirm-btn {
		width: 630upx;
		height: 76upx;
		line-height: 76upx;
		border-radius: 50px;
		margin-top: 70upx;
		background: $uni-color-primary;
		color: #fff;
		font-size: $font-lg;

	}

	.forget-section {
		font-size: $font-sm+2upx;
		color: $font-color-spec;
		text-align: center;
		margin-top: 40upx;
	}

	.register-section {
		position: absolute;
		left: 0;
		bottom: 50upx;
		width: 100%;
		font-size: $font-sm+2upx;
		color: $font-color-base;
		text-align: center;

		text {
			color: $font-color-spec;
			margin-left: 10upx;
		}
	}
</style>
