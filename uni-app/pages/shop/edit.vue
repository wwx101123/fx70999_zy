<template>
	<view class="content">
		<view class="row b-b" style="height:100px;">
			<text class="tit">LOGO</text>


			<view>
				<sunui-upbasic :upImgConfig="upImgBasic" @onUpImg="upBasicData" @onImgDel="delImgInfo" ref="uImage"></sunui-upbasic>
				<!-- <button type="primary" @tap="uImageTap">手动上传图片</button> -->
			</view>
		</view>
		<view class="row b-b">
			<text class="tit">店铺名称</text>


			<input class="input" type="text" v-model="shopData.title" placeholder="店铺名称" placeholder-class="placeholder" />
		</view>
		<view class="row b-b">
			<text class="tit">所属地区</text>
			<view class="input" @tap="chooseCity">
				{{shopData.province}}{{shopData.city}}{{shopData.area}}
			</view>

		</view>
		<view class="row b-b">
			<text class="tit">详细地址</text>
			<text @click="chooseLocation" class="input" width="100%">
				{{shopData.address}}
			</text>
			<text class="yticon icon-shouhuodizhi"></text>
		</view>

		<view class="row b-b">
			<text class="tit">联系电话</text>
			<input class="input" type="text" v-model="shopData.mobile" placeholder="联系电话" placeholder-class="placeholder" />
		</view>

		<input class="input" type="text" v-model="shopData.id" v-show="false" />
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
		computed: {
			...mapState(['hasLogin', 'userInfo', 'bi'])
		},
		data() {
			return {
				shopData: {
					name: '',
					mobile: '',
					address: '在地图选择',

					area: '请选择省市区',
					seller_id: 0
				},
				lotusAddressData: {
					visible: false,
					provinceName: '广东省',
					cityName: '广州市',
					townName: '天河区',
				},
				region: '',

				basicArr: [],
				// 基础版配置
				upImgBasic: {
					// 后端图片接口地址
					basicConfig: {
						url: common.Upload
					},
					// 是否开启提示(提醒上传图片的数量)
					// tips: false,
					// 是否开启notli(开启的话就是选择完直接上传，关闭的话当count满足数量时才上传)
					notli: true,
					// 图片数量
					count: 1,
					// 相机来源(相机->camera,相册->album,两者都有->all,默认all)
					sourceType: 'camera',
					// 是否压缩上传照片(仅小程序生效)
					sizeType: true,
					// 上传图片背景修改 
					upBgColor: '#E8A400',
					// 上传icon图标颜色修改(仅限于iconfont)
					upIconColor: '#fff',
					// 上传svg图标名称
					// upSvgIconName: 'icon-card',
					// 上传文字描述(仅限四个字)
					// upTextDesc: '上传证书',
					// 删除按钮位置(left,right,bleft,bright),默认右上角
					delBtnLocation: '',
					// 是否隐藏添加图片
					// isAddImage: false,
					// 是否隐藏删除图标
					// isDelIcon: false,
					// 删除图标定义背景颜色
					// delIconColor: '',
					// 删除图标字体颜色
					// delIconText: '',
					// 上传图标替换(+),是个http,https图片地址(https://www.playsort.cn/right.png)
					iconReplace: ''
				}


			}
		},
		successCallback(data) {

			console.log(2222)
		},
		onLoad() {

			let that = this;
			console.log(that.userInfo.id)
			uni.request({
				url: common.seller_edit, //仅为示例，并非真实接口地址。
				data: {
					is_mobile: 1,
					id: that.userInfo.id
				},
				method: 'POST',
				header: {
					'content-type': 'application/x-www-form-urlencoded'
				},
				success: function(res) {
					console.log(res.data.data.id)
					if (res.data.status == 1) {
						that.shopData.title = res.data.data.title;
						that.shopData.province = res.data.data.province;
						that.shopData.city = res.data.data.city;
						that.shopData.area = res.data.data.area;
						that.shopData.address = res.data.data.address;
						that.shopData.mobile = res.data.data.phone;
						that.shopData.seller_id = res.data.data.id;
						console.log(res.data.data.img)
						that.upImgBasic.iconReplace =common.IP+ res.data.data.img;
						that. basicArr.push(res.data.data.img);
					} else {



					}





				}
			});
		},
		methods: {

			// 手动上传图片(适用于表单等上传) -2019/05/10增加
			uImageTap() {
				this.$refs.uImage.uploadimage(this.upImgBasic);
			},
			// 删除图片 -2019/05/12(本地图片进行删除)
			async delImgInfo(e) {
				console.log('你删除的图片地址为:', e, this.basicArr.splice(e.index, 1));
			},
			// 基础版
			async upBasicData(e) {
				console.log('===>', e);
				// 上传图片数组
				let arrImg = [];
				for (let i = 0, len = e.length; i < len; i++) {
					try {
						if (e[i].path != "") {
							await arrImg.push(e[i].path.split(','));
						}
					} catch (err) {
						console.log('上传失败...');
					}
				}

				// 图片信息保存到data数组
				this.basicArr = arrImg;

				// 可以根据长度来判断图片是否上传成功. 2019/4/11新增
				if (arrImg.length == this.upImgBasic.count) {
					uni.showToast({
						title: `上传成功`,
						icon: 'none'
					});
				}
			},
			// 获取上传图片basic
			getUpImgInfoBasic() {
				console.log('后端转成一维数组:', this.basicArr.join().split(','));
			},
			chooseCity() {
				this.$refs.mpvueCityPicker.show()
			},
			onConfirm(e) {
				let label = e.label.split('-');
				this.shopData.province = label[0];
				this.shopData.city = label[1];
				this.shopData.area = label[2];
				this.cityPickerValue = e.value;



			},


			//地图选择地址
			chooseLocation() {
				uni.chooseLocation({
					success: (data) => {
						this.shopData.address = data.name;
					}
				})
			},

			//提交
			confirm() {
				let data = this.shopData;
				if (!data.title) {
					this.$api.msg('请填写店铺名称');
					return;
				}

				if (!data.address) { 
					this.$api.msg('请在地图选择所在位置');
					return;
				}
				if (!data.province) {
					this.$api.msg('请选择地区');
					return;
				}
				let that = this;
				uni.request({
					url: common.open_seller_step4, //仅为示例，并非真实接口地址。
					data: {
						is_mobile: 1,
						txtProvince: data.province,
						txtCity: data.city,
						txtArea: data.area,
						title: data.title,
						txtAddress: data.address,
						txtMobile: data.mobile,
						// is_default: data.default,
						user_id: that.userInfo.id,
						seller_id: data.seller_id,
						img_url: that.basicArr[0]
					},
					method: 'POST',
					header: {
						'content-type': 'application/x-www-form-urlencoded'
					},
					success: function(res) {
						if (res.data.status == 1) {
							//this.$api.prePage()获取上一页实例，可直接调用上页所有数据和方法，在App.vue定义
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
