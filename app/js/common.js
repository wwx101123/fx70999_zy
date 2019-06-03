/*检测是否移动设备来访否则跳转*/
if(!browserRedirect()) {

}
/*页面加载完毕时运行函数
-----------------------------------------------------*/
/*当窗体加载时缩放内容元素的大小*/
setItem();
//mui.plusReady(function() {
//	InitObjectWidth($(".entry iframe"));
//	InitObjectWidth($(".entry embed"));
//	InitObjectWidth($(".entry video"));
//	setItem();
//	//	$('.page__bd').css('padding-top', $('.header').height())
//	//	$.getScript("../js/chinese.js");
//});
/*当窗体大小发生改变时缩放内容元素的大小*/

/*全局函数
-----------------------------------------------------*/
//检测是否移动设备来访
function browserRedirect() {
	var sUserAgent = navigator.userAgent.toLowerCase();
	var bIsIpad = sUserAgent.match(/ipad/i) == "ipad";
	var bIsIphoneOs = sUserAgent.match(/iphone os/i) == "iphone os";
	var bIsMidp = sUserAgent.match(/midp/i) == "midp";
	var bIsUc7 = sUserAgent.match(/rv:1.2.3.4/i) == "rv:1.2.3.4";
	var bIsUc = sUserAgent.match(/ucweb/i) == "ucweb";
	var bIsAndroid = sUserAgent.match(/android/i) == "android";
	var bIsCE = sUserAgent.match(/windows ce/i) == "windows ce";
	var bIsWM = sUserAgent.match(/windows mobile/i) == "windows mobile";
	if(bIsIpad || bIsIphoneOs || bIsMidp || bIsUc7 || bIsUc || bIsAndroid || bIsCE || bIsWM) {
		return true;
	} else {
		return false;
	}
}
//初始化页面无素的大小
function InitObjectWidth(obj) {
	var maxWidth = $(".entry").width();
	obj.each(function() {
		if($(this).width() > maxWidth) {
			var wh = $(this).width() / $(this).height();
			var newHeight = maxWidth / wh;
			$(this).width(maxWidth);
			$(this).height(newHeight);
		}
	});
}
//写Cookie
function addCookie(objName, objValue, objHours) {
	var str = objName + "=" + escape(objValue);
	if(objHours > 0) { //为0时不设定过期时间，浏览器关闭时cookie自动消失
		var date = new Date();
		var ms = objHours * 3600 * 1000;
		date.setTime(date.getTime() + ms);
		str += "; expires=" + date.toGMTString();
	}
	document.cookie = str;
}

//读Cookie
function getCookie(objName) { //获取指定名称的cookie的值
	var arrStr = document.cookie.split("; ");
	for(var i = 0; i < arrStr.length; i++) {
		var temp = arrStr[i].split("=");
		if(temp[0] == objName) return unescape(temp[1]);
	}
	return "";
}
/*页面通用函数
-----------------------------------------------------*/
/*切换验证码*/
function ToggleCode(obj, codeurl) {
	$(obj).children("img").eq(0).attr("src", codeurl + "?time=" + Math.random());
	return false;
}
/*下载链接*/
function downLink(point, linkurl) {
	if(point > 0) {
		weui.confirm('下载需扣除' + point + '个积分<br />重复下载不扣积分，需要继续吗？', function() {
			window.location.href = linkurl;
		}, function() {});
	} else {
		window.location.href = linkurl;
	}
	return false;
}
/*弹出窗口*/
function showWindow(obj) {
	var tit = $(obj).attr("title");
	var box = $(obj).html();
	weui.dialog({
		title: tit,
		content: box,
		buttons: [{
			label: '确定',
			type: 'primary',
			onClick: function() {}
		}]
	});
}
/*弹出浮动层*/
function showDialogBox(obj) {
	$(obj).fadeIn(200);
}

function closeDialogBox(obj) {
	$(obj).fadeOut(200);
}

//发送验证邮件
function sendEmail(username, sendurl) {
	if(username == "") {
		weui.alert("对不起，用户名不允许为空！");
		return false;
	}
	//提交
	$.ajax({
		url: sendurl,
		type: "POST",
		timeout: 60000,
		data: {
			"username": username
		},
		dataType: "json",
		success: function(data, type) {
			if(data.status == 1) {
				weui.alert(data.msg);
			} else {
				weui.alert(data.msg);
			}
		},
		error: function(XMLHttpRequest, textStatus, errorThrown) {
			weui.alert("状态：" + textStatus + "；出错提示：" + errorThrown);
		}
	});
}

