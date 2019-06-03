var ServerIP = "";
var IP = localStorage.getItem('IP');
var PreUrl = localStorage.getItem('PreUrl');
var IndexUrl = localStorage.getItem('IndexUrl');
/**
 * 获取商品列表
 */
var get_user_addr_book = PreUrl + "Goods/get_user_addr_book";
var get_user_addr_book_info = PreUrl + "Goods/get_user_addr_book_info";
var set_user_addr_book_default = PreUrl + "Goods/set_user_addr_book_default";
var user_address_delete = PreUrl + "Goods/user_address_delete";
var get_userEdit_val = IndexUrl + "User/userEdit";
var user_recomend_tree_val = IndexUrl + "User/user_recomend_tree";
var get_tree2_val = PreUrl + "Tree/tree2";
var get_user_feedback_list_val = PreUrl + "User/feedback_list";
var fenhong_val = PreUrl + "YouZi/adminClearingSave";
var weituo_val = PreUrl + "User/weituo";
var futou_val = PreUrl + "YouZi/futou";
var pingyi_val = PreUrl + "YouZi/pingyi";
var clearTags_val = PreUrl + "Goods/clearTags";
var user_address_edit_val = PreUrl + "Goods/user_address_edit";
var open_shop_step1_val = PreUrl + "User/open_seller_step1";
var open_shop_step2_val = PreUrl + "User/open_seller_step2";
var open_shop_step3_val = PreUrl + "User/open_seller_step3";
var open_shop_step4_val = PreUrl + "User/open_seller_step4";
var open_shop_val = PreUrl + "User/open_shop";
var delete_shop_val = PreUrl + "User/delete_shop";
var friend_dynamic_list_val = PreUrl + "User/friend_dynamic_list";
var seller_edit_val = PreUrl + "Shop/seller_edit";
var my_seller_val = PreUrl + "Shop/my_seller";
var shop_order_list_val = PreUrl + "Shop/shop_order_list";

