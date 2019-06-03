var ServerIP = "";
var IP = localStorage.getItem('IP');
var PreUrl = localStorage.getItem('PreUrl');
var IndexUrl = localStorage.getItem('IndexUrl');
var UploadUrl = localStorage.getItem('UploadUrl');
var notice_list_val = PreUrl + "News/adminnews";
/**
 * 获取商品列表
 */
var get_user_addr_book = PreUrl + "Goods/get_user_addr_book";
var get_user_addr_book_info = PreUrl + "Goods/get_user_addr_book_info";
var set_user_addr_book_default = PreUrl + "Goods/set_user_addr_book_default";
var user_address_delete = PreUrl + "Goods/user_address_delete";
var get_userEdit_val = IndexUrl + "User/userEdit";
var user_recomend_tree_val = IndexUrl + "User/user_recomend_tree";
var myRecommendlist_val = PreUrl + "User/myRecommendlist";
var user_list_val = PreUrl + "User/user_list";
var user_achievement_list_val = PreUrl + "User/user_achievement";
var get_tree2_val = PreUrl + "Tree/tree2";
var get_user_feedback_list_val = PreUrl + "User/feedback_list";
var fenhong_val = PreUrl + "YouZi/adminClearingSave";
var adminTerminal_val = PreUrl + "YouZi/adminTerminal";
var weituo_val = PreUrl + "User/weituo";
var futou_val = PreUrl + "YouZi/futou";
var pingyi_val = PreUrl + "YouZi/pingyi";
var clearTags_val = PreUrl + "Goods/clearTags";
var user_address_edit_val = PreUrl + "Goods/user_address_edit";
var open_shop_step1_val = PreUrl + "User/open_shop_step1";
var open_shop_step2_val = PreUrl + "User/open_shop_step2";
var open_shop_step3_val = PreUrl + "User/open_shop_step3";
var open_shop_val = PreUrl + "User/open_shop";
var friend_dynamic_list_val = PreUrl + "User/friend_dynamic_list";
var seller_edit_val = PreUrl + "Shop/seller_edit";
var change_bank_val = PreUrl + "User/bankEdit";
var tixian_fun = UploadUrl + "payment/wxpayv3/tiqu.php";
var alipay_tixian_fun = UploadUrl + "payment/alipayrsa2/tiqu.php";
var add_tixian_fun = PreUrl + "Currency/frontCurrencyConfirm";
var update_tixian_fun = PreUrl + "Currency/CurrencyConfirm";
var get_recharge_list_val = PreUrl + "Recharge/currencyRecharge";
var video_list_val = PreUrl + "News/adminnewslist";
var tixian_list_val = PreUrl + "Currency/adminCurrency";
var detail_userday_money_list_val = PreUrl + "User/detail_userday_money_list";
var check_user_saoma_pay_val = PreUrl + "User/check_user_saoma_pay";
var get_user_seller_info_val = PreUrl + "User/get_user_seller_info";

function get_user_seller_info(user_id, successCallback) {
	$.ajax({
		url: get_user_seller_info_val,
		data: {
			is_mobile: 1,
			user_id: localStorage.getItem('user_id')
		},
		dataType: 'json', //服务器返回json格式数据
		type: 'POST', //HTTP请求类型
		timeout: 10000, //超时时间设置为10秒；

		success: function(data) {

			data = JSON.stringify(data);
			localStorage.setItem('ListData', data)
			data = JSON.parse(data);

			successCallback && successCallback(data);

		},
		error: function(xhr, type, errorThrown) {
			//异常处理；
			console.log(type);
		}
	});

}

function user_saoma_pay(user_id, successCallback) {
	$.ajax({
		url: check_user_saoma_pay_val,
		data: {
			is_mobile: 1,
			user_id: localStorage.getItem('user_id')
		},
		dataType: 'json', //服务器返回json格式数据
		type: 'POST', //HTTP请求类型
		timeout: 10000, //超时时间设置为10秒；

		success: function(data) {

			data = JSON.stringify(data);
			localStorage.setItem('ListData', data)
			data = JSON.parse(data);

			successCallback && successCallback(data);

		},
		error: function(xhr, type, errorThrown) {
			//异常处理；
			console.log(type);
		}
	});

}

function detail_userday_money_list(page_index, page_num, successCallback) {
	$.ajax({
		url: detail_userday_money_list_val,
		data: {
			is_mobile: 1,
			page_index: page_index,
			page_num: page_num,
			user_id: localStorage.getItem('user_id'),
			time: localStorage.getItem('detail_time')
		},
		dataType: 'json', //服务器返回json格式数据
		type: 'POST', //HTTP请求类型
		timeout: 10000, //超时时间设置为10秒；

		success: function(data) {

			data = JSON.stringify(data);
			localStorage.setItem('ListData', data)
			data = JSON.parse(data);

			successCallback && successCallback(data);

		},
		error: function(xhr, type, errorThrown) {
			//异常处理；
			console.log(type);
		}
	});

}

function get_user_list(page_index, page_num, successCallback) {
	$.ajax({
		url: user_list_val,
		data: {
			is_mobile: 1,
			page_index: page_index,
			page_num: page_num,
			user_id: localStorage.getItem('user_id')
		},
		dataType: 'json', //服务器返回json格式数据
		type: 'POST', //HTTP请求类型
		timeout: 10000, //超时时间设置为10秒；

		success: function(data) {

			data = JSON.stringify(data);
			localStorage.setItem('ListData', data)
			data = JSON.parse(data);

			successCallback && successCallback(data);

		},
		error: function(xhr, type, errorThrown) {
			//异常处理；
			console.log(type);
		}
	});

}

function get_tixian_list(page_index, page_num, successCallback) {
	$.ajax({
		url: tixian_list_val,
		data: {
			is_mobile: 1,
			page_index: page_index,
			page_num: page_num,
			user_id: localStorage.getItem('user_id')
		},
		dataType: 'json', //服务器返回json格式数据
		type: 'POST', //HTTP请求类型
		timeout: 10000, //超时时间设置为10秒；

		success: function(data) {

			data = JSON.stringify(data);
			localStorage.setItem('ListData', data)
			data = JSON.parse(data);

			successCallback && successCallback(data);

		},
		error: function(xhr, type, errorThrown) {
			//异常处理；
			console.log(type);
		}
	});

}

function get_video_list(page_index, page_num, successCallback) {
	$.ajax({
		url: video_list_val,
		data: {
			is_mobile: 1,
			type: 2,
			page_index: page_index,
			page_num: page_num,
			user_id: localStorage.getItem('user_id'),
			category_id: localStorage.getItem('category_id')
		},
		dataType: 'json', //服务器返回json格式数据
		type: 'POST', //HTTP请求类型
		timeout: 10000, //超时时间设置为10秒；

		success: function(data) {

			data = JSON.stringify(data);
			localStorage.setItem('ListData', data)
			data = JSON.parse(data);

			successCallback && successCallback(data);

		},
		error: function(xhr, type, errorThrown) {
			//异常处理；
			console.log(type);
		}
	});

}
//获取未登记终端
function get_terminal_list(page_index, page_num, successCallback) {
	localStorage.setItem('sns', '');

	var terminal_type = localStorage.getItem('terminal_type');
	var user_terminal_type = localStorage.getItem('user_terminal_type');
	console.log(terminal_type)
	$.ajax({
		url: adminTerminal_val,
		data: {
			is_mobile: 1,
			user_terminal_type: user_terminal_type,
			terminal_type: terminal_type,
			page_index: 1,
			page_num: 100000,
			user_id: localStorage.getItem('user_id')
		},
		dataType: 'json', //服务器返回json格式数据
		type: 'POST', //HTTP请求类型
		timeout: 10000, //超时时间设置为10秒；

		success: function(data) {

			data = JSON.stringify(data);
			localStorage.setItem('ListData', data)
			data = JSON.parse(data);

			successCallback && successCallback(data);

		},
		error: function(xhr, type, errorThrown) {
			//异常处理；
			console.log(type);
		}
	});

}

function terminal_list(page_index, page_num, successCallback) {
	var terminal_type = localStorage.getItem('terminal_type');
	var user_terminal_type = localStorage.getItem('user_terminal_type');

	var keywords = localStorage.getItem('keywords');
	console.log(terminal_type)
	$.ajax({
		url: adminTerminal_val,
		data: {
			is_mobile: 1,
			terminal_type: terminal_type,
			user_terminal_type: user_terminal_type,
			page_index: page_index,
			page_num: page_num,
			user_id: localStorage.getItem('user_id'),
			keywords: keywords
		},
		dataType: 'json', //服务器返回json格式数据
		type: 'POST', //HTTP请求类型
		timeout: 10000, //超时时间设置为10秒； 

		success: function(data) {
			data = JSON.stringify(data);
			localStorage.setItem('ListData', data)
			data = JSON.parse(data);

			successCallback && successCallback(data);

		},
		error: function(xhr, type, errorThrown) {
			//异常处理；
			console.log(type);
		}
	});

}

function seller_edit() {
	$.ajax({
		url: seller_edit_val,
		data: $('#editForm').serialize(),
		dataType: 'json', //服务器返回json格式数据
		type: 'post', //HTTP请求类型
		timeout: 10000, //超时时间设置为10秒；

		success: function(data) {
			var status = data.status;
			mui.toast(data.msg)
			if(status == 1) {
				localStorage.setItem('user', JSON.stringify(data.user));
			}
			if(data.url) {
				setTimeout(function() {
					mui.back();

				}, 2000);
			}

		},
		error: function(xhr, type, errorThrown) {
			//异常处理；
			console.log(xhr.responseText);
		}
	});

}

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
		$(obj).html(day + '天' + hour + '时' + minute + '分' + second + '秒');
		intDiff--;
	}, 1000);
}

