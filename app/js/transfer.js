var ServerIP = "";
var IP = localStorage.getItem('IP');
var PreUrl = localStorage.getItem('PreUrl');
var IndexUrl = localStorage.getItem('IndexUrl');
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
var get_tixian_list_val = IndexUrl + "Currency/currencyTixian";
var moneyflows_list_val = PreUrl + "Transfer/adminTransferMoney2";

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
					console.log(xhr);
				}
			});
		}
	}, 'div');

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

function get_tixian_list() {
	$.ajax({
		url: get_tixian_list_val,
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

						item += '	<div class="weui-form-preview__item">';
						item += '		<label class="weui-form-preview__label">用户帐号</label>';
						item += '		<label class="weui-form-preview__bd">' + (content.user_id) + '</label>';
						item += '	</div>';

						item += '	<div class="weui-form-preview__item">';
						item += '		<label class="weui-form-preview__label">姓名</label>';
						item += '		<label class="weui-form-preview__bd">' + (content.user_name) + '</label>';
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