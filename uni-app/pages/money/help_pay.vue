<template>
	<view class="app">
		<swiper class="screen-swiper" :class="dotStyle?'square-dot':'round-dot'" :indicator-dots="true" :circular="true"
		 :autoplay="true" interval="5000" duration="500">
			<swiper-item v-for="(item,index) in swiperList" :key="index">
				<image :src="item.url" mode="aspectFill" v-if="item.type=='image'"></image>
				<video :src="item.url" autoplay loop muted :show-play-btn="false" :controls="false" objectFit="cover" v-if="item.type=='video'"></video>
			</swiper-item>
		</swiper>
		<view class="cu-bar bg-white solid-bottom margin-top">
			<view class="action">
				<text class="icon-title text-orange"></text> 通过微信、QQ等将代付请求发送给好友,即可让您的好友为您买单!
			</view>
			<view class="action">
			</view>
		</view>
		<view class="price-box">
			<text>支付金额</text>
			<text class="price">{{orderInfo.order_amount}}</text>
		</view>
		<view class="cu-form-group  margin-top">
			<view class="title">捎一句话</view>
			<textarea></textarea>
		</view>
		<view class="pay-type-list">

			<view class="type-item b-b" @click="changePayType(0)">
				<image class="icon_logo" src="../../static/img/weact.png"></image>
				<view class="con">
					<text class="tit">微信分享</text>
					<text>推荐使用微信分享</text>
				</view>
				<label class="radio">
					<radio value="" color="#fa436a" :checked='payType ==0' />
					</radio>
				</label>
			</view>
			<view class="type-item b-b" @click="changePayType(1)">
				<image class="icon_logo" src="../../static/img/qq.png"></image>
				<view class="con">
					<text class="tit">QQ分享</text>
				</view>
				<label class="radio">
					<radio value="" color="#fa436a" :checked='payType ==1' />
					</radio>
				</label>
			</view>

		</view>

		<text class="mix-btn" @click="share">请好友帮我付款</text>
	</view>
</template>

<script>
	import common from '../../common/common.js';
	import {
		mapState
	} from 'vuex';
	export default {
		data() {
			return {
				order: {},
				type: 0,
				recommend_url: '',
				re_share_sub_title: '',
				re_share_title: '',
				payType: 0,
				orderInfo: {},
				swiperList: [],
				providerList: [],
				dotStyle: false,
				towerStart: 0,
				direction: ''
			}
		},
		computed: {

		},
		onLoad(options) {
			console.log(options.id)
			let that = this;
			uni.request({
				url: common.order_edit, //仅为示例，并非真实接口地址。
				data: {
					is_mobile: 1,
					id: options.id
				},
				method: 'POST',
				header: {
					'content-type': 'application/x-www-form-urlencoded'
				},
				success: function(res) {
					if (res.data.status == 1) {
						that.orderInfo = res.data.data;
						that.swiperList = res.data.swiperList;

						that.recommend_url = common.PreUrl + 'Goods/daifu/id/' + that.orderInfo.id;


						that.re_share_sub_title = res.data.re_pay_sub_title;
						that.re_share_title = res.data.re_pay_title;
						that.re_share_title = that.re_share_title.replace('{0}', that.userInfo.user_name);
						that.re_share_title = that.re_share_title.replace('{1}', that.orderInfo.order_amount);


					} else {



					}



				}
			});


			uni.getProvider({
				service: 'share',
				success: (e) => {
					let data = [];
					for (let i = 0; i < e.provider.length; i++) {
						switch (e.provider[i]) {
							case 'weixin':
								data.push({
									name: '分享到微信好友',
									id: 'weixin'
								})
								 
								 
								data.push({
									name: '分享到QQ',
									id: 'qq'
								})
								break;
							default:
								break;
						}
					}
					this.providerList = data;
				},
				fail: (e) => {
					console.log('获取登录通道失败' + JSON.stringify(e));
				}
			});
		},

		computed: {
			...mapState(['hasLogin', 'userInfo', 'bi'])
		},
		methods: {
			//选择支付方式
			changePayType(type) {
				this.payType = type;
				console.log(type)
			},
			//确认支付

			share() {
				var tapIndex = this.payType;
				if (this.providerList.length === 0) {
					uni.showModal({
						title: '当前环境无分享渠道!',
						showCancel: false
					})
					return;
				}
				let itemList = this.providerList.map(function(value) {
					return value.name
				})

				let that = this;
				// uni.showActionSheet({
				// 	itemList: itemList,
				// 	success: (res) => {
				// 		console.log(this.providerList[res.tapIndex].id)
				// 		if (this.providerList[res.tapIndex].id == 'qq') {
				// 			this.type = 1
				// 		} else {
				// 			this.type = 0
				// 		}
				console.log(that.providerList[tapIndex].id)
				if (that.providerList[tapIndex].id == 'qq') {
					that.type = 1
				} else {
					that.type = 0
				}
				uni.share({
					provider: that.providerList[tapIndex].id,
					scene: that.providerList[tapIndex].type && that.providerList[tapIndex].type === 'WXSenceTimeline' ?
						'WXSenceTimeline' : "WXSceneSession",
					type: that.type,
					title: that.re_share_title,
					summary: that.re_share_sub_title,
					imageUrl: '../../static/logo.png',
					href: that.recommend_url,
					success: (res) => {
						console.log("success:" + JSON.stringify(res));
					},
					fail: (e) => {
						uni.showModal({
							content: e.errMsg,
							showCancel: false
						})
					}
				});
				// 	}
				// })




			},
		}
	}
</script>

<style lang='scss'>
	.app {
		width: 100%;
	}

	.icon_logo {
		width: 20px;
		height: 20px;
		margin-right: 10px
	}

	.price-box {
		background-color: #fff;
		height: 265upx;
		display: flex;
		flex-direction: column;
		justify-content: center;
		align-items: center;
		font-size: 28upx;
		color: #909399;

		.price {
			font-size: 50upx;
			color: #303133;
			margin-top: 12upx;

			&:before {
				content: '￥';
				font-size: 40upx;
			}
		}
	}

	.pay-type-list {
		margin-top: 20upx;
		background-color: #fff;
		padding-left: 60upx;

		.type-item {
			height: 120upx;
			padding: 20upx 0;
			display: flex;
			justify-content: space-between;
			align-items: center;
			padding-right: 60upx;
			font-size: 30upx;
			position: relative;
		}

		.icon {
			width: 100upx;
			font-size: 52upx;
		}

		.icon-erjiye-yucunkuan {
			color: #fe8e2e;
		}

		.icon-weixinzhifu {
			color: #36cb59;
		}

		.icon-alipay {
			color: #01aaef;
		}

		.tit {
			font-size: $font-lg;
			color: $font-color-dark;
			margin-bottom: 4upx;
		}

		.con {
			flex: 1;
			display: flex;
			flex-direction: column;
			font-size: $font-sm;
			color: $font-color-light;
		}
	}

	.mix-btn {
		display: flex;
		align-items: center;
		justify-content: center;
		width: 630upx;
		height: 80upx;
		margin: 80upx auto 30upx;
		font-size: $font-lg;
		color: #fff;
		background-color: $base-color;
		border-radius: 10upx;
		box-shadow: 1px 2px 5px rgba(219, 63, 96, 0.4);
	}
</style>