function get_friend_dynamic_list() {
	$.ajax({
		url: friend_dynamic_list_val,
		data: {
			is_mobile: 1,
			user_id: localStorage.getItem('user_id')
		},
		dataType: 'json', //服务器返回json格式数据
		type: 'post', //HTTP请求类型
		timeout: 10000, //超时时间设置为10秒；

		success: function(data) {
			var status = data.status;
			if(status == 1) {
				$('.friend_dynamic_list .listonebox').remove();
				var info = data.data;
				if(info == null) {
					$('.nodata').css('display', 'block');

				}
				$.each(info, function(index, content) {

					if(content.avatar.indexOf("http") >= 0) {
						//						content.avatar=UploadUrl + content.avatar;
					} else {
						content.avatar = UploadUrl + content.avatar;

					}

					var item = '<div class="listonebox" style="cursor: pointer">';
					item += '	<div class="chpicbox clearfix">';
					item += '<div class="user_logo" style="background: url(' + content.avatar + '); background-size: 100% 100%;  "> </div>';
					item += '<div class="info"> 	<div class="chshoptxt clearfix">';
					item += '	<div class="listnametit "> 	<h3>  ' + content.nickname + '                    </h3>';
					item += '	<span class="nameicon">';
					item += '                              <a class="geti" title="已进行个体认证">' + content.age + ' </a>                        <a class="tuij" title="推荐商家">LV' + content.u_level + ' </a>                    </span>';
					item += '	</div>';
					item += '	<i class="fraddt"><img src="../images/shop.png" style="width: 20px; " /><span>' + content.shop_title + ' </span></i>';
					item += '</div>';

					item += '</div>';
					item += '	</div>';
					item += '	<div class="listnewddl">';
					item += '	<span style="display: none;"></span>' + content.title + ' </div>';

					item += '<div class="chphotobox clearfix">';
					item += '	<ul> ';

					$.each(content.albums_list, function(index1, content1) {

						item += '<li>';
						item += '	<img class="lazy" data-preview-src="" data-preview-group="' + content1.pid + '"  src="' + UploadUrl + content1.path + '">';
						item += '	</li> ';

					});

					item += '	</ul>';
					item += '<span></span>';
					item += '	</div>';
					item += '<div class="chpicinner">';
					item += '<span>' + content.addtime_str + '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' + content.distance + '公里</span>';
					item += '<span class="yuyrs">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<em>' + content.read_count + '</em>阅读</span>';

					item += '<span class="yuyrs" style="float: right;">倒计时:<em style="color: red;"   class="remain_time" remain_time="10000"   >50:20:11</em></span>';
					item += '</div>';
					item += '<div class="chpicinner">';
					item += '<div class="pro-price-new">';
					item += '<img class="people_logo" src="../images/boy.png" width="10px">';
					item += '	<img class="people_logo" src="../images/boy.png" width="10px">';
					item += '<img class="people_logo" src="../images/boy.png" width="10px">';
					item += '	<img class="people_logo" src="../images/girl.png" width="10px">';

					item += '	<div class="yuyrs pin"></div>';
					item += '<div class="yuyrs chat"></div>';
					item += '<div class="yuyrs collect"></div>';
					item += '</div>';
					item += '</div>';

					item += '</div>';
					$('.friend_dynamic_list').append(item);

				});

				var imgeGroup = mui.previewImage();
				$(".remain_time").each(function(i) {
					timer($(this).attr('remain_time'), this)
				});

			}

		},
		error: function(xhr, type, errorThrown) {
			//异常处理；
			console.log(type);
		}
	});

}
 

function open_shop_step3() {
	$.ajax({
		url: open_shop_step3_val,
		data: $('#editForm').serialize(),
		dataType: 'json', //服务器返回json格式数据
		type: 'post', //HTTP请求类型
		timeout: 10000, //超时时间设置为10秒；

		success: function(data) {
			var status = data.status;
			mui.toast(data.msg)
			if(status == 1) {
				localStorage.setItem('user', JSON.stringify(data.user));
			} else {
				mui.toast(data.msg)
				return;
			}
			console.log(data.url)
			if(data.url) {
				console.log(data.url)
				setTimeout(function() {
					openWindowWithTitle(data.title, data.url)
				}, 2000);
			} else {
				mui.back();
			}

		},
		error: function(xhr, type, errorThrown) {
			//异常处理；
			console.log(type);
		}
	});

}

function open_shop_step2() {
	$.ajax({
		url: open_shop_step2_val,
		data: $('#editForm').serialize(),
		dataType: 'json', //服务器返回json格式数据
		type: 'post', //HTTP请求类型
		timeout: 10000, //超时时间设置为10秒；

		success: function(data) {
			var status = data.status;
			mui.toast(data.msg)
			if(status == 1) {
				localStorage.setItem('user', JSON.stringify(data.user));
			} else {
				mui.toast(data.msg)
				return;
			}
			console.log(data.url)
			if(data.url) {
				setTimeout(function() {

					console.log(data.url)
					openWindowWithTitle(data.title, data.url)
				}, 2000);
			} else {
				mui.back();
			}

		},
		error: function(xhr, type, errorThrown) {
			//异常处理；
			console.log(type);
		}
	});

}

function open_shop_step1() {

	$.ajax({
		url: open_shop_step1_val,
		data: $('#editForm').serialize(),
		dataType: 'json', //服务器返回json格式数据
		type: 'post', //HTTP请求类型
		timeout: 10000, //超时时间设置为10秒；

		success: function(data) {
			var status = data.status;
			mui.toast(data.msg)
			if(status == 1) {
				localStorage.setItem('user', JSON.stringify(data.user));
			} else {
				mui.toast(data.msg)
				return;
			}
			if(data.url) {
				setTimeout(function() {

					openWindowWithTitle(data.title, data.url)
				}, 2000);
			} else {
				mui.back();
			}

		},
		error: function(xhr, type, errorThrown) {
			//异常处理；
			console.log(type);
		}
	});

}

