function submit_register() {
	var register_type = 0;
	var register_type1 = $('.check:eq(0)').attr('check');
	var register_type2 = $('.check:eq(1)').attr('check');
	if (register_type1 == 'true') {
		register_type = $('.check:eq(0)').attr('register_type');
	}
	if (register_type2 == 'true') {
		register_type = $('.check:eq(1)').attr('register_type');

	}

	$('#register_type').val(register_type)

	$('.sa-error-container').css('overflow', 'initial')
	var recommend = $("input[name=reference_id]").val();
	if (recommend == '') {
		var d = dialog({
			content : '推荐人不能为空！'
		}).show();
		setTimeout(function() {
			d.close().remove();
		}, 2000);
		return;

	}

	var realname = $("input[name=name]").val();
	if (realname == '') {

		var d = dialog({
			content : '姓名不能为空！'
		}).show();
		setTimeout(function() {
			d.close().remove();
		}, 2000);
		return;

	}

	var phone = $("input[name=phone]").val();
	if (phone == '') {
		var d = dialog({
			content : '手机号不能为空！'
		}).show();
		setTimeout(function() {
			d.close().remove();
		}, 2000);
		return;

	}

	var province = $("select[name=province]").val();
	if (province == '') {
		
		var d = dialog({
			content : '省份不能为空！'
		}).show();

		setTimeout(function() {
			d.close().remove();
		}, 2000);
		return;

	}
	 // $('#registSubmitBtn').unbind("click").removeAttr("onclick");

	$('.sa-error-container').css('overflow', 'hidden')

	$
			.ajax({
				type : "POST",
				url : $("#register_form").attr("action"),
				data : $('#register_form').serialize(),
				success : function(data) {
					$('.sa-error-container').css('overflow', 'initial')
					if (data.status == 1) {
						
						$('.sa-error-container').css('overflow', 'hidden')
						$('.sweet-overlay').css('display', 'none');
						$('#register').css('display', 'none');

						if (data.app_url != "" && data.app_url != undefined) {
							var sUserAgent = navigator.userAgent.toLowerCase();
							var bIsIpad = sUserAgent.match(/ipad/i) == "ipad";
							var bIsIphoneOs = sUserAgent.match(/iphone os/i) == "iphone os";
							var bIsMidp = sUserAgent.match(/midp/i) == "midp";
							var bIsUc7 = sUserAgent.match(/rv:1.2.3.4/i) == "rv:1.2.3.4";
							var bIsUc = sUserAgent.match(/ucweb/i) == "ucweb";
							var bIsAndroid = sUserAgent.match(/android/i) == "android";
							var bIsCE = sUserAgent.match(/windows ce/i) == "windows ce";
							var bIsWM = sUserAgent.match(/windows mobile/i) == "windows mobile";
							if (bIsIpad || bIsIphoneOs) {

								mui.toast(data.info)
								setTimeout(function() {
									location.href = data.ios_url
								}, 2000);

								return;
							}
							if (bIsAndroid) {
								mui.toast(data.info)
								setTimeout(function() {
									location.href = data.app_url
								}, 2000);

								return;
							}
						}

					} else {
						$('.sa-error-container .icon').css('display',
								'inline-block');
						$('.sa-error-container p').html(data.info)
						mui.toast(data.info)
						 
						return;
					}
				}
			});

}

// 提示信息
var timetishi = null;
var tishinum = 0;
function closetishi() {
	if (tishinum >= 100) {
		clearInterval(timetishi);
		$('#tishi').css("display", "none");
		tishinum = 0;
	} else {
		tishinum++;
		var f = document.getElementById("tishi");
		if (tishinum > 50) {
			$('#tishi').css("opacity", ((150 - tishinum)) / 100);
		} else {
			$('#tishi').css("opacity", 1);
		}
	}
}
// 打开提示
function open_tishi(msg) {
	clearInterval(timetishi);
	$('#tishi').css("display", "none");
	tishinum = 0;

	document.getElementById("tishi").style.display = "block";
	$("#tishi_msg").html(msg);
	timetishi = setInterval(closetishi, 20);
}

// 获取短信验证码倒计时
var djsnum = 0;
var time_reg = null;
function djs_reg() {
	djsnum = 60;
	if (time_reg != null) {
		clearInterval(time_reg);
	}
	document.getElementById("reg_djs").innerHTML = "重发:" + djsnum + "秒";
	time_reg = setInterval(begindjs_reg, 1000);
}
function begindjs_reg() {
	if (djsnum <= 0) {
		djsnum = 0;
		clearInterval(time_reg);
		document.getElementById("reggetcode").style.display = "block";
		document.getElementById("reg_djs").style.display = "none";
		document.getElementById("reg_djs").innerHTML = "";
		$("#reggetcode").removeAttr("disabled");
	} else {
		djsnum--;
		document.getElementById("reg_djs").innerHTML = "重发:" + djsnum + "秒";
	}
}

