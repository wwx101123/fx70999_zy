var ServerIP = "";
var IP = localStorage.getItem('IP');
var PreUrl = localStorage.getItem('PreUrl');
var IndexUrl = localStorage.getItem('IndexUrl');
/**
 * 获取未开通会员
 */
var menber_val = IndexUrl + "Agent/menber";
var menberok_val = IndexUrl + "Agent/menberok";
var menberAC_val = PreUrl + "Agent/menberAC";
var myTeam_val = IndexUrl + "User/myTeam";

function get_menber() {
	$.ajax({
		url: menber_val,
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
				var info = data.data;
				if(info == null) {
					$('.nodata').css('display', 'block');

				}
				if(info != null) {
					$.each(info, function(index, content) {
						var item = '<li>';
						item += '	<div class="detail-hd">';
						item += '		<span class="fr" style="color: red;">【' + content.treeplace_str + '】</span><span class="fr" style="color:#999"> 接点人:' + content.father_name + '  推荐人:' + content.re_name + '</span>';
						item += '		<span>' + content.user_id + '</span>';
						item += '	</div>';
						item += '	<div class="detail-bd">';

						item += '	</div>';
						item += '	<div class="detail-ft">';
						item += '		<p class="fr"></p>';
						item += '		<p>	注册时间:' + content.rdt_str + ' &nbsp    消费级别:' + content.level_str + '     </p>';
						item += '		<p style="color:red">	   消费类型:【' + content.register_type_str + '】</p>';

						item += '		<div class="ft-btn">';
						item += '			<div class="btn-box">';
						item += '			<a href="javascript:void(0)"   onclick="menberOpenUse(' + content.id + ');" class="order_info"   id="' + content.id + '">确认</a>';
						item += '			<a href="javascript:void(0)"   onclick="menberDelUse(' + content.id + ');"  class="order_info"   id="' + content.id + '">删除</a>';

						item += '		</div>';
						item += '		</div>';
						item += '	</div>';
						item += '	</li>';

						$('.detail-list ul').append(item);

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

function get_menberok() {
	$.ajax({
		url: menberok_val,
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
				var info = data.data;
				if(info == null) {
					$('.nodata').css('display', 'block');

				}
				if(info != null) {
					$.each(info, function(index, content) {
						var item = '<li>';
						item += '	<div class="detail-hd">';
						item += '		<span class="fr" style="color: red;">【' + content.treeplace_str + '】</span><span class="fr" style="color:#999"> 接点人:' + content.father_name + '  推荐人:' + content.re_name + '</span>';
						item += '		<span>' + content.user_id + '</span>';
						item += '	</div>';
						item += '	<div class="detail-bd">';

						item += '	</div>';
						item += '	<div class="detail-ft">';
						item += '		<p class="fr"></p>';
						item += '		<p>	注册时间:' + content.rdt_str + ' &nbsp    开通时间:' + content.pdt_str + '</p>';

						item += '		<div class="ft-btn">';
						item += '			<div class="btn-box">';
						item += '			<a href="javascript:void(0)"   class="order_info"   id="' + content.id + '">已激活</a>';
						item += '			<a href="javascript:void(0)"    class="order_info"   id="' + content.id + '">投资金额:' + content.cpzj + '</a>';

						item += '		</div>';
						item += '		</div>';
						item += '	</div>';
						item += '	</li>';

						$('.detail-list ul').append(item);

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

function menberOpenUse(user_id) {
	weui.confirm('开通此会员，您确定吗？', function() {
		$.ajax({
			url: menberAC_val,
			data: {
				is_mobile: 1,
				id: user_id,
				user_id: localStorage.getItem('user_id'),
				action: 'confirm'
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

function menberDelUse(user_id) {
	weui.confirm('删除此会员，您确定吗？', function() {
		$.ajax({
			url: menberAC_val,
			data: {
				is_mobile: 1,
				id: user_id,
				user_id: localStorage.getItem('user_id'),
				action: 'del'
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

function get_myteam() {
	$.ajax({
		url: myTeam_val,
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
				$('.recommend_count').html(data.recommend_count);
				$('.tree_count').html(data.tree_count);

				if(info != null) {
					$.each(info, function(index, content) {

						var item = '<div class="weui-form-preview" style="margin-top: 10px;">';
						item += '<div class="weui-form-preview__hd">';
						//						item += '	<div class="weui-form-preview__item">';
						//						item += '		<label class="weui-form-preview__label">序号</label>';
						//						item += '		<label class="weui-form-preview__bd">' + (content.key + 1) + '</label>';
						//						item += '	</div>';

						item += '	<div class="weui-form-preview__item">';
						item += '		<label class="weui-form-preview__label">用户账号</label>';
						item += '		<label class="weui-form-preview__bd">' + (content.user_id) + '</label>';
						item += '	</div>';

						item += '	<div class="weui-form-preview__item">';
						item += '		<label class="weui-form-preview__label">会员姓名</label>';
						item += '		<label class="weui-form-preview__bd">' + (content.user_name) + '</label>';
						item += '	</div>';
						item += '	<div class="weui-form-preview__item">';
						item += '		<label class="weui-form-preview__label">注册等级</label>';
						item += '		<label class="weui-form-preview__bd">' + (content.level_str) + '</label>';
						item += '	</div>';
//						item += '	<div class="weui-form-preview__item">';
//						item += '		<label class="weui-form-preview__label">联系电话</label>';
//						item += '		<label class="weui-form-preview__bd">' + (content.user_tel) + '</label>';
//						item += '	</div>';
						item += '	<div class="weui-form-preview__item">';
						item += '		<label class="weui-form-preview__label">状态</label>';
						item += '		<label class="weui-form-preview__bd">' + (content.status_str) + '</label>';
						item += '	</div>';

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