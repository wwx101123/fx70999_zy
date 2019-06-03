function openShare() {
	shareWebview();
}
mui.plusReady(function() {
	ws = plus.webview.currentWebview();
	//关闭splash页面；
	plus.navigator.closeSplashscreen();
})
var sharew;
var ws = null;
/**
 *分享窗口
 */
function shareWebview() {

	ws = plus.webview.currentWebview();
	if(sharew) { // 避免快速多次点击创建多个窗口
		return;
	}
	var top = plus.display.resolutionHeight - 134;
	var href = "share.html";
	sharew = plus.webview.create(href, "share.html", {
		width: '100%',
		height: '134',
		top: top,
		scrollIndicator: 'none',
		scalable: false,
		popGesture: 'none'
	}, {
		shareInfo: {
			"href": $('#sharehref').val(),
			"title": $('#sharehrefTitle').val(),
			"content": $('#sharehrefDes').val(),
			"pageSourceId": ws.id
		}
	});
	sharew.addEventListener("loaded", function() {
		sharew.show('slide-in-bottom', 300);
	}, false);
	// 显示遮罩层  
	ws.setStyle({
		mask: "rgba(0,0,0,0.5)"
	});
	// 点击关闭遮罩层
	ws.addEventListener("maskClick", closeMask, false);
}

function closeMask() {
	ws.setStyle({
		mask: "none"
	});
	//避免出现特殊情况，确保分享页面在初始化时关闭 
	if(!sharew) {
		sharew = plus.webview.getWebviewById("share.html");
	}
	if(sharew) {
		sharew.close();
		sharew = null;
	}
}

var shares = null;
var Intent = null,
	File = null,
	Uri = null,
	main = null;
// H5 plus事件处理
function plusReady() {
	updateSerivces();
	if(plus.os.name == "Android") {
		main = plus.android.runtimeMainActivity();
		Intent = plus.android.importClass("android.content.Intent");
		File = plus.android.importClass("java.io.File");
		Uri = plus.android.importClass("android.net.Uri");
		main = plus.android.runtimeMainActivity();
	}
	var shareInfo = plus.webview.currentWebview().shareInfo;
	sharehref.value = shareInfo.href;
	console.log(sharehref.value)
	sharehrefTitle.value = shareInfo.title;
	sharehrefDes.value = shareInfo.content;
	pageSourceId = shareInfo.pageSourceId;
	console.log("pageSource:" + pageSourceId);
}
if(window.plus) {
	plusReady();
} else {
	document.addEventListener("plusready", plusReady, false);
}

/**
 * 
 * 更新分享服务
 */
function updateSerivces() {
	plus.share.getServices(function(s) {
		shares = {};
		for(var i in s) {
			var t = s[i];
			shares[t.id] = t;
		}
	}, function(e) {
		outSet("获取分享服务列表失败：" + e.message);
	});
}

/**
 * 分享操作
 * @param {JSON} sb 分享操作对象s.s为分享通道对象(plus.share.ShareService)
 * @param {Boolean} bh 是否分享链接
 */
function shareAction(sb, bh) {
	var user = localStorage.getItem('user');
	var weixin_type = localStorage.getItem('weixin_type');
	user = JSON.parse(user);
	var pictures = '_doc/recommend_images.jpg';
	if(weixin_type == 'web') {
		pictures = user.weixinlogo;
//		if(pictures == '') {
			pictures = '_www/logo.png';
//		}
	}
	if(!sb || !sb.s) {
		console.log("无效的分享服务！");
		return;
	}
	console.log(plus.os.name)
	if(plus.os.name !== "Android") {
		//		plus.nativeUI.alert(plus.os.name + "此平台暂不支持系统分享功能!");
		//		return;
	}

	var msg = {
		content: sharehrefDes.value,
		extra: {
			scene: sb.x
		}
	};
	console.log(bh)
	if(bh) {
		msg.href = sharehref.value;
		msg.type =weixin_type;
		if(sharehrefTitle && sharehrefTitle.value != "") {
			msg.title = sharehrefTitle.value;
		}
		if(sharehrefDes && sharehrefDes.value != "") {
			msg.content = sharehrefDes.value;
		}
		msg.thumbs = [pictures];
		msg.pictures = [pictures];
	} else {
		if(pic && pic.realUrl) {
			msg.pictures = [pic.realUrl];
		}
	}
	// 发送分享
	if(sb.s.authenticated) {
		console.log("---已授权1---");
		shareMessage(msg, sb.s);
	} else {
		console.log("---未授权---");
		sb.s.authorize(function() {
			shareMessage(msg, sb.s);
		}, function(e) {
			console.log("认证授权失败：" + e.code + " - " + e.message);

		});
	}
}
/**
 * 发送分享消息
 * @param {JSON} msg
 * @param {plus.share.ShareService} s
 */
function shareMessage(msg, s) {

	console.log(JSON.stringify(msg));
	s.send(msg, function() {
		var item_id = localStorage.getItem('item_id')
		console.log('开始分享')
		updata_share(item_id, s)

	}, function(e) {
		var item_id = localStorage.getItem('item_id')
		mui.toast("分享到" + s.description + "失败");

	});
}
// 分析链接
function shareHref(index) {
	console.log(index)
	localStorage.setItem('share_func', 0);
	var shareBts = [];
	// 更新分享列表
	var ss = shares['weixin'];
	ss && ss.nativeClient && (shareBts.push({
			title: '微信朋友圈',
			s: ss,
			x: 'WXSceneTimeline'
		}),
		shareBts.push({
			title: '微信好友',
			s: ss,
			x: 'WXSceneSession'
		}));
	ss = shares['qq'];
	ss && ss.nativeClient && shareBts.push({
		title: 'QQ',
		s: ss
	});
	var item_id = localStorage.getItem('item_id')

	shareAction(shareBts[index], true);
}
//      mui.back = function() {
//			var sourcePage = plus.webview.getWebviewById(pageSourceId);
//			if (sourcePage) {
//				sourcePage.evalJS("closeMask()");
//			}
//		}

function closeShare() {
	localStorage.setItem('share_func', 0);
}

var ServerIP = "";
var IP = localStorage.getItem('IP');
var PreUrl = localStorage.getItem('PreUrl');