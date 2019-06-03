var ServerIP = "";
var IP = localStorage.getItem('IP');
var PreUrl = localStorage.getItem('PreUrl');
/**
 * 获取订单列表
 */
var get_user_order_list = PreUrl + "Goods/get_user_order";
var get_user_shop_order_list = PreUrl + "YouZi/tradeflows";
/**
 * 获取众筹订单列表
 */
var get_user_crowd_order_list = PreUrl + "Goods/get_user_crowd_order";
/**
 * 获取赠送订单列表
 */
var get_user_give_order_list = PreUrl + "Goods/get_user_give_order";
/**
 * 获取订单详情
 */
var get_order_info = PreUrl + "Goods/get_order_info";
/**
 * 完成订单
 */
var order_complete_fun = PreUrl + "Goods/order_complete";
/**
 *付款订单
 */
var order_payment_fun = PreUrl + "Goods/order_payment";
var order_give_fun = PreUrl + "Goods/order_give";
var order_money_fun = PreUrl + "Goods/order_money";
var order_tousu_fun = PreUrl + "Goods/order_tousu";
var order_upload_img_fun = PreUrl + "Goods/order_upload_img";
var check_order_fun = PreUrl + "Goods/check_order";

var order_cancel_fun = PreUrl + "Goods/order_cancel";
var order_back_fun = PreUrl + "Goods/order_back";
var order_apply_daifu_fun = PreUrl + "Goods/order_apply_daifu";

function view_img(img) {

}

function go_upload(order_id, user_id) {
	localStorage.setItem('tousu_order_id', order_id)
	localStorage.setItem('tousu_user_id', user_id)
	location.href = 'tousu_upload.html';

}

function get_user_order(type, pageNum, pageSize, successCallback) {
	var user_id = localStorage.getItem('user_id');
	$.ajax({
		url: get_user_order_list,
		data: {
			is_mobile: 1,
			type: type,
			user_id: user_id,
			pageNum: pageNum,
			pageSize: pageSize
		},
		dataType: 'json', //服务器返回json格式数据
		type: 'POST', //HTTP请求类型
		timeout: 10000, //超时时间设置为10秒；

		success: function(data) {

			data = JSON.stringify(data);

			localStorage.setItem('ListData', data)
			data = JSON.parse(data);
			var status = data.status;

			successCallback && successCallback(data);

		},
		error: function(xhr, type, errorThrown) {
			//异常处理；
			console.log(type);
		}
	});

}
var intDiff = parseInt(60); //倒计时总秒数量
function timer(intDiff, obj) {
	window.setInterval(function() {
		var day = 0,
			hour = 0,
			minute = 0,
			second = 0; //时间默认值        
		if(intDiff > 0) {
			day = Math.floor(intDiff / (60 * 60 * 24));
			hour = Math.floor(intDiff / (60 * 60)) - (day * 24);
			minute = Math.floor(intDiff / 60) - (day * 24 * 60) - (hour * 60);
			second = Math.floor(intDiff) - (day * 24 * 60 * 60) - (hour * 60 * 60) - (minute * 60);
		}
		if(minute <= 9) minute = '0' + minute;
		if(second <= 9) second = '0' + second;

		if(intDiff == 0) {
			$(obj).find('#day_show').css("display", 'none');
			$(obj).find('#hour_show').css("display", 'none');
			$(obj).find('#minute_show').css("display", 'none');
			$(obj).find('#second_show').html('打款时间已到');

			check_order($(obj).find('.time-item').attr('order_id'));

		} else {
			$(obj).find('#day_show').html(day + "天");
			$(obj).find('#hour_show').html('<s id="h"></s>' + hour + '时');
			$(obj).find('#minute_show').html('<s></s>' + minute + '分');
			$(obj).find('#second_show').html('<s></s>' + second + '秒');
		}

		intDiff--;
	}, 1000);
}

