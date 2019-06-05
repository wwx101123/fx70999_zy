<template>
	<view class="container" style="background: #f5f5f5;">

		<view class="user-section">
			<image class="bg" src="/static/user-bg.jpg"></image>
			<view class="user-info-box">
				<view class="portrait-box">
					<avatar selWidth="150px" selHeight="150px" @upload="myUpload" :avatarSrc="url" avatarStyle="width: 150upx; height: 150upx; border-radius: 100%;">
					</avatar>

					<!-- <image class="portrait" :src="userInfo.portrait || '/static/missing-face.png'"></image> -->
				</view>
				<view class="info-box">
					<text class="username">{{userInfo.user_id || '游客'}}</text>
				</view>
			</view>
			<view class="vip-card-box">
				<image class="card-bg" src="/static/vip-card-bg.png" mode=""></image>
				<view class="b-btn" @click="navTo('/pages/shop/edit')">
					编辑店铺
				</view>
				<view class="tit">
					<text class="yticon icon-iLinkapp-"></text>
					{{userInfo.uLevel|| ''}}
				</view>
				<text class="e-m">DCloud Union</text>
				<text class="e-b">开通会员开发无bug 一测就上线</text>
			</view>
		</view>

		<view class="cover-container" :style="[{
				transform: coverTransform,
				transition: coverTransition
			}]"
		 @touchstart="coverTouchstart" @touchmove="coverTouchmove" @touchend="coverTouchend">
			<image class="arc" src="/static/arc.png"></image>

			<view class="tj-sction">
				<view class="tj-item" @click="navTo('/user/user/usermoney?type=buy_point&&title='+bi.buy_point)">
					<text class="num">{{userInfo.buy_point||''}}</text>
					<text>{{bi.buy_point||''}}</text>
				</view>
				<view class="tj-item" @click="navTo('/user/user/usermoney?type=agent_use&&title='+bi.agent_use)">
					<text class="num">{{userInfo.agent_use||''}}</text>
					<text>{{bi.agent_use||''}}</text>
				</view>
				<view class="tj-item" @click="navTo('/user/user/usermoney?type=agent_cash&&title='+bi.agent_cash)">
					<text class="num">{{userInfo.agent_cash||''}}</text>
					<text>{{bi.agent_cash||''}}</text>
				</view>
			</view>
			<view class="history-section icon" style="padding:0px">
				<view class="cu-bar bg-white ">
					<view class="sec-header">
						<text class="yticon icon-lishijilu"></text>
						<text>邀请</text>
					</view>
					<view class="action">
						<button class="cu-btn bg-red shadow" @tap="navTo('/user/user/zhiwen-share')" data-target="Modal">邀请好友</button>
					</view>
				</view>
				<view class="tj-sction">
					<view class="tj-item">
						<text class="num">{{userInfo.user_count1}}</text>
						<text>我的邀请</text>
					</view>
					<view class="tj-item">
						<text class="num">{{userInfo.user_count2}}</text>
						<text>今日邀请</text>
					</view>
					<view class="tj-item">
						<text class="num">{{userInfo.user_count3}}</text>
						<text>粉丝人数</text>
					</view>
					<view class="tj-item">
						<text class="num">{{userInfo.user_count4}}</text>
						<text>今日新增</text>
					</view>
				</view>
			</view>
			<!-- 订单 -->
			<view class="order-section">
				<view class="order-item" @click="navTo('/pages/order/order?state=1')" hover-class="common-hover" :hover-stay-time="50">
					<text class="yticon icon-shouye"></text>
					<text>全部订单</text>
				</view>
				<view class="order-item" @click="navTo('/pages/order/order?state=2')" hover-class="common-hover" :hover-stay-time="50">
					<text class="yticon icon-daifukuan"></text>
					<text>待付款</text>
				</view>
				<view class="order-item" @click="navTo('/pages/order/order?state=3')" hover-class="common-hover" :hover-stay-time="50">
					<text class="yticon icon-yishouhuo"></text>
					<text>待发货</text>
				</view>
				<view class="order-item" @click="navTo('/pages/order/order?state=4')" hover-class="common-hover" :hover-stay-time="50">
					<text class="yticon icon-shouhoutuikuan"></text>
					<text>待收货</text>
				</view>
			</view>
			<!-- 浏览历史 -->
			<view class="history-section icon">
				<view class="sec-header" v-if="userInfo.goods_show_list_count > 0 ">
					<text class="yticon icon-lishijilu"></text>
					<text>浏览历史</text>
				</view>
				<scroll-view scroll-x class="h-list">

					<image :src="item.icon" v-for="(item, index) in goods_show_list" :key="index" mode="aspectFill" @click="navTo(item.url)"></image>

				</scroll-view>


			</view>
			<!-- 工具栏 -->
			<view class="toolbar icon">
				<view class="title">我的工具栏</view>
				<view class="list">
					<view class="box" @click="navTo('/pages/shop/goods_list')">
						<view class="img">
							<image src="../../static/img/user/3.png"></image>
						</view>
						<view class="text">店铺商品</view>
					</view>
					<view class="box" @click="navTo('/pages/shop/dui_list')">
						<view class="img">
							<image src="../../static/img/user/4.png"></image>
						</view>
						<view class="text">兑换商品</view>
					</view>
					<view class="box" @click="navTo('/user/user/tiqu')">
						<view class="img">
							<image src="../../static/img/user/8.png"></image>
						</view>
						<view class="text">我要提现</view>
					</view>
					<view class="box" @click="navTo('/user/user/bank')">
						<view class="img">
							<image src="../../static/img/user/5.png"></image>
						</view>
						<view class="text">个人信息</view>
					</view>












					<view class="box" @click="navTo('/user/user/password')">
						<view class="img">
							<image src="../../static/img/user/7.png"></image>
						</view>
						<view class="text">密码修改</view>
					</view>
					<view class="box" @click="navTo('/pages/address/address')">
						<view class="img">
							<image src="../../static/img/user/2.png"></image>
						</view>
						<view class="text">地址管理</view>
					</view>
					<view class="box" v-if="isShow" @click="update_version">
						<view class="img">
							<image src="../../static/img/user/6.png"></image>
						</view>
						<view class="text">检查版本</view>
					</view>
					<view class="box" @click="toLogout">
						<view class="img">
							<image src="../../static/img/user/1.png"></image>
						</view>
						<view class="text">退出登录</view>
					</view>






				</view>
			</view>
		</view>


	</view>
