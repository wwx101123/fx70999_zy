<template>
	<view class="container">
		<mescroll-uni @down="downCallback" :up="upOption" @init="mescrollInit">
			<!-- 空白页 -->
			<view v-if="!hasLogin || empty===true" class="empty">
				<image src="/static/emptyCart.jpg" mode="aspectFit"></image>
				<view v-if="hasLogin" class="empty-tips">
					空空如也
					<!-- <navigator class="navigator" v-if="hasLogin" url="../index/index" open-type="switchTab">随便逛逛></navigator> -->
				</view>
				<view v-else class="empty-tips">
					空空如也
					<view class="navigator" @click="navToLogin">去登陆></view>
				</view>
			</view>
			<view v-else>
				<!-- 列表 -->
				<view class="cart-list">
					<block v-for="(item, index) in cartList" :key="item.id">
						<view class="cart-item" :class="{'b-b': index!==cartList.length-1}">
							<view class="image-wrapper">
								<image :src="item.icon" class="loaded" mode="aspectFill" lazy-load @load="onImageLoad('cartList', index)"
								 @error="onImageError('cartList', index)"></image>
								<view class="yticon icon-xuanzhong2 checkbox" :class="{checked: item.checked}" @click="check('item', index)"></view>
							</view>
							<view class="item-right">
								<text class="clamp title">{{item.title}}</text>
								<text class="attr">{{item.attr_val}}</text>
								<text class="price">¥{{item.price}}</text>
								<uni-number-box class="step" :min="1" :max="item.stock" :value="item.number" :isMax="item.number>=item.stock?true:false"
								 :isMin="item.number===1" :index="index" @eventChange="numberChange"></uni-number-box>
							</view>
							<text class="del-btn yticon icon-fork" @click="deleteCartItem(index)"></text>
						</view>
					</block>
				</view>
				<!-- 底部菜单栏 -->
				<view class="action-section">
					<view class="checkbox">
						<image :src="allChecked?'/static/selected.png':'/static/select.png'" mode="aspectFit" @click="check('all')"></image>
						<view class="clear-btn" :class="{show: allChecked}" @click="clearCart(cart_ids)">
							清空
						</view>
					</view>
					<view class="total-box">
						<text class="price">¥{{total}}</text>
						<!-- <text class="coupon">
						已优惠
						<text>74.35</text>
						元
					</text> -->
					</view>
					<button type="primary" class="no-border confirm-btn" @click="createOrder">去兑换</button>
				</view>
			</view>
		</mescroll-uni>
	</view>
</template>

