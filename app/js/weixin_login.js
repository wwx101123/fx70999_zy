var auths;
var headImage = $('#assets_img');

var ServerIP = "";
var IP = localStorage.getItem('IP');
var PreUrl = localStorage.getItem('PreUrl');
var authEdit_val = PreUrl + "User/weixinauthEdit";

mui.plusReady(function() {
	plus.oauth.getServices(function(services) {
		auths = services;
	}, function(e) {
		alert("获取登录服务列表失败：" + e.message + " - " + e.code);
	});
})
// 登录操作
function authLogin(type) {
	if(type == 'qq') {

		mui.toast("QQ登录暂未开放！");
		return;
	}
	if(type == 'weibo') {

		mui.toast("微博登录暂未开放！");
		return;
	}
if(type == 'weixin') {

//		mui.toast("微信登录暂未开放！");
//		return;
	}
	var s;
	for(var i = 0; i < auths.length; i++) {
		if(auths[i].id == type) {
			s = auths[i];
			break;
		}
	}
	if(!s.authResult) {
		s.login(function(e) {
			//			mui.toast("登录成功！");
			authUserInfo(type);
		}, function(e) {
			mui.toast("登录失败！" + e.getMessage());
		});
	} else {
		mui.toast("已经登录认证！");
		authLogout()
	}
}
//注销
function authLogout() {
	for(var i in auths) {
		var s = auths[i];
		if(s.authResult) {
			s.logout(function(e) {
				console.log("注销登录认证成功！");
			}, function(e) {
				console.log("注销登录认证失败！");
			});
		}
	}
}
// 微信登录认证信息
function authUserInfo(type) {
	var s;
	for(var i = 0; i < auths.length; i++) {
		if(auths[i].id == type) {
			s = auths[i];
			break;
		}
	}
	if(!s.authResult) {
		mui.toast("未授权登录！");
	} else {
		s.getUserInfo(function(e) {
			var josnStr = JSON.stringify(s.userInfo);
			var jsonObj = s.userInfo;
			console.log(josnStr);

			//			showData(type, jsonObj);
			authEdit(s.userInfo, jsonObj.openid, jsonObj.unionid, jsonObj.headimgurl, jsonObj.nickname);
			authLogout();
		}, function(e) {
			alert("获取用户信息失败：" + e.message + " - " + e.code);
		});
	}
}
// 显示用户头像信息
function showData(type, data) {
	switch(type) {
		case 'weixin':

			console.log("头像：" + data.headimgurl);
			$('#assets_img').attr('src', data.headimgurl);
			break;
		case 'qq':
			headImage.src = data.figureurl_qq_2;
			break;
		case 'sinaweibo':
			headImage.src = data.avatar_large;
			break;
		default:
			break;
	}
}

function authEdit(jsonStr, openid, unionid, headimgurl, nickname) {
console.log(openid)
	$.ajax({
		url: authEdit_val,
		data: {
			is_mobile: 1,
			openid: openid,
			unionid: unionid,
			headimgurl: headimgurl,
			nickname: nickname,
			username: $('#register_phone').val()
		},
		dataType: 'json', //服务器返回json格式数据
		type: 'POST', //HTTP请求类型
		timeout: 10000, //超时时间设置为10秒；

		success: function(data) {

			mui.toast(data.info)
			if(data.is_set_sex == 1) {
				localStorage.setItem('headimgurl', headimgurl);
				localStorage.setItem('nickname', nickname);
				localStorage.setItem('unionid', unionid);
				localStorage.setItem('openid', openid);
				openWindowWithTitle('编辑资料', 'edit_sex.html')
			} else {
			 
			}
		},
		error: function(xhr, type, errorThrown) {
			//异常处理；
			console.log(type);
		}
	});

}
 