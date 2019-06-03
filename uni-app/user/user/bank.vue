<template>
	<view class="content">
		<view class="cu-form-group margin-top">
			<view class="title">姓名</view>


			<input class="input" type="text" v-model="user.user_name" placeholder="姓名" placeholder-class="placeholder" />
		</view>
		<view class="cu-form-group margin-top">
			<view class="title">选择银行</view>
			<picker @change="PickerChange" :value="index" :range="picker">
				<view class="picker">
					{{user.bank_name}}
				</view>
			</picker>
		</view>
		<view class="cu-form-group margin-top">
			<view class="title">银行卡号</view>
			<input class="input" type="text" v-model="user.bank_card" placeholder="银行卡号" placeholder-class="placeholder" />
		</view>


		<view class="cu-form-group margin-top">
			<view class="title">支行名称</view>
			<input class="input" type="text" v-model="user.bank_address" placeholder="支行名称" placeholder-class="placeholder" />
		</view>

		<input class="input" type="text" v-model="user.id" v-show="false" />
		<button class="add-btn" @click="confirm">提交</button>
		<mpvue-city-picker :themeColor="themeColor" ref="mpvueCityPicker" :pickerValueDefault="cityPickerValue" @onCancel="onCancel"
		 @onConfirm="onConfirm"></mpvue-city-picker>
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
					bank_name: '',
					bank_card: '',
					user_name: '',
					bank_address: ''
				},
				index: -1,
				picker: ['喵喵喵', '汪汪汪', '哼唧哼唧'],
				region: ''
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
						that.user.bank_card = res.data.data.bank_card;
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
			PickerChange(e) {

				this.index = e.detail.value;
				console.log(this.index)
				let that = this;
				that.user.bank_name = that.userInfo.user_bank[this.index];
			},
			chooseCity() {
				this.$refs.mpvueCityPicker.show()
			},
			//提交
			confirm() {
				let data = this.user;
				let that = this;
				if (!data.user_name) {
					this.$api.msg('请填写姓名');
					return;
				}

				if (!data.bank_address) {
					this.$api.msg('请填写支行名称');
					return;
				}
				if (!data.bank_card) {
					this.$api.msg('请填写银行卡号');
					return;
				}
				console.log(that.userInfo.user_bank[that.index])
				if (!that.index) {
					this.$api.msg('请选择银行');
					return;
				}
				uni.request({
					url: common.set_bank, //仅为示例，并非真实接口地址。
					data: {
						is_mobile: 1,
						user_id: that.userInfo.id,
						bank_name: that.userInfo.user_bank[that.index],
						bank_card: data.bank_card,
						bank_address: data.bank_address,
						bank_username: data.user_name
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