<script>
	// 自定义的mescroll-meituan.vue
	import MescrollUni from "../../components/mescroll-diy/mescroll-meituan.vue";
	import common from '../../common/common.js';
	import {
		mapState
	} from 'vuex';
	import uniNumberBox from '@/components/uni-number-box.vue'
	export default {
		components: {
			MescrollUni,
			uniNumberBox
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
				total: 0, //总价格
				allChecked: false, //全选状态  true|false 
				empty: false, //空白页现实  true|false
				cartList: [],
				cart_ids: ''
			};
		},
		//注册列表滚动事件,用于下拉刷新
		onPageScroll(e) {
			this.mescroll && this.mescroll.onPageScroll(e);
		},

		onLoad() {
			if (this.userInfo == undefined) {
				let url = '/pages/public/login';
				uni.navigateTo({
					url
				})

			}
		},
		onShow() {
			console.log(2222)
			this.refresh()
		},
		watch: {
			//显示空白页 
			cartList(e) {
				let empty = e.length === 0 ? true : false;
				if (this.empty !== empty) {
					this.empty = empty;
				}
			}
		},
		methods: {
			//添加或修改成功之后回调
			refresh() {
				let that = this;
				that.mescrollInit(that.mescroll);
				that.downCallback(that.mescroll)
			},
			// mescroll组件初始化的回调,可获取到mescroll对象
			mescrollInit(mescroll) {
				this.mescroll = mescroll;
			},
			// 下拉刷新的回调
			downCallback(mescroll) {


				let that = this;
				uni.request({
					url: common.dui_cart_items, //仅为示例，并非真实接口地址。
					data: {
						is_mobile: 1,
						user_id: that.userInfo.id
					},
					method: 'POST',
					header: {
						'content-type': 'application/x-www-form-urlencoded'
					},
					success: function(res) {
						if (res.data.status == 1) {
							let list = res.data.data;

							if (list != null) {



								console.log(list)
								let cartList = list.map(item => {
									item.checked = true;
									return item;
								});

								that.cart_ids = res.data.cart_ids;
								that.cartList = cartList;
							}
							that.calcTotal(); //计算总价
						} else {



						}

						setTimeout(() => {
							mescroll.endSuccess()
						}, 1000)




					}
				});
			},


			//监听image加载完成
			onImageLoad(key, index) {
				this.$set(this[key][index], 'loaded', 'loaded');
			},
			//监听image加载失败
			onImageError(key, index) {
				this[key][index].image = '/static/errorImage.jpg';
			},
			navToLogin() {
				uni.navigateTo({
					url: '/pages/public/login'
				})
			},
			//选中状态处理
			check(type, index) {
				if (type === 'item') {
					this.cartList[index].checked = !this.cartList[index].checked;
				} else {
					const checked = !this.allChecked
					const list = this.cartList;
					list.forEach(item => {
						item.checked = checked;
					})
					this.allChecked = checked;
				}
				this.calcTotal(type);
			},
			//数量
			numberChange(data) {
				this.cartList[data.index].number = data.number;

				let that = this;

				uni.request({
					url: common.dui_cart_goods_update, //仅为示例，并非真实接口地址。
					data: {
						"is_mobile": 1,
						"user_id": that.userInfo.id,
						"article_id": that.cartList[data.index].article_id,
						"goods_id": that.cartList[data.index].goods_id,
						"quantity": data.number
					},
					method: 'POST',
					header: {
						'content-type': 'application/x-www-form-urlencoded'
					},
					success: function(res) {
						if (res.data.status == 1) {} else {



						}





					}
				});




				this.calcTotal();
			},
			//删除
			deleteCartItem(index) {
				let list = this.cartList;
				let row = list[index];
				let id = row.id;

				this.cartList.splice(index, 1);
				this.calcTotal();

				let that = this;
				uni.request({
					url: common.dui_cart_goods_delete, //仅为示例，并非真实接口地址。
					data: {
						"is_mobile": 1,
						"user_id": that.userInfo.id,
						"article_id": row.article_id,
						"goods_id": row.goods_id,
						"cart_ids": id
					},
					method: 'POST',
					header: {
						'content-type': 'application/x-www-form-urlencoded'
					},
					success: function(res) {
						if (res.data.status == 1) {} else {



						}





					}
				});

				uni.hideLoading();
			},
			//清空
			clearCart() {
				uni.showModal({
					content: '清空兑换列表？',
					success: (e) => {
						if (e.confirm) {


							let that = this;


							uni.request({
								url: common.dui_cart_goods_delete, //仅为示例，并非真实接口地址。
								data: {
									"is_mobile": 1,
									"user_id": that.userInfo.id,
									"cart_ids": that.cart_ids
								},
								method: 'POST',
								header: {
									'content-type': 'application/x-www-form-urlencoded'
								},
								success: function(res) {
									if (res.data.status == 1) {


										that.cartList = [];

									} else {



									}





								}
							});



						}
					}
				})
			},
			//计算总价
			calcTotal() {
				let list = this.cartList;
				if (list.length === 0) {
					this.empty = true;
					return;
				}
				let total = 0;
				let checked = true;
				list.forEach(item => {
					if (item.checked === true) {
						total += item.price * item.number;
					} else if (checked === true) {
						checked = false;
					}
				})
				this.allChecked = checked;
				this.total = Number(total.toFixed(2));
			},
			//创建订单
			createOrder() {
				 let that=this;
				 uni.request({
				 	url: common.dui_shop, //仅为示例，并非真实接口地址。
				 	data: {
				 		"is_mobile": 1,
				 		"user_id": that.userInfo.id
				 	},
				 	method: 'POST',
				 	header: {
				 		'content-type': 'application/x-www-form-urlencoded'
				 	},
				 	success: function(res) {
						if (res.data.status == 1) {

 
							that.$api.msg(res.data.info);
							 setTimeout(() => {
							 	uni.navigateBack()
							 }, 1000)
						} else {



							that.$api.msg(res.data.info);
						}



					}
				 });
			}
		}
	}