function shop_order_list(pageNum, pageSize, successCallback) {
	console.log(pageNum)
	console.log(pageSize)
	$.ajax({
		url: shop_order_list_val,
		data: {
			is_mobile: 1,
			page_index: pageNum,
			page_size: pageSize,
			seller_no: localStorage.getItem('seller_no')
		},
		dataType: 'json', //服务器返回json格式数据
		type: 'post', //HTTP请求类型
		timeout: 10000, //超时时间设置为10秒；

		success: function(data) {

			console.log(data.current_count)
			var status = data.status;
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

function my_seller_list(pageNum, pageSize, successCallback) {

	$.ajax({
		url: my_seller_val,
		data: {
			is_mobile: 1,
			user_id: localStorage.getItem('user_id'),
			keyword: localStorage.getItem('keywords'),
			type: localStorage.getItem('my_seller_list_type'),
			seller_type: localStorage.getItem('seller_type'),
			category_id: localStorage.getItem('category_id'),
			lat: localStorage.getItem('lat'),
			lng: localStorage.getItem('lng'),

			page_index: pageNum,
			page_size: pageSize,
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

		},
		error: function(xhr, type, errorThrown) {
			//异常处理；
			console.log(type);
		}
	});

}

function call(phone) {
	// 导入Activity、Intent类
	var Intent = plus.android.importClass("android.content.Intent");
	var Uri = plus.android.importClass("android.net.Uri");
	// 获取主Activity对象的实例
	var main = plus.android.runtimeMainActivity();
	// 创建Intent
	var uri = Uri.parse("tel:" + phone); // 这里可修改电话号码
	var call = new Intent("android.intent.action.CALL", uri);
	// 调用startActivity方法拨打电话
	main.startActivity(call);
	// ...
}

function get_seller_info(id, successCallback) {
	$.ajax({
		url: seller_edit_val,
		data: {
			is_mobile: 1,
			id: id
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

function open_shop_step4() {
	$.ajax({
		url: open_shop_step4_val,
		data: $('#editForm').serialize(),
		dataType: 'json', //服务器返回json格式数据
		type: 'post', //HTTP请求类型
		timeout: 10000, //超时时间设置为10秒；

		success: function(data) {
			var status = data.status;
			
			if(status == 1) {
				mui.toast(data.msg)
				localStorage.setItem('seller', JSON.stringify(data.seller));
				localStorage.setItem('seller_id', data.seller.id);

			} else {
				mui.toast(data.msg)
				return;
			}

			mui.back();

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
			console.log(JSON.stringify(data))
			var status = data.status;
			mui.toast(data.msg)
			if(status == 1) {
				localStorage.setItem('seller', JSON.stringify(data.seller));
				localStorage.setItem('seller_id', data.seller.id);

			} else {
				mui.toast(data.msg)
				return;
			}

			mui.back();

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
			console.log(JSON.stringify(data))
			var status = data.status;
			mui.toast(data.msg)
			if(status == 1) {
				localStorage.setItem('seller', JSON.stringify(data.seller));
				localStorage.setItem('user_terminal', JSON.stringify(data.user_terminal));
				localStorage.setItem('seller_id', data.seller.id);
			} else {
				mui.toast(data.msg)
				return;
			}

			mui.back();

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
			console.log(data)
			var status = data.status;
			mui.toast(data.msg)
			if(status == 1) {
				localStorage.setItem('seller', JSON.stringify(data.seller));
				localStorage.setItem('seller_id', data.seller.id);
			} else {

				return;
			}

			mui.back();

		},
		error: function(xhr, type, errorThrown) {
			//异常处理；
			console.log(type);
		}
	});

}

function del_shop(id) {
	var txt = '';

	txt = '您删除此商户';

	mui.confirm(txt + '，您确定吗？', function(e) {
		if(e.index == 0) {
			$.ajax({
				url: delete_shop_val,
				data: {
					user_id: localStorage.getItem('user_id'),
					is_mobile: 1,
					seller_id: id
				},
				dataType: 'json', //服务器返回json格式数据
				type: 'post', //HTTP请求类型
				timeout: 10000, //超时时间设置为10秒；

				success: function(data) {
					var status = data.status;
					mui.toast(data.msg)

					if(status == 1) {
						location.reload()
					} else {
						return;
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

function open_shop(shop_type) {
	var txt = '';
	if(shop_type == 1) {
		txt = '您选择实名认证';
	}
	if(shop_type == 2) {

		txt = '您选择实名认证';
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
						localStorage.setItem('seller', JSON.stringify(data.seller));
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

function get_user_feedback_list() {
	$.ajax({
		url: get_user_feedback_list_val,
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
				if(info == null) {
					$('.nodata').css('display', 'block');

				}
				$.each(info, function(index, content) {

					var item = '<li>';
					item += '	<div class="avatar">';
					item += '	<i class="iconfont icon-user-full"></i>';
					item += '	</div>';
					item += '	<div class="inner">';
					item += '	<div class="meta">';
					item += '		<span class="blue">' + content.title + '</span>';
					item += '		<span class="time">' + content.add_time_str + '</span>';
					item += '	</div>';
					item += '	<p>' + content.content + '</p>';
					item += '	</div>';
					if(content.is_lock == 0) {
						item += '<div class="answer">';
						item += '<div class="meta">';
						item += '	<span class="time">' + content.reply_time_str + '</span>';
						item += '	<span class="blue">管理员回复：</span>';
						item += '</div>';
						item += '	<p> ' + content.reply_content + '</p>';
						item += '</div>';
					}
					item += '</li>';
					$('.comment-list').append(item);

				});
				////lang()

			}

		},
		error: function(xhr, type, errorThrown) {
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
	mui.confirm('领取分红，您确定吗？', function() {
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
					weui.alert(data.msg, function() {

						location.reload();

					});

				} else {
					weui.alert(data.msg);
				}

			},
			error: function(xhr, type, errorThrown) {
				//异常处理；
				console.log(type);
			}
		});
	}, function() {});

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

/**
 * 获取用户信息
 */
var get_user_info_val = PreUrl + "User/userInfo";

function get_user_info(user_id, successCallback) {
	//	console.log(user_id)
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
 
//
//var get_zhihang_list_val = PreUrl + "User/get_zhihang";
//
//function get_zhihang_list(userPicker) {
//	console.log(localStorage.getItem('city'))
//	console.log(localStorage.getItem('province'))
//	console.log(localStorage.getItem('area'))
//	console.log(localStorage.getItem('bank'))
//	$.ajax({
//		url: get_zhihang_list_val,
//		data: {
//			is_mobile: 1,
//			p: 1,
//			user_id: localStorage.getItem('user_id'),
//			province: localStorage.getItem('province'),
//			city: localStorage.getItem('city'),
//			area: localStorage.getItem('area'),
//			bank: localStorage.getItem('bank')
//		},
//		dataType: 'json', //服务器返回json格式数据
//		type: 'POST', //HTTP请求类型
//		timeout: 10000, //超时时间设置为10秒；
//
//		success: function(data) {
//			console.log(JSON.stringify(data))
//
//			var status = data.status;
//			if(status == 1) {
//				var info = data.data;
//				//普通示例
//
//				var shopList1 = []; //可以选择的列表实际上是一个数组。我们用shopList1来表
//				var user = localStorage.getItem('seller');
//
//				user = JSON.parse(user);
//				var index; //用index来记住给你的那个默认值的下标
//				var List = data.data;
//				if(List == null) {
//
//					mui.toast('所在城市没有' + localStorage.getItem('bank'))
//
//				}
//				for(var i = 0; i < List.length; i++) {
//					shopList1.push({
//
//						value: List[i].fh, //mui给的例子中只写了value和text。实际上我们可以写很多自己命名的变量
//						text: List[i].fh
//
//					})
//
//					if(List[i].value == user.zhihang) {
//						index = i
//					}
//
//				}
//				userPicker.setData(shopList1);
//				var showUserPickerButton = document.getElementById('zhihang_select');
//				var userResult = document.getElementById('zhihang');
//				var bank = document.getElementById('bank');
//
//				showUserPickerButton.addEventListener('tap', function(event) {
//					userPicker.show(function(items) {
//						localStorage.setItem('bank', items[0].text)
//						userResult.innerText = (items[0].text);
//					});
//				}, false);
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