//发送手机短信验证码
var wait = 0; //计算变量
function sendSMS(btnObj, valObj, sendUrl) {
	if($(valObj).val() == "") {
		weui.alert('对不起，请填写手机号码后再获取！');
		return false;
	}
	//发送AJAX请求
	$.ajax({
		url: sendUrl,
		type: "POST",
		timeout: 60000,
		data: {
			"mobile": $(valObj).val()
		},
		dataType: "json",
		beforeSend: function(XMLHttpRequest) {
			$(btnObj).unbind("click").removeAttr("onclick"); //移除按钮事件
		},
		success: function(data, type) {
			if(data.status == 1) {
				weui.alert(data.msg, function() {
					wait = data.time * 60; //赋值时间
					time(); //调用计算器
				});
			} else {
				weui.alert(data.msg, function() {
					$(btnObj).removeClass("gray").text("获取验证码");
					$(btnObj).bind("click", function() {
						sendSMS(btnObj, valObj, sendurl); //重新绑定事件
					});
				});
			}
		},
		error: function(XMLHttpRequest, textStatus, errorThrown) {
			weui.alert(data.msg, function() {
				$(btnObj).removeClass("gray").text("获取验证码");
				$(btnObj).bind("click", function() {
					sendSMS(btnObj, valObj, sendUrl); //重新绑定事件
				});
			});
		}
	});
	//倒计时计算器
	function time() {
		if(wait == 0) {
			$(btnObj).removeClass("gray").text("获取验证码");
			$(btnObj).bind("click", function() {
				sendSMS(btnObj, valObj, sendurl); //重新绑定事件
			});
		} else {
			$(btnObj).addClass("gray").text("重新发送(" + wait + ")");
			wait--;
			setTimeout(function() {
				time(btnObj);
			}, 1000)
		}
	}
}

//单击执行AJAX请求操作
function clickSubmit(sendUrl) {
	$.ajax({
		type: "POST",
		url: sendUrl,
		dataType: "json",
		timeout: 20000,
		success: function(data, textStatus) {
			if(data.status == 1) {
				weui.alert(data.msg, function() {
					location.reload();
				});
			} else {
				weui.alert(data.msg);
			}
		},
		error: function(XMLHttpRequest, textStatus, errorThrown) {
			weui.alert("状态：" + textStatus + "；出错提示：" + errorThrown);
		}
	});
}

/*全选取消按钮函数*/
function checkAll(chkobj) {
	if($(chkobj).text() == "全选") {
		$(chkobj).text("取消");
		$(".checkall").prop("checked", true);
	} else {
		$(chkobj).text("全选");
		$(".checkall").prop("checked", false);
	}
}
var ServerIP = "";
var IP = localStorage.getItem('IP');
var PreUrl = localStorage.getItem('PreUrl');
/**
 * 获取商品列表
 */
var del_fun = PreUrl + "Goods/";
/*执行删除操作*/
function ExecDelete(sendUrl, checkValue, urlId) {
	var urlObj = $(urlId);
	//检查传输的值
	if(!checkValue) {
		mui.alert("对不起，请选中您要操作的记录！");
		return false;
	}
	mui.confirm('删除记录后不可恢复，您确定吗？', function(e) {
		if(e.index == 1) {
			$.ajax({
				type: "POST",
				url: del_fun + sendUrl,
				dataType: "json",
				data: {
					"checkId": checkValue,
					"is_mobile": 1
				},
				timeout: 20000,
				success: function(data, textStatus) {
					if(data.status == 1) {
						mui.alert(data.msg, function() {
							if(urlObj) {
								location.href = urlObj.val();
							} else {
								location.reload();
							}
						});
					} else {
						mui.toast(data.msg);
					}
				},
				error: function(XMLHttpRequest, textStatus, errorThrown) {
					weui.alert("状态：" + textStatus + "；出错提示：" + errorThrown);
				}
			});
		}
	}, function() {});
}

/*表单AJAX提交封装(包含验证)*/
function AjaxInitForm(formId, btnId, isDialog, urlId) {
	var formObj = $(formId); //表单元素
	var btnObj = $(btnId); //按钮元素
	var urlObj = $(urlId); //隐藏域元素

	formObj.mvalidate({
		type: 1,
		onKeyup: true,
		sendForm: false,
		firstInvalidFocus: true,
		valid: function(event, options) {
			$.ajax({
				type: "post",
				url: formObj.attr("url"),
				data: formObj.serialize(),
				dataType: "json",
				beforeSend: function(formData, jqForm, options) {
					btnObj.prop("disabled", true);
					btnObj.val("请稍候...");
				},
				success: function(data, textStatus) {

					$('.COMM_MSGBOX_MASK').css('display', 'none')
					$('.COMM_MSGBOX_WRAPPER').css('display', 'none')
					if(data.status == 1) {
						if(data.is_login == 1) {
							localStorage.setItem('user_id', data.data.id);
							localStorage.setItem('user', JSON.stringify(data.data));
							localStorage.setItem('bank_list', JSON.stringify(data.bank_list));
							localStorage.setItem('is_sign', data.data.is_sign);

							mui.toast(data.msg);
							setTimeout(function() {
								localStorage.setItem('autoBackButton', false)
								openWindowWithTitle('首页', 'index.html')
							}, 1000);
							return;

						}
						if(data.is_shopping == 1) {
							localStorage.setItem('shoppingCartCount', data.cart_num)
							localStorage.setItem('zfb_img', data.zfb_img)
							localStorage.setItem('wx_img', data.wx_img)

						}
						localStorage.setItem('FID', '');
						localStorage.setItem('RID', '');
						localStorage.setItem('TPL', '');
						btnObj.val("提交成功");
						//lang()

						//是否提示，需配合weui.js使用，默认不提示
						if(isDialog == 1) {

							mui.alert(data.msg, '提示', function() {
								if(data.url) {
									location.href = data.url;
								} else if(urlObj.length > 0 && urlObj.val() != "") {
									location.href = urlObj.val();
								} else {
									location.reload();
								}
							});

						} else {
							if(data.url) {
								location.href = data.url;
							} else if(urlObj) {
								location.href = urlObj.val();
							} else {
								location.reload();
							}
						}
					} else {
						mui.toast(data.msg);
						btnObj.prop("disabled", false);
						btnObj.val("重新提交");
					}
				},
				error: function(XMLHttpRequest, textStatus, errorThrown) {
					mui.alert("状态：" + textStatus + "；出错提示：" + errorThrown);
					btnObj.prop("disabled", false);
					btnObj.val("重新提交");
				}
			});
		},
		conditional: {
			confirmpwd: function() {
				return $("#txtPassword").val() == $("#txtPassword1").val();
			}
		},
		descriptions: {
			confirmpassword: {
				required: '请再次输入密码',
				conditional: '两次密码不一致'
			}
		}
	});
}