var time_mmzh = null;
function djs_mmzh() {
	djsnum = 60;
	if (time_mmzh != null) {
		clearInterval(time_mmzh);
	}
	document.getElementById("mmzh_djs").innerHTML = "重发:" + djsnum + "秒";
	time_mmzh = setInterval(begindjs_mmzh, 1000);
}
function begindjs_mmzh() {
	if (djsnum <= 0) {
		djsnum = 0;
		clearInterval(time_mmzh);
		document.getElementById("mmzhgetcode").style.display = "block";
		document.getElementById("mmzh_djs").style.display = "none";
		document.getElementById("mmzh_djs").innerHTML = "";
		$("#mmzhgetcode").removeAttr("disabled");
	} else {
		djsnum--;
		document.getElementById("mmzh_djs").innerHTML = "重发:" + djsnum + "秒";
	}
}

var time_addphone = null;
function djs_addphone() {
	djsnum = 60;
	if (time_addphone != null) {
		clearInterval(time_addphone);
	}
	document.getElementById("addphone_djs").innerHTML = "重发:" + djsnum + "秒";
	time_addphone = setInterval(begindjs_addphone, 1000);
}
function begindjs_addphone() {
	if (djsnum <= 0) {
		djsnum = 0;
		clearInterval(time_addphone);
		document.getElementById("addphonegetcode").style.display = "block";
		document.getElementById("addphone_djs").style.display = "none";
		document.getElementById("addphone_djs").innerHTML = "";
		$("#addphonegetcode").removeAttr("disabled");
	} else {
		djsnum--;
		document.getElementById("addphone_djs").innerHTML = "重发:" + djsnum
				+ "秒";
	}
}

// 注册
function register(url) {
	if ($("#regbutton").attr("disabled")) {
		return;
	}
	$("#regbutton").attr("disabled", true);

	if ($("[id='loginpassword_reg']").val() != $("[id='loginpassword1']").val()) {
		open_tishi("两次输入的登陆密码不一致");
		$("#regbutton").removeAttr("disabled");
		return;
	}
	if ($("[id='paypassword']").val() != $("[id='paypassword1']").val()) {
		open_tishi("两次输入的交易密码不一致");
		$("#regbutton").removeAttr("disabled");
		return;
	}

	if ($("[id='autoRandom']").text() != $("[id='text_YZM']").val()) {
		open_tishi("验证码不正确");
		$("#regbutton").removeAttr("disabled");
		return;
	}

	var datejson = {
		nickname : $("[id='nickname_reg']").val(),
		name : $("[id='nickname_reg']").val(),
		password : $("[id='loginpassword_reg']").val(),
		loginpassword1 : $("[id='loginpassword1']").val(),
		password2 : $("[id='paypassword']").val(),
		paypassword1 : $("[id='paypassword1']").val(),
		reference_member_id : $("[id='tjrnickname']").val(),
		isappstore : $("[id='isappstore']").val(),
		yzcode : $("[id='text_YZM']").val(),
		verification : $("[id='text_vcode']").val(),
		phone : $("[id='phone_reg']").val(),
		is_mobile : 1
	};

	$.ajax({
		url : url,
		data : datejson,
		dataType : 'json',
		type : "POST",
		success : function(r) {
			if (r.status == 1) {
				$("[id='nickname_reg']").val("");
				$("[id='loginpassword_reg']").val("");
				$("[id='loginpassword1']").val("");
				$("[id='paypassword']").val("");
				$("[id='paypassword1']").val("");
				$("[id='tjrnickname']").val("");
				$("[id='text_YZM']").val("");
				$("[id='text_vcode']").val("");
				$("[id='phone_reg']").val("");
				document.getElementById("div_register").style.display = "none";
				onlogin();
			}

			$("[id='text_YZM']").val("");
			open_tishi(r.info);

			hyz();
		}
	})
}

