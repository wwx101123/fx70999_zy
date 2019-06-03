<template>
	<view class="content">
		<view class="row b-b">
			<text class="tit">联系人</text>


			<input class="input" type="text" v-model="addressData.name" placeholder="收货人姓名" placeholder-class="placeholder" />
		</view>
		<view class="row b-b">
			<text class="tit">手机号</text>
			<input class="input" type="number" v-model="addressData.mobile" placeholder="收货人手机号码" placeholder-class="placeholder" />
		</view>
		<view class="row b-b">
			<text class="tit">省市区</text>
			<view class="input" @tap="chooseCity">
				{{addressData.province}}{{addressData.city}}{{addressData.area}}
			</view>

		</view>
		<view class="row b-b">
			<text class="tit">地址</text>
			<text @click="chooseLocation" class="input" width="100%">
				{{addressData.addressName}}
			</text>
			<text class="yticon icon-shouhuodizhi"></text>
		</view>
		<view class="row b-b" >
			<text class="tit">门牌号</text>
			<input class="input" type="text" v-model="addressData.street" placeholder="楼号、门牌" placeholder-class="placeholder" />
		</view>

		<view class="row default-row">
			<text class="tit">设为默认</text>
			<switch :checked="addressData.default" color="#fa436a" @change="switchChange" />
		</view>
		<input class="input" type="text" v-model="addressData.id" v-show="false" />
		<button class="add-btn" @click="confirm">提交</button>
		<mpvue-city-picker :themeColor="themeColor" ref="mpvueCityPicker" :pickerValueDefault="cityPickerValue" @onCancel="onCancel"
		 @onConfirm="onConfirm"></mpvue-city-picker>
	</view>
</template>

<script>
	import mpvueCityPicker from '../../components/mpvue-citypicker/mpvueCityPicker.vue';
	import common from '../../common/common.js';
	export default {
		components: {
			mpvueCityPicker
		},
		data() {
			return {
				addressData: {
					name: '',
					mobile: '',
					addressName: '在地图选择',
					address: '',
					
					area: '请选择省市区',
					default: false,
					street:''
				}, 
				lotusAddressData: {
					visible: false,
					provinceName: '广东省',
					cityName: '广州市',
					townName: '天河区',
				},
				region: ''
			}
		},
		onLoad(option) {
			let that = this;
			let title = '新增收货地址';
			option.type = 'add';
			if (option.data != 'undefined') {


			console.log(option.data)
				this.addressData = JSON.parse(option.data)
				if (this.addressData.id > 0) {
					title = '编辑收货地址';
					option.type = 'edit'; 

				}
			}
			this.manageType = option.type;
			uni.setNavigationBarTitle({
				title
			})
		},
		methods: {
			chooseCity() {
				this.$refs.mpvueCityPicker.show()
			},
			onConfirm(e) {
				let label = e.label.split('-');
				this.addressData.province = label[0];
				this.addressData.city = label[1];
				this.addressData.area = label[2];
				this.cityPickerValue = e.value;
			},
			switchChange(e) {


				// this.addressData.is_default = e.detail;

				this.addressData.default = e.detail;
			},

			//地图选择地址
			chooseLocation() {
				uni.chooseLocation({
					success: (data) => {
						this.addressData.addressName = data.name;
						this.addressData.address = data.name;
					}
				})
			},

			//提交
			confirm() {
				let data = this.addressData;
				if (!data.name) {
					this.$api.msg('请填写收货人姓名');
					return;
				}
				if (!/(^1[3|4|5|7|8][0-9]{9}$)/.test(data.mobile)) {
					this.$api.msg('请输入正确的手机号码');
					return;
				}
				if (!data.address) {
					this.$api.msg('请在地图选择所在位置');
					return;
				}
				if (!data.area) {
					this.$api.msg('请填写门牌号信息');
					return;
				}
				console.log(data.id)
				let that = this;
				uni.request({
					url: common.user_address_editUrl, //仅为示例，并非真实接口地址。
					data: {
						is_mobile: 1,
						txtProvince: data.province,
						txtCity: data.city,
						txtArea: data.area,
						txtAcceptName: data.name,
						txtAddress: data.addressName,
						street: data.street,
						txtMobile: data.mobile,
						// is_default: data.default,
						default: data.default,
						id: data.id
					},
					method: 'POST',
					header: {
						'content-type': 'application/x-www-form-urlencoded'
					},
					success: function(res) {
						if (res.data.status == 1) {
							//this.$api.prePage()获取上一页实例，可直接调用上页所有数据和方法，在App.vue定义
							that.$api.prePage().refreshList(data, that.manageType);
							that.$api.msg(`地址${that.manageType=='edit' ? '修改': '添加'}成功`);
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
