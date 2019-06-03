<template>
	<view class="content">
		<view class="cu-form-group margin-top">
			<view class="title">姓名</view>

			<view class='padding'>{{user.user_name}}</view>
		</view>


		<view class="cu-form-group margin-top">
			<view class="title">账户余额</view>
			<view class='padding'>{{user.agent_use}}</view>
		</view>
		<radio-group class="block  margin-top" @change="RadioChange">
			<view class="cu-form-group ">
				<text class="text-grey" style="background: url(../../static/img/icon-bank.png);BACKGROUND-SIZE: 100% 100%;				WIDTH: 30PX;HEIGHT: 30PX;"></text>
				<view class="title" style="width:70%;">银行卡提现</view>
				<radio class='radio' :class="radio=='1'?'checked':''" :checked="radio=='1'?true:false" value="1"></radio>
			</view>

			<view class="cu-form-group">
				<text class="text-grey" style="background: url(../../static/img/icon-wx.png);BACKGROUND-SIZE: 100% 100%;				WIDTH: 30PX;HEIGHT: 30PX;"></text>
				<view class="title" style="width:70%;">微信提现</view>
				<radio class='radio' :class="radio=='2'?'checked':''" :checked="radio=='2'?true:false" value="2"></radio>
			</view>
			<view class="cu-form-group">
				<text class="text-grey" style="background: url(../../static/img/icon-zfb.png);BACKGROUND-SIZE: 100% 100%;WIDTH: 30PX;HEIGHT: 30PX;"></text>
				<view class="title" style="width:70%;">支付宝提现</view>

				<radio class='radio' :class="radio=='3'?'checked':''" :checked="radio=='3'?true:false" value="3"></radio>
			</view>
		</radio-group>
		<view class=" show">
			<view @tap.stop="">

				<view class="grid col-3 padding-sm">
					<view v-for="(item,index) in checkbox" class="padding-xs" :key="index">
						<button class="cu-btn orange lg block" :class="item.checked?'bg-orange':'line-orange'" @tap="ChooseCheckbox"
						 :data-value="item.value"> {{item.name}}
							<view class="cu-tag sm round" :class="item.checked?'bg-white text-orange':'bg-orange'" v-if="item.hot">HOT</view>
						</button>
					</view>
				</view>
			</view>
		</view>
		<view class="row b-b">
			<text class="tit">自定义</text>
			<input class="input" type="number"  @input="input"
@focus="focus" placeholder="自定义金额" placeholder-class="placeholder" />
		</view>
		<button class="add-btn" @click="confirm">提交</button>
	</view>
</template>

