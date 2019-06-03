<template>
	<view class="container">
		<view class="list-cell b-b m-t" @click="navTo('/pages/userinfo/userinfo')" hover-class="cell-hover" :hover-stay-time="50">
			<text class="cell-tit">个人资料</text>
			<text class="cell-more yticon icon-you"></text>
		</view>
		<view class="list-cell b-b" @click="navTo('收货地址')" hover-class="cell-hover" :hover-stay-time="50">
			<text class="cell-tit">收货地址</text>
			<text class="cell-more yticon icon-you"></text>
		</view>
		<view class="list-cell" @click="navTo('实名认证')" hover-class="cell-hover" :hover-stay-time="50">
			<text class="cell-tit">实名认证</text>
			<text class="cell-more yticon icon-you"></text>
		</view>

		<view class="list-cell m-t">
			<text class="cell-tit">消息推送</text>
			<switch checked color="#fa436a" @change="switchChange" />
		</view>
		<view class="list-cell m-t b-b" @click="navTo('清除缓存')" hover-class="cell-hover" :hover-stay-time="50">
			<text class="cell-tit">清除缓存</text>
			<text class="cell-more yticon icon-you"></text>
		</view>
		<view class="list-cell b-b" @click="navTo('关于Dcloud')" hover-class="cell-hover" :hover-stay-time="50">
			<text class="cell-tit">关于Dcloud</text>
			<text class="cell-more yticon icon-you"></text>
		</view>
		<view class="list-cell " v-show="isShow" @click="update_version">
			<text class="cell-tit">检查更新</text>
			<text class="cell-tip">{{app_version}}</text>
			<text class="cell-more yticon icon-you"></text>
		</view>
		<view class="list-cell log-out-btn" @click="toLogout">
			<text class="cell-tit">退出登录</text>
		</view>
	</view>
</template>

<script>
	import common from '../../common/common.js'
	import autoUpdater from '../../common/autoUpdater.min.js'
	import {
		mapMutations
	} from 'vuex';
	export default { 
		data() {
			return {
				app_version: "",
				isShow: false,
			};
		},
		onLoad() {
			let that = this;

			console.log(common.get_client())

			if (common.get_client() == 'android' || common.get_client() == 'ios') {

				const res = uni.getSystemInfoSync();
				console.log(JSON.stringify(res));
				//判断app还是小程序
				if (res.brand == ''||res.brand == 'iPhone') {
					that.show_update(true)
				} else {
					that.show_update(false)
				}



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
									user_id: 1,
									IP: common.IP
								},
								method: 'POST',
								header: {
									'content-type': 'application/x-www-form-urlencoded'
								},
								success: function(res) {
									console.log(res.data.status)
									if (res.data.status == 1) {
										that.app_version = '有新版本';
										const update_tips = res.data.update_tip.join("\n");

										that.init_update_version(update_tips, res.data.url);
									} else {
										that.app_version = '最新版';

									}



								}
							});
						}
					});
				});
			} else {

				// this.update_version_view.hide();
			}
		},
		methods: {
			...mapMutations(['logout']),

			navTo(url) {
				uni.navigateTo({  
					url
				})   
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
								uni.navigateBack();
							}, 200)
						}
					}
				})
			},
			//switch
			switchChange(e) {
				console.log(e.detail)
				let statusTip = e.detai ? '打开' : '关闭';
				this.$api.msg(`${statusTip}消息推送`);
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

				autoUpdater.show();

			},
			show_update: function(client) {
				// const is_view_show=true;
				// if (client == 'ios' || client == 'android') {
				//  
				// } else {
				// 	is_view_show =false;
				// }
				console.log(client)
				this.isShow = client;
			}
		}
	}
</script>

<style lang='scss'>
	page {
		background: $page-color-base;
	}

	.list-cell {
		display: flex;
		align-items: baseline;
		padding: 20upx $page-row-spacing;
		line-height: 60upx;
		position: relative;
		background: #fff;
		justify-content: center;

		&.log-out-btn {
			margin-top: 40upx;

			.cell-tit {
				color: $uni-color-primary;
				text-align: center;
				margin-right: 0;
			}
		}

		&.cell-hover {
			background: #fafafa;
		}

		&.b-b:after {
			left: 30upx;
		}

		&.m-t {
			margin-top: 16upx;
		}

		.cell-more {
			align-self: baseline;
			font-size: $font-lg;
			color: $font-color-light;
			margin-left: 10upx;
		}

		.cell-tit {
			flex: 1;
			font-size: $font-base + 2upx;
			color: $font-color-dark;
			margin-right: 10upx;
		}

		.cell-tip {
			font-size: $font-base;
			color: $font-color-light;
		}

		switch {
			transform: translateX(16upx) scale(.84);
		}
	}
</style>