function check_order(order_id) {
	$.ajax({
		url: check_order_fun,
		data: {
			is_mobile: 1,
			order_id: order_id
		},
		dataType: 'json', //服务器返回json格式数据
		type: 'POST', //HTTP请求类型
		timeout: 10000, //超时时间设置为10秒；

		success: function(data) {
			var status = data.status;
			get_user_order(1);

		},
		error: function(xhr, type, errorThrown) {
			//异常处理；
			console.log(type);
		}
	});
}

function init_upload() {
	var ServerIP = "";
	var IP = localStorage.getItem('IP');
	var PreUrl = localStorage.getItem('PreUrl');
	var allowTypes = ['image/jpg', 'image/jpeg', 'image/png', 'image/gif'];
	// 1024KB，也就是 1MB  
	var maxSize = 2048 * 2048;
	// 图片最大宽度  
	var maxWidth = 10000;
	// 最大上传图片数量  
	var maxCount = 11116;
	var tmpl = '<li class="weui-uploader__file  " style="background-image:url(#url#);background-size: 100% 100%;"></li>',
		$gallery = $(".gallery"),
		$galleryImg = $(".galleryImg"),
		$uploaderInput = $(".uploaderInput"),
		$uploaderFiles = $(".uploaderFiles");
	$uploader_list = $(".uploader_list");

	$uploaderInput.on("change", function(e) {
		var src, url = window.URL || window.webkitURL || window.mozURL,
			files = e.target.files;
		if(($('.original_path').length + files.length) > 1) {
			$.toast("最多上传一张图片", "forbidden")
			remove_toast()
			return;
		}
		for(var i = 0, len = files.length; i < len; ++i) {
			var file = files[i];
			if(url) {
				src = url.createObjectURL(file);
			} else {
				src = e.target.result;
			}

			if($('.original_path').length < 2) {
				$uploaderFiles.append($(tmpl.replace('#url#', src)));
			}
			imgLoad(src);
		}
	});
	var index; //第几张图片
	$uploaderFiles.on("click", "li", function() {
		index = $(this).index();

		$galleryImg.attr("style", this.getAttribute("style"));
		$gallery.fadeIn(100);
	});
	$gallery.on("click", function() {
		$gallery.fadeOut(100);
	});
	//删除图片 删除图片的代码也贴出来。
	$(".weui-gallery__del").click(function() { //这部分刚才放错地方了，放到$(function(){})外面去了

		$('.original_path').eq(index).remove();
		$uploaderFiles.find("li").eq(index).remove();

		if($('.original_path').length < 2) {
			$('.uploader_btn_div').css('display', 'block')
			$('.upload_img_url').css('display', 'none')
			//			init_upload()
		}
	});
}

function imgLoad(url) {
	var tmpl = '<li class="weui-uploader__file" style="background-image:url(#url#)"></li>',
		$uploaderInput = $(".uploaderInput"),
		$uploaderFiles = $(".uploaderFiles");
	$uploader_list = $(".uploader_list");
	var allowTypes = ['image/jpg', 'image/jpeg', 'image/png', 'image/gif'];
	// 1024KB，也就是 1MB  
	var maxSize = 2048 * 2048;
	// 图片最大宽度  
	var maxWidth = 10000;
	// 最大上传图片数量  
	var maxCount = 11116;
	var img = new Image();
	img.src = url;
	if(img.complete) {
		callback(img.width, img.height);
	} else {
		img.onload = function() {

			// 不要超出最大宽度  
			var w = Math.min(maxWidth, img.width);
			// 高度按比例计算  
			var h = img.height * (w / img.width);
			var canvas = document.createElement('canvas');
			var ctx = canvas.getContext('2d');
			// 设置 canvas 的宽度和高度  
			canvas.width = w;
			canvas.height = h;
			ctx.drawImage(img, 0, 0, w, h);　　　　　　　　　　　　
			var base64 = canvas.toDataURL('image/jpeg', 0.8);
			$.ajax({
				url: PreUrl + "User/upload",

				type: 'POST',

				data: {
					is_mobile: 1,
					imgOne: base64
				},

				dataType: 'json', //服务器返回json格式数据

				success: function(data) {

					$('.weui-mask_transparent').remove();
					$('.weui_loading_toast').remove();

					var UploadUrl = localStorage.getItem('UploadUrl');
					var item = '<input type="hidden" class="original_path" name="image" id="original_path" value="' + UploadUrl + data.url + '">'

					if($('.original_path').length < 2) {
						$uploader_list.append(item);
					}
					remove_toast()
					if($('.original_path').length == 1) {
						$('.uploader_btn_div').css('display', 'none')
						$('.uploader_btn').css('display', 'none')
						$('.upload_img_url').css('display', 'block')

					}
					img.onload = null;

				},
				error: function(xhr, type) {
					alert('Ajax error!')
				}
			});
		};
	};
};