</template>
<script>
	import avatar from "../../components/yq-avatar/yq-avatar.vue";
	import common from '../../common/common.js';
	import listCell from '@/components/mix-list-cell';
	import autoUpdater from '../../common/autoUpdater.min.js';
	import {
		mapState,
		mapMutations
	} from 'vuex';
	let startY = 0,
		moveY = 0,
		pageAtTop = true;
	export default {
		components: {
			listCell,
			avatar
		},
		computed: {
			...mapState(['hasLogin', 'userInfo', 'bi'])
		},

		data() {
			return {
				coverTransform: 'translateY(0px)',
				coverTransition: '0s',
				moving: false,
				isShow: true,
				update_tips: '',
				goods_show_list: [],
				userInfo: {},
				bi: {},
				url: "../../static/logo.png",
				basicArr: [],
				// 工具栏列表
				mytoolbarList: [{
						url: '../user/keep/keep',
						text: '我的收藏',
						img: '../../static/img/user/point.png'
					},
					{
						url: '../user/coupon/coupon',
						text: '优惠券',
						img: '../../static/img/user/quan.png'
					},
					{
						url: '',
						text: '新客豪礼',
						img: '../../static/img/user/renw.png'
					},
					{
						url: '',
						text: '领红包',
						img: '../../static/img/user/momey.png'
					},

					{
						url: '../user/address/address',
						text: '收货地址',
						img: '../../static/img/user/addr.png'
					},
					{
						url: '',
						text: '账户安全',
						img: '../../static/img/user/security.png'
					},
					{
						url: '',
						text: '银行卡',
						img: '../../static/img/user/bank.png'
					},
					{
						url: '',
						text: '抽奖',
						img: '../../static/img/user/choujiang.png'
					},
					// {text:'客服',img:'../../static/img/user/kefu.png'},
					// {text:'签到',img:'../../static/img/user/mingxi.png'}

				]
			}
		},
		onLoad() {


			// #ifdef MP-WEIXIN
			console.log('MP-WEIXIN')
			this.isShow = false;
			// #endif
			// #ifdef H5
			console.log('H5')
			this.isShow = false;
			// #endif
			console.log(this.userInfo);
			let user_id = uni.getStorageSync('user_id');



			if (user_id == undefined) {
				this.navTo('/pages/public/login')
			}
			let userInfo = uni.getStorageSync('userInfo');
			let bi = uni.getStorageSync('bi');
			this.userInfo = userInfo;
			this.bi = bi;
			this.url = this.userInfo.portrait;
			// console.log(common.IP + this.userInfo.portrait)

			this.goods_show_list = this.userInfo.goods_show_list;
			// console.log(JSON.stringify(this.userInfo.goods_show_list))

			let that = this;
			// console.log(common.get_client())

			// #ifdef APP-PLUS
			if (common.get_client() == 'android' || common.get_client() == 'ios') {

				const res = uni.getSystemInfoSync();
				// console.log(JSON.stringify(res));
				//判断app还是小程序
				if (res.brand == '' || res.brand == 'iPhone') {

					//这里就是赋值  
					plus.runtime.getProperty(plus.runtime.appid, function(inf) {
						console.log(plus.device.vendor);

						const net = uni.getNetworkType();

						uni.getNetworkType({
							success: function(res1) {
								const networkType = (res1.networkType);


								//console.log(res.pixelRatio);
								//console.log(res.windowWidth);
								//console.log(res.windowHeight);
								//console.log(res.language);
								//console.log(res.version);
								//console.log(res.platform);
								// this.app_version = plus.runtime.version;
								// this.$api.msg(this.app_version);
								//console.log(networkType)
								//console.log(res.system)

								uni.request({
									url: common.checkUrl, //仅为示例，并非真实接口地址。
									data: {
										is_mobile: 1,
										device_model: res.model,
										device_connection_type: networkType,
										device_vendor: plus.device.vendor,
										device_version: res.system,
										version: plus.runtime.version,
										apk_version: plus.runtime.version,
										client: res.platform,
										user_id: that.userInfo.id,
										IP: common.IP
									},
									method: 'POST',
									header: {
										'content-type': 'application/x-www-form-urlencoded'
									},
									success: function(res) {
										if (res.data.status == 1) {
											that.update_tips = '有新版本';
											// that.$refs.version.tips = '有新版本';
											const update_tips = res.data.update_tip.join("\n");
											// console.log(that.$refs.version.tips)

											that.init_update_version(update_tips, res.data.url);

											// that.update_version();

										} else {
											that.update_tips = '最新版';
											that.$refs.version.tips = '最新版';

										}



									}
								});
							}
						});
					});
				} else {

				}




			} else {

				// this.update_version_view.hide();
			}
			// #endif
		},
		// #ifndef MP
		onNavigationBarButtonTap(e) {
			const index = e.index;
			if (index === 0) {
				this.navTo('/pages/notice/notice');
				// this.navTo('/pages/set/set');
			}
			if (index === 1) {
				this.navTo('/pages/notice/notice');
			}
		},
		// #endif
		methods: {
			...mapMutations(['logout']),
			myUpload(rsp) {
				let that = this;
				//rsp.avatar.imgSrc = rsp.path; //更新头像方式二

				const uploadTask = uni.uploadFile({
					url: common.Upload,
					filePath: rsp.path,
					name: 'file',
					formData: {
						is_mobile: 1,

						// is_default: data.default,
						user_id: that.userInfo.id
					},
					success: function(res) {
						var info = JSON.parse(res.data)
						if (info.status == 1) {
							//this.$api.prePage()获取上一页实例，可直接调用上页所有数据和方法，在App.vue定义
							that.$api.msg(info.info);

							that.url = info.icon; //更新头像方式一
						} else {

							that.$api.msg(info.info);

						}



					}
				});
			},
			/**
			 * 统一跳转接口,拦截未登录路由
			 * navigator标签现在默认没有转场动画，所以用view
			 */
			navTo(url) {
				if (!this.hasLogin) {
					url = '/pages/public/login';
				}
				uni.navigateTo({
					url
				})
			},
			/**
			 *  会员卡下拉和回弹
			 *  1.关闭bounce避免ios端下拉冲突
			 *  2.由于touchmove事件的缺陷（以前做小程序就遇到，比如20跳到40，h5反而好很多），下拉的时候会有掉帧的感觉
			 *    transition设置0.1秒延迟，让css来过渡这段空窗期
			 *  3.回弹效果可修改曲线值来调整效果，推荐一个好用的bezier生成工具 http://cubic-bezier.com/
			 */
			coverTouchstart(e) {
				if (pageAtTop === false) {
					return;
				}
				this.coverTransition = 'transform .1s linear';
				startY = e.touches[0].clientY;
			},
			coverTouchmove(e) {
				moveY = e.touches[0].clientY;
				let moveDistance = moveY - startY;
				if (moveDistance < 0) {
					this.moving = false;
					return;
				}
				this.moving = true;
				if (moveDistance >= 80 && moveDistance < 100) {
					moveDistance = 80;
				}

				if (moveDistance > 0 && moveDistance <= 80) {
					this.coverTransform = `translateY(${moveDistance}px)`;
				}
			},
			coverTouchend() {
				if (this.moving === false) {
					return;
				}
				this.moving = false;
				this.coverTransition = 'transform 0.3s cubic-bezier(.21,1.93,.53,.64)';
				this.coverTransform = 'translateY(0px)';
			},
			init_update_version(update_tip, url) {
				autoUpdater.init({
					packageUrl: url,
					content: '更新内容:\n' + update_tip,
					contentAlign: 'left',
					cancel: '取消该版本',
					cancelColor: '#007fff'
				});


			},
			update_version() {
				let that = this;
				console.log(222)
				// that.$refs.version.tips = that.update_tips;
				that.$api.msg(that.update_tips);
				autoUpdater.show();

			},
			show_update: function(client) {
				// const is_view_show=true;
				// if (client == 'ios' || client == 'android') {
				//  
				// } else {
				// 	is_view_show =false;
				// }
				// console.log(client)



			},
			//退出登录
			toLogout() {
				console.log(22222)
				uni.showModal({
					title: '提示',
					content: '确定要退出登录么？',
					success: (e) => {
						if (e.confirm) {
							this.logout();
							setTimeout(() => {
								let url = '/pages/public/login';
								uni.navigateTo({
									url
								})
							}, 200)
						}
					}
				})
			}
		}
	}
