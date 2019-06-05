<template>
	<view>
		<view class="banner">
			<image class="banner-img" :src="banner.icon"></image>
			<view class="banner-title">{{banner.title}}</view>
		</view>
		<view class="article-meta">
			<text class="article-author">{{banner.author_name}}</text>
			<text class="article-text">发表于</text>
			<text class="article-time">{{banner.time_str}}</text>
		</view>
		<view class="article-content">
			<rich-text :nodes="htmlString"></rich-text>
		</view>
		<!-- #ifdef MP-WEIXIN -->
		<ad v-if="htmlString" unit-id="adunit-01b7a010bf53d74e"></ad>
		<!-- #endif -->
	</view>
</template>

<script>
	import common from '../../common/common.js';
	import {
		mapState
	} from 'vuex';
	export default {
		computed: {
			...mapState(['hasLogin', 'userInfo', 'bi'])
		},
		data() {
			return {
				title: 'list-triplex-row',
				banner: {},
				userInfo: {},
				htmlString: ""
			}
		},
		onShareAppMessage() {
			return {
				title: this.banner.title,
				path: '/pages/template/list2detail-detail/list2detail-detail?detailDate=' + JSON.stringify(this.banner)
			}
		},
		onLoad(event) {
			// 目前在某些平台参数会被主动 decode，暂时这样处理。

			this.getDetail(event.id);
			 
		},
		methods: {
			getDetail(id) {
			let user_id = uni.getStorageSync('user_id');
			let userInfo = uni.getStorageSync('userInfo');
			this.userInfo = userInfo;
				let that=this;
						console.log(that.userInfo.id)
				uni.request({
					url: common.form_detail,
					data: {
						is_mobile: 1,
						user_id:  that.userInfo.id,
						id: id 
					},
							method: 'POST',
							header: {
								'content-type': 'application/x-www-form-urlencoded'
							},
					success: (res) => {
						console.log(JSON.stringify(res))
						if (res.data.status == 1) {
							console.log( res.data.data.content)
							that.htmlString = res.data.data.content ;
							that.banner = res.data.data;
						}
					},
					fail: () => {
						console.log('fail');
					}
				})
			}
		}
	}
</script>

<style>
	view {
		font-size: 28upx;
		line-height: 1.8;
	}

	.banner {
		height: 360upx;
		overflow: hidden;
		position: relative;
		background-color: #ccc;
	}

	.banner-img {
		width: 100%;
	}

	.banner-title {
		max-height: 84upx;
		overflow: hidden;
		position: absolute;
		left: 30upx;
		bottom: 30upx;
		width: 90%;
		font-size: 32upx;
		font-weight: 400;
		line-height: 42upx;
		color: white;
		z-index: 11;
	}

	.article-meta {
		padding: 20upx 40upx;
		display: flex;
		flex-direction: row;
		justify-content: flex-start;
		color: gray;
	}

	.article-text {
		font-size: 26upx;
		line-height: 50upx;
		margin: 0 20upx;
	}

	.article-author,
	.article-time {
		font-size: 30upx;
	}

	.article-content {
		padding: 0 30upx;
		overflow: hidden;
		font-size: 30upx;
		margin-bottom: 30upx;
	}
</style>