// 登录
function login() {

	if ($("#login").attr("disabled")) {
		return;
	}
	$("#login").attr("disabled", true);

	if ($("[id='nickname']").val() == "") {
		open_tishi("账号不可为空");
		$("#login").removeAttr("disabled");
	} else if ($("[id='loginpassword']").val() == "") {
		open_tishi("密码不可为空");
		$("#login").removeAttr("disabled");
	} else if ($("[id='yzm']").val() == ""
			|| $("[id='autoRandom_login']").text() != $("[id='yzm']").val()) {
		open_tishi("验证码不正确");
		$("#login").removeAttr("disabled");
	} else {
		$
				.ajax({
					url : '${pageContext.request.contextPath}/gamePlayer_login_web.action',
					data : {
						nickname : $("[id='nickname']").val(),
						loginpassword : $("[id='loginpassword']").val(),
						isappstore : $("[id='isappstore']").val(),
						isappstore : $("[id='isappstore']").val(),
						yzcode : $("[id='yzm']").val()
					},
					dataType : 'json',
					type : "POST",
					success : function(r) {
						if (r.success) {
							$("[id='nickname']").val("");
							$("[id='loginpassword']").val("");
							$("[id='yzm']").val("");

							open_tishi("登录成功");

							if (r.hasphone) {
								// $("#tzmain").click();
								$("#gotomainpag").submit();
							} else {
								document.getElementById("qpzz").style.display = "block";
								document.getElementById("div_addphone").style.display = "block";
								dqye = 3;
								$("[name='data.gpid']").val(r.id);
							}
							$("#login").removeAttr("disabled");

						} else {
							open_tishi(r.msg);
							$("[id='yzm']").val("");
							$("#login").removeAttr("disabled");
						}
						hyz_login();
					}
				})
	}
}

// 密码找回
function mmzh() {
	if ($("#a_mmzh").attr("disabled")) {
		return;
	}
	$("#a_mmzh").attr("disabled", true);

	if ($("[id='mmzh_zhanghao']").val() == "") {
		open_tishi("账号不可为空");
		$("#a_mmzh").removeAttr("disabled");
	} else if ($("[id='mmzh_wt1da']").val() == "") {
		open_tishi("请正确填写手机验证码");
		$("#a_mmzh").removeAttr("disabled");
	} else if ($("[id='mmzh_newloginpassword']").val() == "") {
		open_tishi("新登录密码不可为空");
		$("#a_mmzh").removeAttr("disabled");
	} else if ($("[id='mmzh_newpaypassword']").val() == "") {
		open_tishi("新交易密码不可为空");
		$("#a_mmzh").removeAttr("disabled");
	} else {
		$
				.ajax({
					url : '${pageContext.request.contextPath}/gamePlayer_mmzh_web.action',
					data : {
						nickname : $("[id='mmzh_zhanghao']").val(),
						loginpassword : $("[id='mmzh_newloginpassword']").val(),
						paypassword : $("[id='mmzh_newpaypassword']").val(),
						verification : $("[id='mmzh_wt1da']").val()
					},
					dataType : 'json',
					type : "POST",
					success : function(r) {
						if (r.success) {
							$("[id='mmzh_newloginpassword']").val("");
							$("[id='mmzh_newpaypassword']").val("");
							$("[id='mmzh_wt1da']").val("");
							open_tishi("密码修改成功");
							onlogin();
						} else {
							open_tishi(r.msg);
						}

						$("#a_mmzh").removeAttr("disabled");
					}
				})
	}
}

// 更新手机号
function gxphone() {

	if ($("#gxphonebtn").attr("disabled")) {
		return;
	}
	$("#gxphonebtn").attr("disabled", true);

	if ($("[id='phone']").val() == "") {
		open_tishi("手机号不可为空");
		$("#gxphonebtn").removeAttr("disabled");
	} else if ($("[id='verification']").val() == "") {
		open_tishi("验证码不可为空");
		$("#gxphonebtn").removeAttr("disabled");
	} else {
		var gpid = $(':input[name="data.gpid"]').val()
		$
				.ajax({
					url : '${pageContext.request.contextPath}/gamePlayer_gxphone_web.action',
					data : {
						id : gpid,
						phone : $("[id='phone']").val(),
						verification : $("[id='verification']").val()
					},
					dataType : 'json',
					type : "POST",
					success : function(r) {
						if (r.success) {
							document.getElementById("qpzz").style.display = "none";
							document.getElementById("div_addphone").style.display = "none";
							dqye = 0;
							$("#gotomainpag").submit();
						}
						open_tishi(r.msg);
						$("#gxphonebtn").removeAttr("disabled");
					}
				})
	}
}

var dqye = 0;
// /注册页面
function onregister() {
	// closeAllWindows();
	document.getElementById("div_register").style.display = "block";
	hyz();
	dqye = 1;
}

// /登陆页面
function onlogin() {
	closeAllWindows();
	document.getElementById("div_login").style.display = "block";
	hyz_login();
	dqye = 0;
}
function close_dialog(obj) {
	$(".sweet-overlay").css('display', 'none');
	$(".sweetalert").css('display', 'none');
	$(".userinfo").css('display', 'none');
	$(".change_psw").css('display', 'none');
}
// /密码找回页面
function onmmzh() {
	// closeAllWindows();
	$(".div_register2").css('display', "block");

	hyz();
	dqye = 1;
}
function div_register() {
	// closeAllWindows();
	$(".div_register1").css('display', "block");

	hyz();
	dqye = 1;
}

