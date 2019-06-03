var w = null;
var ServerIP = "";
var IP = localStorage.getItem('IP');
var PreUrl = localStorage.getItem('PreUrl');
var UploadUrl = localStorage.getItem('UploadUrl');
var recharge_val = PreUrl + "Pay/recharge";
var notify_val = PreUrl + "Pay/recharge_notify";

var PAYSERVER = UploadUrl + 'payment/wxpayV3/index.php?';
var ALIPAYSERVER = UploadUrl + 'payment/alipayrsa2/index.php?';
// 支付
function pay(id, money, remark, notify_url, is_shopping, out_trade_no) {
	console.log(notify_url)
	//	plusReady();
	var amount = money;

	if(w) {
		return;
	} //检查是否请求订单中
	if(id === 'appleiap') {
		clicked('payment_iap.html');
		return;
	}

	var url = PAYSERVER;
	if(id == 'alipay') {
		url = ALIPAYSERVER;
		//		mui.toast('暂未开放！');
		//		return;
	}
	if(id == 'alipay' || id == 'wxpay') {
		url += id;
	} else {
		plus.nativeUI.alert('当前环境不支持此支付通道！', null, '捐赠');
		return;
	}
	var appid = plus.runtime.appid;
	//				  appid = 'wxb8190168cfb3db2b';

	if(navigator.userAgent.indexOf('StreamApp') >= 0) {
		appid = 'Stream';
	}
	url += '&notify_url=' + notify_url + '&remark=' + remark + '&appid=' + appid + '&out_trade_no=' + out_trade_no + '&user_id=' + localStorage.getItem('user_id') + '&total=';

	w = plus.nativeUI.showWaiting();
	// 请求支付订单 

	var xhr = new XMLHttpRequest();

	xhr.onreadystatechange = function() {
		switch(xhr.readyState) {
			case 4:
				w.close();
				w = null;
				if(xhr.status == 200) {
					var order = xhr.responseText;
					if(is_shopping == 0) {
						$.ajax({
							url: recharge_val,
							data: {
								is_mobile: 1,
								user_id: localStorage.getItem('user_id'),
								trade_no: out_trade_no,
								total: amount,
								paytype: id,
								remark: remark,
								notify_url: notify_url,
								responseText: order
							},

							dataType: 'json', //服务器返回json格式数据
							type: 'POST', //HTTP请求类型
							timeout: 10000, //超时时间设置为10秒；

							success: function(data) {
								var status = data.status;

								if(status == 1) {
									order = xhr.responseText;
									if(id == 'alipay') {

									}
									plus.payment.request(pays[id], order, function(result) {
										mui.toast('支付成功')
										//										mui('.content').pullRefresh().refresh(true); 

										location.reload();
										mui('.content').pullRefresh().pullupLoading();

									}, function(e) {
										mui.toast('支付失败')
									});
								} else {

									mui.toast('支付失败')
								}
							},
							error: function(xhr, type, errorThrown) {
								//异常处理；
								console.log(type);
							}
						});
					} else {

						order = xhr.responseText;

						if(id == 'alipay') {

							//												order = decodeURIComponent(order);
							//mui.toast(order) 
						}
						plus.payment.request(pays[id], order, function(result) {

							var is_order_pay = localStorage.getItem('is_order_pay')
							var is_apply_saoma = localStorage.getItem('is_apply_saoma')
							if(is_apply_saoma == 1) {

								location.reload()
							} else {
								if(is_order_pay == 0) {
									openWindowWithTitle('支付成功', 'succeed.html')
								} else {
									location.reload();
								}
							}
							mui.toast('支付成功')
							//				

						}, function(e) {
							console.log(JSON.stringify(e))
							mui.toast('支付失败' + e.message)
						});

					}

				} else {
					mui.toast('获取订单信息失败')
				}
				break;
			default:
				break;
		}
	}
	xhr.open('GET', url + amount);
	xhr.send();

}
var pays = {};
document.addEventListener('plusready', plusReady, false);

function plusReady() {
	// 获取支付通道
	plus.payment.getChannels(function(channels) {
		var content = document.getElementById('payment_list');
		var info = document.getElementById('info');
		var txt = '支付通道信息：';
		for(var i in channels) {
			var channel = channels[i];
			if(channel.id == 'qhpay' || channel.id == 'qihoo') { // 过滤掉不支持的支付通道：暂不支持360相关支付
				continue;
			}
			pays[channel.id] = channel;
			txt += 'id:' + channel.id + ', ';
			txt += 'description:' + channel.description + ', ';
			txt += 'serviceReady:' + channel.serviceReady + '； ';
			var de = document.createElement('li');
			//						de.setAttribute('class', 'button'); 
			de.setAttribute('onclick', 'pay(this.id)');
			de.id = channel.id;
			de.innerHTML = '<a>' + channel.description + '支付</a>';

			var bool = channel.description.indexOf("Purchase");
			//返回大于等于0的整数值，若不包含"Text"则返回"-1。
			if(bool > 0) {} else {
				//				content.appendChild(de);

				//				checkServices(channel);
			}
		}

		$('#payment_list').append('<li class="cal">取消</li>');
		$(".cal").click(function(e) {
			$(".f-overlay").hide();
			$(".addvideo").hide();
		})
		//		info.innerText = txt;
	}, function(e) {
		//					outLine('获取支付通道失败：' + e.message);
	});
}

function generatePassword(len) {
	len = len || 32;
	var $chars = '123456789'; // 默认去掉了容易混淆的字符oOLl,9gq,Vv,Uu,I1  
	var maxPos = $chars.length;
	var pwd = '';
	for(i = 0; i < len; i++) {
		pwd += $chars.charAt(Math.floor(Math.random() * maxPos));
	}
	return pwd;
}
 