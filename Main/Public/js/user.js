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
var open_shop_step1_val = PreUrl + "User/open_shop_step1";
var open_shop_step2_val = PreUrl + "User/open_shop_step2";
var open_shop_step3_val = PreUrl + "User/open_shop_step3";
var open_shop_step4_val = PreUrl + "User/open_shop_step4";
var open_shop_val = PreUrl + "User/open_shop";

function open_shop_step4() {
	$.ajax({
		url: open_shop_step4_val,
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
					location.href = data.url;
				}, 2000);
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
			}
			if(data.url) {
				setTimeout(function() {
					location.href = data.url;
				}, 2000);
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
			}
			if(data.url) {
				setTimeout(function() {
					location.href = data.url;
				}, 2000);
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
			}
			if(data.url) {
				setTimeout(function() {
					location.href = data.url;
				}, 2000);
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
		txt = '您选择个人类型认证';
	}
	if(shop_type == 2) {

		txt = '您选择企业类型认证';
	}
	mui.confirm(txt + '，您确定吗？', function(e) {
		if(e.index == 1) {
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
					mui.toast(data.msg)
					if(status == 1) {
						localStorage.setItem('user', JSON.stringify(data.user));
					}
					if(data.url) {
						setTimeout(function() {
							location.href = data.url;
						}, 2000);
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
					location.href = data.url;
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
				if(info == null) {
					$('.nodata').css('display', 'block');

				}
				$.each(info, function(index, content) {

					var item = '	<li>';
					item += '  <div class="detail-bd">';
					item += '  <div class="list-box">';
					item += '      <div class="txt-box">';
					item += '        <h2>' + content.accept_name + content.mobile + '</h2>';
					item += '       <p>' + content.email + '</p>';
					item += '     <p>' + content.province + content.city + content.area + content.address + '</p>';
					item += '  </div>';
					item += '</div>';
					item += ' </div>';
					item += ' <div class="detail-ft">';
					item += '  <div class="ft-btn">';
					item += ' <div class="btn-box">';
					item += '   <a href="javascript:void(0)"  class="address_info"   id="' + content.id + '">';
					item += '       <i class="iconfont icon-edit"></i>编辑';
					item += '   </a>';
					if(content.is_default == "0") {
						item += '   <a onclick="clickSubmit(' + content.id + ')" href="javascript:;">设为默认</a>';
					}
					item += '   <a href="javascript:;" onclick="ExecPostBack(' + content.id + ');">';
					item += '     <i class="iconfont icon-delete"></i>删除';
					item += '  </a>';
					item += ' </div>';
					item += ' </div>';
					item += '  </div>';
					item += '    </li>';
					$('.detail-list ul').append(item);

				});
				var select_address = localStorage.getItem('select_address')
				$.each(info, function(index, content) {
					var selected = '';
					var noChoose = '';
					if(select_address != 1) {
						noChoose = 'noChoose ';
					}
					var mobile = content.mobile.substr(0, 3) + '****' + content.mobile.substr(7);
					var item = '<div class="address">';
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
					$('.address_list').append(item);

				});
				$(".edit").on('click', function() {
					localStorage.setItem('address_id', $(this).attr('id'));
					location.href = 'useraddress_edit.html'
				})
				$(".address_info").on('click', function() {
					localStorage.setItem('address_id', $(this).attr('id'));
					location.href = 'useraddress_edit.html'
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

				location.href = 'shopping.html'
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

					if(info.is_default == 0) {
						$('#btnDel').css('display', 'block')

						$('#btnDel').attr('onclick', 'ExecPostBack(' + info.id + ')')

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
		ExecDelete('user_address_delete', checkValue, '#turl');
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