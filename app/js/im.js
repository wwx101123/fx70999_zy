var ServerIP = "";
var IP = localStorage.getItem('IP');
var PreUrl = localStorage.getItem('PreUrl');
/**
 * 获取类别
 */
var category = PreUrl + "/Chinapost/Shop/category";
/**
 * 获取商品
 */
var credit_list = PreUrl + "/Chinapost/Bank/credit_list";
/**
 * 搜索用户
 */
var select_person_list = PreUrl + "/Chinapost/User/lists";
/**
 * 申请添加好友
 */
var add_user_apply_fun = PreUrl + "/User/add_user_apply";
/**
 * 获取好友申请列表
 */
var get_user_apply_fun = PreUrl + "/User/get_user_apply";
/**
 * 添加好友通过
 */
var set_user_apply_fun = PreUrl + "/User/set_user_apply";
/**
 * 获取好友列表
 */
var get_friend_list_fun = PreUrl + "/User/get_my_friend";
/**
 * 删除好友
 */
var delete_myfriend_fun = PreUrl + "/User/delete_myfriend";

/**
 * 发送消息
 */
var send_msg_fun = PreUrl + "User/send_msg";
/**
 * 获取用户消息
 */
var get_user_message_list_fun = PreUrl + "/User/get_user_message_list";
/**
 * 获取用户最近消息
 */
var get_user_recent_message_list_fun = PreUrl + "/User/get_user_recent_message_list";
/**
 * base64字符串转成语音文件(参考http://ask.dcloud.net.cn/question/16935)
 * @param {Object} base64Str
 * @param {Object} callback
 */
function dataURL2Audio(base64Str, callback) { 
	var base64Str = base64Str.replace('data:audio/amr;base64,', '');
	var audioName = (new Date()).valueOf() + '.amr';

	plus.io.requestFileSystem(plus.io.PRIVATE_DOC, function(fs) {
		fs.root.getFile(audioName, {
			create: true
		}, function(entry) {
			// 获得平台绝对路径
			var fullPath = entry.fullPath;

			if(mui.os.android) {
				// 读取音频
				var Base64 = plus.android.importClass("android.util.Base64");
				var FileOutputStream = plus.android.importClass("java.io.FileOutputStream");
				try {
					var out = new FileOutputStream(fullPath);
					var bytes = Base64.decode(base64Str, Base64.DEFAULT);
					out.write(bytes);
					out.close();
					// 回调
					callback && callback(entry);
				} catch(e) {
					console.log(e.message);
				}
			} else if(mui.os.ios) {
				var NSData = plus.ios.importClass('NSData');
				var nsData = new NSData();
				nsData = nsData.initWithBase64EncodedStringoptions(base64Str, 0);
				if(nsData) {
					nsData.plusCallMethod({
						writeToFile: fullPath,
						atomically: true
					});
					plus.ios.deleteObject(nsData);
				}
				// 回调
				callback && callback(entry);
			}
		})
	})
}

function get_new_user_message_list(is_read) {
	var user_id = localStorage.getItem('user_id');
	var chat_user_id = localStorage.getItem('chat_user_id');
	var trade_detail_id = localStorage.getItem('trade_detail_id')
	var get_user_message_list = localStorage.getItem(chat_user_id + 'get_user_message_list');

	$.ajax({
		url: get_user_message_list_fun,
		data: {
			is_mobile: 1,
			user_id: user_id,
			chat_user_id: chat_user_id,
			is_read: is_read,
			trade_detail_id: trade_detail_id
		},
		dataType: 'json', //服务器返回json格式数据

		beforeSend: function() {
			if(window.plus) {
				plus.nativeUI.showWaiting();
			}
		},
		type: 'post', //HTTP请求类型
		timeout: 10000, //超时时间设置为10秒；

		success: function(data) {
			var obj = data.data;
			var time = '';
			var record = [];

			if(window.plus) {
				plus.nativeUI.closeWaiting();
			}
			$('#msg-list').html('')
			$.each(obj, function(index, content) {
				var str = 'msg-item-self';
				if(content.user_id != localStorage.getItem('user_id')) {
					str = '';
				}

				var item = '  <div class="msg-item  ' + str + '">';
				item += '<div class="chat_time"><span>' + content.addtime_str + '</span></div>';

				item += '<i class="msg-user mui-icon  " style="background: url(../images/head_portrait60.png);background-size: 100%; border:0PX;"  ></i>';

				item += '	<div class="msg-content">';
				item += '	<div class="msg-content-inner">';

				content.content = decodeURIComponent(content.content).trim();
				item += '	' + content.content;

				item += '	</div>';
				item += '	<div class="msg-content-arrow"></div>';
				item += '	</div>';
				item += '	<div class="mui-item-clear"></div>';
				item += '	</div>';

				$('#msg-list').append(item)
			});

			scroll_chat()

		},
		error: function(xhr, type, errorThrown) {
			//异常处理；
			console.log(type);
			if(window.plus) {
				plus.nativeUI.closeWaiting();
			}
		}
	});

}

