import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex)

const store = new Vuex.Store({
	state: {
		hasLogin: false,
		userInfo: {},
		bi: {},
		user_id: 0,


		//首屏广告上次显示时间
		splashAdvertPreTime: 0,


	},
	mutations: {

		login(state, provider) {
			console.log(1234)
			state.hasLogin = true;
			state.userInfo = provider.data;
			if (provider.data != null) {

				console.log(provider.data.re_share_title)
				state.user_id = provider.data.id;
				state.bi = provider.bi;
				uni.setStorage({ //缓存用户登陆状态
					key: 'userInfo',
					data: provider.data
				});
				uni.setStorage({ //缓存用户登陆状态
					key: 'bi',
					data: provider.bi
				});
				uni.setStorage({ //缓存用户登陆状态
					key: 'user_id',
					data: provider.data.id
				});
			}
		},
		logout(state) {
			console.log(2222)
			state.hasLogin = false;
			// state.userInfo = {};
			state.bi = {};

			uni.removeStorage({
				key: 'userInfo'
			})
			uni.removeStorage({
				key: 'bi'
			})
		}
	},
	actions: {

	}
})

export default store