function remove_toast() {
	setTimeout(function() { //两秒后跳转  

		$('.weui-toast').remove();
		$('.weui-mask_transparent').remove();
	}, 1000);

}

function get_user_crown_order_show(order_id) {
	$.ajax({
		url: get_order_info,
		data: {
			is_mobile: 1,

			order_id: order_id,

			user_id: localStorage.getItem('user_id')
		},
		dataType: 'json', //服务器返回json格式数据
		type: 'POST', //HTTP请求类型
		timeout: 10000, //超时时间设置为10秒；

		success: function(data) {
			var status = data.status;
			if(status == 1) {
				var info = data.data;
				$('.accept_name').html(info.accept_name);
				$('.area').html(info.area + ' ' + info.address);
				$('.mobile').html(info.mobile);
				$('.message').html(info.message);
				if(info.payment_time_item > 0) {
					$('.payment_time_item').css('display', 'block');
					$('.payment_time').html(info.payment_time_str);
				}

				$('.real_amount').html('元' + info.real_amount + ' ');
				$('.order_status').html(info.status_str);
				$('.order_no').html(info.order_no);
				$('.add_time').html(info.addtime_str);
				$('.order_amount').html('订单总金额：' + info.order_amount + ' ');

				$.each(info.order_goods, function(index1, content1) {

					var item = '<div class="list-box">';
					item += '		<div class="img-box">';
					item += '		<a href="javascript:void(0)"><img src="' + IP + content1.img_url + '" /></a>';
					item += '		</div>';
					item += '		<div class="txt-box">';
					item += '		<h2>' + content1.goods_title + '</h2>';
					item += '		<p>' + content1.spec_text + '</p>';
					item += '		<p class="note">';
					item += '			<span class="fr">×' + content1.quantity + '</span>';
					item += '			<span class="price"> ' + content1.real_price + '元</span>';
					item += '		</p>';
					item += '		</div>';
					item += '		</div>';
					$('.order_goods').append(item);

				});

				if(info.status == 6 && info.payment_status == 1 && info.crowd_status == 0) {

					//					$('.uploader_div').css('display', 'block');
					$('.btnTousu').css('display', 'block');
					$('.btnTousu').css('float', 'left');
					$('.btnTousu').attr('order_id', info.id);
					$('.btnTousu').attr('hot_id', info.goods_hot_id);
					$('.btnMoney').css('display', 'block');
					$('.btnMoney').attr('order_id', info.id);
					$('.btnMoney').css('float', 'left');

				}
			}

		},
		error: function(xhr, type, errorThrown) {
			//异常处理；
			console.log(type);
		}
	});

}