</script>

<style lang='scss'>
	.container {
		padding-bottom: 134upx;

		/* 空白页 */
		.empty {
			position: fixed;
			left: 0;
			top: 0;
			width: 100%;
			height: 100vh;
			padding-bottom: 100upx;
			display: flex;
			justify-content: center;
			flex-direction: column;
			align-items: center;
			background: #fff;

			image {
				width: 240upx;
				height: 160upx;
				margin-bottom: 30upx;
			}

			.empty-tips {
				display: flex;
				font-size: $font-sm+2upx;
				color: $font-color-disabled;

				.navigator {
					color: $uni-color-primary;
					margin-left: 16upx;
				}
			}
		}
	}

	/* 购物车列表项 */
	.cart-item {
		display: flex;
		position: relative;
		padding: 30upx 40upx;

		.image-wrapper {
			width: 230upx;
			height: 230upx;
			flex-shrink: 0;
			position: relative;

			image {
				border-radius: 8upx;
			}
		}

		.checkbox {
			position: absolute;
			left: -16upx;
			top: -16upx;
			z-index: 8;
			font-size: 44upx;
			line-height: 1;
			padding: 4upx;
			color: $font-color-disabled;
			background: #fff;
			border-radius: 50px;
		}

		.item-right {
			display: flex;
			flex-direction: column;
			flex: 1;
			overflow: hidden;
			position: relative;
			padding-left: 30upx;

			.title,
			.price {
				font-size: $font-base + 2upx;
				color: $font-color-dark;
				height: 40upx;
				line-height: 40upx;
			}

			.attr {
				font-size: $font-sm + 2upx;
				color: $font-color-light;
				height: 50upx;
				line-height: 50upx;
			}

			.price {
				height: 50upx;
				line-height: 50upx;
			}
		}

		.del-btn {
			padding: 4upx 10upx;
			font-size: 34upx;
			height: 50upx;
			color: $font-color-light;
		}
	}

	/* 底部栏 */
	.action-section {
		/* #ifdef H5 */
		margin-bottom: 100upx;
		/* #endif */
		position: fixed;
		left: 30upx;
		bottom: 30upx;
		z-index: 95;
		display: flex;
		align-items: center;
		width: 690upx;
		height: 100upx;
		padding: 0 30upx;
		background: rgba(255, 255, 255, .9);
		box-shadow: 0 0 20upx 0 rgba(0, 0, 0, .5);
		border-radius: 16upx;

		.checkbox {
			height: 52upx;
			position: relative;

			image {
				width: 52upx;
				height: 100%;
				position: relative;
				z-index: 5;
			}
		}

		.clear-btn {
			position: absolute;
			left: 26upx;
			top: 0;
			z-index: 4;
			width: 0;
			height: 52upx;
			line-height: 52upx;
			padding-left: 38upx;
			font-size: $font-base;
			color: #fff;
			background: $font-color-disabled;
			border-radius: 0 50px 50px 0;
			opacity: 0;
			transition: .2s;

			&.show {
				opacity: 1;
				width: 120upx;
			}
		}

		.total-box {
			flex: 1;
			display: flex;
			flex-direction: column;
			text-align: right;
			padding-right: 40upx;

			.price {
				font-size: $font-lg;
				color: $font-color-dark;
			}

			.coupon {
				font-size: $font-sm;
				color: $font-color-light;

				text {
					color: $font-color-dark;
				}
			}
		}

		.confirm-btn {
			padding: 0 38upx;
			margin: 0;
			border-radius: 100px;
			height: 76upx;
			line-height: 76upx;
			font-size: $font-base + 2upx;
			background: $uni-color-primary;
			box-shadow: 1px 2px 5px rgba(217, 60, 93, 0.72)
		}
	}

	/* 复选框选中状态 */
	.action-section .checkbox.checked,
	.cart-item .checkbox.checked {
		color: $uni-color-primary;
	}
</style>
