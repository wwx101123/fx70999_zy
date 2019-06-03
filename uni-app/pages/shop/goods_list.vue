<template>
	<view class="content">
		<div class="main" v-title data-title="登录">
		</div>
		<view class="navbar" :style="{position:headerPosition,top:headerTop}">
			<view class="nav-item" :class="{current: filterIndex === 0}" @click="tabClick(0)">
				综合排序
			</view>
			<view class="nav-item" :class="{current: filterIndex === 1}" @click="tabClick(1)">
				销量优先
			</view>
			<view class="nav-item" :class="{current: filterIndex === 2}" @click="tabClick(2)">
				<text>价格</text>
				<view class="p-box">
					<text :class="{active: priceOrder === 1 && filterIndex === 2}" class="yticon icon-shang"></text>
					<text :class="{active: priceOrder === 2 && filterIndex === 2}" class="yticon icon-shang xia"></text>
				</view>
			</view>
			<text class="cate-item yticon icon-fenlei1" @click="toggleCateMask('show')"></text>
		</view>

		<mescroll-uni @down="downCallback" @up="upCallback" @init="mescrollInit">
			<view class="goods-list">
				<view v-for="(item, index) in goodsList" :key="index" class="goods-item">
					<view class="image-wrapper">
						<image :src="item.icon" mode="aspectFill"></image>
					</view>
					<text class="title clamp">{{item.title}}</text>
					<view class="price-box">
						<text class="price">{{item.price}}</text>
						<view class="iconfont" :id="item.id" :data-img="item.icon" @tap="addShopCar">
							库存:{{item.stock}}
						</view>
					</view>
					<view class="flex margin-top">
						<view class="cu-progress round">
							<view class="bg-green" :style="[{ width:loading?item.percent+'%':''}]"></view>
						</view>
						<text class="margin-left">{{item.percent}}%</text>
					</view>
				</view>
			</view>
		</mescroll-uni>

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
				<text>商品总数</text>
				<text class="price-tip"></text>
				<text class="price">{{totalCount}}</text>
				
				
				<text>商品总金额</text>
				<text class="price-tip"></text>
				<text class="price">{{totalAmount}}</text>
			</view>
		</view>
		<!-- 加入购物车动画 cartx 和 carty 是购物车位置在屏幕位置的比例 例如左上角x0.1 y0.1 右下角 x0.9 y0.9-->
		<shopCarAnimation ref="carAnmation" cartx="0.1" carty="1.1"></shopCarAnimation>
	</view>
</template>