function get_user_order_show(order_id) {
	$.ajax({
		url: get_order_info,
		data: {
			is_mobile: 1,

			order_id: order_id,

			user_id: localStorage.getItem('user_id')
		},
		dataType: 'json', //服务器返回json格式数据
		type: 'POST', //HTTP请求类型
		timeout: 10000, //超时时间设置为10秒；

		success: function(data) {
			var status = data.status;
			var type = localStorage.getItem('type');
			if(status == 1) {
				var info = data.data;
				$('.accept_name').html(info.accept_name);
				$('.area').html(info.area + ' ' + info.address);
				$('.mobile').html(info.mobile);
				$('.message').html(info.message);
				if(info.payment_time_item > 0) {
					$('.payment_time_item').css('display', 'block');
					$('.payment_time').html(info.payment_time_str);
				}

				$('.real_amount').html('元' + info.real_amount + ' ');
				$('.order_status').html(info.status_str);
				$('.order_no').html(info.order_no);
				$('.add_time').html(info.addtime_str);
				$('.order_amount').html('订单总金额：' + info.order_amount + ' ');

				$.each(info.order_goods, function(index1, content1) {

					var item = '<div class="list-box">';
					item += '		<div class="img-box">';
					item += '		<a href="javascript:void(0)"><img src="' + IP + content1.img_url + '" /></a>';
					item += '		</div>';
					item += '		<div class="txt-box">';
					item += '		<h2>' + content1.goods_title + '</h2>';
					item += '		<p>' + content1.spec_text + '</p>';
					item += '		<p class="note">';
					item += '			<span class="fr">×' + content1.quantity + '</span>';
					item += '			<span class="price"> ' + content1.real_price + '元</span>';
					item += '		</p>';
					item += '		</div>';
					item += '		</div>';
					$('.order_goods').append(item);

				});
				if(type != 5) {
					$.each(info.order_user, function(index1, content1) {

						var item = '';
						item += '		<li>';
						item += '		<div class="detail-hd">';
						item += '		<span class="fr user_name">' + content1.all_crowd_price + '</span>';

						item += '	<span class="  order_no">' + content1.user_name + '【' + content1.bank_card + '】</span>';
						item += '		</div>';

						item += '		</li>';
						$('.user_list ul').append(item);

					});
				}
				if(info.express_status == 2) {
					$('.payment_time_item').css('display', 'block');
					$('.express_title').html(info.express);
					$('.express_time').html(info.express_time_str);
					$('.express_no').html(info.express_no);
				}
				if(info.express_status == 2 && info.status == 2) {

					$('.btnExpress').css('display', 'block');
					$('.btnExpress').attr('order_id', info.id);

				}
				if(info.express_status == 0 && info.payment_status == 0) {

					$('.btnPayment').css('display', 'block');
					$('.btnPayment').attr('order_id', info.id);

				}

				if(type == 5) {
					$('.uploader_div').css('display', 'block');
					$('.btnTousu').css('display', 'block');

					$('.order_amount').html('投诉金额:' + info.tousu_money);

				}
			}

		},
		error: function(xhr, type, errorThrown) {
			//异常处理；
			console.log(type);
		}
	});

}

function order_tousu(obj) {
	mui.confirm('投诉，您确定吗？', function() {
		$.ajax({
			url: order_tousu_fun,
			data: {
				is_mobile: 1,
				order_id: $(obj).attr('order_id'),
				user_id: localStorage.getItem('user_id')
			},
			dataType: 'json', //服务器返回json格式数据
			type: 'POST', //HTTP请求类型
			timeout: 10000, //超时时间设置为10秒；

			success: function(data) {
				var status = data.status;
				if(status == 1) {
					mui.alert(data.msg, function() {

						location.reload();

					});

				} else {
					mui.toast(data.msg);
				}

			},
			error: function(xhr, type, errorThrown) {
				//异常处理；
				console.log(type);
			}
		});
	}, function() {});

}

