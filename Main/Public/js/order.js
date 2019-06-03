var ServerIP = "";
var IP = localStorage.getItem('IP');
var PreUrl = localStorage.getItem('PreUrl');
/**
 * 获取订单列表
 */
var get_user_order_list = PreUrl + "Goods/get_user_order";
var get_user_shop_order_list = PreUrl + "Shop/get_user_order";
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

function get_user_give_order(type) {
	var user_id = localStorage.getItem('user_id');
	$.ajax({
		url: get_user_give_order_list,
		data: {
			is_mobile: 1,
			user_id: user_id,
			type: type
		},
		dataType: 'json', //服务器返回json格式数据
		type: 'POST', //HTTP请求类型
		timeout: 10000, //超时时间设置为10秒；

		success: function(data) {
			var status = data.status;
			if(status == 1) {
				var info = data.data;
				$('.detail-list ul li').remove()

				if(info == null) {
					$('.nodata').css('display', 'block');

				}
				if(info != null) {

					$('.nodata').css('display', 'none');
					$.each(info, function(index, content) {

						var item = '<li>';
						item += '	<div class="detail-hd">';
						item += '		<span class="fr">' + content.status_str + '</span>';
						item += '		<span>' + content.order_no + '</span>';
						item += '	</div>';
						item += '	<div class="detail-bd">';

						$.each(content.order_goods, function(index1, content1) {

							item += '	<div class="list-box">';
							item += '		<div class="img-box">';
							item += '		<img src="' + IP + content1.img_url + ' " />';
							item += '		</div>';
							item += '	<div class="txt-box">';
							item += '		<h2>' + content1.goods_title + '</h2>';
							item += '		<p class="note">';
							item += '			<span class="fr"></span>';
							item += '			<span class="price"> ' + content.order_amount + '元</span>';
							item += '			<span class="price">  </span>';
							item += '		</p>';
							item += '	</div>';
							item += '	</div>';

						});
						item += '	</div>';
						item += '	<div class="detail-ft">';
						item += '		<p class="fr">订单总量： ' + content.order_amount + ' </p>';
						item += '		<p>' + content.addtime_str + ' </p>';

						if(content.status == 1 && content.is_give == 1 && content.give_user_id == user_id) {

							item += '		<div class="ft-btn">';
							item += '			<div class="btn-box">';
							if(content.give_user_id == user_id) {
								item += '			<div class="time-item" order_id="' + content.id + '"> <strong id="second_show"><s></s>需赠送:' + content.user_name + '</strong>	    </div>';

							}

							item += '			<a  href="javascript:void(0)" onclick="order_give(this,2)"  class="order_info"   order_id="' + content.id + '"  hot_id="' + content.goods_hot_id + '">拒绝赠送</a>';
							item += '			<a href="javascript:void(0)" onclick="order_give(this,1)"  class="order_info"   order_id="' + content.id + '"  hot_id="' + content.goods_hot_id + '">确认赠送</a>';

							item += '		</div>';

						}
						if(content.order_id == 0) {

							item += '		<div class="ft-btn">';
							item += '			<div class="btn-box">';
							item += '			<a href="javascript:void(0)" onclick="order_cancel(this)"  class="order_info"   hot_id="' + content.hot_id + '">取消拼单</a>';

							item += '		</div>';

						}
						item += '		</div>';
						item += '	</div>';
						item += '	</li>';

						$('.detail-list ul').append(item);

					});
					//					$(".order_info").on('click', function() {
					//						localStorage.setItem('order_id', $(this).attr('id'));
					//						location.href = 'usercrownorder_show.html'
					//					})
				}
			}

		},
		error: function(xhr, type, errorThrown) {
			//异常处理；
			console.log(type);
		}
	});

}

