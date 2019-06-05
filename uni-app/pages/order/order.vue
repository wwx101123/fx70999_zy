<template>
	<view class="content">
		<view class="navbar">
			<view v-for="(item, index) in navList" :key="index" class="nav-item" :class="{current: tabCurrentIndex == index}"
			 @click="tabClick(index)">
				{{item.text}}
			</view>
		</view>

		<mescroll-uni @down="downCallback" @up="upCallback" @init="mescrollInit">
			<!-- 空白页 -->

			<!-- 订单列表 -->
			<view v-for="(item,index) in orderList" :key="index" class="order-item">
				<view class="i-top b-b">
					<text class="time">{{item.addtime_str}}</text>
					<text class="state" :style="{color: item.stateTipColor}">{{item.status_str}}</text>
					<!-- <text v-if="item.state===9" Aclass="del-btn yticon icon-iconfontshanchu1" @click="deleteOrder(index)"></text> -->
				</view>

				<!-- <scroll-view v-if="item.order_goods.length > 1" class="goods-box" scroll-x>
					<view v-for="(goodsItem, goodsIndex) in item.goodsList" :key="goodsIndex" class="goods-item">
						<image class="goods-img" :src="goodsItem.image" mode="aspectFill"></image>
					</view>
				</scroll-view> -->
				<view class="goods-box-single" v-for="(goodsItem, goodsIndex) in item.order_goods" :key="goodsIndex">
					<image class="goods-img" :src="goodsItem.icon" mode="aspectFill"></image>
					<view class="right">
						<text class="title clamp">{{goodsItem.goods_title}}</text>
						<!-- <text class="attr-box">
							 {{goodsItem.attr}} x  {{goodsItem.quantity}}</text>
						<text class="price">{{goodsItem.goods_price}}</text> -->
						<uni-view data-v-23b32da1="" class="price-number"><uni-view data-v-23b32da1="" class="price">{{goodsItem.goods_price}}</uni-view>x<uni-view
							 data-v-23b32da1="" class="number">{{goodsItem.quantity}}</uni-view>
						</uni-view>

					</view>
				</view> 

				<view class="price-box">
					共
					<text class="num">{{item.order_quantity}}</text>
					件商品 实付款
					<text class="price">{{item.order_amount}}</text>
				</view>
				<view class="action-box b-t" v-if="item.state != 9">
					 <button class="action-btn"v-if="item.status == 1 "  @click="cancelOrder(item)">取消订单</button>
					 
					 
					 <button class="action-btn recom"v-if="item.is_show_daifu == 1 "   @click="navToPage(item.help_pay_url)" >找人代付</button>
					 
					 
					<button class="action-btn recom"v-if="item.payment_status == 1 ">立即支付</button>
				</view>
			</view>



		</mescroll-uni>
	</view>
</template>