function order_payment(obj, type, payment_id, money, order_no) {

	localStorage.setItem('is_order_pay', 1)
	localStorage.setItem('zfb_img', $(obj).attr('zfb_img'))
	localStorage.setItem('wx_img', $(obj).attr('wx_img'))
	var notify_val = PreUrl + "Pay/order_notify";

	var pay_type = 'wxpay';
	if(payment_id == 7) {
		pay_type = 'alipay';
		notify_val = PreUrl + "Pay/zfb_order_notify";
	}
	if(payment_id == 8) {
		pay_type = 'wxpay';
	}
	pay(pay_type, money, '订单编号' + order_no, notify_val, 1, order_no);

}

function order_give(obj, type) {
	var txt = '赠送';
	if(type == 2) {
		txt = '拒绝赠送';
	}

	mui.confirm(txt + '，您确定吗？', function(e) {
		if(e.index == 1) {
			$.ajax({
				url: order_give_fun,
				data: {
					is_mobile: 1,
					order_id: $(obj).attr('order_id'),
					type: type,
					user_id: localStorage.getItem('user_id')
				},
				dataType: 'json', //服务器返回json格式数据
				type: 'POST', //HTTP请求类型
				timeout: 10000, //超时时间设置为10秒；

				success: function(data) {
					var status = data.status;
					if(status == 1) {
						mui.alert(data.msg, function() {

							location.reload();

						});

					} else {
						mui.toast(data.msg);
					}

				},
				error: function(xhr, type, errorThrown) {
					//异常处理；
					console.log(type);
				}
			});
		}
	}, function() {});

}

function order_apply_daifu(obj) {
	var daifu_user_name = $(obj).parent().parent().find('#daifu_user_name');
	var btnArray = ['取消', '确定'];
	mui.prompt('请输入代付人账号', '', '申请代付', function(e) {
		if(e.index == 1) {
			$.ajax({
				url: order_apply_daifu_fun,
				data: {
					is_mobile: 1,
					order_id: $(obj).attr('order_id'),
					user_id: localStorage.getItem('user_id'),
					daifu_user_name: e.value
				},
				dataType: 'json', //服务器返回json格式数据
				type: 'POST', //HTTP请求类型
				timeout: 10000, //超时时间设置为10秒；

				success: function(data) {
					var status = data.status;
					if(status == 1) {
						mui.alert(data.msg, function() {

							location.reload();

						});

					} else {
						mui.toast(data.msg);
					}

				},
				error: function(xhr, type, errorThrown) {
					//异常处理；
					console.log(type);
				}
			});
		} else {

		}
	}, function() {

	});

}

function order_complete(obj) {
	mui.confirm('收货订单，您确定吗？', function(e) {
		if(e.index == 0) {
			$.ajax({
				url: order_complete_fun,
				data: {
					is_mobile: 1,
					order_id: $(obj).attr('order_id')
				},
				dataType: 'json', //服务器返回json格式数据
				type: 'POST', //HTTP请求类型
				timeout: 10000, //超时时间设置为10秒；

				success: function(data) {
					var status = data.status;
					if(status == 1) {
						mui.alert(data.msg, function() {

							location.reload();

						});

					} else {
						mui.alert(data.msg);
					}

				},
				error: function(xhr, type, errorThrown) {
					//异常处理；
					console.log(type);
				}
			});
		}
	}, function() {});

}

function order_money(obj) {
	mui.confirm('确认收款，您确定吗？', function(e) {
		if(e.index == 1) {
			$.ajax({
				url: order_money_fun,
				data: {
					is_mobile: 1,
					order_id: $(obj).attr('order_id'),
					user_id: localStorage.getItem('user_id')
				},
				dataType: 'json', //服务器返回json格式数据
				type: 'POST', //HTTP请求类型
				timeout: 10000, //超时时间设置为10秒；

				success: function(data) {
					var status = data.status;
					if(status == 1) {
						mui.alert(data.msg, function() {

							location.reload();

						});

					} else {
						mui.toast(data.msg);
					}

				},
				error: function(xhr, type, errorThrown) {
					//异常处理；
					console.log(type);
				}
			});
		}
	}, function() {});

}