// 关闭所有窗口
function closeAllWindows() {
	document.getElementById("div_login").style.display = "none";
	document.getElementById("div_register").style.display = "none";
	$(".div_register1").css('display', "none");

	document.getElementById("div_addphone").style.display = "none";
	document.getElementById("qpzz").style.display = "none";

}

function createCode(len) {
	var seed = new Array('0123456789'); // 创建需要的数据数组
	var idx, i;
	var result = ''; // 返回的结果变量
	for (i = 0; i < len; i++) // 根据指定的长度
	{
		idx = Math.floor(Math.random() * 1); // 获得随机数据的整数部分-获取一个随机整数
		result += seed[idx].substr(Math.floor(Math.random()
				* (seed[idx].length)), 1);// 根据随机数获取数据中一个值
	}
	return result; // 返回随机结果
}

function test() {
	var inputRandom = document.getElementById("inputRandom").value;
	var autoRandom = document.getElementById("autoRandom").innerHTML;
	if (inputRandom == autoRandom) {
		alert("通过验证");
	} else {
		alert("没有通过验证");
	}
}

function hyz() {
	// $.ajax({
	// url:'${pageContext.request.contextPath}/gamePlayer_getcode_web.action',
	// data:{},
	// dataType:'json',
	// type: "POST",
	// success:function(r){
	// if(r.success){
	// autoRandom.innerHTML = r.code;
	// }else{
	// open_tishi(r.msg);
	// }
	// }
	// })
}

function hyz_login() {
	$
			.ajax({
				url : '${pageContext.request.contextPath}/gamePlayer_getcode_web.action',
				data : {},
				dataType : 'json',
				type : "POST",
				success : function(r) {
					if (r.success) {
						autoRandom_login.innerHTML = r.code;
					} else {
						open_tishi(r.msg);
					}
				}
			})
}

/**
 * 获取手机验证码(注册)
 */
function getvcode_reg() {
	if ($("#reggetcode").attr("disabled")) {
		return;
	}
	$("#reggetcode").attr("disabled", true);
	var phone = document.getElementById("phone_reg").value;
	if (phone == null || phone == "") {
		open_tishi("手机号不可为空");
	}

	var datajson = {
		phone : phone
	};
	$
			.ajax({
				url : '${pageContext.request.contextPath}/gamePlayer_getphonevcode_reg_web.action',
				data : datajson,
				dataType : 'json',
				type : "POST",
				success : function(r) {
					if (r.success) {
						document.getElementById("reggetcode").style.display = "none";
						document.getElementById("reg_djs").style.display = "block";
						djs_reg();
					} else {
						$("#reggetcode").removeAttr("disabled");
					}
					open_tishi(r.msg);
				}
			})
}

/**
 * 获取手机验证码（密码找回）
 */
function getvcode_mmzh() {
	if ($("#mmzhgetcode").attr("disabled")) {
		return;
	}
	$("#mmzhgetcode").attr("disabled", true);

	var datajson = {
		nickname : $("[id='mmzh_zhanghao']").val()
	};
	$
			.ajax({
				url : '${pageContext.request.contextPath}/gamePlayer_getphonevcode_mmzh_web.action',
				data : datajson,
				dataType : 'json',
				type : "POST",
				success : function(r) {
					if (r.success) {
						document.getElementById("mmzhgetcode").style.display = "none";
						document.getElementById("mmzh_djs").style.display = "block";
						djs_mmzh();
					} else {
						$("#mmzhgetcode").removeAttr("disabled");
					}
					open_tishi(r.msg);
				}
			})
}

/**
 * 获取手机验证码_更新手机号
 */
function getvcode_addphone() {
	if ($("#addphonegetcode").attr("disabled")) {
		return;
	}
	$("#addphonegetcode").attr("disabled", true);

	var datajson = {};
	var gpid = $(':input[name="data.gpid"]').val();
	var phone = document.getElementById("phone").value;
	if (phone == null || phone == "") {
		open_tishi("手机号不可为空");
	}

	datajson = {
		phone : phone,
		id : gpid
	};

	$
			.ajax({
				url : '${pageContext.request.contextPath}/gamePlayer_getphonevcode_addphone_web.action',
				data : datajson,
				dataType : 'json',
				type : "POST",
				success : function(r) {
					if (r.success) {
						document.getElementById("addphonegetcode").style.display = "none";
						document.getElementById("addphone_djs").style.display = "block";
						djs_addphone();
					} else {
						$("#addphonegetcode").removeAttr("disabled");
					}
					open_tishi(r.msg);
				}
			})
}

if (window.orientation == 0 || window.orientation == 180) {

}

hyz_login();