<script>
	// 自定义的mescroll-meituan.vue
	import MescrollUni from "../../components/mescroll-diy/mescroll-meituan.vue";
	import uniLoadMore from '@/components/uni-load-more/uni-load-more.vue';
	import empty from "@/components/empty";
	import common from '../../common/common.js';
	import Json from '@/Json';
	import {
		mapState
	} from 'vuex';
	export default {
		components: {
			MescrollUni,
			uniLoadMore,
			empty
		},
		computed: {
			...mapState(['hasLogin', 'userInfo', 'bi'])
		},
		data() {
			return {
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
				tabCurrentIndex:0,
				navList: [{
						state: 1,
						text: '全部',
						loadingType: 'more',
						orderList: []
					},
					{
						state: 2, 
						text: '待付款',
						loadingType: 'more',
						orderList: []
					},
					{
						state: 3,
						text: '待发货',
						loadingType: 'more',
						orderList: []
					},
					{
						state: 4,
						text: '待收货',
						loadingType: 'more',
						orderList: []
					},
					{
						state: 5,
						text: '已完成',
						loadingType: 'more',
						orderList: []
					}
				],
				userInfo: {},
				orderList: []
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
			/**
			 * 修复app端点击除全部订单外的按钮进入时不加载数据的问题
			 * 替换onLoad下代码即可
			 */
			console.log( options.state)
			  this.tabCurrentIndex =  options.state-1;
			// // #ifndef MP
			// this.loadData()
			// // #endif
			// // #ifdef MP
			// if (options.state == 0) {
			// 	this.loadData()
			// }
			// #endif

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
				this.getListDataFromNet(common.get_user_order, mescroll.num, mescroll.size, (curPageData) => {
					//curPageData=[]; //打开本行注释,可演示列表无任何数据empty的配置

					//联网成功的回调,隐藏下拉刷新和上拉加载的状态;
					//mescroll会根据传的参数,自动判断列表如果无任何数据,则提示空;列表无下一页数据,则提示无更多数据;
					console.log("mescroll.num=" + mescroll.num + ", mescroll.size=" + mescroll.size + ", curPageData.length=" +
						curPageData.length);

					//方法一(推荐): 后台接口有返回列表的总页数 totalPage
					//mescroll.endByPage(curPageData.length, totalPage); //必传参数(当前页的数据个数, 总页数)

					//方法二(推荐): 后台接口有返回列表的总数据量 totalSize
					//mescroll.endBySize(curPageData.length, totalSize); //必传参数(当前页的数据个数, 总数据量)

					//方法三(推荐): 您有其他方式知道是否有下一页 hasNext
					//mescroll.endSuccess(curPageData.length, hasNext); //必传参数(当前页的数据个数, 是否有下一页true/false)

					//方法四 (不推荐),会存在一个小问题:比如列表共有20条数据,每页加载10条,共2页.如果只根据当前页的数据个数判断,则需翻到第三页才会知道无更多数据,如果传了hasNext,则翻到第二页即可显示无更多数据.
					mescroll.endSuccess(curPageData.length);

					//设置列表数据
					if (mescroll.num == 1) that.orderList = []; //如果是第一页需手动制空列表
					that.orderList = that.orderList.concat(curPageData); //追加新数据
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
				//延时一秒,模拟联网
				setTimeout(() => {
					try {

						uni.request({
							url: url, //仅为示例，并非真实接口地址。
							data: {
								is_mobile: 1,
								user_id: that.userInfo.id,
								page_index: pageNum,
								page_size: pageSize,
								type: that.navList[that.tabCurrentIndex].state
							},
							method: 'POST',
							header: {
								'content-type': 'application/x-www-form-urlencoded'
							},
							success: function(res) {
								if (res.data.status == 1) {

									// that.orderList.push(res.data.data)

								} else {



								}
								if(res.data.data==null){
									res.data.data=[];
								}
								successCallback && successCallback(res.data.data);





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
				}, 500)
			},
			//详情
			navToPage(url) { 
				//测试数据没有写id，用title代替
				 
				uni.navigateTo({
					url: url
				})
			},



			//获取订单列表
			loadData(source) { 
				
			},

			//swiper 切换
			changeTab(e) {
				this.tabCurrentIndex = e.target.current;
				this.loadData('tabChange');
			},
			//顶部tab点击
			tabClick(index) {
				this.tabCurrentIndex = index;
				this.mescroll.triggerDownScroll()
			},
			//删除订单
			deleteOrder(index) {
				uni.showLoading({
					title: '请稍后'
				})
				setTimeout(() => {
					this.navList[this.tabCurrentIndex].orderList.splice(index, 1);
					uni.hideLoading();
				}, 600)
			},
			//取消订单
			cancelOrder(item) {
				uni.showLoading({
					title: '请稍后'
				});
				let that = this;
				uni.request({
					url: common.order_cancel, //仅为示例，并非真实接口地址。
					data: {
						is_mobile: 1,
						user_id: that.userInfo.id ,
						order_id: item.id 
					},
					method: 'POST',
					header: {
						'content-type': 'application/x-www-form-urlencoded'
					},
					success: function(res) {
						if (res.data.status == 1) {
				that.mescroll.triggerDownScroll()
							setTimeout(() => {
								 
							
								uni.hideLoading();
							}, 600)
				
						} else {
				
				
				
						} 
				
				
				
				
				
					}
				});
				
				
				
			},

			//订单状态文字和颜色
			orderStateExp(state) {
				let stateTip = '',
					stateTipColor = '#fa436a';
				switch (+state) {
					case 1:
						stateTip = '待付款';
						break;
					case 2:
						stateTip = '待发货';
						break;
					case 9:
						stateTip = '订单已关闭';
						stateTipColor = '#909399';
						break;

						//更多自定义
				}
				return {
					stateTip,
					stateTipColor
				};
			}
		},
	}
</script>

<style lang="scss">
	page,
	.content {
		background: $page-color-base;
		height: 100%;
	}

	.swiper-box {
		height: calc(100% - 40px);
	}

	.list-scroll-content {
		height: 100%;
	}

	.navbar {
		display: flex;
		height: 40px;
		padding: 0 5px;
		background: #fff;
		box-shadow: 0 1px 5px rgba(0, 0, 0, .06);
		position: relative;
		z-index: 10;

		.nav-item {
			flex: 1;
			display: flex;
			justify-content: center;
			align-items: center;
			height: 100%;
			font-size: 15px;
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
					width: 44px;
					height: 0;
					border-bottom: 2px solid $base-color;
				}
			}
		}
	}

	.uni-swiper-item {
		height: auto;
	}

	.order-item {
		display: flex;
		flex-direction: column;
		padding-left: 30upx;
		background: #fff;
		margin-top: 16upx;

		.i-top {
			display: flex;
			align-items: center;
			height: 80upx;
			padding-right: 30upx;
			font-size: $font-base;
			color: $font-color-dark;
			position: relative;

			.time {
				flex: 1;
			}

			.state {
				color: $base-color;
			}

			.del-btn {
				padding: 10upx 0 10upx 36upx;
				font-size: $font-lg;
				color: $font-color-light;
				position: relative;

				&:after {
					content: '';
					width: 0;
					height: 30upx;
					border-left: 1px solid $border-color-dark;
					position: absolute;
					left: 20upx;
					top: 50%;
					transform: translateY(-50%);
				}
			}
		}

		/* 多条商品 */
		.goods-box {
			height: 160upx;
			padding: 20upx 0;
			white-space: nowrap;

			.goods-item {
				width: 120upx;
				height: 120upx;
				display: inline-block;
				margin-right: 24upx;
			}

			.goods-img {
				display: block;
				width: 100%;
				height: 100%;
			}
		}
 .price-number  {
    position: absolute;
    bottom: 0;
    width: 80%;
    display: -webkit-box;
    display: -webkit-flex;
    display: -ms-flexbox;
    display: flex;
    -webkit-box-pack: end;
    -webkit-justify-content: flex-end;
    -ms-flex-pack: end;
    justify-content: flex-end;
    font-size: 9px;
    color: #333;
    display: flex;
    -webkit-box-align: end;
    -webkit-align-items: flex-end;
    -ms-flex-align: end;
    align-items: flex-end;
}
		/* 单条商品 */
		.goods-box-single {
			display: flex;
			padding: 20upx 0;

			.goods-img {
				display: block;
				width: 120upx;
				height: 120upx;
			}

			.right {
				flex: 1;
				display: flex;
				flex-direction: column;
				padding: 0 30upx 0 24upx;
				overflow: hidden;
				position: relative;

				.title {
					font-size: $font-base + 2upx;
					color: $font-color-dark;
					line-height: 1;
				}

				.attr-box {
					font-size: $font-sm + 2upx;
					color: $font-color-light;
					padding: 10upx 12upx;
				}

				.price {
					font-size: $font-base + 2upx;
					color: $font-color-dark;

					&:before {
						content: '￥';
						font-size: $font-sm;
						margin: 0 2upx 0 8upx;
					}
				}
			}
		}

		.price-box {
			display: flex;
			justify-content: flex-end;
			align-items: baseline;
			padding: 20upx 30upx;
			font-size: $font-sm + 2upx;
			color: $font-color-light;

			.num {
				margin: 0 8upx;
				color: $font-color-dark;
			}

			.price {
				font-size: $font-lg;
				color: $font-color-dark;

				&:before {
					content: '￥';
					font-size: $font-sm;
					margin: 0 2upx 0 8upx;
				}
			}
		}

		.action-box {
			display: flex;
			justify-content: flex-end;
			align-items: center;
			height: 100upx;
			position: relative;
			padding-right: 30upx;
		}

		.action-btn {
			width: 160upx;
			height: 60upx;
			margin: 0;
			margin-left: 24upx;
			padding: 0;
			text-align: center;
			line-height: 60upx;
			font-size: $font-sm + 2upx;
			color: $font-color-dark;
			background: #fff;
			border-radius: 100px;

			&:after {
				border-radius: 100px;
			}

			&.recom {
				background: #fff9f9;
				color: $base-color;

				&:after {
					border-color: #f7bcc8;
				}
			}
		}
	}


	/* load-more */
	.uni-load-more {
		display: flex;
		flex-direction: row;
		height: 80upx;
		align-items: center;
		justify-content: center
	}

	.uni-load-more__text {
		font-size: 28upx;
		color: #999
	}

	.uni-load-more__img {
		height: 24px;
		width: 24px;
		margin-right: 10px
	}

	.uni-load-more__img>view {
		position: absolute
	}

	.uni-load-more__img>view view {
		width: 6px;
		height: 2px;
		border-top-left-radius: 1px;
		border-bottom-left-radius: 1px;
		background: #999;
		position: absolute;
		opacity: .2;
		transform-origin: 50%;
		animation: load 1.56s ease infinite
	}

	.uni-load-more__img>view view:nth-child(1) {
		transform: rotate(90deg);
		top: 2px;
		left: 9px
	}

	.uni-load-more__img>view view:nth-child(2) {
		transform: rotate(180deg);
		top: 11px;
		right: 0
	}

	.uni-load-more__img>view view:nth-child(3) {
		transform: rotate(270deg);
		bottom: 2px;
		left: 9px
	}

	.uni-load-more__img>view view:nth-child(4) {
		top: 11px;
		left: 0
	}

	.load1,
	.load2,
	.load3 {
		height: 24px;
		width: 24px
	}

	.load2 {
		transform: rotate(30deg)
	}

	.load3 {
		transform: rotate(60deg)
	}

	.load1 view:nth-child(1) {
		animation-delay: 0s
	}

	.load2 view:nth-child(1) {
		animation-delay: .13s
	}

	.load3 view:nth-child(1) {
		animation-delay: .26s
	}

	.load1 view:nth-child(2) {
		animation-delay: .39s
	}

	.load2 view:nth-child(2) {
		animation-delay: .52s
	}

	.load3 view:nth-child(2) {
		animation-delay: .65s
	}

	.load1 view:nth-child(3) {
		animation-delay: .78s
	}

	.load2 view:nth-child(3) {
		animation-delay: .91s
	}

	.load3 view:nth-child(3) {
		animation-delay: 1.04s
	}

	.load1 view:nth-child(4) {
		animation-delay: 1.17s
	}

	.load2 view:nth-child(4) {
		animation-delay: 1.3s
	}

	.load3 view:nth-child(4) {
		animation-delay: 1.43s
	}

	@-webkit-keyframes load {
		0% {
			opacity: 1
		}

		100% {
			opacity: .2
		}
	}
</style>