function order_back_href(obj) {
	localStorage.setItem('hot_id', $(obj).attr('hot_id'))
	localStorage.setItem('order_id', $(obj).attr('order_id'))
	mui.confirm('申请退货，您确定吗？', function(e) {
		if(e.index == 1) {
			location.href = 'dealback.html'

		}
	}, function() {});

}

function order_cancel_href(obj) {
	order_cancel(obj)

}

function order_back(obj) {

	mui.confirm('退货，您确定吗？', function(e) {
		if(e.index == 1) {

			$.ajax({
				url: order_back_fun,
				data: {
					is_mobile: 1,
					order_id: $(obj).attr('order_id'),
					user_id: localStorage.getItem('user_id'),
					cancel_reason: localStorage.getItem('cancel_reason')
				},
				dataType: 'json', //服务器返回json格式数据
				type: 'POST', //HTTP请求类型
				timeout: 10000, //超时时间设置为10秒；

				success: function(data) {
					var status = data.status;
					if(status == 1) {
						mui.alert(data.msg, function() {
							location.href = data.url
						});

					} else {
						mui.toast(data.msg);
					}

				},
				error: function(xhr, type, errorThrown) {
					//异常处理；
					console.log(type);
				}
			});
		}
	}, function() {});

}

function order_cancel(obj) {

	mui.confirm('取消订单，您确定吗？', function(e) {
		if(e.index == 1) {

			$.ajax({
				url: order_cancel_fun,
				data: {
					is_mobile: 1,
					order_id: $(obj).attr('order_id'),
					user_id: localStorage.getItem('user_id'),
					cancel_reason: localStorage.getItem('cancel_reason')
				},
				dataType: 'json', //服务器返回json格式数据
				type: 'POST', //HTTP请求类型
				timeout: 10000, //超时时间设置为10秒；

				success: function(data) {
					var status = data.status;
					if(status == 1) {
						mui.alert(data.msg, function() {

							location.href = 'userorder.html'

						});

					} else {
						mui.toast(data.msg);
					}

				},
				error: function(xhr, type, errorThrown) {
					//异常处理；
					console.log(type);
				}
			});
		}
	}, 'div');

}

function order_upload_img(obj) {
	var img_url = $('.original_path').val();

	mui.confirm('确认提交凭证，您确定吗？', function(e) {
		if(e.index == 1) {
			$.ajax({
				url: order_upload_img_fun,
				data: {
					is_mobile: 1,
					order_id: $(obj).attr('order_id'),
					user_id: $(obj).attr('user_id'),
					img_url: img_url
				},
				dataType: 'json', //服务器返回json格式数据
				type: 'POST', //HTTP请求类型
				timeout: 10000, //超时时间设置为10秒；

				success: function(data) {
					var status = data.status;
					if(status == 1) {
						mui.alert(data.msg, function() {

							location.href = 'userorder.html'

						});

					} else {
						mui.toast(data.msg);
					}

				},
				error: function(xhr, type, errorThrown) {
					//异常处理；
					console.log(type);
				}
			});
		}
	}, function() {});

}

function get_user_shop_order(pageNum, pageSize,successCallback) {
	var user_id = localStorage.getItem('user_id');
	$.ajax({
		url: get_user_shop_order_list,
		data: {
			is_mobile: 1, 
			page_index: pageNum,
			page_size: pageSize,
			user_id: user_id
		},
		dataType: 'json', //服务器返回json格式数据
		type: 'POST', //HTTP请求类型
		timeout: 10000, //超时时间设置为10秒；

		success: function(data) {  

			mui.toast(data.msg)

			data = JSON.stringify(data);
			localStorage.setItem('ListData', data)
			data = JSON.parse(data);

			successCallback && successCallback(data);

			remove_toast();
		},
		error: function(xhr, type, errorThrown) {
			//异常处理；
			console.log(type);
		}
	});

}