function init_user_recent_message_html(update_chat_user_id, count, content_obj) {

	var temp_chat_user_id = 0;

	if(localStorage.getItem('user_recent_message_html') != '') {

		$('.msg_item').each(function() {
			var chat_user_id = $(this).attr('chat_user_id');

			if(update_chat_user_id == chat_user_id) {
				temp_chat_user_id = chat_user_id;

				content_obj.content = decodeURIComponent(content_obj.content);

				content_obj.content = html_decode(content_obj.content);

				content_type(content_obj);

				if(count > 0) {
					$(this).find('.msg').css('display', 'block')
					$(this).find('.msg').html(count)
					$(this).find('.content').html(content_obj.content)
					$(this).find('.time').html(content_obj.addtime_str)
					localStorage.setItem('no_read_user_id', update_chat_user_id);
					console.log(update_chat_user_id)
				} else {
					$(this).find('.msg').css('display', 'none')
					$(this).find('.content').html(content_obj.content)
					$(this).find('.time').html(content_obj.addtime_str)
				}
				localStorage.setItem('user_recent_message_html', $('.people-list').html());
			}
		});
	}
	if(temp_chat_user_id == 0) {
		insert_item(PreUrl, content_obj);
	}
	var webview = plus.webview.getLaunchWebview();

	webview.evalJS('update_count();')

}

function update_is_read() {
	var no_read_user_id = localStorage.getItem('no_read_user_id');

	if(no_read_user_id > 0) {
		localStorage.setItem('chat_user_id', no_read_user_id);
		get_new_user_message_list(1);
	}
}

function update_user_recent_message_list() {
	var user_id = localStorage.getItem('user_id');

	mui.ajax(get_user_recent_message_list_fun, {
		data: {
			is_mobile: 1,
			user_id: user_id
		},
		dataType: 'json', //服务器返回json格式数据
		type: 'post', //HTTP请求类型
		timeout: 10000, //超时时间设置为10秒；

		success: function(data) {
			var status = data.status;
			if(status == 1) {
				var info = data.data;
				var all_no_read_count = 0;

				$.each(info, function(index, content) {

					var member_id = content.member_id;
					var no_read_count = content.no_chat_read_count;
					var addtime_str = content.addtime_str;
					var chat_user_id = content.user_id;
					if(chat_user_id == user_id) {
						chat_user_id = content.chat_user_id;
						member_id = content.member_id1;
						no_read_count = content.no_read_count;

					}
					all_no_read_count += parseInt(no_read_count);

					init_user_recent_message_html(chat_user_id, no_read_count, content);
					if(no_read_count > 0) {
						localStorage.setItem('chat_user_id', chat_user_id);
						get_new_user_message_list(0);
					}
				});
				localStorage.setItem('all_no_read_count', all_no_read_count);
				localStorage.setItem('get_user_recent_message_list', encodeURIComponent(JSON.stringify(info)));

			} else {
				//				mui.toast(data.info)
			}

		},
		error: function(xhr, type, errorThrown) {
			//异常处理；
			console.log(type);
		}
	});

}

