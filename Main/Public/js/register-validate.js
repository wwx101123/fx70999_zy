//=====================初始化代码======================

function setItem() {
	var host = window.location.host;
	var protocol = window.location.protocol;

	ServerIP = host;
	var registerUrl = '';
	var str = 'fx70999_spw2';

	IP = "http://" + ServerIP + "";
	PreUrl = IP + "/" + str + "/Main/adm.php/";
	IndexUrl = IP + "/" + str + "/Main/index.php/";
	UploadUrl = IP + "/" + str + "/Main/";
	AppUrl = IP + "/Main/app/android/index.html";
	registerUrl = IP + "/" + str + "/wap/main/register.html";
	 

	ServerIP = "dasadmin.super-nba.com";
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
$(function() {
	setItem()
	//检查是否注册条款
	if($("#chkAgree").length > 0) {
		//		$("#btnSubmit").prop("disabled", true);
	}
	//同意条款
	$("#chkAgree").click(function() {
		if($(this).is(":checked")) {
			$("#btnSubmit").prop("disabled", false);
		} else {
			$("#btnSubmit").prop("disabled", true);
		}
	});
	var IP = localStorage.getItem('IP');
	var PreUrl = localStorage.getItem('PreUrl');
	var IndexUrl = localStorage.getItem('IndexUrl');
	//$('#regForm').attr('url', PreUrl + "Reg/usersAdd");

	//初始化验证表单
	$("#regForm").mvalidate({
		type: 1,
		onKeyup: true,
		sendForm: false,
		firstInvalidFocus: true,
		valid: function(event, options) {
			$.ajax({
				type: "post",
				url: $("#regForm").attr("url"),
				data: $("#regForm").serialize(),
				dataType: "json",
				beforeSend: showRequest,
				success: showResponse,
				error: showError,
				timeout: 60000
			});
			return false;
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

	//表单提交前
	function showRequest(formData, jqForm, options) {
		$("#btnSubmit").val("正在提交...")
		$("#btnSubmit").prop("disabled", true);
	}
	//表单提交后
	function showResponse(data, textStatus) {
		if(data.status == 1) { //成功
$("#btnSubmit").val(data.msg)
			mui.alert(data.msg, '提示', function() { 
				
				download(data.ios_url,data. app_url) 
			});

		} else { //失败
			mui.toast(data.msg)
			$("#btnSubmit").val("重新提交");
			$("#btnSubmit").prop("disabled", false);

		}
	}
	//表单提交出错
	function showError(XMLHttpRequest, textStatus, errorThrown) {
		weui.alert('状态：' + textStatus + '；出错提示：' + errorThrown, function() {
			$("#btnSubmit").val("重新提交");
			$("#btnSubmit").prop("disabled", false);
		});
	}
});
function download(ios_url,app_url) {
	 
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

		 

		setTimeout(function() {
			location.href = ios_url
		}, 1000);

		return;
	}
	if (bIsAndroid) {
		 

		setTimeout(function() {
			location.href = app_url
		}, 1000);

		return;
	}
	
	 
} 