/*AJAX加载评论列表*/
var commentPageIndex = 1; //评论页码全局变量
function CommentAjaxList(btnObj, listDiv, pageSize, pageCount, sendUrl) {
	//计算总页数
	var pageTotal = Math.ceil(pageCount / pageSize);
	if(commentPageIndex > pageTotal) {
		return false;
	}
	//发送AJAX请求
	$.ajax({
		type: "post",
		url: sendUrl + "&page_size=" + pageSize + "&page_index=" + commentPageIndex,
		dataType: "json",
		beforeSend: function() {
			$(btnObj).prop("disabled", true);
			$(btnObj).val('正在加载...');
		},
		success: function(data) {
			var strHtml = '';
			for(var i in data) {
				strHtml += '<li>' +
					'<div class="avatar">';
				if(typeof(data[i].avatar) != "undefined" && data[i].avatar.length > 0) {
					strHtml += '<img src="' + data[i].avatar + '" />';
				} else {
					strHtml += '<i class="iconfont icon-user-full"></i>';
				}
				strHtml += '</div>' +

					'<div class="inner">' +
					'<div class="meta">' +
					'<span class="blue">' + data[i].user_name + '</span>\n' +
					'<span class="time">' + data[i].add_time + '</span>' +
					'</div>' +
					'<p>' + unescape(data[i].content) + '</p>' +
					'</div>';
				if(data[i].is_reply == 1) {
					strHtml += '<div class="answer">' +
						'<div class="meta">' +
						'<span class="time">' + data[i].reply_time + '</span>' +
						'<span class="blue">管理员回复：</span>' +
						'</div>' +
						'<p>' + unescape(data[i].reply_content) + '</p>' +
						'</div>';
				}
				strHtml += '</li>';
			}
			//添加到列表
			if(commentPageIndex == 1) {
				$(listDiv).html(strHtml);
			} else {
				$(listDiv).append(strHtml);
			}
			commentPageIndex++; //当前页码加一
			if(commentPageIndex > pageTotal) {
				$(btnObj).parent().hide();
			}
			lang()
		},
		complete: function() {
			$(btnObj).prop("disabled", false);
			$(btnObj).val('加载更多评论');
		}
	});
}
var jinzhi = 0;
document.addEventListener('DOMContentLoaded', function(event) {
	// chrome 浏览器直接加上下面这个样式就行了，但是ff不识别
	document.body.style.zoom = 'reset';
	document.addEventListener('keydown', function(event) {
		if((event.ctrlKey === true || event.metaKey === true) &&
			(event.which === 61 || event.which === 107 ||
				event.which === 173 || event.which === 109 ||
				event.which === 187 || event.which === 189)) {
			event.preventDefault();
		}
	}, false);
	document.addEventListener('mousewheel DOMMouseScroll', function(event) {
		if(event.ctrlKey === true || event.metaKey) {
			event.preventDefault();
		}
	}, false);
}, false);

function setItem() {
	var host = window.location.host;
	var protocol = window.location.protocol;

	ServerIP = "zyadmin.super-nba.com";
	//	ServerIP = "192.168.0.81:8077";  
	IP = "http://" + ServerIP + "";
	PreUrl = IP + "/adm.php/";
	IndexUrl = IP + "/index.php/";
	AppUrl = IP + "/app/android/index.html";
	registerUrl = "https://" + host + "/main/register.html";

	UploadUrl = IP + "/";

	localStorage.setItem('UploadUrl', UploadUrl);
	localStorage.setItem('ServerIP', ServerIP);
	localStorage.setItem('PreUrl', PreUrl);
	localStorage.setItem('IndexUrl', IndexUrl);
	localStorage.setItem('AppUrl', AppUrl);
	localStorage.setItem('IP', IP);
	localStorage.setItem('registerUrl', registerUrl);
	localStorage.setItem('gd_address', '02ac29432b34f34492a1e444f99ab451');
	localStorage.setItem('gd_map', '99caff418ca5fb3aa1b922d4a4a283fb');

	localStorage.setItem('wx_upload', 0);
	localStorage.setItem('zfb_upload', 0);
	localStorage.setItem('license_img1_upload', 0);
	localStorage.setItem('license_img1_upload', 0);
	//	localStorage.setItem('lang', 0);
}

