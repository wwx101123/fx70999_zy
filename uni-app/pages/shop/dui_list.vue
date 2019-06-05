<template>
	<view class=" ">
		<div class="main" v-title data-title="登录">
		</div>

		<wuc-tab :tab-list="tabList" :tabCur.sync="TabCur" tab-class="text-center bg-white wuc-tab " :tab-style="CustomBar"
		 select-class="text-blue tab" @change="tabChange"      style="background: white; position: fixed;top:80upx;z-index: 1;"></wuc-tab>


		<view class="goods-list" style="margin-top: 100upx;margin-bottom: 100upx;" >
			<view v-for="(item, index) in goodsList" :key="index" class="goods-item">
				<view class="image-wrapper">
					<image :src="item.icon" mode="aspectFill"></image>
				</view>
				<text class="title clamp">{{item.title}}</text>
				<view class="price-box">
					<text class="price">{{item.market_price}}</text>
					<view class="pb-car iconfont" :id="item.id" :data-img="item.icon" @tap="addShopCar">
						<image src="../../static/jiaru.png"></image>
					</view>
				</view>
			</view>
		</view>


		<view class="cate-mask" :class="cateMaskState===0 ? 'none' : cateMaskState===1 ? 'show' : ''" @click="toggleCateMask">
			<view class="cate-content" @click.stop.prevent="stopPrevent" @touchmove.stop.prevent="stopPrevent">
				<scroll-view scroll-y class="cate-list">
					<view v-for="item in cateList" :key="item.id">
						<view class="cate-item b-b two">{{item.title}}</view>
						<view v-for="tItem in item.child" :key="tItem.id" class="cate-item b-b" :class="{active: tItem.id==cateId}"
						 @click="changeCate(tItem)">
							{{tItem.title}}
						</view>
					</view>
				</scroll-view>
			</view>
		</view>
		<!-- 底部 -->
		<view class="footer">
			<view class="price-content">
				<text>已选择</text>
				<text class="price-tip">￥</text>
				<text class="price">{{totalAmount}}</text>
			</view>
			<text class="submit" @click="submit">查看兑换</text>
		</view>
		<!-- 加入购物车动画 cartx 和 carty 是购物车位置在屏幕位置的比例 例如左上角x0.1 y0.1 右下角 x0.9 y0.9-->
		<shopCarAnimation ref="carAnmation" cartx="0.1" carty="1.1"></shopCarAnimation>
	</view>
</template>