</script>
<style lang='scss'>
	%flex-center {
		display: flex;
		flex-direction: column;
		justify-content: center;
		align-items: center;
	}

	%section {
		display: flex;
		justify-content: space-around;
		align-content: center;
		background: #fff;
		border-radius: 10upx;
	}

	.sunsin_add_image {
		background-color: transparent;
	}

	.user-section {
		height: 520upx;
		padding: 100upx 30upx 0;
		position: relative;

		.bg {
			position: absolute;
			left: 0;
			top: 0;
			width: 100%;
			height: 100%;
			filter: blur(1px);
			opacity: .7;
		}
	}

	.user-info-box {
		height: 180upx;
		display: flex;
		align-items: center;
		position: relative;
		z-index: 1;

		.portrait {
			width: 130upx;
			height: 130upx;
			border: 5upx solid #fff;
			border-radius: 50%;
		}

		.username {
			font-size: $font-lg + 6upx;
			color: $font-color-dark;
			margin-left: 20upx;
		}
	}

	.vip-card-box {
		display: flex;
		flex-direction: column;
		color: #f7d680;
		height: 240upx;
		background: linear-gradient(left, rgba(0, 0, 0, .7), rgba(0, 0, 0, .8));
		border-radius: 16upx 16upx 0 0;
		overflow: hidden;
		position: relative;
		padding: 20upx 24upx;

		.card-bg {
			position: absolute;
			top: 20upx;
			right: 0;
			width: 380upx;
			height: 260upx;
		}

		.b-btn {
			position: absolute;
			right: 20upx;
			top: 16upx;
			width: 132upx;
			height: 40upx;
			text-align: center;
			line-height: 40upx;
			font-size: 22upx;
			color: #36343c;
			border-radius: 20px;
			background: linear-gradient(left, #f9e6af, #ffd465);
			z-index: 1;
		}

		.tit {
			font-size: $font-base+2upx;
			color: #f7d680;
			margin-bottom: 28upx;

			.yticon {
				color: #f6e5a3;
				margin-right: 16upx;
			}
		}

		.e-b {
			font-size: $font-sm;
			color: #d8cba9;
			margin-top: 10upx;
		}
	}

	.cover-container {
		background: $page-color-base;
		margin-top: -150upx;
		padding: 0 30upx;
		position: relative;
		background: #f5f5f5;
		padding-bottom: 20upx;

		.arc {
			position: absolute;
			left: 0;
			top: -34upx;
			width: 100%;
			height: 36upx;
		}
	}

	.tj-sction {
		@extend %section;

		.tj-item {
			@extend %flex-center;
			flex-direction: column;
			height: 140upx;
			font-size: $font-sm;
			color: #75787d;
		}

		.num {
			font-size: $font-lg;
			color: $font-color-dark;
			margin-bottom: 8upx;
		}
	}

	.order-section {
		@extend %section;
		padding: 32upx 0;
		margin-top: 20upx;

		.order-item {
			@extend %flex-center;
			font-size: $font-sm;
			color: $font-color-dark;
		}

		.yticon {
			font-size: 48upx;
			margin-bottom: 18upx;
			color: #fa436a;
		}

		.icon-shouhoutuikuan {
			font-size: 44upx;
		}
	}

	.history-section {
		padding: 30upx 0 0;
		margin-top: 20upx;
		background: #fff;
		border-radius: 10upx;

		.sec-header {
			display: flex;
			align-items: center;
			font-size: $font-base;
			color: $font-color-dark;
			line-height: 40upx;
			margin-left: 30upx;

			.yticon {
				font-size: 44upx;
				color: #5eba8f;
				margin-right: 16upx;
				line-height: 40upx;
			}
		}

		.h-list {
			white-space: nowrap;
			padding: 30upx 30upx 0;

			image {
				display: inline-block;
				width: 160upx;
				height: 160upx;
				margin-right: 20upx;
				border-radius: 10upx;
			}
		}
	}

	.toolbar {
		margin-top: 10px;
		padding: 0 0 20upx 0;
		background-color: #fff;
		border-radius: 5px;

		.title {
			padding-top: 10upx;
			margin: 0 0 10upx 3%;
			font-size: 30upx;
			height: 80upx;
			display: flex;
			align-items: center;
		}

		.list {
			display: flex;
			flex-wrap: wrap;

			.box {
				width: 25%;
				margin-bottom: 30upx;

				.img {
					width: 23vw;
					height: 9vw;
					display: flex;
					justify-content: center;

					image {
						width: 6.5vw;
						height: 6.5vw;
					}
				}

				.text {
					width: 100%;
					display: flex;
					justify-content: center;
					font-size: 22upx;
					color: #3d3d3d;
				}
			}
		}
	}
</style>
