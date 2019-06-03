<template>

	<view class="index" >
		<mescroll-uni @down="downCallback" @up="upCallback" @init="mescrollInit">
		<!-- 筛选组件调用 --> 
		<!-- 数据列表 -->
		<view class="list-box ">
			<view class="history-section icon" v-for="(item,index) in orderList" :key="index">
				<view class="sec-header">
					<text class="yticon icon-lishijilu"></text>
					<text>{{item.month_str}}</text>
					<text style="position: absolute;right: 50upx;">支出:{{item.sum2}} 收入:{{item.sum1}}</text>
				</view>
				
				<view class="container_of_slide" v-for="(item1,index1) in item.list" :key="index1">
				<view class="slide_list" 
				  :style="{transform:'translate3d('+item1.slide_x+'px, 0, 0)'}">
					<view class="now-message-info" hover-class="uni-list-cell-hover" :style="{width:Screen_width+'px'}" @click="getDetail(item1)">
						<view class="icon-circle" style="background: url(../../static/logo.png);background-size: 100% 100%;" ></view>
						<view class="list-right">
							<view class="list-title"  >{{item1.bz}}</view>
							<view class="list-detail">{{item1.time_str}}</view>
						</view>
						<view class="list-right-1">
							{{item1.epoints}}
						</view>
					</view>
					</view>
					  
				</view>
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
				navList: [ 
				],
				orderList: [],
				type:''
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
			 	title: options.title+'明细'
			 })
			 
			this.type= options.type;

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
				this.getListDataFromNet(common.pointflowslist, mescroll.num, mescroll.size, (curPageData) => {
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
								type: that.type
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



			//获取订单列表
			loadData(source) { 
				
			},

			//swiper 切换
			changeTab(e) { },
			//顶部tab点击
			tabClick(index) { },
			//删除订单
			deleteOrder(index) { },
			//取消订单
			cancelOrder(item) { },

			//订单状态文字和颜色
			orderStateExp(state) { }
		},
	}
</script>

<style lang='scss'>
	
	.history-section {
		background: #d5d8db;
		border-radius: 10upx;
	
		.sec-header {
			display: flex;
			align-items: center;
			font-size: $font-base;
			color: $font-color-dark;
			height:120upx;
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
	.index{
		background: #F8F8F8;
	}
	.list-box{
	}
	.container_of_slide {
		width: 100%;
		overflow: hidden;
	}

	.slide_list {
		transition: all 100ms;
		transition-timing-function: ease-out;
		min-width: 200%;
		height: 160upx;
	}

	.now-message-info {
		box-sizing:border-box;
		display: flex;
		align-items: center;
		/* justify-content: space-between; */
		font-size: 16px;
		clear:both;
		height: 160upx;
		padding: 0 30upx;
		margin-bottom: 0upx;
		background: #FFFFFF;
		width: 100%;
	}
	.now-message-info,
	.group-btn {
		float: left;
	}

	.group-btn {
		display: flex;
		flex-direction: row;
		height: 160upx;
		min-width: 100upx;
		align-items: center;

	}

	.group-btn .btn-div {
		height: 160upx;
		color: #fff;
		text-align: center;
		padding: 0 50upx;
		font-size: 34upx;
		line-height: 160upx;
	}

	.group-btn .top {
		background-color: #c4c7cd;
	}

	.group-btn .removeM {
		background-color: #ff3b32;
	}
	
	
	.icon-circle{
		background: #3396fb;
		border-radius: 100%;
		width:100upx;
		height: 100upx;
		line-height:100upx;
		text-align:center;
		color: #FFFFFF;
		font-weight: bold;
		font-size: 20px;
		float: left;
	}
	.list-right{
		float: left;
		margin-left: 25upx;
		margin-right: 30upx;
	}
	.list-right-1{
		float: right;
		color: #A9A9A9;
	}
	.list-title{
		width: 350upx;
		line-height:1.5;
		overflow:hidden;
		margin-bottom: 10upx;
		color:#333;
		display:-webkit-box;
		-webkit-box-orient:vertical;
		-webkit-line-clamp:1;
		overflow:hidden;
	}
	.list-detail{
		width: 350upx;
		font-size: 14px;
		color: #a9a9a9;
		display:-webkit-box;
		-webkit-box-orient:vertical;
		-webkit-line-clamp:1;
		overflow:hidden;
	}
	.button-box{
		box-sizing: border-box;
		position: fixed;
		left: 0;
		bottom: 0;
		width: 100%;
		z-index: 998;
		background: #F8F8F8;
	}
	.btn-sub{
		display: -webkit-box;
		display: -webkit-flex;
		display: flex;
		-webkit-box-pack: center;
		-webkit-justify-content: center;
		justify-content: center;
		-webkit-box-align: center;
		-webkit-align-items: center;
		align-items: center;
		-webkit-box-orient: vertical;
		-webkit-box-direction: normal;
		float: left;
		width: 100%;
		height: 100upx;
		background: #F8F8F8;
		color: #7fb2ff;
	}
	.btn-plusempty{
		width: 110upx;
		height: 110upx;
		background: #007bfa;
		position: fixed;
		bottom: 50upx;
		right: 20upx;
		border-radius: 100%;
		overflow: hidden;
		text-align: center;
		line-height: 110upx;
	}
	.empty{
		color: #999999;
	}
	.plusempty-img{
		width: 50upx;
		height: 50upx;
		margin-top: 30upx;
	}
	.scan-box{
		display:block;
		position:fixed;
		top:0;
		bottom:0;
		left:0;
		right:0;
		background-color:rgba(0, 0, 0, 0.3);
		z-index:99999;
	}
	.scan-item{
		display:-webkit-box;
		display:-webkit-flex;
		display:-ms-flexbox;
		display:flex;
		position:relative;
		margin:0 auto;
		width:80%;
		height:100%;
		-webkit-box-pack:center;
		-webkit-justify-content:center;
		-ms-flex-pack:center;
		justify-content:center;
		-webkit-box-align:center;
		-webkit-align-items:center;
		-ms-flex-align:center;
		align-items:center;
		-webkit-box-sizing:border-box;
		box-sizing:border-box;
		opacity:1;

	}
	.scan-content{
		position:relative;
		width: 400upx;
		height: 500upx;
		background: #FFFFFF;
		border-radius: 20upx;
	}
	.scan-icon{
		position: absolute;
		top: -50upx;
		left: 150upx;
		width: 100upx;
		height: 100upx;
		border-radius: 100%;
		box-sizing:border-box;
		background: #FFFFFF;
		padding: 20upx;
	}
	.scan-icon-img{
		width: 100%;
		height: 100%;
	}
	.scan-text{
		position: absolute;
		bottom: 40upx;
		left: 0;
		width: 100%;
		text-align: center;
		font-size: 14px;
	}
	.scan-share{
		width: 100%;
		height: 100%;
	}
	.scan-img{
		width: 300upx;
		height: 300upx;
		margin: auto;
		display: block;
		position: absolute;
		top: 80upx;
		left: 50upx;
		z-index: 99;
	}
	.scan-btn{
		top:-30upx;
		left:auto;
		right:-30upx;
		bottom:auto;
		position:absolute;
		width:64upx;
		height:64upx;
		border-radius:50%;
		z-index:99999;
		display: flex;
	}
	.uni-list-cell-hover {
		background-color: #eeeeee;
	}
	.bottom-btn-hover{
		background: #0564c7!important;;
	}
</style>