function exit() {
	var btn = ["确定", "取消"];
	plus.runtime.getProperty(plus.runtime.appid, function(wgtInfo) {

		console.log("[APP] name: " +
			wgtInfo.name);

		btn = ["确定", "取消"];
		mui.confirm('确认关闭app？', wgtInfo.name, btn, function(e) {
			if(e.index == 0) {
				localStorage.setItem('user_name', '');
				localStorage.setItem('user_id', '');
				localStorage.setItem('user', '');
				if(mui.os.ios) {
					localStorage.clear();
					plus.runtime.restart();
				} else {
					plus.runtime.quit();
				}
			}
		});

	});

}

function ajax() {
	var ajaxData = {
		type: arguments[0].type || "GET",
		url: arguments[0].url || "",
		async: arguments[0].async || "true",
		data: arguments[0].data || null,
		dataType: arguments[0].dataType || "text",
		contentType: arguments[0].contentType || "application/x-www-form-urlencoded",
		beforeSend: arguments[0].beforeSend || function() {},
		success: arguments[0].success || function() {},
		error: arguments[0].error || function() {}
	}
	ajaxData.beforeSend()
	var xhr = createxmlHttpRequest();
	xhr.responseType = ajaxData.dataType;
	xhr.open(ajaxData.type, ajaxData.url, ajaxData.async);
	xhr.setRequestHeader("Content-Type", ajaxData.contentType);
	xhr.send(convertData(ajaxData.data));
	xhr.onreadystatechange = function() {
		if(xhr.readyState == 4) {
			if(xhr.status == 200) {
				ajaxData.success(xhr.response)
			} else {
				ajaxData.error()
			}
		}
	}
}

function createxmlHttpRequest() {
	if(window.ActiveXObject) {
		return new ActiveXObject("Microsoft.XMLHTTP");
	} else if(window.XMLHttpRequest) {
		return new XMLHttpRequest();
	}
}

function convertData(data) {
	if(typeof data === 'object') {
		var convertResult = "";
		for(var c in data) {
			convertResult += c + "=" + data[c] + "&";
		}
		convertResult = convertResult.substring(0, convertResult.length - 1)
		return convertResult;
	} else {
		return data;
	}
}

