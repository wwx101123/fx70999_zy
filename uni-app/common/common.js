const ServerIP = "zyadmin.super-nba.com";
const IP = "https://" + ServerIP + "";
const PreUrl = IP + "/adm.php/";
const IndexUrl = IP + "/index.php/";
const AppUrl = IP + "/app/android/index.html";
const checkUrl = PreUrl + 'Index/uni_app_version';
const checkLoginUrl = PreUrl + 'Public/checkLogin';
const userInfoUrl = PreUrl + 'User/userInfo';
const get_goods_listUrl = PreUrl + 'Goods/goods';
const goods_editUrl = PreUrl + 'Goods/goods_edit';
const category_listUrl = PreUrl + 'Goods/category_list';
const get_user_addr_bookUrl = PreUrl + 'Goods/get_user_addr_book';
const user_address_editUrl = PreUrl + 'Goods/user_address_edit';
const cart_goods_addUrl = PreUrl + 'Goods/cart_goods_add';
const cart_goods_updateUrl = PreUrl + 'Goods/cart_goods_update';
const cart_goods_delete = PreUrl + 'Goods/cart_goods_delete';
const get_cart_itemsUrl = PreUrl + 'Goods/cart_items';
const order_saveUrl = PreUrl + 'Goods/order_save';
const get_user_order = PreUrl + 'Goods/get_user_order';
const pointflowslist = PreUrl + "YouZi/pointflows";
const seller_edit = PreUrl + "Shop/seller_edit";
const open_seller_step4 = PreUrl + "User/open_seller_step4";
const Upload = PreUrl + "Upload/Upload";
const set_bank = PreUrl + "User/set_bank";
const pwdEdit = PreUrl + "User/pwdEdit";
const pwd2Edit = PreUrl + "User/pwd2Edit";
const order_edit = PreUrl + 'Goods/order_edit';
const order_cancel = PreUrl + 'Goods/order_cancel';
const frontCurrencyConfirm = PreUrl + "Currency/frontCurrencyConfirm";
const usersAdd = PreUrl + "Reg/usersAdd";
const dui_cart_goods_add = PreUrl + "Goods/dui_cart_goods_add";
const dui_cart_items = PreUrl + "Goods/dui_cart_items";
const dui_cart_goods_delete = PreUrl + "Goods/dui_cart_goods_delete";
const dui_cart_goods_update = PreUrl + "Goods/dui_cart_goods_update";
const dui_shop = PreUrl + "Goods/dui_shop";
const main = PreUrl + "Goods/main";
const dui_list = PreUrl + "Goods/dui_list";
const adminnewslist = PreUrl + "News/adminnewslist";
const form_detail = PreUrl + "News/form_detail";
const appVerson = '1.1.0';
const sayHi = function() {
	console.log('hi');
}

function get_client() {
	const client = '';

	return uni.getSystemInfoSync().platform;

}
// 参数： url:请求地址  param：请求参数  way：请求方式 res：回调函数
function urlRequest(url, param, way, res) {

	let deviceId = ''

	uni.getStorage({
		key: 'deviceIds',
		success: function(res) {
			deviceId = res.data;
		}
	})

	let baseParam = {
		deviceId: deviceId,
		os: "ios",
		version: "",
		appName: "wsj",
	}

	let token = "";

	uni.getStorage({
		key: 'token',
		success: function(ress) {
			token = ress.data
		}
	});

	uni.request({
		url: url,
		data: JSON.stringify(Object.assign(param, baseParam)),
		header: {
			'Token': token,
			'Accept': 'application/json',
			'Content-Type': 'application/json', //自定义请求头信息
		},
		method: way,
		success: (data) => {
			// console.log("网络请求返回值:"+ JSON.stringify(data))
			res(data)
		}
	});


}
export default {
	ServerIP,
	IP,
	PreUrl,
	IndexUrl,
	AppUrl,
	checkUrl,
	checkLoginUrl,
	userInfoUrl,
	get_goods_listUrl,
	get_client,
	goods_editUrl,
	category_listUrl,
	get_user_addr_bookUrl,
	user_address_editUrl,
	cart_goods_addUrl,
	get_cart_itemsUrl,
	order_saveUrl,
	cart_goods_updateUrl,
	cart_goods_delete,
	get_user_order,
	pointflowslist,dui_cart_goods_delete,dui_shop,main,adminnewslist,form_detail,dui_list,
	seller_edit,open_seller_step4,Upload,set_bank,pwd2Edit,pwdEdit,order_edit,order_cancel,frontCurrencyConfirm,usersAdd,dui_cart_goods_add,dui_cart_items,dui_cart_goods_update,
	url_Request: function(url, param, way, res) {
		return urlRequest(url, param, way, res);
	}
}