function open_shop(shop_type) {
	var txt = '';
	if(shop_type == 1) {
		txt = '您现在进行实名认证';
	}
	if(shop_type == 2) {

		txt = '您现在进行实名认证';
	}
	mui.confirm(txt + '，您确定吗？', function(e) {
		if(e.index == 0) {
			$.ajax({
				url: open_shop_val,
				data: {
					user_id: localStorage.getItem('user_id'),
					is_mobile: 1,
					shop_type: shop_type
				},
				dataType: 'json', //服务器返回json格式数据
				type: 'post', //HTTP请求类型
				timeout: 10000, //超时时间设置为10秒；

				success: function(data) {
					var status = data.status;

					if(status == 1) {
						localStorage.setItem('user', JSON.stringify(data.user));
						mui.toast(data.msg)
					} else {
						mui.toast(data.msg)
						return;
					}
					if(data.url) {
						setTimeout(function() {

							openWindowWithTitle(data.title, data.url)
						}, 2000);
					} else {
						mui.back();
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

function change_bank() {
	console.log($('#bank_name').val())
	$.ajax({
		url: change_bank_val,
		data: {
			is_mobile: 1,
			user_id: localStorage.getItem('user_id'),
			user_tel: $('#user_tel').val(),
			bank_name: $('#bank_name').val(),
			bank_card: $('#bank_card').val(),
			yzm: $('#yzm').val()
		},
		dataType: 'json', //服务器返回json格式数据
		type: 'post', //HTTP请求类型
		timeout: 10000, //超时时间设置为10秒；

		success: function(data) {
			var status = data.status;
			mui.toast(data.msg)
			if(status == 1) {
				localStorage.setItem('change_user', JSON.stringify(data));

				mui.back();
			}

		},
		error: function(xhr, type, errorThrown) {
			//异常处理；
			console.log(type);
		}
	});

}

function user_address_edit() {
	$.ajax({
		url: user_address_edit_val,
		data: $('#editForm').serialize(),
		dataType: 'json', //服务器返回json格式数据
		type: 'post', //HTTP请求类型
		timeout: 10000, //超时时间设置为10秒；

		success: function(data) {
			var status = data.status;
			mui.toast(data.msg)
			if(status == 1) {

			}
			if(data.is_shopping == 1) {
				localStorage.setItem('shoppingCartCount', data.cart_num)
				localStorage.setItem('zfb_img', data.zfb_img)
				localStorage.setItem('wx_img', data.wx_img)

			}
			if(data.url) {
				setTimeout(function() {
					localStorage.setItem('set_address', 1);
					mui.back();
				}, 2000);

			}
		},
		error: function(xhr, type, errorThrown) {
			//异常处理；
			console.log(type);
		}
	});

}

function clearTags() {
	$.ajax({
		url: clearTags_val,
		data: {
			is_mobile: 1,
			user_id: localStorage.getItem('user_id')
		},
		dataType: 'json', //服务器返回json格式数据
		type: 'post', //HTTP请求类型
		timeout: 10000, //超时时间设置为10秒；

		success: function(data) {
			var status = data.status;
			if(status == 1) {
				mui.toast(data.info)
				$('.search-toast').addClass('displayNone')
				$('.recent-search').css('display', 'none')

			}
			//lang()

		},
		error: function(xhr, type, errorThrown) {
			//异常处理；
			console.log(type);
		}
	});

}

function init_tree(json) {
	var curMenu = null,
		zTree_Menu = null;
	var setting = {
		view: {
			selectedMulti: false
		},
		data: {
			simpleData: {
				enable: true
			}
		}
	};

	var zNodes = json;
	$.fn.zTree.init($("#treeDemo"), setting, zNodes);
	var newCount = 1;

	function addHoverDom(treeId, treeNode) {
		var sObj = $("#" + treeNode.tId + "_span");
		if(treeNode.editNameFlag || $("#addBtn_" + treeNode.tId).length > 0) return;
		var addStr = "<span class='button add' id='addBtn_" + treeNode.tId +
			"' title='add node' onfocus='this.blur();'></span>";
		sObj.after(addStr);
		var btn = $("#addBtn_" + treeNode.tId);
		if(btn) btn.bind("click", function() {
			var zTree = $.fn.zTree.getZTreeObj("treeDemo");
			zTree.addNodes(treeNode, {
				id: (100 + newCount),
				pId: treeNode.id,
				name: "new node" + (newCount++)
			});
			return false;
		});
	};

	function removeHoverDom(treeId, treeNode) {
		$("#addBtn_" + treeNode.tId).unbind().remove();
	};

}

function get_user_team(pageNum, pageSize, successCallback) {
	var keywords = localStorage.getItem('keywords');
	var tel_type = localStorage.getItem('tel_type');
	console.log(tel_type)
	$.ajax({
		url: myRecommendlist_val,
		data: {
			is_mobile: 1,
			p: 1,
			user_id: localStorage.getItem('user_id'),
			page_index: pageNum,
			page_size: pageSize,
			keywords: keywords,
			type: tel_type
		},
		dataType: 'json', //服务器返回json格式数据
		type: 'post', //HTTP请求类型
		timeout: 10000, //超时时间设置为10秒；

		success: function(data) {

			data = JSON.stringify(data);
			localStorage.setItem('ListData', data)
			data = JSON.parse(data);
			var status = data.status;

			successCallback && successCallback(data);

			//lang()

		},
		error: function(xhr, type, errorThrown) {
			//异常处理；
			console.log(type);
		}
	});

}

function user_achievement_list(successCallback) {
	var keywords = localStorage.getItem('keywords');
	$.ajax({
		url: user_achievement_list_val,
		data: {
			is_mobile: 1,
			p: 1,
			user_id: localStorage.getItem('view_user_id'),
			keywords: keywords
		},
		dataType: 'json', //服务器返回json格式数据
		type: 'post', //HTTP请求类型
		timeout: 10000, //超时时间设置为10秒；

		success: function(data) {
			data = JSON.stringify(data);
			console.log(data)
			localStorage.setItem('ListData', data)

			successCallback && successCallback(data);

		},
		error: function(xhr, type, errorThrown) {
			//异常处理；
			console.log(type);
		}
	});

}

function get_user_recomend_tree() {
	$.ajax({
		url: user_recomend_tree_val,
		data: {
			is_mobile: 1,
			user_id: localStorage.getItem('user_id')
		},
		dataType: 'json', //服务器返回json格式数据
		type: 'post', //HTTP请求类型
		timeout: 10000, //超时时间设置为10秒；

		success: function(data) {
			var status = data.status;
			if(status == 1) {
				var info = data.zNodes;

				var zNodes = jQuery.parseJSON(info);
				init_tree(zNodes)

			}
			//lang()

		},
		error: function(xhr, type, errorThrown) {
			//异常处理；
			console.log(type);
		}
	});

}

function get_tree2(tree_user_id) {
	localStorage.setItem('tree_user_id', tree_user_id)
	var tree_user_id = localStorage.getItem('tree_user_id')
	if(tree_user_id == null || tree_user_id == '') {
		tree_user_id = localStorage.getItem('user_id');
	}
	$.ajax({
		url: get_tree2_val,
		data: {
			is_mobile: 1,
			IP: IP,
			user_id: tree_user_id
		},
		dataType: 'json', //服务器返回json格式数据
		type: 'post', //HTTP请求类型
		timeout: 10000, //超时时间设置为10秒；

		success: function(data) {
			var status = data.status;
			if(status == 1) {

				$('.wop').html(data.wop)
				$(".user").on('click', function() {
					localStorage.setItem('FID', $(this).attr('FID'));
					localStorage.setItem('RID', $(this).attr('RID'));
					localStorage.setItem('TPL', $(this).attr('TPL'));
					location.href = 'register.html'
				})
			}
			//lang()

		},
		error: function(xhr, type, errorThrown) {
			//异常处理；
			console.log(type);
		}
	});

}

function get_user_feedback_list(pageNum, pageSize, successCallback) {
	$.ajax({
		url: get_user_feedback_list_val,
		data: {
			is_mobile: 1,
			page_index: pageNum,
			page_size: pageSize,
			user_id: localStorage.getItem('user_id')
		},
		dataType: 'json', //服务器返回json格式数据
		type: 'post', //HTTP请求类型
		timeout: 10000, //超时时间设置为10秒；

		success: function(data) {
			data = JSON.stringify(data);
			localStorage.setItem('ListData', data)
			data = JSON.parse(data);

			successCallback && successCallback(data);

		},
		error: function(xhr, type, errorThrown) {
			//异常处理；
			console.log(type);
		}
	});

}
var feedback_val = PreUrl + "User/feedback";

function feedback() {

	var user = localStorage.getItem('user');
	user = JSON.parse(user);

	plus.nativeUI.showWaiting("提交中...");
	$.ajax({
		url: feedback_val,
		data: {
			is_mobile: 1,
			user_id: localStorage.getItem('user_id'),
			txtContent: $('#txtContent').val(),
			txtTitle: $('#txtTitle').val()

		},
		dataType: 'json', //服务器返回json格式数据
		type: 'POST', //HTTP请求类型
		timeout: 10000, //超时时间设置为10秒；

		success: function(data) {
			plus.nativeUI.closeWaiting();

			mui.toast(data.msg)

			if(data.status == 1) {
				location.reload()
			}
			remove_toast();
		},
		error: function(xhr, type, errorThrown) {

			mui.toast('提交失败');
			//异常处理；
			console.log(type);
		}
	});

}

function get_user_addr_book_list() {
	$.ajax({
		url: get_user_addr_book,
		data: {
			is_mobile: 1,
			user_id: localStorage.getItem('user_id')
		},
		dataType: 'json', //服务器返回json格式数据
		type: 'post', //HTTP请求类型
		timeout: 10000, //超时时间设置为10秒；

		success: function(data) {
			var status = data.status;
			if(status == 1) {
				var info = data.data;
				$('.address_list .mui-card').remove()
				$('.nodata').css('display', 'none');
				if(info == null) {
					$('.nodata').css('display', 'block');

				}

				var select_address = localStorage.getItem('select_address')
				$.each(info, function(index, content) {
					var selected = '';
					var noChoose = '';
					if(select_address != 1) {
						noChoose = 'noChoose ';
					}
					var mobile = content.mobile.substr(0, 3) + '****' + content.mobile.substr(7);
					var item = '<div class="address address_info address_select" id="' + content.id + '" >';
					if(content.is_default == 1) {
						selected = ' selected';
					}
					item += ' <ul  id="' + content.id + '"   class="' + noChoose + selected + '">';
					item += ' <li><strong>' + content.accept_name + '</strong>&nbsp;<strong>' + mobile + '</strong></li>';
					item += ' <li> ' + content.province + content.city + content.area + '' + content.address + ' </li>';
					item += ' <li class="edit"  id="' + content.id + '" adid="744921693" type="1">编辑</li>';
					item += ' </ul>';
					item += ' <p class="act" adid="744921693" type="1"><span class="del">删除</span></p>';
					item += ' 	</div>';
					var item = '<div class="mui-card " id="' + content.id + '">';

					var active = '默认';

					if(content.is_default == 0) {
						active = '';
					}

					item += ' <div class="mui-card-header"  id="' + content.id + '">' + content.accept_name + '' + mobile + '';
					item += ' <a class="mui-card-link " style="color:red"  id="' + content.id + '" >' + active + '</a> ';
					item += '  </div>';
					item += ' <div class="mui-card-content">';
					item += ' <div class="mui-card-content-inner">';
					item += content.province + content.city + content.area + '' + content.address + ' ';
					item += ' </div>';
					item += ' </div>';
					item += ' <div class="mui-card-footer"><a class="mui-card-link address_del"  id="' + content.id + '" >删除</a><a class="mui-card-link address_info" id="' + content.id + '">编辑</a>  </div>';
					item += ' </div>';
					$('.address_list').append(item);

				});

				if(select_address == 1) {
					$(".mui-card").on('click', function() {
						set_user_addr_default($(this).attr('id'))

					})
				}

				$(".address_info").on('click', function() {
					localStorage.setItem('address_id', $(this).attr('id'));
					openWindowWithTitle('地址编辑', 'useraddress_edit.html')
				})
				$(".address_del").on('click', function() {

					var id = $(this).attr('id')

					mui.confirm('删除地址，您确定吗？', function(e) {
						if(e.index == 0) {
							$.ajax({
								url: user_address_delete,
								data: {
									is_mobile: 1,
									checkId: id
								},
								dataType: 'json', //服务器返回json格式数据
								type: 'POST', //HTTP请求类型
								timeout: 10000, //超时时间设置为10秒；

								success: function(data) {
									var status = data.status;
									if(status == 1) {
										mui.alert(data.msg, function() {

											mui('.page__bd').pullRefresh().pulldownLoading();
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
				})

				if(select_address == 1) {
					$(".address ul").on('click', function() {
						console.log($(this).attr('id'))
						set_user_addr_default($(this).attr('id'))
					})

				}

				//lang()
			}

		},
		error: function(xhr, type, errorThrown) {
			//异常处理；
			console.log(type);
		}
	});

}

function set_user_addr_default(address_id) {
	console.log(address_id)
	$.ajax({
		url: set_user_addr_book_default,
		data: {
			is_mobile: 1,
			id: address_id,
			user_id: localStorage.getItem('user_id')
		},
		dataType: 'json', //服务器返回json格式数据
		type: 'POST', //HTTP请求类型
		timeout: 10000, //超时时间设置为10秒；

		success: function(data) {

			var status = data.status;
			if(status == 1) {
				localStorage.setItem('set_address', 1);
				mui.back()
			}
			//lang()

		},
		error: function(xhr, type, errorThrown) {
			//异常处理；
			console.log(type);
		}
	});

}

function get_user_addr_book_show(address_id) {
	$.ajax({
		url: get_user_addr_book_info,
		data: {
			is_mobile: 1,
			id: address_id
		},
		dataType: 'json', //服务器返回json格式数据
		type: 'POST', //HTTP请求类型
		timeout: 10000, //超时时间设置为10秒；

		success: function(data) {
			var $target = $('#J_Address2');

			if(address_id > 0) {

			} else {
				$('.title').html('新增地址')
			}

			var status = data.status;
			if(status == 1) {
				var info = data.data;
				if(info != null) {
					$('#txtAcceptName').val(info.accept_name);
					$('#txtAddress').val(info.address);
					$('#txtMobile').val(info.mobile);
					$('#txtTelphone').val(info.telphone);
					$('#txtEmail').val(info.email);
					$('#city-picker').val(info.area);
					$('#area').val(info.area);
					$('#id').val(info.id);
					$('#is_default').val(info.is_default);

					if(info.is_default == 0) {
						$('#btnDel').css('display', 'block')

						$('#btnDel').attr('onclick', 'ExecPostBack(' + info.id + ')')

					} else {
						$('.mui-switch').addClass('mui-active')

					}
					if(info.province == null) {

					} else {

						$target.citySelect({
							provance: info.province,
							city: info.city,
							area: info.area
						});

						$target.val(info.province + info.city + info.area)
						$('#txtProvince').val(info.province);
						$('#txtCity').val(info.city);
						$('#txtArea').val(info.area);

					}
				} else {
					$target.citySelect();
				}

				$target.on('click', function(event) {
					event.stopPropagation();
					$target.citySelect('open');
				});

				$target.on('done.ydui.cityselect', function(ret) {
					$(this).val(ret.provance + ' ' + ret.city + ' ' + ret.area);
					$('#txtProvince').val(ret.provance);
					$('#txtCity').val(ret.city);
					$('#txtArea').val(ret.area);
				});

			}
			//lang()

		},
		error: function(xhr, type, errorThrown) {
			//异常处理；
			console.log(type);
		}
	});

}

function ExecPostBack(checkValue) {
	if(arguments.length == 1) {

		mui.confirm('删除地址，您确定吗？', function(e) {
			if(e.index == 0) {
				$.ajax({
					url: user_address_delete,
					data: {
						is_mobile: 1,
						checkId: checkValue
					},
					dataType: 'json', //服务器返回json格式数据
					type: 'POST', //HTTP请求类型
					timeout: 10000, //超时时间设置为10秒；

					success: function(data) {
						var status = data.status;
						if(status == 1) {
							mui.toast(data.msg)
							localStorage.setItem('set_address', 1);
							mui.back();

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

	} else {
		var valueArr = '';
		$("input[name='checkId']:checked").each(function(i) {
			valueArr += $(this).val();
			if(i < $("input[name='checkId']:checked").length - 1) {
				valueArr += ","
			}
		});
		ExecDelete('user_address_delete', valueArr, '#turl');
	}
}

function get_userEdit(user_id) {
	$.ajax({
		url: get_userEdit_val,
		data: {
			is_mobile: 1,
			user_id: user_id
		},
		dataType: 'json', //服务器返回json格式数据
		type: 'GET', //HTTP请求类型
		timeout: 10000, //超时时间设置为10秒；

		success: function(data) {
			if(address_id > 0) {

			} else {
				$('.title').html('新增地址')
			}

			var status = data.status;
			if(status == 1) {
				var info = data.data;
				$('#txtAcceptName').val(info.accept_name);
				$('#txtAddress').val(info.address);
				$('#txtMobile').val(info.mobile);
				$('#txtTelphone').val(info.telphone);
				$('#txtEmail').val(info.email);
				$('#id').val(info.id);

				var mypcas = new PCAS("txtProvince,所属省份", "txtCity,所属城市", "txtArea,所属地区");
				var areaArr = (info.area).split(",");
				if(areaArr.length == 3) {
					mypcas.SetValue(areaArr[0], areaArr[1], areaArr[2]);
				}
			}
			//lang()

		},
		error: function(xhr, type, errorThrown) {
			//异常处理；
			console.log(type);
		}
	});

}

function recommend() {
	mui.confirm('领取分红，您确定吗？', function(e) {
		if(e.index == 1) {
			$.ajax({
				url: fenhong_val,
				data: {
					is_mobile: 1,
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
	}, 'div');

}

function weituo() {
	var user = localStorage.getItem('user');
	user = JSON.parse(user);
	var txt = '';
	if(user.is_tixian == 1) {
		txt = '取消委托，您确定吗？';
	}
	if(user.is_tixian == 0) {
		txt = '委托拼单，您确定吗？';
	}

	mui.confirm(txt, function(e) {
		if(e.index == 1) {
			$.ajax({
				url: weituo_val,
				data: {
					is_mobile: 1,
					user_id: localStorage.getItem('user_id'),
					price: user.agent_use,
					bank_card: user.bank_card,
					bankusername: user.user_name,
					bank_name: user.bank_name,
					password: user.pwd1
				},
				dataType: 'json', //服务器返回json格式数据
				type: 'POST', //HTTP请求类型
				timeout: 10000, //超时时间设置为10秒；

				success: function(data) {
					var status = data.status;
					if(status == 1) {
						mui.alert(data.msg, function() {

							localStorage.setItem('user', JSON.stringify(data.data));
							location.reload();

						});

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

function fenhong() {

	$.ajax({
		url: fenhong_val,
		data: {
			is_mobile: 1,
			user_id: localStorage.getItem('user_id')
		},
		dataType: 'json', //服务器返回json格式数据
		type: 'POST', //HTTP请求类型
		timeout: 10000, //超时时间设置为10秒；

		success: function(data) {
			var status = data.status;
			if(status == 1) {
				$('.mui-popup-backdrop').css('display', 'block')
				$('.sign_div').css('display', 'block')
				$('.sign_div .mui-popup-text').html(data.msg)

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

function futou() {
	var user = localStorage.getItem('user');
	user = JSON.parse(user);

	if(user.limit_money <= 0 && user.get_level == 1) {
		//		weui.alert('未激活,不能操作')
	}
	weui.confirm('复投，您确定吗？', function() {
		$.ajax({
			url: futou_val,
			data: {
				is_mobile: 1,
				user_id: localStorage.getItem('user_id')
			},
			dataType: 'json', //服务器返回json格式数据
			type: 'POST', //HTTP请求类型
			timeout: 10000, //超时时间设置为10秒；

			success: function(data) {
				var status = data.status;
				if(status == 1) {
					weui.alert(data.msg, function() {

						location.reload();

					});

				} else {
					weui.alert(data.msg);
				}
				//				weui.toast(data.msg);

			},
			error: function(xhr, type, errorThrown) {
				//异常处理；
				console.log(type);
			}
		});
	}, function() {});

}

function pingyi() {
	var user = localStorage.getItem('user');
	user = JSON.parse(user);

	var money_a = user.out_money_value - user.extra_limit_money - user.extra_limit_money1 / 3;
	money_a = Math.round(money_a, 2);
	weui.confirm('释放平移额度,需扣除激活点值<span style="color:red">' + money_a + '</span>,您确定吗？', function() {
		$.ajax({
			url: pingyi_val,
			data: {
				is_mobile: 1,
				user_id: localStorage.getItem('user_id')
			},
			dataType: 'json', //服务器返回json格式数据
			type: 'POST', //HTTP请求类型
			timeout: 10000, //超时时间设置为10秒；

			success: function(data) {
				var status = data.status;
				if(status == 1) {
					weui.alert(data.msg, function() {

						location.reload();

					});

				} else {
					weui.alert(data.msg);
				}
				//				weui.toast(data.msg);

			},
			error: function(xhr, type, errorThrown) {
				//异常处理；
				console.log(type);
			}
		});
	}, function() {});

}

function transfertob_edit() {
	location.href = 'transfertob_edit.html'
}

var get_zhihang_list_val = PreUrl + "User/get_zhihang";

function get_zhihang_list() {
	console.log(localStorage.getItem('city'))
	console.log(localStorage.getItem('province'))
	console.log(localStorage.getItem('area'))
	console.log(localStorage.getItem('bank'))
	console.log(localStorage.getItem('bank_keywords'))
	$.ajax({
		url: get_zhihang_list_val,
		data: {
			is_mobile: 1,
			p: 1,
			user_id: localStorage.getItem('user_id'),
			province: localStorage.getItem('province'),
			city: localStorage.getItem('city'),
			bank: localStorage.getItem('bank'),
			keywords: localStorage.getItem('bank_keywords')
		},
		dataType: 'json', //服务器返回json格式数据
		type: 'POST', //HTTP请求类型
		timeout: 10000, //超时时间设置为10秒；

		success: function(data) {
			console.log(JSON.stringify(data))

			var status = data.status;
			if(status == 1) {
				$('.poslist li').remove();
				var info = data.data;
				//普通示例

				var shopList1 = []; //可以选择的列表实际上是一个数组。我们用shopList1来表
				var user = localStorage.getItem('seller');

				user = JSON.parse(user);
				var index; //用index来记住给你的那个默认值的下标
				var List = data.data;
				$('.nodata').css('display', 'none')
				if(List == null) {
					$('.nodata').css('display', 'block')
					mui.toast('没有找到对应支行')
					return;

				}
				$.each(List, function(index, content) {

					var item = '<li class="mui-table-view-cell">';
					item += '<a class="mui-navigate-right">';
					item += content.fh;
					item += '</a>';
					item += '</li>';

					$('.poslist').append(item);
				})
			}

		},
		error: function(xhr, type, errorThrown) {
			//异常处理；
			console.log(type);
		}
	});

}
/**
 * 获取用户收益明细
 */
var get_user_income_val = PreUrl + "User/user_income";
var get_user_money_list_val = PreUrl + "User/user_money_list";

function user_income(user_id, pageNum, pageSize, successCallback) {

	var income_type = localStorage.getItem('income_type')

	console.log(income_type)
	$.ajax({
		url: get_user_income_val,
		data: {
			is_mobile: 1,

			user_id: user_id,
			page_index: pageNum,
			page_size: pageSize,

			type: income_type
		},
		dataType: 'json', //服务器返回json格式数据
		type: 'POST', //HTTP请求类型
		timeout: 10000, //超时时间设置为10秒；

		success: function(data) {
			var status = data.status;
			//mui('.content').pullRefresh().endPulldown();
			//			mui('.page__bd').pullRefresh().endPulldown();
			localStorage.setItem('user_income', JSON.stringify(data));

			data = JSON.stringify(data);
			localStorage.setItem('ListData', data)
			data = JSON.parse(data);
			var status = data.status;

			successCallback && successCallback(data);
			////lang()

		},
		error: function(xhr, type, errorThrown) {
			//异常处理；
			console.log(JSON.stringify(xhr));
		}
	});

}

function user_money_list(user_id, successCallback) {

	$.ajax({
		url: get_user_money_list_val,
		data: {
			is_mobile: 1,

			user_id: user_id
		},
		dataType: 'json', //服务器返回json格式数据
		type: 'POST', //HTTP请求类型
		timeout: 10000, //超时时间设置为10秒；

		success: function(data) {

			var status = data.status;
			//mui('.content').pullRefresh().endPulldown();
			//			mui('.page__bd').pullRefresh().endPulldown();
			localStorage.setItem('user_money_list', JSON.stringify(data));

			data = JSON.stringify(data);
			localStorage.setItem('ListData', data)
			data = JSON.parse(data);
			var status = data.status;

			successCallback && successCallback(data);

			////lang()

		},
		error: function(xhr, type, errorThrown) {
			//异常处理；
			console.log(JSON.stringify(xhr));
		}
	});

}

var user_day_money_list_val = PreUrl + "User/user_day_money_list";

function user_day_money_list(time, user_id, successCallback) {

	$.ajax({
		url: user_day_money_list_val,
		data: {
			is_mobile: 1,

			user_id: user_id,

			time: time
		},
		dataType: 'json', //服务器返回json格式数据
		type: 'POST', //HTTP请求类型
		timeout: 10000, //超时时间设置为10秒；

		success: function(data) {

			var status = data.status;
			data = JSON.stringify(data);
			localStorage.setItem('ListData', data)
			data = JSON.parse(data);
			var status = data.status;

			successCallback && successCallback(data);

			////lang()

		},
		error: function(xhr, type, errorThrown) {
			//异常处理；
			console.log(JSON.stringify(xhr));
		}
	});

}
/**
 * 获取用户信息
 */
var get_tixian_info_val = PreUrl + "Currency/CurrencyInfo";

function get_tixian_info(tixian_id, successCallback) {

	$.ajax({
		url: get_tixian_info_val,
		data: {
			is_mobile: 1,

			id: tixian_id
		},
		dataType: 'json', //服务器返回json格式数据
		type: 'POST', //HTTP请求类型
		timeout: 10000, //超时时间设置为10秒；

		success: function(data) {
			data = JSON.stringify(data);
			localStorage.setItem('ListData', data)
			data = JSON.parse(data);

			successCallback && successCallback(data);

			////lang()

		},
		error: function(xhr, type, errorThrown) {
			//异常处理；
			console.log(JSON.stringify(xhr));
		}
	});

}

/**
 * 获取用户信息
 */
var get_user_info_val = PreUrl + "User/userInfo";

function get_user_info(user_id, successCallback) {

	$.ajax({
		url: get_user_info_val,
		data: {
			is_mobile: 1,
			id: user_id
		},
		dataType: 'json', //服务器返回json格式数据
		type: 'POST', //HTTP请求类型
		timeout: 10000, //超时时间设置为10秒； 

		success: function(data) {
			var status = data.status;
			//mui('.content').pullRefresh().endPulldown(); 
			localStorage.setItem('user', JSON.stringify(data.data));

			localStorage.setItem('userbank', JSON.stringify(data.userbank));

			localStorage.setItem('user_terminal', JSON.stringify(data.data.user_terminal));

			localStorage.setItem('re_share_sub_title', data.re_share_sub_title);
			localStorage.setItem('re_share_title', data.re_share_title);

			data = JSON.stringify(data);
			localStorage.setItem('ListData', data)
			data = JSON.parse(data);
			var status = data.status;

			successCallback && successCallback(data);

			////lang()

		},
		error: function(xhr, type, errorThrown) {
			//异常处理；
			console.log(JSON.stringify(xhr));
		}
	});

}

var bankEdit_val = PreUrl + "User/set_bank";

function bankEdit() {

	var user = localStorage.getItem('user');
	user = JSON.parse(user);

	plus.nativeUI.showWaiting("提交中...");
	$.ajax({
		url: bankEdit_val,
		data: {
			is_mobile: 1,
			user_id: localStorage.getItem('user_id'),
			bank_name: $('#bank_name').val(),
			bank_card: $('#bank_card').val(),
			bank_address: $('#bank_address').val(),
			bank_username: $('#bank_username').val()

		},
		dataType: 'json', //服务器返回json格式数据
		type: 'POST', //HTTP请求类型
		timeout: 10000, //超时时间设置为10秒；

		success: function(data) {
			plus.nativeUI.closeWaiting();

			mui.toast(data.msg)

			if(data.status == 1) {
				localStorage.setItem('user', JSON.stringify(data.data))
				mui.back();

			}
			remove_toast();
		},
		error: function(xhr, type, errorThrown) {

			mui.toast('提交失败');
			//异常处理；
			console.log(type);
		}
	});

}
/**
 * 获取用户信息
 */
var get_trade_rank_val = PreUrl + "User/get_trade_rank";

function get_trade_rank_list(successCallback) {
	var trade_type = localStorage.getItem('trade_type')
	$.ajax({
		url: get_trade_rank_val,
		data: {
			is_mobile: 1,
			trade_type: trade_type
		},
		dataType: 'json', //服务器返回json格式数据
		type: 'POST', //HTTP请求类型
		timeout: 10000, //超时时间设置为10秒；

		success: function(data) {
			var status = data.status;
			data = JSON.stringify(data);
			localStorage.setItem('ListData', data)
			data = JSON.parse(data);
			var status = data.status;

			successCallback && successCallback(data);

			////lang()

		},
		error: function(xhr, type, errorThrown) {
			//异常处理；
			console.log(JSON.stringify(xhr));
		}
	});

}

/**
 * 获取用户信息
 */
var get_terminal_rank_val = PreUrl + "User/get_termi\nnal_rank";

function get_terminal_rank_list(successCallback) {
	//	console.log(user_id)
	$.ajax({
		url: get_terminal_rank_val,
		data: {
			is_mobile: 1
		},
		dataType: 'json', //服务器返回json格式数据
		type: 'POST', //HTTP请求类型
		timeout: 10000, //超时时间设置为10秒；

		success: function(data) {
			var status = data.status;
			data = JSON.stringify(data);
			localStorage.setItem('ListData', data)
			data = JSON.parse(data);
			var status = data.status;

			successCallback && successCallback(data);

			////lang()

		},
		error: function(xhr, type, errorThrown) {
			//异常处理；
			console.log(JSON.stringify(xhr));
		}
	});

}

var update_user_wx_fun = PreUrl + "User/update_user_wx";

function update_user_wx() {
	$.ajax({
		url: update_user_wx_fun,
		data: {

			is_mobile: 1,
			user_id: localStorage.getItem('user_id')

		},

		dataType: 'json', //服务器返回json格式数据
		type: 'POST', //HTTP请求类型
		timeout: 10000, //超时时间设置为10秒；

		success: function(data) {

			localStorage.setItem('user', JSON.stringify(data.user));
			mui.toast(data.msg);
			hideActionSheet()
		},
		error: function(xhr, type, errorThrown) {
			//异常处理；
			console.log(JSON.stringify(xhr));
		}
	});
}

//打开规格选择窗口
function showYzmActionSheet() {

	//显示窗口
	$('#yzmActionsheet').addClass('weui-actionsheet_toggle');
	$('#specMask').fadeIn(200);
	$('#yzmActionsheet').css('height', '70%');

	//添加隐藏事件
	$('#specMask').on('click', hideActionSheet);
	$('.specActionsheetCancel').on('click', hideActionSheet);
	//判断是选择规格|立即购买|加入购物车
	$('#confirmButton').unbind("click"); //移除事件 

	$('#confirmButton').on('click', function() {

		hideActionSheet();
		$('#confirmButton').prop('disabled', true)
		post_tixian(money, user, type, '.tiqu')
	});

	$('#changeButton').on('click', function() {

		update_user_wx();

	});

}
//打开规格选择窗口
function showActionSheet(wx_logo, money, user, type) {

	//显示窗口
	$('#specActionsheet').addClass('weui-actionsheet_toggle');
	$('#specMask').fadeIn(200);
	$('.wx_logo').attr('src', wx_logo)
	$('.tiqu_money').html(money)
	$('.tiqu_user_name').html(user.wx_nickname)

	//添加隐藏事件
	$('#specMask').on('click', hideActionSheet);
	$('#specActionsheetCancel').on('click', hideActionSheet);
	//判断是选择规格|立即购买|加入购物车
	$('#confirmButton').unbind("click"); //移除事件 

	$('#confirmButton').on('click', function() {

		hideActionSheet();
		post_tixian(money, user, type, '.tiqu')
	});

	$('#changeButton').on('click', function() {

		update_user_wx();

	});

}

//隐藏规格选择窗口
function hideActionSheet() {

	$('#yzmActionsheet').removeClass('weui-actionsheet_toggle');
	$('#specActionsheet').removeClass('weui-actionsheet_toggle');
	$('#specMask').fadeOut(200);
}

var check_user_money_val = PreUrl + "User/check_user_money";

function add_tixian(obj, s_type) {
	var type = $(s_type).val();

	var money = $('#money').val();
	$(obj).val('正在申请中');
	$.ajax({
		url: check_user_money_val,
		data: {
			is_mobile: 1,

			id: localStorage.getItem('user_id'),
			money: money,
			ttype: type
		},
		dataType: 'json', //服务器返回json格式数据
		type: 'POST', //HTTP请求类型
		timeout: 10000, //超时时间设置为10秒；

		success: function(data) {
			console.log(JSON.stringify(data))
			if(data.status == 0) {
				mui.toast(data.info);
				return;
			}
			var user = data.data;

			localStorage.setItem('user_id', data.data.id);
			localStorage.setItem('user', JSON.stringify(data.data));

			var money = $('#money').val();
			if(type == 2) {
				console.log(user.gzh_openid)
				if(user.gzh_openid != null && user.gzh_openid != '') {
					var tiqu_money = money;
					showActionSheet(user.weixinlogo, tiqu_money, user, type);
					return;
				} else {
					sendSMS('.get_yzm', '#user_id', 'tiqu')
					showYzmActionSheet();
					return;
				}

			}

			if(type == 3) {

				if(user.alipay == '') {

					mui.toast('请先绑定支付宝')

					return;
				}

			}
			post_tixian(money, user, type, obj)

		},
		error: function(xhr, type, errorThrown) {

			//异常处理；
			console.log(type);
		}
	});

}

function post_tixian(money, user, type, obj) {
	plus.nativeUI.showWaiting("提现申请中..");
	$.ajax({
		url: add_tixian_fun,
		data: {
			price: money,
			is_mobile: 1,
			user_id: localStorage.getItem('user_id'),
			openid: user.gzh_openid,
			alipay: user.alipay,

			ttype: type

		},

		dataType: 'json', //服务器返回json格式数据
		type: 'POST', //HTTP请求类型
		timeout: 10000, //超时时间设置为10秒；

		success: function(data) {
			console.log(type)
			var status = data.status;
			$('.weui-mask_transparent').remove();
			$('.weui_loading_toast').remove();
			plus.nativeUI.closeWaiting();

			$('#confirmButton').prop('disabled', false)
			if(status == 1) {

				mui.toast(data.msg);
				$(obj).removeAttr('disabled')
				$(obj).val('提现成功');
				if(type == 3) {
					alpay_tixian(data.tixian_id, data.out_trade_no, data.deduct_auth_no, data.deduct_out_order_no, data.out_request_no, data.payee_user_id, data.payee_logon_id)
				}
				if(type == 2) {
					tixian(data.tixian_id)
				}
			} else {
				mui.toast(data.msg);
				$(obj).removeAttr('disabled')
				$(obj).html('立即提现');
			}
		},
		error: function(xhr, type, errorThrown) {
			//异常处理；
			console.log(type);
		}
	});
}

function alpay_tixian(tixian_id, out_trade_no, deduct_auth_no, deduct_out_order_no, out_request_no, payee_user_id, payee_logon_id) {
	console.log(payee_logon_id)
	var user = localStorage.getItem('user');
	user = JSON.parse(user);
	if(user.openid == null || user.openid == '') {
		//		mui.toast('请先绑定微信')
		//		return;
	}
	if(user.gzh_openid == null || user.gzh_openid == '') {
		//		mui.toast('现在进行提现授权')
		//		return;
	}
	console.log(user.agent_use)
	var money = $('#money').val();
	//	if(money > user.agent_use) {
	//		mui.toast('可提现金额不足')
	//		return;
	//	}
	plus.nativeUI.showWaiting("提现申请中...");
	$.ajax({
		url: alipay_tixian_fun,
		data: {
			money: money,
			is_mobile: 1,
			user_id: localStorage.getItem('user_id'),
			tixian_id: tixian_id,
			openid: user.gzh_openid,
			out_trade_no: out_trade_no,
			deduct_auth_no: deduct_auth_no,
			deduct_out_order_no: deduct_out_order_no,
			out_request_no: out_request_no,
			payee_user_id: payee_user_id,
			payee_logon_id: payee_logon_id

		},

		dataType: 'json', //服务器返回json格式数据
		type: 'POST', //HTTP请求类型
		timeout: 10000, //超时时间设置为10秒；

		success: function(data) {
			mui.toast(data.msg);
			remove_toast();
			plus.nativeUI.closeWaiting();

		},
		error: function(xhr, type, errorThrown) {
			mui.toast('提现失败')
			remove_toast();
			//异常处理；
			console.log(JSON.stringify(type));
		}
	});

}

function tixian(tixian_id) {
	var user = localStorage.getItem('user');
	user = JSON.parse(user);
	if(user.openid == null || user.openid == '') {
		//		mui.toast('请先绑定微信')
		//		return;
	}
	if(user.gzh_openid == null || user.gzh_openid == '') {
		//		mui.toast('现在进行提现授权')
		//		return;
	}

	var money = $('#money').val();
	if(money > user.agent_kt) {
		//		mui.toast('可提现金额不足')
		//		return;
	}
	plus.nativeUI.showWaiting("提现申请中...");

	console.log(update_tixian_fun)
	$.ajax({
		url: tixian_fun,
		data: {
			money: money,
			is_mobile: 1,
			user_id: localStorage.getItem('user_id'),
			tixian_id: tixian_id,
			openid: user.gzh_openid,
			update_tixian_url: update_tixian_fun

		},
		dataType: 'json', //服务器返回json格式数据
		type: 'POST', //HTTP请求类型
		timeout: 10000, //超时时间设置为10秒；

		success: function(data) {
			console.log(JSON.stringify(data));

			if(data.status == 0) {

				mui.toast(data.msg);
			} else {

				mui.toast(data.msg);
				localStorage.setItem('tixian_id', tixian_id)
				openWindowWithTitle('提现成功', 'tx_success.html')
			}

			remove_toast();
			plus.nativeUI.closeWaiting();

		},
		error: function(xhr, type, errorThrown) {
			mui.toast('提现失败')
			plus.nativeUI.closeWaiting();
			//异常处理；
			console.log(type);
		}
	});

}

function update_tixian(tixian_id, mch_billno, send_listid, result_code, return_msg) {
	$.ajax({
		url: update_tixian_fun,
		data: {

			is_mobile: 1,
			user_id: localStorage.getItem('user_id'),
			tixian_id: tixian_id,
			mch_billno: mch_billno,
			send_listid: send_listid,
			result_code: result_code,
			return_msg: return_msg

		},

		dataType: 'json', //服务器返回json格式数据
		type: 'POST', //HTTP请求类型
		timeout: 10000, //超时时间设置为10秒；

		success: function(data) {

			mui.toast(data.msg);
			remove_toast();

		},
		error: function(xhr, type, errorThrown) {
			//异常处理；
			console.log(type);
		}
	});
}

function remove_toast() {
	setTimeout(function() { //两秒后跳转  

		$('.weui-toast').remove();
		$('.weui-mask_transparent').remove();
		$('.weui_loading_toast').remove();
		plus.nativeUI.closeWaiting();
	}, 1000);

}

var tradeflows_list_val = PreUrl + "YouZi/tradeflows";

function tradeflows_list(pageNum, pageSize, successCallback) {
	console.log(localStorage.getItem('year'))
	console.log(localStorage.getItem('month'))
	console.log(localStorage.getItem('day'))

	$.ajax({
		url: tradeflows_list_val,
		data: {
			is_mobile: 1,
			p: 1,
			user_id: localStorage.getItem('user_id'),
			year: localStorage.getItem('year'),
			month: localStorage.getItem('month'),
			day: localStorage.getItem('day'),

			page_index: pageNum,
			page_size: pageSize
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

var wxYzmCheck_fun = PreUrl + "User/wxYzmCheck";

function wxYzmCheck(user_tel, successCallback) {
	var yzm = localStorage.getItem('password');
	console.log(yzm)
	$.ajax({
		url: wxYzmCheck_fun,
		data: {

			is_mobile: 1,
			user_id: localStorage.getItem('user_id'),
			user_tel: user_tel,
			yzm: yzm

		},

		dataType: 'json', //服务器返回json格式数据
		type: 'POST', //HTTP请求类型
		timeout: 10000, //超时时间设置为10秒；

		success: function(data) {

			mui.toast(data.msg);
			remove_toast();

			data = JSON.stringify(data);
			localStorage.setItem('ListData', data)
			data = JSON.parse(data);
			var status = data.status;

			successCallback && successCallback(data);

		},
		error: function(xhr, type, errorThrown) {
			//异常处理；
			console.log(JSON.stringify(xhr));
		}
	});
}
var forget_password_fun = PreUrl + "User/forget_password";

function forget_password() {
	var yzm = $('#yzm').val();
	console.log(yzm)
	$.ajax({
		url: forget_password_fun,
		data: {

			is_mobile: 1,
			user_name: $('#user_name').val(),
			pwd: $('#pwd').val(),
			sjyzm: yzm

		},

		dataType: 'json', //服务器返回json格式数据
		type: 'POST', //HTTP请求类型
		timeout: 10000, //超时时间设置为10秒；

		success: function(data) {
			console.log(JSON.stringify(data))
			mui.toast(data.msg);
			remove_toast();
			if(data.status == 1) {
				mui.back();
			}

		},
		error: function(xhr, type, errorThrown) {
			//异常处理；
			console.log(JSON.stringify(xhr));
		}
	});
}
var sendSMSUrl = PreUrl + "Reg/fasongyanzhengma";

function sendSMS(btnObj, valObj, type) {

	if($(valObj).val() == "") {
		mui.toast('对不起，请填写手机号码后再获取！')
		return false;
	}

	//发送AJAX请求
	$.ajax({
		url: sendSMSUrl,
		type: "POST",
		timeout: 60000,
		data: {
			"user_tel": $(valObj).val(),
			'is_mobile': 1,
			'type': type
		},
		dataType: "json",
		success: function(data, type1) {
			if(data.status == 1) {
				wait = data.time * 60; //赋值时间
				console.log(type)
				time(type); //调用计算器
				mui.toast(data.info);

			} else {
				if(type == 'tiqu') {
					var phone = $(valObj).val().substring(0, 3) + "****" + $(valObj).val().substring(8, 11)
					$(btnObj).removeClass("gray").html("短信发送至:<span class='phone'>" + phone + "</span>,重新发送验证码");
				}
				$(btnObj).bind("click", function() {
					sendSMS(btnObj, valObj, type); //重新绑定事件
				});

				mui.toast(data.info);
			}
		},
		error: function(xhr, type, errorThrown) {

			mui.toast('获取失败');
			//异常处理；
			console.log(type);
		}
	});
	//倒计时计算器
	function time(type) {
		var phone = $(valObj).val().substring(0, 3) + "****" + $(valObj).val().substring(8, 11)
		if(wait == 0) {
			if(type == 'tiqu') {

				$(btnObj).removeClass("gray").html("短信发送至:<span class='phone'>" + phone + "</span>,发送验证码");
			} else {

				$(btnObj).removeClass("gray").html("发送验证码");
			}
			$(btnObj).bind("click", function() {
				sendSMS(btnObj, valObj, type); //重新绑定事件
			});
		} else {
			$(btnObj).unbind("click").removeAttr("onclick"); //移除按钮事件

			if(type == 'tiqu') {
				$(btnObj).addClass("gray").html("短信发送至:<span class='phone'>" + phone + "</span>,重新发送(" + wait + ")");
			} else {
				$(btnObj).addClass("gray").html("" + wait + "S");
			}
			$(btnObj).attr("disable")
			wait--;
			setTimeout(function() {
				time(type);
			}, 1000)
		}
	}
}

var sendchangeSMSUrl = PreUrl + "Reg/send_change_msg";

function sendChangeSMS(btnObj, valObj) {

	if($(valObj).val() == "") {
		mui.toast('对不起，请填写手机号码后再获取！')
		return false;
	}

	//发送AJAX请求
	$.ajax({
		url: sendchangeSMSUrl,
		type: "POST",
		timeout: 60000,
		data: {
			"user_tel": $(valObj).val(),
			'is_mobile': 1
		},
		dataType: "json",
		success: function(data, type) {
			if(data.status == 1) {
				wait = data.time * 60; //赋值时间
				time(); //调用计算器
				mui.toast(data.info);

			} else {
				$(btnObj).removeClass("gray").html("发送验证码");
				$(btnObj).bind("click", function() {
					sendChangeSMS(btnObj, valObj, sendchangeSMSUrl); //重新绑定事件
				});

				mui.toast(data.info);
			}
		},
		error: function(xhr, type, errorThrown) {

			mui.toast('获取失败');
			//异常处理；
			console.log(type);
		}
	});
	//倒计时计算器
	function time() {
		if(wait == 0) {
			$(btnObj).removeClass("gray").html("发送验证码");
			$(btnObj).bind("click", function() {
				sendChangeSMS(btnObj, valObj); //重新绑定事件
			});
		} else {
			$(btnObj).unbind("click").removeAttr("onclick"); //移除按钮事件
			$(btnObj).addClass("gray").html("重新发送(" + wait + ")");
			$(btnObj).attr("disable")
			wait--;
			setTimeout(function() {
				time(btnObj);
			}, 1000)
		}
	}
}

var notice_show_val = PreUrl + "News/form_detail";

function get_notice_show(back) {
	var id = localStorage.getItem('notice_id');
	$.ajax({
		url: notice_show_val,
		data: {
			is_mobile: 1,
			user_id: localStorage.getItem('user_id'),
			id: id
		},
		dataType: 'json', //服务器返回json格式数据
		type: 'post', //HTTP请求类型
		timeout: 10000, //超时时间设置为10秒；  
		success: function(data) {
			//			console.log(JSON.stringify(data))
			var status = data.status;
			if(status == 1) {
				var info = data.data;
				if(back && back instanceof Function) {
					localStorage.setItem('notice_detail', JSON.stringify(data));
					back();
				}
			}

		},
		error: function(xhr, type, errorThrown) {
			//异常处理；
			console.log(type);
		}
	});

}

function notice_list(page_index, page_num, successCallback) {
	var notice_type = localStorage.getItem('notice_type')
	console.log(notice_type)
	$.ajax({
		url: notice_list_val,
		data: {
			is_mobile: 1,
			notice_type: notice_type,
			page_index: page_index,
			page_num: page_num,
			user_id: localStorage.getItem('user_id')
		},
		dataType: 'json', //服务器返回json格式数据
		type: 'POST', //HTTP请求类型
		timeout: 10000, //超时时间设置为10秒；

		success: function(data) {

			data = JSON.stringify(data);
			localStorage.setItem('ListData', data)
			data = JSON.parse(data);

			successCallback && successCallback(data);

		},
		error: function(xhr, type, errorThrown) {
			//异常处理；
			console.log(type);
		}
	});

	//	mui.toast(notice_type)
	//	if(notice_type == 'index') {
	//
	//		//		$('.page').css('margin-top', '25px');
	//	}
	//	if(mui.os.ios) {
	//		$('.page').css('margin-top', '0')
	//	}
	//	$.ajax({
	//		url: notice_list_val,
	//		data: {
	//			is_mobile: 1,
	//
	//			user_id: localStorage.getItem('user_id')
	//		},
	//		dataType: 'json', //服务器返回json格式数据
	//		type: 'POST', //HTTP请求类型
	//		timeout: 10000, //超时时间设置为10秒；
	//
	//		success: function(data) {
	//			var status = data.status;
	//			mui('.page').pullRefresh().endPulldown();
	//			$('.notice_list .mui-media').remove();
	//			$('.user_notice_list .mui-media').remove();
	//			if(status == 1) {
	//				var info = data.user_list;
	//				if(info == null) {
	//					$('.notice_list .nodata').css('display', 'block');
	//				}
	//				if(info != null) {
	//					$('.notice_list .nodata').css('display', 'none');
	//					$.each(info, function(index, content) {
	//
	//						var item = '<li class="mui-table-view-cell mui-media" v-for="item in items">';
	//						item += '<a href="javascript:;" :data-guid="item.guid" @tap="open_detail(item)">';
	//						item += '<img class="mui-media-object mui-pull-left"  src="../images/logo.png" >';
	//						item += '<div class="mui-media-body">';
	//						item += '	<div class="mui-ellipsis-2">' + content.content + '</div>';
	//						item += '</div>';
	//						item += '<div class="meta-info">';
	//						item += '	<div class="time">' + content.addtime_str + '</div>';
	//						item += '</div>';
	//						item += '</a>';
	//						item += '	</li>';
	//
	//						$('.notice_list').append(item);
	//					});
	//				}
	//
	//				info = data.data;
	//				if(info == null) {
	//					$('.user_notice_list .nodata').css('display', 'block');
	//				}
	//				if(info != null) {
	//					$('.user_notice_list .nodata').css('display', 'none');
	//					$.each(info, function(index, content) {
	//						var item = '<li class="mui-table-view-cell mui-media" v-for="item in items">';
	//						item += '<a href="javascript:;" :data-guid="item.guid" @tap="open_detail(item)">';
	//						item += '<img class="mui-media-object mui-pull-left"  src="../images/logo.png" >';
	//						item += '<div class="mui-media-body">';
	//						item += '	<div class="mui-ellipsis-2">' + content.content + '</div>';
	//						item += '</div>';
	//						item += '<div class="meta-info">';
	//						item += '	<div class="time">' + content.addtime_str + '</div>';
	//						item += '</div>';
	//						item += '</a>';
	//						item += '	</li>';
	//						$('.user_notice_list').append(item);
	//					});
	//				}
	//
	//			}
	//
	//		},
	//		error: function(xhr, type, errorThrown) {
	//			//异常处理；
	//			console.log(type);
	//		}
	//	});

}

var user_chat_list_val = PreUrl + "User/user_chat_list";

function get_user_chat_list(page_index, page_num, successCallback) {
	$.ajax({
		url: user_chat_list_val,
		data: {
			is_mobile: 1,
			user_id: localStorage.getItem('user_id'),

			page_index: page_index,
			page_num: page_num
		},
		dataType: 'json', //服务器返回json格式数据
		type: 'post', //HTTP请求类型
		timeout: 10000, //超时时间设置为10秒；

		success: function(data) {

			data = JSON.stringify(data);
			localStorage.setItem('ListData', data)
			data = JSON.parse(data);

			successCallback && successCallback(data);

		},
		error: function(xhr, type, errorThrown) {
			//异常处理；
			console.log(type);
		}
	});

}
var active_val = PreUrl + "Agent/menberAC";

function user_active() {
	var txt = '';
	var img = '/app/img/logo.png';
	txt = '您现在进行激活申请';

	var btnArray = ['否', '是'];

	mui.confirm(txt + '，您确定吗？', function(e) {
		if(e.index == 1) {

			$.ajax({
				url: active_val,
				data: {
					id: localStorage.getItem('user_id'),

					user_id: localStorage.getItem('user_id'),
					action: 'confirm',
					is_mobile: 1
				},
				dataType: 'json', //服务器返回json格式数据
				type: 'post', //HTTP请求类型
				timeout: 10000, //超时时间设置为10秒；

				success: function(data) {
					var status = data.status;

					if(status == 1) {
						localStorage.setItem('user', JSON.stringify(data.user));

						mui.toast(data.msg);
						location.reload()
					} else {
						mui.toast(data.msg)

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

var pointflowslist_val = PreUrl + "YouZi/pointflows";

function pointflows_list(page_index, page_num, successCallback) {
	$.ajax({
		url: pointflowslist_val,
		data: {
			is_mobile: 1,
			page_index: page_index,
			page_num: page_num,
			user_id: localStorage.getItem('user_id'),
			type: localStorage.getItem('point_type')
		},
		dataType: 'json', //服务器返回json格式数据
		type: 'POST', //HTTP请求类型
		timeout: 10000, //超时时间设置为10秒；

		success: function(data) {
			data = JSON.stringify(data);
			localStorage.setItem('ListData', data)
			data = JSON.parse(data);
			var status = data.status;
			if(status == 1) {

			}
			successCallback && successCallback(data);

		},
		error: function(xhr, type, errorThrown) {
			//异常处理；
			console.log(type);
		}
	});

}

var myTeam_val = PreUrl + "User/myTeam";

function myteam_list(page_index, page_num, successCallback) {
	$.ajax({
		url: myTeam_val,
		data: {
			is_mobile: 1,
			page_index: page_index,
			page_num: page_num,
			is_pay: localStorage.getItem('is_pay'),
			user_id: localStorage.getItem('user_id'),
			keywords: localStorage.getItem('keywords')
		},
		dataType: 'json', //服务器返回json格式数据
		type: 'POST', //HTTP请求类型
		timeout: 10000, //超时时间设置为10秒；

		success: function(data) {
			data = JSON.stringify(data);
			localStorage.setItem('ListData', data)
			data = JSON.parse(data);
			var status = data.status;
			if(status == 1) {

			}
			successCallback && successCallback(data);

		},
		error: function(xhr, type, errorThrown) {
			//异常处理；
			console.log(type);
		}
	});

}

/**
 * 获取转账记录
 */
var transferMoney_val = PreUrl + "Transfer/transferMoney";
/**
 *转账
 */
var transferMoneyTouser_val = PreUrl + "Transfer/transferMoneyAC";
var transferTob_val = IndexUrl + "Transfer/transferTob";
var get_recharge_list_val = IndexUrl + "Recharge/currencyRecharge";
var tixian_list_val = PreUrl + "Currency/adminCurrency";
var moneyflows_list_val = PreUrl + "YouZi/adminmoneyflows";

function transferMoneyAC(user_name, price, zztype) {
	mui.confirm('转账,您确定吗？', function(e) {
		if(e.index == 1) {
			$.ajax({
				url: transferMoneyTouser_val,
				data: {
					is_mobile: 1,
					tousername: user_name,
					price: price,
					user_id: localStorage.getItem('user_id'),
					zztype: zztype

				},
				dataType: 'json', //服务器返回json格式数据
				type: 'post', //HTTP请求类型
				timeout: 10000, //超时时间设置为10秒；

				success: function(data) {
					var status = data.status;
					mui.toast(data.msg)
					if(status == 1) {
						$('#dialog').addClass('bounceOutUp').fadeOut();
						mescroll.resetUpScroll();
					}
					if(data.url) {
						setTimeout(function() {
							mui.back();

						}, 2000);
					}

				},
				error: function(xhr, type, errorThrown) {
					//异常处理；
					console.log(JSON.stringify(xhr));
				}
			});
		}
	}, 'div');

}

function moneyflows_list() {
	$.ajax({
		url: moneyflows_list_val,
		data: {
			is_mobile: 1,
			p: 1,
			user_id: localStorage.getItem('user_id')
		},
		dataType: 'json', //服务器返回json格式数据
		type: 'POST', //HTTP请求类型
		timeout: 10000, //超时时间设置为10秒；

		success: function(data) {
			var status = data.status;
			if(status == 1) {
				var info = data.data;
				if(info == null) {
					$('.nodata').css('display', 'block');
				}
				if(info != null) {
					$.each(info, function(index, content) {

						var item = '<div class="weui-form-preview" style="margin-top: 10px;">';
						item += '<div class="weui-form-preview__hd">';
						//						item += '	<div class="weui-form-preview__item">';
						//						item += '		<label class="weui-form-preview__label">序号</label>';
						//						item += '		<label class="weui-form-preview__bd">' + (content.id) + '</label>';
						//						item += '	</div>';

						item += '	<div class="weui-form-preview__item">';
						item += '		<label class="weui-form-preview__label">用户帐号</label>';
						item += '		<label class="weui-form-preview__bd">' + (content.user_id) + '</label>';
						item += '	</div>';
						//						item += '	<div class="weui-form-preview__item">';
						//						item += '		<label class="weui-form-preview__label">类型</label>';
						//						item += '		<label class="weui-form-preview__bd">' + (content.type_str) + '</label>';
						//						item += '	</div>';

						//						item += '	<div class="weui-form-preview__item">';
						//						item += '		<label class="weui-form-preview__label">相关会员</label>';
						//						item += '		<label class="weui-form-preview__bd">' + (content.user_id) + '</label>';
						//						item += '	</div>';
						item += '	<div class="weui-form-preview__item">';
						item += '		<label class="weui-form-preview__label">说明</label>';
						item += '		<label class="weui-form-preview__bd">' + (content.bz) + '</label>';
						item += '	</div>';
						item += '	<div class="weui-form-preview__item">';
						item += '		<label class="weui-form-preview__label">金额</label>';
						item += '		<label class="weui-form-preview__bd">' + (content.epoints) + '</label>';
						item += '	</div>';
						item += '	<div class="weui-form-preview__item">';
						item += '		<label class="weui-form-preview__label">时间</label>';
						item += '		<label class="weui-form-preview__bd">' + (content.addtime_str) + '</label>';
						item += '	</div>';
						//						item += '	<div class="weui-form-preview__item">';
						//						item += '		<label class="weui-form-preview__label">状态</label>';
						//						item += '		<label class="weui-form-preview__bd" style="color: red;"> ' + (content.status_str) + '</label>';
						//						item += '	</div>';

						item += '</div>';
						item += '	</div>';

						$('.detail').append(item);

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

function get_transferMoney(page_index, page_num, successCallback) {
	$.ajax({
		url: transferMoney_val,
		data: {
			is_mobile: 1,
			page_index: page_index,
			page_num: page_num,
			user_id: localStorage.getItem('user_id')
		},
		dataType: 'json', //服务器返回json格式数据
		type: 'POST', //HTTP请求类型
		timeout: 10000, //超时时间设置为10秒；

		success: function(data) {

			data = JSON.stringify(data);
			localStorage.setItem('ListData', data)
			data = JSON.parse(data);
			console.log(data)

			successCallback && successCallback(data);

		},
		error: function(xhr, type, errorThrown) {
			//异常处理；
			console.log(type);
		}
	});

}
//function get_transferMoney() {
//	$.ajax({
//		url: transferMoney_val,
//		data: {
//			is_mobile: 1,
//			p: 1,
//
//			user_id: localStorage.getItem('user_id')
//		},
//		dataType: 'json', //服务器返回json格式数据
//		type: 'POST', //HTTP请求类型
//		timeout: 10000, //超时时间设置为10秒；
//
//		success: function(data) {
//			var status = data.status;
//			if(status == 1) {
//				var info = data.data;
//				if(info == null) {
//					$('.nodata').css('display', 'block');
//
//				}
//				if(info != null) {
//					$.each(info, function(index, content) {
//
//						var item = '<div class="weui-form-preview" style="margin-top: 10px;">';
//						item += '<div class="weui-form-preview__hd">';
//						//						item += '	<div class="weui-form-preview__item">';
//						//						item += '		<label class="weui-form-preview__label">序号</label>';
//						//						item += '		<label class="weui-form-preview__bd">' + (content.id) + '</label>';
//						//						item += '	</div>';
//
//						item += '	<div class="weui-form-preview__item">';
//						item += '		<label class="weui-form-preview__label">用户帐号</label>';
//						item += '		<label class="weui-form-preview__bd">' + (content.out_userid) + '</label>';
//						item += '	</div>';
//
//						item += '	<div class="weui-form-preview__item">';
//						item += '		<label class="weui-form-preview__label">转入会员</label>';
//						item += '		<label class="weui-form-preview__bd">' + (content.in_userid) + '</label>';
//						item += '	</div>';
//						item += '	<div class="weui-form-preview__item">';
//						item += '		<label class="weui-form-preview__label">货币类型</label>';
//						item += '		<label class="weui-form-preview__bd">' + (content.type_str) + '</label>';
//						item += '	</div>';
//						item += '	<div class="weui-form-preview__item">';
//						item += '		<label class="weui-form-preview__label">金额</label>';
//						item += '		<label class="weui-form-preview__bd">' + (content.epoint) + '</label>';
//						item += '	</div>';
//						item += '	<div class="weui-form-preview__item">';
//						item += '		<label class="weui-form-preview__label">手续费</label>';
//						item += '		<label class="weui-form-preview__bd">' + (content.percent) + '</label>';
//						item += '	</div>';
//						item += '	<div class="weui-form-preview__item">';
//						item += '		<label class="weui-form-preview__label">实际到账</label>';
//						item += '		<label class="weui-form-preview__bd">' + (content.real_epoint) + '</label>';
//						item += '	</div>';
//						item += '	<div class="weui-form-preview__item">';
//						item += '		<label class="weui-form-preview__label">时间</label>';
//						item += '		<label class="weui-form-preview__bd">' + (content.rdt_str) + '</label>';
//						item += '	</div>';
//						item += '	<div class="weui-form-preview__item">';
//						item += '		<label class="weui-form-preview__label">状态</label>';
//						item += '		<label class="weui-form-preview__bd" style="color: red;"> ' + (content.status_str) + '</label>';
//						item += '	</div>';
//
//						item += '</div>';
//						item += '	</div>';
//
//						$('.detail').append(item);
//
//					});
//					lang()
//				}
//
//			}
//
//		},
//		error: function(xhr, type, errorThrown) {
//			//异常处理；
//			console.log(type);
//		}
//	});
//
//}

function get_news_list(type, page_index, page_num, successCallback) {
	$.ajax({
		url: video_list_val,
		data: {
			is_mobile: 1,
			type: type,
			page_index: page_index,
			page_num: page_num,
			user_id: localStorage.getItem('user_id'),
			category_id: localStorage.getItem('category_id')
		},
		dataType: 'json', //服务器返回json格式数据
		type: 'POST', //HTTP请求类型
		timeout: 10000, //超时时间设置为10秒；

		success: function(data) {

			data = JSON.stringify(data);
			localStorage.setItem('ListData', data)
			data = JSON.parse(data);

			successCallback && successCallback(data);

		},
		error: function(xhr, type, errorThrown) {
			//异常处理；
			console.log(type);
		}
	});

}

function get_tixian_list(page_index, page_num, successCallback) {
	$.ajax({
		url: tixian_list_val,
		data: {
			is_mobile: 1,
			page_index: page_index,
			page_num: page_num,
			user_id: localStorage.getItem('user_id')
		},
		dataType: 'json', //服务器返回json格式数据
		type: 'POST', //HTTP请求类型
		timeout: 10000, //超时时间设置为10秒；

		success: function(data) {

			data = JSON.stringify(data);
			localStorage.setItem('ListData', data)
			data = JSON.parse(data);

			successCallback && successCallback(data);

		},
		error: function(xhr, type, errorThrown) {
			//异常处理；
			console.log(type);
		}
	});

}

function get_recharge_list() {
	$.ajax({
		url: get_recharge_list_val,
		data: {
			is_mobile: 1,
			p: 1,

			user_id: localStorage.getItem('user_id')
		},
		dataType: 'json', //服务器返回json格式数据
		type: 'POST', //HTTP请求类型
		timeout: 10000, //超时时间设置为10秒；

		success: function(data) {
			var status = data.status;
			if(status == 1) {
				var info = data.data;
				if(info == null) {
					$('.nodata').css('display', 'block');

				}

				var bank_list = data.bank_list;
				var bankusername = data.bankusername;
				var bankcard = data.bankcard;

				if(bank_list != null) {
					$.each(bank_list, function(index1, content1) {
						var item = '<option value="' + content1 + '"  bankcard="' + bankcard[index1] + '"   bankusername="' + bankusername[index1] + '">' + content1 + '</option>';
						$('#bank').append(item);
					});
					$("#bank").change(function() {

						$.each($("#bank option"), function(index2, content2) {
							if(content2.selected) {
								var bankcard = content2.attributes['bankcard'].value
								var bankusername = content2.attributes['bankusername'].value
								$("#bankcard").val(bankcard);
								$("#bankusername").val(bankusername);
							}
						});

					});

				}
				if(info != null) {
					$.each(info, function(index, content) {

						var item = '<div class="weui-form-preview" style="margin-top: 10px;">';
						item += '<div class="weui-form-preview__hd">';
						//						item += '	<div class="weui-form-preview__item">';
						//						item += '		<label class="weui-form-preview__label">序号</label>';
						//						item += '		<label class="weui-form-preview__bd">' + (content.id) + '</label>';
						//						item += '	</div>';

						//						item += '	<div class="weui-form-preview__item">';
						//						item += '		<label class="weui-form-preview__label">用户帐号</label>';
						//						item += '		<label class="weui-form-preview__bd">' + (content.uid) + '</label>';
						//						item += '	</div>';
						//
						//						item += '	<div class="weui-form-preview__item">';
						//						item += '		<label class="weui-form-preview__label">转入会员</label>';
						//						item += '		<label class="weui-form-preview__bd">' + (content.user_id) + '</label>';
						//						item += '	</div>';
						item += '	<div class="weui-form-preview__item">';
						item += '		<label class="weui-form-preview__label">货币类型</label>';
						item += '		<label class="weui-form-preview__bd">' + (content.type_str) + '</label>';
						item += '	</div>';
						item += '	<div class="weui-form-preview__item">';
						item += '		<label class="weui-form-preview__label">金额</label>';
						item += '		<label class="weui-form-preview__bd">' + (content.epoint) + '</label>';
						item += '	</div>';
						item += '	<div class="weui-form-preview__item">';
						item += '		<label class="weui-form-preview__label">时间</label>';
						item += '		<label class="weui-form-preview__bd">' + (content.rdt_str) + '</label>';
						item += '	</div>';
						item += '	<div class="weui-form-preview__item">';
						item += '		<label class="weui-form-preview__label">状态</label>';
						item += '		<label class="weui-form-preview__bd" style="color: red;"> ' + (content.status_str) + '</label>';
						item += '	</div>';

						item += '</div>';
						item += '	</div>';

						$('.detail').append(item);

					});
					lang()
				}

			}

		},
		error: function(xhr, type, errorThrown) {
			//异常处理；
			console.log(type);
		}
	});

}

function get_transferTob() {
	$.ajax({
		url: transferTob_val,
		data: {
			is_mobile: 1,
			p: 1,

			user_id: localStorage.getItem('user_id')
		},
		dataType: 'json', //服务器返回json格式数据
		type: 'POST', //HTTP请求类型
		timeout: 10000, //超时时间设置为10秒；

		success: function(data) {
			var status = data.status;
			if(status == 1) {
				var info = data.data;
				if(info == null) {
					$('.nodata').css('display', 'block');

				}
				if(info != null) {
					$.each(info, function(index, content) {

						var item = '<div class="weui-form-preview" style="margin-top: 10px;">';
						item += '<div class="weui-form-preview__hd">';
						//						item += '	<div class="weui-form-preview__item">';
						//						item += '		<label class="weui-form-preview__label">序号</label>';
						//						item += '		<label class="weui-form-preview__bd">' + (content.id) + '</label>';
						//						item += '	</div>';

						item += '	<div class="weui-form-preview__item">';
						item += '		<label class="weui-form-preview__label">用户帐号</label>';
						item += '		<label class="weui-form-preview__bd">' + (content.out_userid) + '</label>';
						item += '	</div>';

						//						item += '	<div class="weui-form-preview__item">';
						//						item += '		<label class="weui-form-preview__label">转入会员</label>';
						//						item += '		<label class="weui-form-preview__bd">' + (content.in_userid) + '</label>';
						//						item += '	</div>';
						item += '	<div class="weui-form-preview__item">';
						item += '		<label class="weui-form-preview__label">货币类型</label>';
						item += '		<label class="weui-form-preview__bd">' + (content.type_str) + '</label>';
						item += '	</div>';
						item += '	<div class="weui-form-preview__item">';
						item += '		<label class="weui-form-preview__label">金额</label>';
						item += '		<label class="weui-form-preview__bd">' + (content.epoint) + '</label>';
						item += '	</div>';
						item += '	<div class="weui-form-preview__item">';
						item += '		<label class="weui-form-preview__label">实际到账</label>';
						item += '		<label class="weui-form-preview__bd">' + (content.real_epoint) + '</label>';
						item += '	</div>';
						item += '	<div class="weui-form-preview__item">';
						item += '		<label class="weui-form-preview__label">时间</label>';
						item += '		<label class="weui-form-preview__bd">' + (content.rdt_str) + '</label>';
						item += '	</div>';
						item += '	<div class="weui-form-preview__item">';
						item += '		<label class="weui-form-preview__label">状态</label>';
						item += '		<label class="weui-form-preview__bd" style="color: red;"> ' + (content.status_str) + '</label>';
						item += '	</div>';

						item += '</div>';
						item += '	</div>';

						$('.detail').append(item);

					});
					lang()
				}

			}

		},
		error: function(xhr, type, errorThrown) {
			//异常处理；
			console.log(type);
		}
	});

}

var pwdEdit_val = PreUrl + "User/pwdEdit";

function pwdEdit() {

	var user = localStorage.getItem('user');
	user = JSON.parse(user);

	plus.nativeUI.showWaiting("提交中...");
	$.ajax({
		url: pwdEdit_val,
		data: {
			is_mobile: 1,
			user_id: localStorage.getItem('user_id'),
			pwd: $('#pwd').val(),
			repwd: $('#repwd').val(),
			old_pwd: $('#old_pwd').val()

		},
		dataType: 'json', //服务器返回json格式数据
		type: 'POST', //HTTP请求类型
		timeout: 10000, //超时时间设置为10秒；

		success: function(data) {
			plus.nativeUI.closeWaiting();

			mui.toast(data.msg)

			if(data.status == 1) {

				setTimeout(function() {
					localStorage.clear();
					if(mui.os.ios) {
						plus.runtime.restart();
					} else {
						plus.runtime.restart();
					}
				}, 2000)

			}
			remove_toast();
		},
		error: function(xhr, type, errorThrown) {

			mui.toast('提交失败');
			//异常处理；
			console.log(type);
		}
	});

}

var pwdEdit2_val = PreUrl + "User/pwd2Edit";

function pwdEdit2() {

	var user = localStorage.getItem('user');
	user = JSON.parse(user);
	console.log($('#pwd').val())
	plus.nativeUI.showWaiting("提交中...");
	$.ajax({
		url: pwdEdit2_val,
		data: {
			is_mobile: 1,
			user_id: localStorage.getItem('user_id'),
			pwd: $('#pwd').val(),
			repwd: $('#repwd').val(),
			old_pwd: $('#old_pwd').val()

		},
		dataType: 'json', //服务器返回json格式数据
		type: 'POST', //HTTP请求类型
		timeout: 10000, //超时时间设置为10秒；

		success: function(data) {
			plus.nativeUI.closeWaiting();

			mui.toast(data.msg)

			if(data.status == 1) {

				setTimeout(function() {
					mui.back();
				}, 2000)

			}
			remove_toast();
		},
		error: function(xhr, type, errorThrown) {

			mui.toast('提交失败');
			//异常处理；
			console.log(type);
		}
	});

}
var check_password2_val = PreUrl + "User/check_password2";

function check_password2(password) {

	var user = localStorage.getItem('user');
	user = JSON.parse(user);

	plus.nativeUI.showWaiting("验证中...");
	$.ajax({
		url: check_password2_val,
		data: {
			is_mobile: 1,
			user_id: localStorage.getItem('user_id'),
			password: password

		},
		dataType: 'json', //服务器返回json格式数据
		type: 'POST', //HTTP请求类型
		timeout: 10000, //超时时间设置为10秒；

		success: function(data) {
			plus.nativeUI.closeWaiting();

			mui.toast(data.msg)

			if(data.status == 1) {
				$('#step').val(2)
				$('.content').html('请设置新交易密码')
				$('#old_pwd').val(data.old_pwd)

			}
			remove_toast();
		},
		error: function(xhr, type, errorThrown) {

			mui.toast('提交失败');
			//异常处理；
			console.log(type);
		}
	});

}

function checkpassword2(password, successCallback) {

	var user = localStorage.getItem('user');
	user = JSON.parse(user);

	plus.nativeUI.showWaiting("验证中...");
	$.ajax({
		url: check_password2_val,
		data: {
			is_mobile: 1,
			user_id: localStorage.getItem('user_id'),
			password: password

		},
		dataType: 'json', //服务器返回json格式数据
		type: 'POST', //HTTP请求类型
		timeout: 10000, //超时时间设置为10秒；

		success: function(data) {
			plus.nativeUI.closeWaiting();

			mui.toast(data.msg)

			data = JSON.stringify(data);
			localStorage.setItem('ListData', data)
			data = JSON.parse(data);

			successCallback && successCallback(data);

			remove_toast();
		},
		error: function(xhr, type, errorThrown) {

			mui.toast('提交失败');
			//异常处理；
			console.log(type);
		}
	});

}

var add_trade_order_val = PreUrl + "User/add_trade_order";

function add_trade_order(money) {
 
console.log( localStorage.getItem('seller_id'))
	plus.nativeUI.showWaiting("验证中...");
	$.ajax({
		url: add_trade_order_val,
		data: {
			is_mobile: 1,
			user_id: localStorage.getItem('user_id'),
			money: money,
			seller_id: localStorage.getItem('seller_id')

		},
		dataType: 'json', //服务器返回json格式数据
		type: 'POST', //HTTP请求类型
		timeout: 10000, //超时时间设置为10秒；

		success: function(data) {
			plus.nativeUI.closeWaiting();

			mui.toast(data.msg)
			if(data.status == 1) {
				mui.back();
			}
		},
		error: function(xhr, type, errorThrown) {

			mui.toast('提交失败');
			//异常处理；
			console.log(type);
		}
	});

}