function imgLoad(url) {
	var tmpl = '<li class="weui-uploader__file" style="background-image:url(#url#)"></li>',
		$uploaderInput = $("#uploaderInput"),
		$uploaderFiles = $("#uploaderFiles");
	$uploader_list = $("#uploader_list");
	$uploaderzfbFiles = $("#uploaderzfbFiles_list");
	$uploaderbank1Files = $("#uploaderbank1Files_list");
	$uploaderbank2Files = $("#uploaderbank2Files_list");

	var upload_type = localStorage.getItem('upload_type')

	//	$uploadercard_img5_list = $("#uploadercard_img5_list");
	//	$uploadercard_img4_list = $("#uploadercard_img4_list");
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
			$.showLoading("上传中...");
			$.ajax({
				url: PreUrl + "User/upload",

				type: 'POST',

				data: {
					is_mobile: 1,
					imgOne: base64
				},

				dataType: 'json', //服务器返回json格式数据

				success: function(data) {
					var wx_upload = localStorage.getItem('wx_upload')
					var zfb_upload = localStorage.getItem('zfb_upload')
					var license_img1_upload = localStorage.getItem('license_img1_upload')
					var license_img2_upload = localStorage.getItem('license_img2_upload')
					var card_img_upload = localStorage.getItem('card_img' + upload_type + '_upload')
					console.log('上传成功' + card_img_upload)
					$('.weui-mask_transparent').remove();
					$('.weui_loading_toast').remove();

					var UploadUrl = localStorage.getItem('UploadUrl');
					var item = '<input type="hidden" class="original_path" name="image" id="original_path" value="' + data.url + '">'
					var zfbitem = '<input type="hidden" class="zfboriginal_path" name="zfbimage" id="original_path" value="' + data.url + '">'
					var license_img1 = '<input type="hidden" class="license_img1" name="license_img1" id="license_img1" value="' + data.url + '">'
					var license_img2 = '<input type="hidden" class="license_img2" name="license_img2" id="license_img2" value="' + data.url + '">'
					var card_img = '<input type="hidden" class="card_img' + upload_type + '" name="card_img' + upload_type + '" id="card_img' + upload_type + '" value="' + data.url + '">'
					if(wx_upload == 1) {
						if($('.original_path').length < 2) {
							$uploader_list.find('input').remove();
							$uploader_list.append(item);
						}
					}
					if(zfb_upload == 1) {
						if($('.zfboriginal_path').length < 2) {
							$uploaderzfbFiles.find('input').remove();
							$uploaderzfbFiles.append(zfbitem);
						}
					}
					if(license_img1_upload == 1) {
						if($('.license_img1').length < 2) {
							$uploader_list.find('input').remove();
							$uploader_list.append(license_img1);
						}
					}
					if(license_img2_upload == 1) {
						if($('.license_img2').length < 2) {
							$uploaderzfbFiles.find('input').remove();
							$uploaderzfbFiles.append(license_img2);
						}
					}
					if(card_img_upload == 1) {
						if($('.card_img' + upload_type + '').length < 2) {
							console.log('放进去' + card_img)
							$('.uploadercard_img' + upload_type + '_list').find('.card_img' + upload_type + '').remove();
							$('.mui-input-group').append(card_img);
						}
					}

					$.toast(data.info);
					remove_toast()
					if($('.original_path').length > 0) {
						//						$('.uploader_btn').css('display', 'none')
					}
					if($('.zfboriginal_path').length > 0) {
						//						$('.zfbuploader_btn').css('display', 'none')
					}
					if($('.avatar_path').length > 0) {
						avatarEdit(data.url)
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

var avatarEdit_fun = PreUrl + "User/avatarEdit";

function avatarEdit(avatar) {
	$.ajax({
		url: avatarEdit_fun,
		data: {
			is_mobile: 1,
			user_id: localStorage.getItem('user_id'),
			avatar: avatar
		},
		dataType: 'json', //服务器返回json格式数据
		type: 'POST', //HTTP请求类型
		timeout: 10000, //超时时间设置为10秒；

		success: function(data) {
			var status = data.status;
			if(status == 1) {
				mui.toast(data.msg);

			} else {
				mui.toast(data.msg)
			}
			////lang()

		},
		error: function(xhr, type, errorThrown) {
			//异常处理；
			console.log(type);
		}
	});

}

function lang() {
	var type = localStorage.getItem('lang');
	//	/**
	//	 *  0  简体中文、type:1 繁体中文
	//	 **/ 
	if(type == 1) {

		zh_tran('t');
	} else {

		zh_tran('s');
	}
}

function load_footer(url, obj, js, index) {
	//	if(index == 0 || index == 3 || index == 1000) {
	//		$('.page__bd').css('margin-top', 0)
	//	}
	$(obj).html('');

	$.get(url, function(result) {
		$(obj).html(result);
		$(".tabbar a").removeClass('active');
		$(".tabbar a").eq(index).addClass("active");
		$('.tabbar img:eq(' + 0 + ')').attr('src', '../images/123_03.png')
		$('.tabbar img:eq(' + 1 + ')').attr('src', '../images/2(2).png')
		$('.tabbar img:eq(' + 2 + ')').attr('src', '../images/123_07.png')
		$('.tabbar img:eq(' + 3 + ')').attr('src', '../images/123_09.png')
		if(index == 0) {
			$('.tabbar img:eq(' + index + ')').attr('src', '../images/123_15.png')
		}
		if(index == 1) {
			$('.tabbar img:eq(' + index + ')').attr('src', '../images/1(2).png')
		}
		if(index == 2) {
			$('.tabbar img:eq(' + index + ')').attr('src', '../images/123_17.png')
		}
		if(index == 3) {
			$('.tabbar img:eq(' + index + ')').attr('src', '../images/123_18.png')
		}

		$('#commonNav img:eq(' + 0 + ')').attr('src', '../images/5aa10cd2N1318ac50.png')
		$('#commonNav img:eq(' + 1 + ')').attr('src', '../images/5aa10cdbNa9cd0320.png')
		$('#commonNav img:eq(' + 2 + ')').attr('src', '../images/5b483f93Nc6b13dd5-1.png')
		$('#commonNav img:eq(' + 3 + ')').attr('src', '../images/5aa10ceaN649eec12.png')
		$('#commonNav img:eq(' + 4 + ')').attr('src', '../images/5aa10cf6Nee5122a5.png')
		if(index == 0) {
			$('#commonNav img:eq(' + index + ')').attr('src', '../images/5aa10cd2N46f18ce6.png')
		}
		if(index == 1) {
			$('#commonNav img:eq(' + index + ')').attr('src', '../images/5aa10cdbNf3977e9f.png')
		}
		if(index == 2) {
			$('#commonNav img:eq(' + index + ')').attr('src', '../images/5b483f93Nc6b13dd5-2.png')
		}
		if(index == 3) {
			$('#commonNav img:eq(' + index + ')').attr('src', '../images/5aa10ceaN649eec13.png')
		}
		if(index == 4) {
			$('#commonNav img:eq(' + index + ')').attr('src', '../images/5aa10cf6Nbfbdc1af.png')
		}

		init_cart_num();

	});
}

function init_cart_num() {
	var cart_quantity = localStorage.getItem('shoppingCartCount');
	if(cart_quantity == 0) {
		$('.cart-num').css('display', 'none')
	} else {

		$('.cart-num').css('display', 'block')
		$('.cart-num').html(cart_quantity)
	}

}

function change_language() {
	if(localStorage.getItem('lang') == null) {
		localStorage.setItem('lang', 0);
		localStorage.setItem('language', '简体中文');

	}
	var defaultValue = localStorage.getItem('lang')

	//		var language = [{
	//			'label': '简体',
	//			'value': '0'
	//		}, {
	//			'label': '繁体',
	//			'value': '1'
	//		}]
	//		weui.picker(language, {
	//			defaultValue: [defaultValue],
	//			onChange: function(result, a) {
	//	
	//			},
	//			onConfirm: function(result) {
	//				localStorage.setItem('lang', result);
	//				localStorage.setItem('language', language[result]['label']);
	//	
	//				location.reload();
	//	
	//			}
	//		});
	//		var height=$('body').height();

	/**
	 * 获取对象属性的值
	 * 主要用于过滤三级联动中，可能出现的最低级的数据不存在的情况，实际开发中需要注意这一点；
	 * @param {Object} obj 对象
	 * @param {String} param 属性名
	 */
	mui.init();
	//普通示例
	var userPicker = new mui.PopPicker();
	userPicker.setData([{
		value: '0',
		text: '简体'
	}, {
		value: '1',
		text: '繁体'
	}, {
		value: '2',
		text: '英语'
	}, {
		value: '3',
		text: '德语'
	}, {
		value: '4',
		text: '韩语'
	}, {
		value: '5',
		text: '俄语'
	}]);

	userPicker.show(function(items) {
		var userResult = JSON.stringify(items[0]);
		//返回 false 可以阻止选择框的关闭
		localStorage.setItem('lang', items[0]['value']);
		localStorage.setItem('language', items[0]['value']);
		if(items[0]['value'] > 0) {
			mui.toast(items[0]['text'] + '版暂未开放')
			//location.reload();
		}
	});

	$('.mui-poppicker-body').css('height', 500);
	$('.weui-picker__hd').css('padding', '0.1rem 0.2rem')
	$('.mui-pciker-rule').css('height', '.5rem')
	$('.mui-pciker-rule').css('line-height', '.5rem')
	$('.weui-picker__action').css('font-size', '.3rem')
	$('.mui-poppicker-header').css('font-size', '.3rem')
	$('.mui-btn').css('font-size', '.3rem')
	$('.mui-btn').css('padding', '0.1rem 0.1rem')
	$('.weui-picker__indicator').css('top', '1.6rem')
}

function open_img(obj) {
	var url = $(obj).attr('url');
	if(url == '' || url == null) {
		mui.toast('请上传二维码')
		return;
	}
	location.href = $(obj).attr('url')

}

function getQueryString(name) {

	var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)", "i");
	var r = window.location.search.substr(1).match(reg);
	if(r != null) return unescape(r[2]);
	return null;
}

function open_order_list(type) {
	localStorage.setItem('type', type)
	location.href = 'usershoporder.html';

}

function openWindowWithTitle(title, URL) {
console.log(title)
//	plus.webview.close(URL)
	mui.openWindowWithTitle({
		url: URL,
		id: URL,
		styles: webview_style(title)
	})

}

function webview_style(title) {
	var is_login = localStorage.getItem('is_login');
	var autoBackButton = localStorage.getItem('autoBackButton');

	var webview_style = {
		popGesture: "close"
	}
	webview_style.titleNView = { //配置原生标题
		'backgroundColor': '#4c2664',
		'titleText': title,
		'titleColor': '#FFFFFF',
		autoBackButton: autoBackButton,
		splitLine: {
			color: '#FFFFFF',
			height: '0px'
		}
	};
	webview_style.zindex = "100";

	if(is_login == 1) {
		var titleNView = { //详情页原生导航配置
			backgroundColor: '#f7f7f7', //导航栏背景色
			titleText: '', //导航栏标题
			titleColor: '#000000', //文字颜色
			type: 'transparent', //透明渐变样式

			splitLine: { //底部分割线
				color: '#cccccc'
			}
		}
		webview_style.zindex = "100";
		webview_style.render = "always";
		webview_style.popGesture = "hide";
		webview_style.bounce = "vertical";
		webview_style.bounceBackground = "#efeff4";
		webview_style.titleNView = false;

	}
	return webview_style;
}

function copyShareUrl(copy_content) {
	mui.plusReady(function() {
		//复制链接到剪切板

		//判断是安卓还是ios
		if(mui.os.ios) {
			//ios
			var UIPasteboard = plus.ios.importClass("UIPasteboard");  
			var generalPasteboard = UIPasteboard.generalPasteboard();   //设置/获取文本内容:
			  
			generalPasteboard.plusCallMethod({    
				setValue: copy_content,
				    forPasteboardType: "public.utf8-plain-text"  
			});  
			generalPasteboard.plusCallMethod({    
				valueForPasteboardType: "public.utf8-plain-text"  
			});
		} else {
			//安卓
			var context = plus.android.importClass("android.content.Context"); 
			var main = plus.android.runtimeMainActivity(); 
			var clip = main.getSystemService(context.CLIPBOARD_SERVICE); 
			plus.android.invoke(clip, "setText", copy_content);
		}
	});
	mui.toast('复制成功')
}

/**
 * 获得sd卡根目录
 */
function getSDRoot() {
	var url = '';
	if(!mui.os.ios) {

		// 导入android.os.Environment类对象
		var environment = plus.android.importClass("android.os.Environment");
		// 判断SD卡是否插入
		if(environment.getExternalStorageState() !== environment.MEDIA_MOUNTED) {
			plus.nativeUI.toast('没有找到SD卡');
			return;
		}
		console.log("获取 Android 数据目录:" + environment.getDataDirectory());
		console.log("获取 Android 下载/缓存内容目录:" + environment.getDownloadCacheDirectory());
		console.log("获取外部存储目录即 SDCard:" + environment.getExternalStorageDirectory());
		console.log("获取外部存储设备的当前状态:" + environment.getExternalStorageState());
		url = environment.getExternalStorageDirectory();
	} else {
		url = '_doc/gallery/';
	}
	return url;
}

function NoClickDelay(el) {
	this.element = typeof el == 'object' ? el : document.getElementById(el);
	if(window.Touch) this.element.addEventListener('touchstart', this, false);
}
NoClickDelay.prototype = {
	handleEvent: function(e) {
		switch(e.type) {
			case 'touchstart':
				this.onTouchStart(e);
				break;
			case 'touchmove':
				this.onTouchMove(e);
				break;
			case 'touchend':
				this.onTouchEnd(e);
				break;
		}
	},
	onTouchStart: function(e) {
		e.preventDefault();
		this.moved = false;
		this.theTarget = document.elementFromPoint(e.targetTouches[0].clientX, e.targetTouches[0].clientY);
		if(this.theTarget.nodeType == 3) this.theTarget = theTarget.parentNode;
		this.theTarget.className += ' pressed';
		this.element.addEventListener('touchmove', this, false);
		this.element.addEventListener('touchend', this, false);
	},
	onTouchMove: function(e) {
		this.moved = true;
		this.theTarget.className = this.theTarget.className.replace(/ ?pressed/gi, '');
	},
	onTouchEnd: function(e) {
		this.element.removeEventListener('touchmove', this, false);
		this.element.removeEventListener('touchend', this, false);
		if(!this.moved && this.theTarget) {
			this.theTarget.className = this.theTarget.className.replace(/ ?pressed/gi, '');
			var theEvent = document.createEvent('MouseEvents');
			theEvent.initEvent('click', true, true);
			this.theTarget.dispatchEvent(theEvent);
		}
		this.theTarget = undefined;
	}
};

function formatBankCarNumber(BankNo) {
	if(BankNo.value == "") return;
	var account = new String(BankNo.value);
	account = account.substring(0, 30); /*帐号的总数, 包括空格在内 */
	if(account.match(".[0-9]{4}-[0-9]{4}-[0-9]{4}-[0-9]{7}") == null) {
		/* 对照格式 */
		if(account.match(".[0-9]{4}-[0-9]{4}-[0-9]{4}-[0-9]{7}|" + ".[0-9]{4}-[0-9]{4}-[0-9]{4}-[0-9]{7}|" +
				".[0-9]{4}-[0-9]{4}-[0-9]{4}-[0-9]{7}|" + ".[0-9]{4}-[0-9]{4}-[0-9]{4}-[0-9]{7}") == null) {
			var accountNumeric = "";
			var accountChar = "";
			var i = undefined;
			for(i = 0; i < account.length; i++) {
				accountChar = account.substr(i, 1);
				if(!isNaN(accountChar.length) && (accountChar != " ")) accountNumeric = accountNumeric + accountChar;
			}
			account = "";
			for(i = 0; i < accountNumeric.length; i++) { /* 可将以下空格改为-,效果也不错 */
				if(i == 4) account = account + " "; /* 帐号第四位数后加空格 */
				if(i == 8) account = account + " "; /* 帐号第八位数后加空格 */
				if(i == 12) account = account + " "; /* 帐号第十二位后数后加空格 */
				if(i == 16) account = account + " "; /* 帐号第十六位后数后加空格 */
				account = account + accountNumeric.substr(i, 1)
			}
		}
	} else {
		account = " " + account.substring(1, 5) + " " + account.substring(6, 10) + " " + account.substring(14, 18) + "-" + account.substring(18, 25);
	}
	if(account != BankNo.value) {
		BankNo.value = account;
	}
}

function getNow(s) {
	return s < 10 ? '0' + s : s;
}

var HtmlUtil = {
	/*1.用浏览器内部转换器实现html转码*/
	htmlEncode: function(html) {
		//1.首先动态创建一个容器标签元素，如DIV
		var temp = document.createElement("div");
		//2.然后将要转换的字符串设置为这个元素的innerText(ie支持)或者textContent(火狐，google支持)
		(temp.textContent != undefined) ? (temp.textContent = html) : (temp.innerText = html);
		//3.最后返回这个元素的innerHTML，即得到经过HTML编码转换的字符串了
		var output = temp.innerHTML;
		temp = null;
		return output;
	},
	/*2.用浏览器内部转换器实现html解码*/
	htmlDecode: function(text) {
		//1.首先动态创建一个容器标签元素，如DIV
		var temp = document.createElement("div");
		//2.然后将要转换的字符串设置为这个元素的innerHTML(ie，火狐，google都支持)
		temp.innerHTML = text;
		//3.最后返回这个元素的innerText(ie支持)或者textContent(火狐，google支持)，即得到经过HTML解码的字符串了。
		var output = temp.innerText || temp.textContent;
		temp = null;
		return output;
	},
	/*3.用正则表达式实现html转码*/
	htmlEncodeByRegExp: function(str) {
		var s = "";
		if(str.length == 0) return "";
		s = str.replace(/&/g, "&amp;");
		s = s.replace(/</g, "&lt;");
		s = s.replace(/>/g, "&gt;");
		s = s.replace(/ /g, "&nbsp;");
		s = s.replace(/\'/g, "&#39;");
		s = s.replace(/\"/g, "&quot;");
		return s;
	},
	/*4.用正则表达式实现html解码*/
	htmlDecodeByRegExp: function(str) {
		var s = "";
		if(str.length == 0) return "";
		s = str.replace(/&amp;/g, "&");
		s = s.replace(/&lt;/g, "<");
		s = s.replace(/&gt;/g, ">");
		s = s.replace(/&nbsp;/g, " ");
		s = s.replace(/&#39;/g, "\'");
		s = s.replace(/&quot;/g, "\"");
		return s;
	}
};

function distanceByLnglat(lng1, lat1, lng2, lat2) {
	var f = getRad((lat1 + lat2) / 2);
	var g = getRad((lat1 - lat2) / 2);
	var l = getRad((lng1 - lng2) / 2);

	var sg = Math.sin(g);
	var sl = Math.sin(l);
	var sf = Math.sin(f);

	var s, c, w, r, d, h1, h2;
	var a = EARTH_RADIUS;
	var fl = 1 / 298.257;

	sg = sg * sg;
	sl = sl * sl;
	sf = sf * sf;

	s = sg * (1 - sl) + (1 - sf) * sl;
	c = (1 - sg) * (1 - sl) + sf * sl;

	w = Math.atan(Math.sqrt(s / c));
	r = Math.sqrt(s * c) / w;
	d = 2 * w * a;
	h1 = (3 * r - 1) / 2 / c;
	h2 = (3 * r + 1) / 2 / s;

	return d * (1 + fl * (h1 * sf * (1 - sg) - h2 * (1 - sf) * sg));
}
var EARTH_RADIUS = 6378137.0;
var PI = Math.PI;

function getRad(d) {
	return d * PI / 180.0;
}

function init_exit_view() {
	$('body .exit').remove();
	$('body .alert_mask').remove();
	var item = '<div class="mod_alert mod_alert_offline_pay   fixed exit"  style="display: none;"><i class="icon"></i>'
	item += '<p class="txt"></p>';
	item += '<ul class="tips_list">';
	item += '</ul>';

	item += '<div style="width: 100%;" class="">';
	item += '<p class="btns">';
	item += '	<a href="javascript:;" id="ui_btn_cancel" class="btn cancel">取消</a>';
	item += '	<a href="javascript:;" id="ui_btn_confirm" class="btn btn_1 quit">确定</a>';
	item += '	</p>';
	item += '<div class=""></div>';
	item += '</div>';
	item += '</div>';

	item += '<div class="mod_alert_mask   alert_mask" style="display: none;"></div>';
	$('body').append(item)

}

function exit_btn(obj, is_clear) {
	var webview = plus.webview.getWebviewById('index.html');
	webview.evalJS('show_exit();')
	$('.exit .btn').css("background", 'transparent');
	$('.mod_alert_mask').css('display', 'block');
	$('.exit ').css('display', 'block');
	if(is_clear == 1) {
		$('.mod_alert .txt').html('切换账号')

	} else {

		$('.mod_alert .txt').html('残忍退出')
	}
	$('.quit').click(function(e) {
		if(is_clear == 1) {
			$('.mod_alert .txt').html('切换账号')
			localStorage.setItem('user_name', '');
			localStorage.setItem('user_id', '');
			localStorage.setItem('user', '');
			plus.runtime.restart();
			return;

		}
		if(mui.os.ios) {
			localStorage.clear();
			plus.runtime.restart();
		} else {
			plus.runtime.quit();
		}
	});
	$('.cancel').click(function(e) {
		cancel_exit();
		var webview = plus.webview.getWebviewById('index.html');
		console.log(webview)
		webview.evalJS('cancel_exit();')
	});
}

function show_exit() {
	$('.exit .btn').css("background", 'transparent');
	$('.mod_alert_mask').css('display', 'block');
	$('.exit ').css('display', 'block');
}

function cancel_exit() {
	$('.exit .btn').css("background", 'transparent');
	$('.mod_alert_mask').css('display', 'none');
	$('.exit ').css('display', 'none');
}

//扩展mui.showLoading  
(function($, window) {
	//显示加载框  
	$.showLoading = function(message, type) {
		if($.os.plus && type !== 'div') {
			$.plusReady(function() {
				plus.nativeUI.showWaiting(message);
			});
		} else {
			var html = '';
			html += '<i class="mui-spinner mui-spinner-white"></i>';
			html += '<p class="text">' + (message || "数据加载中") + '</p>';

			//遮罩层  
			var mask = document.getElementsByClassName("mui-show-loading-mask");
			if(mask.length == 0) {
				mask = document.createElement('div');
				mask.classList.add("mui-show-loading-mask");
				document.body.appendChild(mask);
				mask.addEventListener("touchmove", function(e) {
					e.stopPropagation();
					e.preventDefault();
				});
			} else {
				mask[0].classList.remove("mui-show-loading-mask-hidden");
			}
			//加载框  
			var toast = document.getElementsByClassName("mui-show-loading");
			if(toast.length == 0) {
				toast = document.createElement('div');
				toast.classList.add("mui-show-loading");
				toast.classList.add('loading-visible');
				document.body.appendChild(toast);
				toast.innerHTML = html;
				toast.addEventListener("touchmove", function(e) {
					e.stopPropagation();
					e.preventDefault();
				});
			} else {
				toast[0].innerHTML = html;
				toast[0].classList.add("loading-visible");
			}
		}
	};

	//隐藏加载框  
	$.hideLoading = function(callback) {
		if($.os.plus) {
			$.plusReady(function() {
				plus.nativeUI.closeWaiting();
			});
		}
		var mask = document.getElementsByClassName("mui-show-loading-mask");
		var toast = document.getElementsByClassName("mui-show-loading");
		if(mask.length > 0) {
			mask[0].classList.add("mui-show-loading-mask-hidden");
		}
		if(toast.length > 0) {
			toast[0].classList.remove("loading-visible");
			callback && callback();
		}
	}
})(mui, window);