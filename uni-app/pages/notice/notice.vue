<template>
	<view>
		<mescroll-uni @down="downCallback" @up="upCallback" @init="mescrollInit">
		<view class="notice-item" v-for="(item,index) in goodsList" :key="index">
			<text class="time">{{item.addtime_str}}</text>
			<view class="content">
				<text class="title">{{item.title}}</text>
				<view class="img-wrapper">
					<image class="pic"    :src="item.icon"></image>
				</view>
				<!-- <text class="introduce">
					虽然做了一件好事，但很有可能因此招来他人的无端猜测，例如被质疑是否藏有其他利己动机等，乃至谴责。即便如此，还是要做好事。
				</text> -->
				<view class="bot b-t"@click="navToDetailPage(item)">
					<text>查看详情</text>
					<text class="more-icon yticon icon-you"></text>
				</view>
			</view>
		</view>
		</mescroll-uni>
		<!-- <view class="notice-item">
			<text class="time">昨天 12:30</text>
			<view class="content">
				<text class="title">新品上市，全场满199减50</text>
				<view class="img-wrapper">
					<image class="pic" src="https://ss1.bdstatic.com/70cFvXSh_Q1YnxGkpoWK1HF6hhy/it/u=3761064275,227090144&fm=26&gp=0.jpg"></image>
					<view class="cover">
						活动结束
					</view>
				</view>
				<view class="bot b-t">
					<text>查看详情</text>
					<text class="more-icon yticon icon-you"></text>
				</view>
			</view>
		</view>
		<view class="notice-item">
			<text class="time">2019-07-26 12:30</text>
			<view class="content">
				<text class="title">新品上市，全场满199减50</text>
				<view class="img-wrapper">
					<image class="pic" src="https://timgsa.baidu.com/timg?image&quality=80&size=b9999_10000&sec=1556465765776&di=57bb5ff70dc4f67dcdb856e5d123c9e7&imgtype=0&src=http%3A%2F%2Fimg.zcool.cn%2Fcommunity%2F01fd015aa4d95fa801206d96069229.jpg%401280w_1l_2o_100sh.jpg"></image>
					<view class="cover">
						活动结束
					</view>
				</view>
				<text class="introduce">新品上市全场2折起，新品上市全场2折起，新品上市全场2折起，新品上市全场2折起，新品上市全场2折起</text>
				<view class="bot b-t">
					<text>查看详情</text>
					<text class="more-icon yticon icon-you"></text>
				</view>
			</view>
		</view> -->
	</view>
</template>

<script>
	import MescrollUni from "../../components/mescroll-diy/mescroll-meituan.vue";
	import common from '../../common/common.js';
	import uniLoadMore from '@/components/uni-load-more/uni-load-more.vue';
	import empty from "@/components/empty";
	import {
		mapState
	} from 'vuex';
	export default {
		components: {
			MescrollUni
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
				headerPosition: "fixed",
				headerTop: "0px",
				loadingType: 'more', //加载更多状态
				filterIndex: 0,
				Id: 0,
				cateId: 0, //已选三级分类id
				priceOrder: 0, //1 价格从低到高 2价格从高到低
				cateList: [],
				goodsList: []
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
				console.log(common.adminnewslist)
				//联网加载数据
				this.getListDataFromNet(common.adminnewslist, mescroll.num, mescroll.size, (curPageData) => {
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
					if (mescroll.num == 1) that.goodsList = []; //如果是第一页需手动制空列表
					that.goodsList = that.goodsList.concat(curPageData); //追加新数据
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
								user_id: 0,
								page_index: pageNum,
								page_size: pageSize,
								keyword: '',
								type: 0
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
				// this.toggleCateMask();
				// uni.pageScrollTo({
				// 	duration: 300,
				// 	scrollTop: 0
				// })
				// this.loadData('refresh', 1);

			},
			//详情
			navToDetailPage(item) {
				//测试数据没有写id，用title代替
				let id = item.id;
				uni.navigateTo({
					url: `/pages/notice/detail?id=${id}`
				})
			},
			stopPrevent() {}
		},
	}
</script>

<style lang='scss'>
	page {
		background-color: #f7f7f7;
		padding-bottom: 30upx;
	}

	.notice-item {
		display: flex;
		flex-direction: column;
		align-items: center;
	}

	.time {
		display: flex;
		align-items: center;
		justify-content: center;
		height: 80upx;
		padding-top: 10upx;
		font-size: 26upx;
		color: #7d7d7d;
	}

	.content {
		width: 710upx;
		padding: 0 24upx;
		background-color: #fff;
		border-radius: 4upx;
	}

	.title {
		display: flex;
		align-items: center;
		height: 90upx;
		font-size: 32upx;
		color: #303133;
	}

	.img-wrapper {
		width: 100%;
		height: 260upx;
		position: relative;
	}

	.pic {
		display: block;
		width: 100%;
		height: 100%;
		border-radius: 6upx;
	}

	.cover {
		display: flex;
		justify-content: center;
		align-items: center;
		position: absolute;
		left: 0;
		top: 0;
		width: 100%;
		height: 100%;
		background-color: rgba(0, 0, 0, .5);
		font-size: 36upx;
		color: #fff;
	}

	.introduce {
		display: inline-block;
		padding: 16upx 0;
		font-size: 28upx;
		color: #606266;
		line-height: 38upx;
	}

	.bot {
		display: flex;
		align-items: center;
		justify-content: space-between;
		height: 80upx;
		font-size: 24upx;
		color: #707070;
		position: relative;
	}

	.more-icon {
		font-size: 32upx;
	}
</style>