function get_user_crowd_order(type) {
	$.ajax({
		url: get_user_crowd_order_list,
		data: {
			is_mobile: 1,
			user_id: localStorage.getItem('user_id'),
			type: type
		},
		dataType: 'json', //服务器返回json格式数据
		type: 'POST', //HTTP请求类型
		timeout: 10000, //超时时间设置为10秒；

		success: function(data) {
			var status = data.status;
			if(status == 1) {
				var info = data.data;
				$('.detail-list ul li').remove()

				if(info == null) {
					$('.nodata').css('display', 'block');

				}
				if(info != null) {

					$('.nodata').css('display', 'none');
					$.each(info, function(index, content) {

						var item = '<li>';
						item += '	<div class="detail-hd">';
						item += '		<span class="fr">' + content.status_str + '</span>';
						item += '		<span>' + content.order_no + '</span>';
						item += '	</div>';
						item += '	<div class="detail-bd money_div " style="background: cornsilk;">';

						$.each(content.order_goods, function(index1, content1) {

							item += '<div class="design-tag">	<p style="transform: rotate(45deg);color:white ;padding-left: 28px;padding-top: 8px;font-size: 5px;width: 70px; height:10px; line-height:10px">' + content1.order_status_str + ' </p></div>';

							item += '<div class="list-box detail-right">	<div class="user_left">	<p style="    margin-left: 16px;margin-top:0px;FONT-SIZE: 18px;width:100% " class="fr title"> &nbsp;' + content1.user_name + ' </p>	';
							item += '<p style="height:20px;line-height: 16px;margin-left:16px;margin-top:0px;border: 1px solid #509DE6;text-align: center;width: 60px;border-radius: 5px;color:#509DE6;" class="fr value">付款人 </p>	</div>';
							item += '<div class="user_right">	<div style="    WIDTH: 100%;   FLOAT: LEFT;margin-top:0PX">	<p class="fr title">手机号码</p>	<p style="margin-left:16px;text-align: center;width: 60px;color:#509DE6;line-height: 22px;" class="fr value">' + content1.mobile + '</p>';
							item += '</div>	<div>	<p class="fr title">' + content1.bank_name + ' </p>	<p style="margin-left:16px;text-align: center;width: 60px;color:#509DE6;line-height: 22px;" class="fr value">' + content1.bank_card + ' </p>	</div>	</div>	</div>';
							item += '	<div class="list-box">';
							item += '		<div class="img-box">';
							item += '		<img src="' + IP + content1.img_url + ' " />';
							item += '		</div>';
							item += '	<div class="txt-box">';
							item += '		<h2>' + content1.goods_title + '</h2>';
							item += '		<p>' + content1.spec_text + '</p>';
							item += '<div class="p-outer progress_wrapper" style="    margin-top: 0px;">	       <div class="p-bar" STYLE="width: 55%;MARGIN-LEFT:0">	          <div style="width: ' + content1.crown_percent + '%" class="p-bar-blue"></div>	     </div><span class="span2" data-sn-index="0" style="float: RIGHT;   line-height: 5px;    height: 15px;    font-size: .25REM;    LINE-HEIGHT: 15PX">' + content1.crown_percent + '%</span>	    </div>';

							item += '		<p class="note">';
							item += '			<span class="price"> ' + content1.crowd_price + '' + content.apat + '</span>';
							item += '		</p>';
							item += '	</div>';
							item += '	</div>';

							item += '	<div class="detail-ft">';
							if(content1.is_money == 1 && content1.money_status == 0) {

								item += '		<div class="ft-btn">';
								item += '			<div class="btn-box">';
								item += '			<a href="javascript:void(0)" onclick="order_money(this)"  class="order_info"   order_id="' + content1.order_id + '"  hot_id="' + content.goods_hot_id + '">确认收款</a>';
								item += '			<a href="javascript:void(0)" onclick="order_tousu(this)"  class="order_info"   order_id="' + content1.order_id + '">投诉提交</a>';

								item += '		</div>';
								item += '		</div>';

							}
							item += '		</div>';

						});
						item += '	</div>';
						item += '	<div class="detail-ft">';
						item += '		<p class="fr">拼单总量： ' + content.all_crowd_price + ' </p>';
						item += '		<p>' + content.addtime_str + ' </p>';

						if(content.order_id == 0) {

							item += '		<div class="ft-btn">';
							item += '			<div class="btn-box">';
							item += '			<a href="javascript:void(0)" onclick="order_cancel(this)"  class="order_info"   hot_id="' + content.hot_id + '">取消拼单</a>';

							item += '		</div>';

						}
						item += '		</div>';
						item += '	</div>';
						item += '	</li>';

						$('.detail-list ul').append(item);

					});
					//					$(".order_info").on('click', function() {
					//						localStorage.setItem('order_id', $(this).attr('id'));
					//						location.href = 'usercrownorder_show.html'
					//					})
				}
			}

		},
		error: function(xhr, type, errorThrown) {
			//异常处理；
			console.log(type);
		}
	});

}