<script>
	import mpvueCityPicker from '../../components/mpvue-citypicker/mpvueCityPicker.vue';
	import common from '../../common/common.js';
	import {
		mapState
	} from 'vuex';
	export default {
		components: {
			mpvueCityPicker
		},
		data() {
			return {
				user: {
					agent_use: '',
					bank_name: '',
					bank_card: '',
					user_name: '',
					bank_address: ''
				},
				index: -1,
				tiqu_money: 0,
				picker: ['喵喵喵', '汪汪汪', '哼唧哼唧'],
				region: '',
				checkbox: [{
					value: 0,
					name: '10元',
					money: '10',
					checked: false,
					hot: false,
				}, {
					value: 1,
					name: '20元',
					money: '20',
					checked: false,
					hot: false,
				}, {
					value: 2,
					name: '30元',
					money: '30',
					checked: false,
					hot: true,
				}, {
					value: 3,
					name: '60元',
					money: '60',
					checked: false,
					hot: true,
				}, {
					value: 4,
					name: '80元',
					money: '80',
					checked: false,
					hot: false,
				}, {
					value: 5,
					name: '100元',
					money: '100',
					checked: false,
					hot: false,
				}],
				type_checkbox: [{
					value: 'A',
					checked: true
				}, {
					value: 'B',
					checked: true
				}, {
					value: 'C',
					checked: false
				}],
				radio:0
			}
		},
		onLoad(option) {
			let that = this;
			uni.request({
				url: common.userInfoUrl, //仅为示例，并非真实接口地址。
				data: {
					is_mobile: 1,
					user_id: that.userInfo.id
				},
				method: 'POST',
				header: {
					'content-type': 'application/x-www-form-urlencoded'
				},
				success: function(res) {
					console.log(res.data.data.bank_address)
					if (res.data.status == 1) {
						that.user.bank_address = res.data.data.bank_address;
						that.user.bank_name = res.data.data.bank_name;
						that.user.agent_use = res.data.data.agent_use;
						that.user.user_name = res.data.data.user_name;
						that.picker = res.data.data.user_bank;


					}



				}
			});
		},
		computed: {
			...mapState(['hasLogin', 'userInfo', 'bi'])
		},
		methods: {
			input(e){
				let items = this.checkbox;
				
				for (let i = 0, lenI = items.length; i < lenI; ++i) {
				
					items[i].checked = false;
				
				
				}
				  this.tiqu_money=e.detail.value
			},
			focus(e){
				let items = this.checkbox;
				
				for (let i = 0, lenI = items.length; i < lenI; ++i) {
				
					items[i].checked = false;
				
				
				}
				
				
				
				
			},
			PickerChange(e) {

				this.index = e.detail.value;
				console.log(this.index)
				let that = this;
				that.user.bank_name = that.userInfo.user_bank[this.index];
			},
			RadioChange(e) {
				this.radio = e.detail.value
			},
			ChooseCheckbox(e) {
				let items = this.checkbox;
				let values = e.currentTarget.dataset.value;
				for (let i = 0, lenI = items.length; i < lenI; ++i) {

					items[i].checked = false;


				}
				for (let i = 0, lenI = items.length; i < lenI; ++i) {
					if (items[i].value == values) {
						items[i].checked = !items[i].checked;
						console.log(items[i].money)
						this.tiqu_money = items[i].money
						break
					}
				}
			},
			chooseCity() {
				this.$refs.mpvueCityPicker.show()
			},
			//提交
			confirm() {
				let data = this.user;
				let that = this;
				if (!that.radio) {
					this.$api.msg('请选择提现方式');
					return;
				}
						console.log(that.radio)

				if (that.tiqu_money == 0) {
					this.$api.msg('请选择金额');
					return;
				}

				uni.request({
					url: common.frontCurrencyConfirm, //仅为示例，并非真实接口地址。
					data: {
						price: that.tiqu_money,
						is_mobile: 1,
						user_id: that.userInfo.id,
						openid:  that.userInfo.gzh_openid,
						alipay:  that.userInfo.alipay,

						ttype: that.radio
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
							}, 800)
						} else {

							that.$api.msg(res.data.info);

						}



					}
				});

			},
		}
	}
</script>

<style>
	button .cu-tag {
		position: absolute;
		top: 8upx;
		right: 8upx;
	}
</style>
<style lang="scss">
	page {
		background: $page-color-base;
		padding-top: 16upx;
	}

	.row {
		display: flex;
		align-items: center;
		position: relative;
		padding: 0 30upx;
		height: 110upx;
		background: #fff;

		.tit {
			flex-shrink: 0;
			width: 120upx;
			font-size: 30upx;
			color: $font-color-dark;
		}

		.input {
			flex: 1;
			font-size: 30upx;
			color: $font-color-dark;
		}

		.icon-shouhuodizhi {
			font-size: 36upx;
			color: $font-color-light;
		}
	}

	.default-row {
		margin-top: 16upx;

		.tit {
			flex: 1;
		}

		switch {
			transform: translateX(16upx) scale(.9);
		}
	}

	.add-btn {
		display: flex;
		align-items: center;
		justify-content: center;
		width: 690upx;
		height: 80upx;
		margin: 60upx auto;
		font-size: $font-lg;
		color: #fff;
		background-color: $base-color;
		border-radius: 10upx;
		box-shadow: 1px 2px 5px rgba(219, 63, 96, 0.4);
	}
</style>

<style>
	.cu-form-group .title {
		min-width: calc(4em + 15px);
	}
</style>