function insert_item(PreUrl, content) {
	var chat_user_id = content.user_id;
	var member_id = content.member_id;
	var no_read_count = content.no_chat_read_count;
	var addtime_str = content.addtime_str;
	var user_id = localStorage.getItem('user_id');
	if(chat_user_id == user_id) {
		chat_user_id = content.chat_user_id;
		member_id = content.member_id1;
		no_read_count = content.no_read_count;

	}
	var item = '	<div class="aw-item msg_item"     chat_user_id="' + chat_user_id + '"> <a class="aw-user-img aw-border-radius-5"  onclick="chat(' + chat_user_id + ',' + member_id + '  )"  href="javascript:void(0)">';
	item += '		<img src="' + PreUrl + content.avatar + '"> </a>';
	item += '	<p class="text-color-999 title">';
	item += '	<a href="javascript:void(0)" onclick="chat(' + chat_user_id + ',' + member_id + ' )"  class="aw-user-name">' + member_id + '</a> </p>';
	item += '	<p class="text-color-999"></p>';
	item += '	<div class="meta" style="overflow: hidden; text-overflow: ellipsis;   white-space: nowrap; width: 70%;">';

	content.content = decodeURIComponent(content.content);

	content.content = html_decode(content.content);

	content_type(content);

	item += '		<span ><i class="icon icon-prestige"></i>  <b class="content">' + content.content + '</b></span>';
	item += '		   </div>';

	if(no_read_count > 0) {

		item += '	<a class="msg aw-border-radius-5" style="right: 10px;"   href="javascript:void(0)">';
		item += '		' + no_read_count;
		item += '	</a>';
	} else {
		item += '	<a class="msg aw-border-radius-5" style="right: 10px;display:none"   href="javascript:void(0)">';
		item += '		' + no_read_count;
		item += '	</a>';
	}
	item += '	<a class="time aw-border-radius-5" style="right: 10px;"   href="javascript:void(0)">';
	item += '		' + addtime_str;
	item += '	</a>';
	item += '	</div>';

	$('.people-list').append(item);
	localStorage.setItem('user_recent_message_html', $('.people-list').html());
}

function get_user_recent_message_list() {
	var user_id = localStorage.getItem('user_id');
	var record = '';
	if(get_user_recent_message_list != null) {
		record = JSON.parse(decodeURIComponent(localStorage.getItem('get_user_recent_message_list')));
		$('.people-list').html(localStorage.getItem('user_recent_message_html'));

	} else {
		$('.people-list').html('');
		mui.ajax(get_user_recent_message_list_fun, {
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
					$('.people-list').html('');
					var all_no_read_count = 0;
					$.each(info, function(index, content) {

						var no_read_count = content.no_chat_read_count;
						all_no_read_count += parseInt(no_read_count);

						insert_item(PreUrl, content)

					});
					localStorage.setItem('all_no_read_count', all_no_read_count);
					localStorage.setItem('get_user_recent_message_list', encodeURIComponent(JSON.stringify(info)));
					localStorage.setItem('user_recent_message_html', $('.people-list').html());
					mui.plusReady(function() {
						var webview = plus.webview.getLaunchWebview();

						webview.evalJS('update_count();')
					});

				} else {
					mui.toast(data.info)
				}

			},
			error: function(xhr, type, errorThrown) {
				//异常处理；
				console.log(type);
			}
		});
	}
}
//
//function get_user_message_list() {
//
//	var user_id = localStorage.getItem('user_id');
//	var chat_user_id = localStorage.getItem('chat_user_id');
//	var msg_id = localStorage.getItem('msg_id');
//
//	$('.apply-people-list').html('');
//	mui.ajax(get_user_message_list_fun, {
//		data: {
//			is_mobile: 1,
//			user_id: localStorage.getItem('user_id'),
//			chat_user_id: chat_user_id,
//			is_read: 1,
//			msg_id: msg_id
//		},
//		dataType: 'json', //服务器返回json格式数据
//		type: 'post', //HTTP请求类型
//		timeout: 10000, //超时时间设置为10秒；
//
//		success: function(data) { 
//			var status = data.status;
//
//			if(status == 1) {
//				record = data.data;
//
//			} else {
//				mui.toast(data.info)
//			}
//
//		},
//		error: function(xhr, type, errorThrown) {
//			//异常处理；
//			console.log(type);
//		}
//	});
//}

function content_type(content) {
	if(content.type == 'sound') {
		content.content = '[语音消息]'
	}
	if(content.type == 'image') {
		content.content = '[图片]'
	}

}

function html_decode(str) {
	var s = "";
	if(str.length == 0) {
		return "";
	} else {

		s = str.replace("&nbsp;", " ");
		s = s.replace("<br>", "\r\n");
		s = s.replace("<br>", "\n");
		s = s.replace("<br />", "\n");
		s = s.replace("<br />", "\r\n");
		var reg = new RegExp("&lt;", "g"); //g,表示全部替换。
		s = s.replace(reg, "<");
		var reg = new RegExp("&gt;", "g"); //g,表示全部替换。
		s = s.replace(reg, ">");
		var reg = new RegExp("&amp;", "g"); //g,表示全部替换。
		s = s.replace(reg, "&");
		var reg = new RegExp("&quot;", "g"); //g,表示全部替换。
		s = s.replace(reg, "");

	}
	return s;
}

function scroll_chat() {
	var div = document.getElementById('container');

	div.scrollTop = div.scrollHeight;

}