<script>
	import shopCarAnimation from '@/components/fly-in-cart/fly-in-cart.vue'
	import MescrollUni from "../../components/mescroll-diy/mescroll-meituan.vue";
	import common from '../../common/common.js';
	import uniLoadMore from '@/components/uni-load-more/uni-load-more.vue';
	import empty from "@/components/empty";
	import {
		mapState
	} from 'vuex';
	export default {
		components: {
			MescrollUni,
			shopCarAnimation
		},
		computed: {
			...mapState(['hasLogin', 'userInfo', 'bi', 'goods_id'])
		},
		data() {
			return {
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
				totalCount: 0,totalAmount:0,
				loading: true,
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
			wx.setNavigationBarTitle({
				title: options.title
			})

			// #ifdef H5
			this.headerTop = document.getElementsByTagName('uni-page-head')[0].offsetHeight + 'px';
			// #endif
			this.cateId = options.tid;
			this.Id = options.sid;
		},

		methods: {

			// mescroll组件初始化的回调,可获取到mescroll对象
			mescrollInit(mescroll) {
				this.mescroll = mescroll;
			},
			// 下拉刷新的回调
			downCallback(mescroll) {
				mescroll.resetUpScroll() // 重置列表为第一页 (自动执行 mescroll.num=1, 再触发upCallback方法 )
			},
			/*上拉加载的回调: mescroll携带page的参数, 其中num:当前页 从1开始, size:每页数据条数,默认10 */
			upCallback(mescroll) {
				let that = this;
				//联网加载数据
				this.getListDataFromNet(common.get_goods_listUrl, mescroll.num, mescroll.size, (curPageData) => {
					//curPageData=[]; //打开本行注释,可演示列表无任何数据empty的配置

					//联网成功的回调,隐藏下拉刷新和上拉加载的状态;
					//mescroll会根据传的参数,自动判断列表如果无任何数据,则提示空;列表无下一页数据,则提示无更多数据;


					//方法一(推荐): 后台接口有返回列表的总页数 totalPage
					//mescroll.endByPage(curPageData.length, totalPage); //必传参数(当前页的数据个数, 总页数)

					//方法二(推荐): 后台接口有返回列表的总数据量 totalSize
					//mescroll.endBySize(curPageData.length, totalSize); //必传参数(当前页的数据个数, 总数据量)

					//方法三(推荐): 您有其他方式知道是否有下一页 hasNext
					//mescroll.endSuccess(curPageData.length, hasNext); //必传参数(当前页的数据个数, 是否有下一页true/false)

					//方法四 (不推荐),会存在一个小问题:比如列表共有20条数据,每页加载10条,共2页.如果只根据当前页的数据个数判断,则需翻到第三页才会知道无更多数据,如果传了hasNext,则翻到第二页即可显示无更多数据.
					mescroll.endSuccess(curPageData.data.length);

					//设置列表数据
					if (mescroll.num == 1) that.goodsList = []; //如果是第一页需手动制空列表
					that.goodsList = that.goodsList.concat(curPageData.data); //追加新数据

					that.cateList = curPageData.category;
					console.log(curPageData.dui_cart_money)
					that.totalCount = curPageData.current_count;
					that.totalAmount = curPageData.totalAmount;


				}, () => {
					//联网失败的回调,隐藏下拉刷新的状态
					mescroll.endErr();
				}) 
			},
			/*联网加载列表数据
			在您的实际项目中,请参考官方写法: http://www.mescroll.com/uni.html#tagUpCallback
			请忽略getListDataFromNet的逻辑,这里仅仅是在本地模拟分页数据,本地演示用
			实际项目以您服务器接口返回的数据为准,无需本地处理分页.
			* */
			getListDataFromNet(url, pageNum, pageSize, successCallback, errorCallback) {
				let that = this;
				console.log(that.filterIndex)
				//延时一秒,模拟联网
				setTimeout(() => {
					try {

						uni.request({
							url: url, //仅为示例，并非真实接口地址。
							data: {
								is_mobile: 1,
								category_id: that.cateId,
								user_id: that.userInfo.id,
								page_index: pageNum,
								page_size: pageSize,
								keyword: '',
								goods_type: 0,
								order: that.filterIndex,
								priceOrder: that.priceOrder
							},
							method: 'POST',
							header: {
								'content-type': 'application/x-www-form-urlencoded'
							},
							success: function(res) {
								console.log(res.data.data)
								if (res.data.status == 1) {

									// that.orderList.push(res.data.data)

								} else {



								}
								if (res.data.data == null) {
									res.data.data = [];
								}
								successCallback && successCallback(res.data);





							}
						});

						// 
						// //模拟分页数据
						// var listData = [];
						// for (var i = (pageNum - 1) * pageSize; i < pageNum * pageSize; i++) {
						// 	if (i == mockData.length) break;
						// 	listData.push(mockData[i]);
						// }
						// //联网成功的回调
					} catch (e) {
						//联网失败的回调
						errorCallback && errorCallback();
					}
				}, 10)
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
		background: $page-color-base;
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
		padding: 0 30upx;
		background: #fff;

		.goods-item {
			display: flex;
			flex-direction: column;
			width: 48%;
			padding-bottom: 40upx;

			&:nth-child(2n+1) {
				margin-right: 4%;
			}
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
			font-size: $font-lg;
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
			font-size: $font-lg;
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