function view_img(img) {

}

function go_upload(order_id, user_id) {
	localStorage.setItem('tousu_order_id', order_id)
	localStorage.setItem('tousu_user_id', user_id)
	location.href = 'tousu_upload.html';

}

function get_user_order(type) {
	var user_id = localStorage.getItem('user_id');
	$.ajax({
		url: get_user_order_list,
		data: {
			is_mobile: 1,
			type: type,
			user_id: user_id
		},
		dataType: 'json', //服务器返回json格式数据
		type: 'POST', //HTTP请求类型
		timeout: 10000, //超时时间设置为10秒；

		success: function(data) {
			var status = data.status;
			if(status == 1) {
				var info = data.data;
				$('.detail-list ul li').remove()

				if(info == null) {
					$('.nodata').css('display', 'block');

				}
				if(info != null) {

					$('.nodata').css('display', 'none');
					$.each(info, function(index, content) {

						var item = '<li>';
						item += '	<div class="detail-hd">';
						item += '		<span class="fr">' + content.status_str + '</span>';
						item += '		<span>' + content.order_no + '</span>';
						item += '	</div>';
						item += '	<div class="detail-bd">';

						$.each(content.order_goods, function(index1, content1) {

							item += '	<div class="list-box">';
							item += '<a href="javacript:void(0);" class="order_shopBar" page="1" ptag="7129.1.43"><em>' + content1.shop_title + '</em></a>';
							item += '		<div class="img-box">';
							item += '		<img src="' + IP + content1.img_url + ' " />';
							item += '		</div>';
							item += '	<div class="txt-box">';
							item += '		<h2>' + content1.goods_title + '</h2>';
							item += '		<p>' + content1.spec_text + '</p>';
							item += '		<p class="note">';
							item += '			<span class="fr"></span>';
							//							if(type == 5) {
							//								item += '			<span class="price"> ' + content.tousu_money + '元</span>';
							//							} else {
							item += '			<span class="price"> ¥' + content1.real_price + '</span>';
							//							}
							item += '			<span class="price"> 【' + content1.quantity + '件】</span>';
							item += '		</p>';
							item += '	</div>';
							item += '	</div>';

						});
						item += '	</div>';

						if(content.express != '' && content.express != null) {
							item += '	<div 	class="detail-ft"   >';
							item += '		<p class="fr">快递单号:' + content.express_no + ' </p>';
							item += '		<p>快递公司： ' + content.express + ' </p>';
							item += '	</div>';
						}

						item += '	<div class="detail-ft"  >';

						item += '		<p class="fr">订单总金额： ' + content.order_amount + ' </p>';
						item += '		<p>' + content.addtime_str + ' </p>';

						//代付款或者取消
						if(content.status == 1 && content.payment_status == 0) {

							item += '		<div class="ft-btn">';
							item += '			<div class="btn-box" style="float:right " >';

							if(content.status != 4) {

								item += '			<a href="javascript:void(0)"  onclick="order_payment(this,1)" class="order_info"   type="' + type + '" order_amount="' + content.order_amount + '"  order_id="' + content.id + '"     zfb_img="' + content.zfb_img + '"  wx_img="' + content.wx_img + '"     >确认付款</a>';
								item += '			<a href="javascript:void(0)"  onclick="order_cancel_href(this,1)" class="order_info"   type="' + type + '"  order_id="' + content.id + '"     zfb_img="' + content.zfb_img + '"  wx_img="' + content.wx_img + '"     >取消订单</a>';
							}
							item += '		</div>';

						}
						//申请退货
						if(content.status == 2 && content.payment_status == 2) {

							item += '		<div class="ft-btn">';
							item += '			<div class="btn-box" style="float:right " >';

							if(content.status != 4) {
								item += '			<a href="javascript:void(0)"  onclick="order_back_href(this)" class="order_info"   type="' + type + '"  order_id="' + content.id + '"     zfb_img="' + content.zfb_img + '"  wx_img="' + content.wx_img + '"     >申请退货</a>';
							}
							if(content.status == 2 && content.express_status == 2) {
								item += '		<a onclick="order_complete(this)" order_id="' + content.id + '" href="javascript:void(0)" class="oh_btn bg_6" >确认收货</a>';
							}
							item += '		</div>';

						}

						item += '		</div>';
						item += '	</div>';

						if(type == 2) {

						}

						item += '	</li>';

						$('.detail-list ul').append(item);
						if(content.remain_money_time > 0) {
							intDiff = content.remain_money_time;
							timer(intDiff, $('.detail-list ul li'));
						}
					});
					init_upload();
					$gallery = $("#gallery");
					$galleryImg = $("#galleryImg");
					$('#view_img').click(function() {
						$galleryImg.attr("style", $(this).attr("url"));
						$gallery.fadeIn(100);
					});
					$gallery.on("click", function() {
						$gallery.fadeOut(100);
					});
				}
			}

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

function order_payment(obj, type) {

	localStorage.setItem('zfb_img', $(obj).attr('zfb_img'))
	localStorage.setItem('wx_img', $(obj).attr('wx_img'))

	location.href = 'payCashier.html?order_id=' + $(obj).attr('order_id') + '&order_amount=' + $(obj).attr('order_amount');

	//	var txt = '付款';
	//	if(type == 2) {
	//		txt = '拒绝付款';
	//	}
	//
	//	mui.confirm(txt + '，您确定吗？', function(e) {
	//		if(e.index == 1) {
	//			$.ajax({
	//				url: order_payment_fun,
	//				data: {
	//					is_mobile: 1,
	//					order_id: $(obj).attr('order_id'),
	//					type: type
	//				},
	//				dataType: 'json', //服务器返回json格式数据
	//				type: 'POST', //HTTP请求类型
	//				timeout: 10000, //超时时间设置为10秒；
	//
	//				success: function(data) {
	//					var status = data.status;
	//					if(status == 1) {
	//						mui.alert(data.msg, function() {
	//
	//							location.reload();
	//
	//						});
	//
	//					} else {
	//						mui.toast(data.msg);
	//					}
	//
	//				},
	//				error: function(xhr, type, errorThrown) {
	//					//异常处理；
	//					console.log(type);
	//				}
	//			});
	//		}
	//	}, function() {});

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
		if(e.index == 1) {
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
	localStorage.setItem('hot_id', $(obj).attr('hot_id'))
	localStorage.setItem('order_id', $(obj).attr('order_id'))
	mui.confirm('取消订单，您确定吗？', function(e) {
		if(e.index == 1) {
			location.href = 'dealcancel.html'

		}
	}, function() {});

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
	}, function() {});

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

function get_user_shop_order(type) {
	var user_id = localStorage.getItem('user_id');
	$.ajax({
		url: get_user_shop_order_list,
		data: {
			is_mobile: 1,
			type: type,
			user_id: user_id
		},
		dataType: 'json', //服务器返回json格式数据
		type: 'POST', //HTTP请求类型
		timeout: 10000, //超时时间设置为10秒；

		success: function(data) {
			var status = data.status;
			if(status == 1) {
				var info = data.data;
				$('.contJs .order_box').remove()

				if(info == null) {
					$('.nodata').css('display', 'block');

				}
				if(info != null) {
					if(type != 6) {
						$('.nodata').css('display', 'none');
						$.each(info, function(index, content) {

							var item = '<div class="order_box removeJs " data_dealid="72858227959">';
							item += '	<div class="order_box_hd" page="3"> <span class="order_box_hd_label">订单号：</span>' + content.order_no + ' <i class="order_box_hd_del removeItemJs" style="display:none" ></i> </div>';
							item += '		<div class="order_head">';
							item += '		<a ordertype="22" target_href="//wqs.jd.com/order/n_detail_v2.shtml?ufc=35b42d1ec4be297e18463d1f148eb2bb&amp;deal_id=72858227959&amp;isoldpin=0&amp;sceneval=2" page="3" class="oh_content">';
							item += '		<p class="pState"><span>状<i></i>态：</span><em class="co_blue">' + content.status_str + '</em></p>';
							item += '	<p><span>总<i></i>价：</span><em class="co_red">¥' + content.order_amount + '</em></p>';
							item += '		</a>';

							if(content.status == 1) {

								item += '			<a href="javascript:void(0)"  onclick="order_cancel_href(this,1)" class="oh_btn toPay"   type="' + type + '"  order_id="' + content.id + '"     zfb_img="' + content.zfb_img + '"  wx_img="' + content.wx_img + '"     >取消订单</a>';

								item += '			<a href="javascript:void(0)"  onclick="order_payment(this,1)" class="oh_btn toPay"   type="' + type + '"   order_amount="' + content.order_amount + '"  order_id="' + content.id + '"     zfb_img="' + content.zfb_img + '"  wx_img="' + content.wx_img + '"     >确认付款</a>';
							}
							if(content.status == 2 && content.payment_status == 2) {
								if(content.status != 4) {
									item += '			<a href="javascript:void(0)"  onclick="order_back_href(this,1)" class="oh_btn toPay"   type="' + type + '"  order_id="' + content.id + '"     zfb_img="' + content.zfb_img + '"  wx_img="' + content.wx_img + '"     >申请退货</a>';
								}
								if(content.status == 2 && content.express_status == 2) {
									item += '		<a onclick="order_complete(this)" order_id="' + content.id + '" href="javascript:void(0)" class="oh_btn bg_6" >确认收货</a>';
								}
							}

							item += '		<a href="javascript:;" class="oh_btn bg_2" id="evaluate_72858227959" dealid="72858227959" style="display:none;" page="3" ptag="7129.2.2">去评价</a>';
							item += '		<a href="javascript:;" class="oh_btn bg_1" id="share_72858227959" style="display:none;" dealid="72858227959" page="3">分享赚积分</a>';
							item += '		</div>';
							item += '	<a  href="javascript:;" class="order_shopBar"    id="' + content.goods_shop_id + '"    name="' + content.shop_name + '"   ><em>' + content.shop_name + '</em></a>';

							$.each(content.order_goods, function(index1, content1) {

								item += '	<div class="order_item" itemsku="1257578927">';
								item += '	<div class="oi_content">';
								item += '	<a href="javascript:void(0)"><img class="image" src="' + IP + content1.img_url + '" onload="reportSpeedTime();" target_href="//item.m.jd.com/product/1257578927.html" page="3" ptag="7129.1.48" onerror="__reloadResource(this)"></a>';
								item += '	<a ordertype="22" href="javascript:void"       >';
								item += '	<div> <span class="skuTitle">' + content1.goods_title + '</span></div>';
								item += '		<p><span class="count">' + content1.quantity + '件</span><span class="price"></span></p>';
								item += '		</a>';
								item += '		</div>';
								item += '		</div>';

							});
							item += '		</div>';

							$('.contJs').append(item);

						});
						$(".order_shopBar").on('click', function() {
							localStorage.setItem('shop_id', $(this).attr('id'));
							localStorage.setItem('title', $(this).attr('name'));
							location.href = 'seller_show.html'
						});
					} else {

						$('.nodata').css('display', 'none');
						$.each(info, function(index, content) {

							var item = '<li>';
							item += '	<div class="detail-hd">';
							item += '		<span class="fr">' + content.status_str + '</span>';
							item += '		<span>' + content.order_no + '</span>';
							item += '	</div>';
							item += '	<div class="detail-bd">';

							$.each(content.order_goods, function(index1, content1) {

								item += '	<div class="list-box">';
								item += '<a href="javacript:void(0);" class="order_shopBar" page="1" ptag="7129.1.43"><em>' + content1.shop_title + '</em></a>';
								item += '		<div class="img-box">';
								item += '		<img src="' + IP + content1.img_url + ' " />';
								item += '		</div>';
								item += '	<div class="txt-box">';
								item += '		<h2>' + content1.goods_title + '</h2>';
								item += '		<p>' + content1.spec_text + '</p>';
								item += '		<p class="note">';
								item += '			<span class="fr"></span>';
								//							if(type == 5) {
								//								item += '			<span class="price"> ' + content.tousu_money + '元</span>';
								//							} else {
								item += '			<span class="price"> ¥' + content1.real_price + '</span>';
								//							}
								item += '			<span class="price"> 【' + content1.quantity + '件】</span>';
								item += '		</p>';
								item += '	</div>';
								item += '	</div>';

							});
							item += '	</div>';

							if(content.express != '' && content.express != null) {
								item += '	<div 	class="detail-ft"   >';
								item += '		<p class="fr">快递单号:' + content.express_no + ' </p>';
								item += '		<p>快递公司： ' + content.express + ' </p>';
								item += '	</div>';
							}

							item += '	<div class="detail-ft"  >';

							item += '		<p class="fr">订单总金额： ' + content.order_amount + ' </p>';
							item += '		<p>' + content.addtime_str + ' </p>';

							//代付款或者取消
							if(content.status == 1 && content.payment_status == 0) {

								item += '		<div class="ft-btn">';
								item += '			<div class="btn-box" style="float:right " >';

								if(content.status != 4) {

									item += '			<a href="javascript:void(0)"  onclick="order_payment(this,1)" class="order_info"   type="' + type + '" order_amount="' + content.order_amount + '"  order_id="' + content.id + '"     zfb_img="' + content.zfb_img + '"  wx_img="' + content.wx_img + '"     >确认付款</a>';
									item += '			<a href="javascript:void(0)"  onclick="order_cancel_href(this,1)" class="order_info"   type="' + type + '"  order_id="' + content.id + '"     zfb_img="' + content.zfb_img + '"  wx_img="' + content.wx_img + '"     >取消订单</a>';
								}
								item += '		</div>';

							}
							//申请退货
							if(content.status == 2 && content.payment_status == 2) {

								item += '		<div class="ft-btn">';
								item += '			<div class="btn-box" style="float:right " >';

								if(content.status != 4) {
									item += '			<a href="javascript:void(0)"  onclick="order_back_href(this)" class="order_info"   type="' + type + '"  order_id="' + content.id + '"     zfb_img="' + content.zfb_img + '"  wx_img="' + content.wx_img + '"     >申请退货</a>';
								}
								if(content.status == 2 && content.express_status == 2) {
									item += '		<a onclick="order_complete(this)" order_id="' + content.id + '" href="javascript:void(0)" class="oh_btn bg_6" >确认收货</a>';
								}
								item += '		</div>';

							}

							item += '		</div>';
							item += '	</div>';

							if(type == 2) {

							}

							item += '	</li>';

							$('.detail-list ul').append(item);
							if(content.remain_money_time > 0) {
								intDiff = content.remain_money_time;
								timer(intDiff, $('.detail-list ul li'));
							}
						});
						 

					}

				}
			}

		},
		error: function(xhr, type, errorThrown) {
			//异常处理；
			console.log(type);
		}
	});

}