function send_msg(obj) {

	var user_id = localStorage.getItem('user_id');
	var chat_user_id = localStorage.getItem('chat_user_id');

	mui.ajax({
		url: send_msg_fun,
		data: {
			is_mobile: 1,
			content: $(obj).html(),
			chat_user_id: chat_user_id
		},
		dataType: 'json', //服务器返回json格式数据
		type: 'post', //HTTP请求类型
		timeout: 10000, //超时时间设置为10秒；

		success: function(data) {
			var status = data.status;
			mui.toast(data.info);
			$(obj).html('');
			get_user_message_list();
		},
		error: function(xhr, type, errorThrown) {
			//异常处理；
			console.log(type);
		}
	});

}

function select_person(obj) {
	$('.aw-people-list').html('');
	var mobile = $(obj).val();
	if(mobile == '') {
		mui.toast('请输入用户名')
	} else {
		$('.aw-people-list').html('');
		mui.ajax(select_person_list, {
			data: {
				is_mobile: 1,
				keywords: mobile
			},
			dataType: 'json', //服务器返回json格式数据
			type: 'post', //HTTP请求类型
			timeout: 10000, //超时时间设置为10秒；

			success: function(data) {
				$('.aw-people-list').html('');
				var status = data.status;
				if(status == 1) {
					var info = data.data;

					$.each(info, function(index, content) {
						var item = '	<div class="aw-item"> <a class="aw-user-img aw-border-radius-5" href="javascript:void(0)">';
						item += '		<img src="' + PreUrl + content.avatar + '"> </a>';
						item += '	<p class="text-color-999 title">';
						item += '	<a href="javascript:void(0)" class="aw-user-name">' + content.member_id + '</a> </p>';
						item += '	<p class="text-color-999"></p>';
						item += '	<div class="meta">';
						item += '		<span><i class="icon icon-prestige"></i>现金 <b>' + content.money + '</b></span>';
						item += '		<span><i class="icon icon-score"></i>积分 <b>' + content.point + '</b></span>   </div>';
						item += '	<a class="aw-user-add aw-border-radius-5" style="right: 10px;"  onclick="add_user_apply(' + content.id + ')" href="javascript:void(0)">';
						item += '		 添加';
						item += '	</a>';

						item += '	</div>';
						$('.aw-people-list').append(item);

					});
				} else {
					mui.toast(data.info)
				}

			},
			error: function(xhr, type, errorThrown) {
				//异常处理；
				console.log(type);
			}
		});

	}
}

function get_user_apply(apply_user_id) {
	var user_id = localStorage.getItem('user_id');

	$('.apply-people-list').html('');
	mui.ajax(get_user_apply_fun, {
		data: {
			is_mobile: 1,
			user_id: localStorage.getItem('user_id'),
			apply_user_id: apply_user_id
		},
		dataType: 'json', //服务器返回json格式数据
		type: 'post', //HTTP请求类型
		timeout: 10000, //超时时间设置为10秒；

		success: function(data) {

			var status = data.status;
			if(status == 1) {
				var info = data.data;
				var count = info.length;
				$('.user_apply_count').html(count);
				localStorage.setItem('user_apply_count', count);
				$.each(info, function(index, content) {
					var item = '	<div class="aw-item"> <a class="aw-user-img aw-border-radius-5" href="javascript:void(0)">';
					item += '		<img src="' + PreUrl + content.avatar + '"> </a>';
					item += '	<p class="text-color-999 title">';
					item += '	<a href="javascript:void(0)" class="aw-user-name">' + content.member_id + '</a> </p>';
					item += '	<p class="text-color-999"></p>';
					item += '	<div class="meta">';
					item += '		<span><i class="icon icon-prestige"></i>现金 <b>' + content.money + '</b></span>';
					item += '		<span><i class="icon icon-score"></i>积分 <b>' + content.point + '</b></span>   </div>';
					item += '	<a class="aw-user-add aw-border-radius-5"  onclick="set_user_apply(' + content.id + ',0 )" href="javascript:void(0)">';
					item += '		 验证';
					item += '	</a>';
					item += '	<a class="aw-user-rejuse aw-border-radius-5"  onclick="set_user_apply(' + content.id + ',2 )" href="javascript:void(0)">';
					item += '		 拒绝';
					item += '	</a>';
					item += '	</div>';
					$('.apply-people-list').append(item);

				});
			} else {
				mui.toast(data.info)
			}

		},
		error: function(xhr, type, errorThrown) {
			//异常处理；
			console.log(type);
		}
	});
}