<script>
	import WucTab from '@/components/wuc-tab/wuc-tab.vue';
	import {
		obj2style
	} from '@/utils/index';
	import shopCarAnimation from '@/components/fly-in-cart/fly-in-cart.vue'
	import common from '../../common/common.js';
	import uniLoadMore from '@/components/uni-load-more/uni-load-more.vue';
	import empty from "@/components/empty";
	import {
		mapState
	} from 'vuex';
	export default {
		components: {
			shopCarAnimation,
			WucTab
		},
		computed: {
			CustomBar() {
				let style = {};
				// #ifdef MP-WEIXIN
				const systemInfo = uni.getSystemInfoSync();
				let CustomBar =
					systemInfo.platform === "android" ?
					systemInfo.statusBarHeight + 50 :
					systemInfo.statusBarHeight + 45;
				style['top'] = CustomBar + 'px';
				// #endif
				// #ifdef H5
				style['top'] = 0 + 'px';
				// #endif
				return obj2style(style);
			},
			...mapState(['hasLogin', 'userInfo', 'bi', 'goods_id'])
		},
		data() {
			return {
				TabCur: 0,
				tabList: [],
				cateMaskState: 0, //分类面板展开状态
				mescroll: null, //mescroll实例对象
				// 下拉刷新的配置
				downOption: {
					use: true, // 是否启用下拉刷新; 默认true
					auto: true, // 是否在初始化完毕之后自动执行下拉刷新的回调; 默认true
				},
				// 上拉加载的配置
				upOption: {
					use: false, // 是否启用上拉加载; 默认true
					auto: false
				},
				headerPosition: "fixed",
				headerTop: "0px",
				loadingType: 'more', //加载更多状态
				filterIndex: 0,
				Id: 0,
				cateId: 0, //已选三级分类id
				priceOrder: 0, //1 价格从低到高 2价格从高到低
				cateList: [],
				goodsList: [],
				totalAmount: 0,
				carouselList: [],
				userInfo: {},
			};
		},

		// 必须注册滚动到底部的事件,使上拉加载生效
		onReachBottom() {
			this.mescroll && this.mescroll.onReachBottom();
		},
		// 必须注册列表滚动事件,使下拉刷新生效
		onPageScroll(e) {
			this.mescroll && this.mescroll.onPageScroll(e);
		},
		onLoad(options) {
             


			let user_id = uni.getStorageSync('user_id');
			let userInfo = uni.getStorageSync('userInfo');
			this.userInfo = userInfo;
			// #ifdef H5
			this.headerTop = document.getElementsByTagName('uni-page-head')[0].offsetHeight + 'px';
			// #endif

			let that = this;

			console.log(common.dui_list)
			uni.request({
				url: common.dui_list, //仅为示例，并非真实接口地址。
				data: {
					is_mobile: 1,
					category_id: 0,
					page_index: 1,
					page_num: 100000,
					user_id: user_id,
					keyword: ''
				},
				method: 'POST',
				header: {
					'content-type': 'application/x-www-form-urlencoded'
				},
				success: function(res) {
					if (res.data.status == 1) {

						let cateList = res.data.category;
						that.tabList = res.data.category;
						that.goodsList = cateList[0].item_list;
						that.totalAmount = res.data.dui_cart_money;
 that.$refs.tab;
						// console.log(hotList)
						// that.hotList = hotList;
					} else {



					}



				}
			});





		},

		methods: {
			tabChange(index) {
				this.TabCur = index;

				this.goodsList = this.tabList[index].item_list;




			},

			//筛选点击
			tabClick(index) {
				if (this.filterIndex === index && index !== 2) {
					return;
				}
				this.filterIndex = index;
				if (index === 2) {
					this.priceOrder = this.priceOrder === 1 ? 2 : 1;
				} else {
					this.priceOrder = 0;
				}
				this.mescroll.triggerDownScroll()
				// this.loadData('refresh', 1);
				// uni.showLoading({
				// 	title: '正在加载'
				// })
			},
			//显示分类面板
			toggleCateMask(type) {
				let timer = type === 'show' ? 10 : 300;
				let state = type === 'show' ? 1 : 0;
				this.cateMaskState = 2;
				setTimeout(() => {
					this.cateMaskState = state;
				}, timer)
			},
			//分类点击
			changeCate(item) {
				this.cateId = item.id;
				this.toggleCateMask();
				uni.pageScrollTo({
					duration: 300,
					scrollTop: 0
				})

				this.mescroll.triggerDownScroll()

			},
			//详情
			navToDetailPage(item) {
				//测试数据没有写id，用title代替
				let id = item.id;
				uni.navigateTo({
					url: `/pages/product/product?id=${id}`
				})
			},
			stopPrevent() {},
			// 加入购物车
			addShopCar(e) {
				console.log('加入购物车');
				// 成功的话，调用加入购物车动画 
				let that = this;
				console.log(e.currentTarget.id);
				uni.request({
					url: common.dui_cart_goods_add, //仅为示例，并非真实接口地址。
					data: {
						"actiontype": 'add',
						"is_mobile": 1,
						"user_id": that.userInfo.id,
						"article_id": e.currentTarget.id,
						"goods_id": 0,
						"quantity": 1,
						"hot_id": 0
					},
					method: 'POST',
					header: {
						'content-type': 'application/x-www-form-urlencoded'
					},
					success: function(res) {
						console.log(res.data.status)
						if (res.data.status == 1) {


							that.$refs.carAnmation.touchOnGoods(e);


							that.totalAmount = res.data.amount;

							// that.detail(that.userInfo, that.goods_id);

						} else {



						}



					}
				});
			},
			submit() {

				uni.navigateTo({
					url: '/pages/shop/createOrder'
				})

			}
		},
	}
</script>