function get_friend_list() {
	var user_id = localStorage.getItem('user_id');

	$('.people-list').html('');
	mui.ajax(get_friend_list_fun, {
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

				$('.people-list').html('');
				$.each(info, function(index, content) {

					var item = '	<div class="aw-item"> <a class="aw-user-img aw-border-radius-5"  onclick="chat(' + content.id + ',' + content.member_id + '  )"  href="javascript:void(0)">';
					item += '		<img src="' + PreUrl + content.avatar + '"> </a>';
					item += '	<p class="text-color-999 title">';
					item += '	<a href="javascript:void(0)" onclick="chat(' + content.id + ',' + content.member_id + '  )"  class="aw-user-name">' + content.member_id + '</a> </p>';
					item += '	<p class="text-color-999"></p>';
					item += '	<div class="meta">';
					item += '		<span><i class="icon icon-prestige"></i>现金 <b>' + content.money + '</b></span>';
					item += '		<span><i class="icon icon-score"></i>积分 <b>' + content.point + '</b></span>   </div>';
					item += '	<a class="aw-user-add aw-border-radius-5" style="right: 10px;"   onclick="delete_myfriend(' + content.id + ' )" href="javascript:void(0)">';
					item += '		删除';
					item += '	</a>';

					item += '	</div>';
					$('.people-list').append(item);

				});
			} else {
				mui.toast(data.info)
			}

		},
		error: function(xhr, type, errorThrown) {
			//异常处理；
			console.log(type);
		}
	});
}
 
function chat(id, name) {
	localStorage.setItem('chat_user_id', id);

	openWindowWithTitle(name.toString(), 'im-chat.html')
}

function delete_myfriend(id) {
	var user_id = localStorage.getItem('user_id');

	if(user_id == 0 || user_id == null) {
		mui.toast('请先登录')
		return;
	}

	var btnArray = ['否', '是'];
	mui.confirm('删除好友，确认？', '惊喜', btnArray, function(e) {
		if(e.index == 1) {
			mui.ajax(delete_myfriend_fun, {
				data: {
					is_mobile: 1,
					user_id: user_id,
					delete_user_id: id
				},
				dataType: 'json', //服务器返回json格式数据
				type: 'post', //HTTP请求类型
				timeout: 10000, //超时时间设置为10秒；

				success: function(data) {
					mui.toast(data.info);
					location.reload();

				},
				error: function(xhr, type, errorThrown) {
					//异常处理；
					console.log(type);
				}
			});
		} else {

		}
	})

}

function set_user_apply(id, status) {
	var user_id = localStorage.getItem('user_id');

	if(user_id == 0 || user_id == null) {
		mui.toast('请先登录')
		return;
	}

	mui.ajax(set_user_apply_fun, {
		data: {
			is_mobile: 1,
			user_id: localStorage.getItem('user_id'),
			id: id,
			status: status
		},
		dataType: 'json', //服务器返回json格式数据
		type: 'post', //HTTP请求类型
		timeout: 10000, //超时时间设置为10秒；

		success: function(data) {
			mui.toast(data.info);
			location.reload();

		},
		error: function(xhr, type, errorThrown) {
			//异常处理；
			console.log(type);
		}
	});
}

function add_user_apply(apply_user_id) {
	var user_id = localStorage.getItem('user_id');

	if(user_id == 0 || user_id == null) {
		mui.toast('请先登录')
		return;
	}

	mui.ajax(add_user_apply_fun, {
		data: {
			is_mobile: 1,
			user_id: localStorage.getItem('user_id'),
			apply_user_id: apply_user_id
		},
		dataType: 'json', //服务器返回json格式数据
		type: 'post', //HTTP请求类型
		timeout: 10000, //超时时间设置为10秒；

		success: function(data) {
			mui.toast(data.info)

		},
		error: function(xhr, type, errorThrown) {
			//异常处理；
			console.log(type);
		}
	});
}

function add_user_apply(apply_user_id) {
	var user_id = localStorage.getItem('user_id');

	if(user_id == 0 || user_id == null) {
		mui.toast('请先登录')
		return;
	}

	mui.ajax(add_user_apply_fun, {
		data: {
			is_mobile: 1,
			user_id: localStorage.getItem('user_id'),
			apply_user_id: apply_user_id
		},
		dataType: 'json', //服务器返回json格式数据
		type: 'post', //HTTP请求类型
		timeout: 10000, //超时时间设置为10秒；

		success: function(data) {
			mui.toast(data.info)

		},
		error: function(xhr, type, errorThrown) {
			//异常处理；
			console.log(type);
		}
	});
}