<style lang="scss">
	@import '../../common/css/pubu.css';

	page,
	.content {
	}

	.content {
		padding-top: 96upx;
	}

	.pb-car {
		float: right;
		color: #FD395B;
		width: 50upx;
		height: 50upx;
		border-radius: 50%;
		text-align: center;
		margin-right: 8upx;

		image {
			width: 100%;
			height: 100%;
		}
	}

	.navbar {
		position: fixed;
		left: 0;
		top: var(--window-top);
		display: flex;
		width: 100%;
		height: 80upx;
		background: #fff;
		box-shadow: 0 2upx 10upx rgba(0, 0, 0, .06);
		z-index: 10;

		.nav-item {
			flex: 1;
			display: flex;
			justify-content: center;
			align-items: center;
			height: 100%;
			font-size: 30upx;
			color: $font-color-dark;
			position: relative;

			&.current {
				color: $base-color;

				&:after {
					content: '';
					position: absolute;
					left: 50%;
					bottom: 0;
					transform: translateX(-50%);
					width: 120upx;
					height: 0;
					border-bottom: 4upx solid $base-color;
				}
			}
		}

		.p-box {
			display: flex;
			flex-direction: column;

			.yticon {
				display: flex;
				align-items: center;
				justify-content: center;
				width: 30upx;
				height: 14upx;
				line-height: 1;
				margin-left: 4upx;
				font-size: 26upx;
				color: #888;

				&.active {
					color: $base-color;
				}
			}

			.xia {
				transform: scaleY(-1);
			}
		}

		.cate-item {
			display: flex;
			justify-content: center;
			align-items: center;
			height: 100%;
			width: 80upx;
			position: relative;
			font-size: 44upx;

			&:after {
				content: '';
				position: absolute;
				left: 0;
				top: 50%;
				transform: translateY(-50%);
				border-left: 1px solid #ddd;
				width: 0;
				height: 36upx;
			}
		}
	}

	/* 分类 */
	.cate-mask {
		position: fixed;
		left: 0;
		top: var(--window-top);
		bottom: 0;
		width: 100%;
		background: rgba(0, 0, 0, 0);
		z-index: 95;
		transition: .3s;

		.cate-content {
			width: 630upx;
			height: 100%;
			background: #fff;
			float: right;
			transform: translateX(100%);
			transition: .3s;
		}

		&.none {
			display: none;
		}

		&.show {
			background: rgba(0, 0, 0, .4);

			.cate-content {
				transform: translateX(0);
			}
		}
	}

	.cate-list {
		display: flex;
		flex-direction: column;
		height: 100%;

		.cate-item {
			display: flex;
			align-items: center;
			height: 90upx;
			padding-left: 30upx;
			font-size: 28upx;
			color: #555;
			position: relative;
		}

		.two {
			height: 64upx;
			color: #303133;
			font-size: 30upx;
			background: #f8f8f8;
		}

		.active {
			color: $base-color;
		}
	}

	/* 商品列表 */
	.goods-list {
		display: flex;
		flex-wrap: wrap;
		background: #fff;

		.goods-item {
			display: flex;
			flex-direction: column;
			width: 46%;
			margin-top: 20upx;
			margin-left: 20upx;
			padding: 20upx;
			background: white;
			border-radius: 5px;
			-webkit-box-shadow: #c7c7c7 0px 0px 18px;
			-moz-box-shadow: #c7c7c7 0px 0px 18px;
			box-shadow: #c7c7c7 0px 0px 18px;

			&:nth-child(2n+1) {}
		}

		.image-wrapper {
			width: 100%;
			height: 330upx;
			border-radius: 3px;
			overflow: hidden;

			image {
				width: 100%;
				height: 100%;
				opacity: 1;
			}
		}

		.title {
			font-size:25upx;
			color: $font-color-dark;
			line-height: 80upx;
		}

		.price-box {
			display: flex;
			align-items: center;
			justify-content: space-between;
			padding-right: 10upx;
			font-size: 24upx;
			color: $font-color-light;
		}

		.price {
			font-size:25upx;
			color: $uni-color-primary;
			line-height: 1;

			&:before {
				content: '￥';
				font-size: 26upx;
			}
		}
	}

	.footer {
		position: fixed;
		left: 0;
		bottom: 0;
		z-index: 995;
		display: flex;
		align-items: center;
		width: 100%;
		height: 90upx;
		justify-content: space-between;
		font-size: 30upx;
		background-color: #fff;
		z-index: 998;
		color: $font-color-base;
		box-shadow: 0 -1px 5px rgba(0, 0, 0, .1);

		.price-content {
			padding-left: 30upx;
		}

		.price-tip {
			color: $base-color;
			margin-left: 8upx;
		}

		.price {
			font-size: 36upx;
			color: $base-color;
		}

		.submit {
			display: flex;
			align-items: center;
			justify-content: center;
			width: 280upx;
			height: 100%;
			color: #fff;
			font-size: 32upx;
			background-color: $base-color;
		}
	}
</style>
