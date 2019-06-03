var ServerIP = "";
var IP = localStorage.getItem('IP');
var PreUrl = localStorage.getItem('PreUrl');
var IndexUrl = localStorage.getItem('IndexUrl');
var UploadUrl = localStorage.getItem('UploadUrl');

/**
 * 获取商品列表
 */
var get_parameter_val = PreUrl + "YouZi/setParameter";
/**
 * 获取用户信息
 */
var get_user_info_val = PreUrl + "User/userInfo";
/**
 * 获取用户信息
 */
var get_jinji_info_val = PreUrl + "Uplevel/MenberJinji";
var MenberJinjiConfirm_fun = PreUrl + "Uplevel/MenberJinjiConfirm";
var get_address_fun = "http://restapi.amap.com/v3/ip?&output=json&key=" + localStorage.getItem('gd_key');

function get_test() {
	$.ajax({
		url: 'http://tags.open.qq.com/interface/tag/articles.php',
		data: {
			is_mobile: 1,
			tag: 'NBA',
			ie: 'utf-8',
			oe: 'gbk',
			p: '1',
			site: 'sports',
			source: 'web',
			l: '20',
		},
		jsonpCallback: 'tagListCb',
		dataType: 'jsonp', //服务器返回json格式数据
		type: 'POST', //HTTP请求类型
		timeout: 10000, //超时时间设置为10秒；

		success: function(data) {
			var status = data.status;
			if(status == 1) {
				//				weui.alert(data.msg, function() {
				//
				//					location.reload();
				//
				//				});

			} else {
				//				weui.alert(data.msg);
			}
			////lang()

		},
		error: function(xhr, type, errorThrown) {
			//异常处理；
			console.log(type);
		}
	});
}

function get_Parameter() {
	$.ajax({
		url: get_parameter_val,
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

				$('.downurl').attr('android_url', info.android_url);

				$('#regrules').html(info.RegisterProtocol);
				$('#user_number').val(info.user_number);
				localStorage.setItem('s16', info.s16)
				localStorage.setItem('gd_key', info.gd_key)
				localStorage.setItem('shoppingCartCount', data.cart_quantity)
				$(".shoppingCartCount").css('display', 'none');
				if(data.cart_quantity > 0) {
					$(".shoppingCartCount").css('display', 'block');
					$(".shoppingCartCount").html(data.cart_quantity);
				}
				if(localStorage.getItem('user_name') != undefined) {

					$('#RID').val(localStorage.getItem('user_name'));
				}
				localStorage.setItem('cz_shouxu', info.cz_shouxu)
				var zz_shouxu = info.zz_shouxu.split("|");

				localStorage.setItem('zz_shouxu', zz_shouxu[1])
				//$('#regForm').attr('url', PreUrl + "Reg/usersAdd");

				var level = info.s9;
				$('#get_level_select').on('click', function() {
					weui.picker(info.s9, {
						onChange: function(result, a) {
							console.log(result);

							$('#get_level').val(result)
							$.each(level, function(index1, content1) {
								if(content1.id == result) {
									$('#get_level_select').val(content1.label)
								}
							});

						},
						onConfirm: function(result) {
							$('#get_level').val(result)
							$.each(level, function(index1, content1) {
								if(content1.id == result) {
									$('#get_level_select').val(content1.label)
								}
							});

						}
					});
				});
				$('#wenti_select').on('click', function() {
					weui.picker(info.wenti, {
						onChange: function(result) {
							console.log(result);

							$('#wenti_select').val(result)
							$('#wenti').val(result)

						},
						onConfirm: function(result) {

							$('#wenti_select').val(result)
							$('#wenti').val(result)

						}
					});
				});
				$('#TPL0').on('click', function() {
					$('#TPL1').prop("checked", false);
					$('#TPL0').prop("checked", true);

					$('#TPL').val(0)

				});
				$('#TPL1').on('click', function() {
					$('#TPL0').prop("checked", false);
					$('#TPL1').prop("checked", true);
					$('#TPL').val(1)

				});
				$('#register_type0').on('click', function() {
					$('#register_type1').prop("checked", false);
					$('#register_type0').prop("checked", true);

					$('#register_type').val(1)

				});
				$('#register_type1').on('click', function() {
					$('#register_type0').prop("checked", false);
					$('#register_type1').prop("checked", true);
					$('#register_type').val(0)

				});
				$('.intro').html(info.RegisterProtocol);
				//				$("#bank").picker({
				//					title: "请选择银行",
				//					cols: [{
				//						textAlign: 'center',
				//						values: info.bank
				//					}]
				//				});

				$('#txtEmail').val(info.email);
				$('#market_new_price').html(info.ssb_money);
				$('#market_max_price').html(info.market_max_price);
				$('#market_min_price').html(info.market_min_price);
				$('#market_all_price').html(info.all_ssb_money);
				$('#market_volume').html(info.all_ssb);
				$('.love_money').html(info.love_money);
				var user = data.user;
				$('.user_love_money').html(user.user_love_money);

				if(data.is_sign > 0) {
					$('.sign_a').css('display', 'none')
				} else {
					$('.sign_a').css('display', 'block')
				}
			}

		},
		error: function(xhr, type, errorThrown) {
			//异常处理；
			console.log(type);
		}
	});

}

function get_user_info(user_id) {
	$.ajax({
		url: get_user_info_val,
		data: {
			is_mobile: 1,

			user_id: user_id
		},
		dataType: 'json', //服务器返回json格式数据
		type: 'POST', //HTTP请求类型
		timeout: 10000, //超时时间设置为10秒；

		success: function(data) {
			var status = data.status;
			
			if(data.data.category_title!=null)
			{
			    localStorage.setItem('category0', JSON.stringify(data.data.category0));
			    localStorage.setItem('category1', JSON.stringify(data.data.category1)); 
			}
			
			localStorage.setItem('user', JSON.stringify(data.data));
			//			localStorage.setItem('shop', JSON.stringify(data.shop));
			localStorage.setItem('bank_list', JSON.stringify(data.bank_list));
			localStorage.setItem('userbank', JSON.stringify(data.userbank));
			localStorage.setItem('article_category', JSON.stringify(data.article_category));
			if(status == 1) {
				var info = data.data;
				localStorage.setItem('user_name', info.user_id)
				$('.username').html(info.user_id);

				if(info.shop != null) {
					if(info.shop.userinfo_logo != '' && info.shop.userinfo_logo != null) {
						$('.avatar img').attr('src', UploadUrl + info.shop.userinfo_logo)
					}
				}

				var mphone = info.user_id.substr(0, 3) + '****' + info.user_id.substr(7);
				$('.er_username').html(mphone);

				$('.userid').html('姓名:' + info.name);
				$('#group').html('注册级别：' + info.uLevel);
				//				if(info.limit_money<=0&&info.cp) {
				//					$('#is_chuju').css('display', 'block'); 
				//				}

				$('#mobile').html('账户名称:' + info.user_id);
				$('#user_number').html('会员ID号：' + info.user_number);
				$('#user_tel').html('手机号码:' + info.user_tel);

				$('#shoppingCartCount').html('' + data.cart_num);
				$('.order_num').html('' + data.order_num + '笔');
				$('.address_num').html('' + data.address_num + '条');
				localStorage.setItem('shoppingCartCount', data.cart_num);
				$('#get_level').html(info.uLevel);
				$('.agent_use').html(info.agent_use);
				$('.live_gupiao').html(info.live_gupiao);
				$('.agent_kt').html(info.agent_kt);
				$('.buy_point').html(info.buy_point);
				$('.all_gupiao').html(info.all_gupiao);
				$('.limit_money').html(info.limit_money);

				$('.agent_use_txt').html(info.agent_use_txt);
				$('.limit_money_txt').html(info.limit_money_txt);
				$('.agent_kt_txt').html(info.agent_kt_txt);

				$('.agent_limit_money').html(info.agent_limit_money);
				$('.ssb').html(info.ssb);
				$('#name').val(info.name);
				$('#bank_address').val(info.bank_address);
				$('#nickname').val(info.nickname);
				$('#user_address').val(info.user_address);
				$('#user_name').val(info.user_id);
				$('#username').val(info.user_name);
				$('#bankusername').val(info.name);
				$('#password').val(info.pwd1);
				$('#time').val(info.user_id);
				$('#group').val(info.uLevel);
				$('#idcard').val(info.user_code);
				$('#mobile').val(info.user_tel);
				$('#email').val(info.email);
				$('#bank_card').val(info.bank_card);
				$('#bank_select').val(info.bank_name);
				$('#us_img').attr('url', info.wx_img);
				$('#zfb_img').attr('url', info.zfb_img);
				if(info.wx_img != '' && info.wx_img != null) {

					$('#wx_url').css('background-image', 'url("' + UploadUrl + info.wx_img + '")');
				}
				if(info.zfb_img != '' && info.zfb_img != null) {
					$('#zfb_url').css('background-image', 'url("' + UploadUrl + info.zfb_img + '")');
				}
				if(info.avatar != '' && info.avatar != null) {
					$('.avatar_path').val(  info.avatar  );
					$('#headPic').attr('src',  UploadUrl + info.avatar  );
				}

				$('#bankname').val(info.bank_name);
				$('.original_path').val(info.wx_img);
				$('.zfboriginal_path').val(info.zfb_img);
				
				
				
				
				
				if(info.order_num1>0)
				{
					$('.order_num1').css('display','block')
					$('.order_num1').html(info.order_num1)
				}
				if(info.order_num2>0)
				{
					$('.order_num2').css('display','block')
					$('.order_num2').html(info.order_num2)
				}
				if(info.order_num3>0)
				{
					$('.order_num3').css('display','block')
					$('.order_num3').html(info.order_num3)
				}
				if(info.order_num5>0)
				{
					$('.order_num5').css('display','block')
					$('.order_num5').html(info.order_num5)
				}
				
				
				
				if(info.shop_order_num1>0)
				{
					$('.shop_order_num1').css('display','block')
					$('.shop_order_num1').html(info.shop_order_num1)
				}
				if(info.shop_order_num2>0)
				{
					$('.shop_order_num2').css('display','block')
					$('.shop_order_num2').html(info.shop_order_num2)
				}
				if(info.shop_order_num3>0)
				{
					$('.shop_order_num3').css('display','block')
					$('.shop_order_num3').html(info.shop_order_num3)
				}
				if(info.shop_order_num5>0)
				{
					$('.shop_order_num5').css('display','block')
					$('.shop_order_num5').html(info.shop_order_num5)
				}
				
				if(info.id==1)
				{
 					$('.select_type').css('display','none') 
				}
				
				
				
				
				
				
				
				

				var bank_list = data.bank_list;
				var userbank = data.userbank;
				var bankusername = data.bankusername;
				var bankcard = data.bankcard;

				if(userbank != null) {
					//					$.each(bank_list, function(index1, content1) {
					//						var str = '';
					//						if(content1 == info.bank_name) {
					//							str = 'selected';
					//						}
					//
					//						var item = '<option value="' + content1 + '" ' + str + ' bankcard="' + bankcard[index1] + '"   bankusername="' + bankusername[index1] + '">' + content1 + '</option>';
					//						$('#bank').append(item);
					//					});
					$('#bank_select').on('click', function() {
						weui.picker(userbank, {
							onChange: function(result, a) {
								console.log(result);

								$('#bankname').val(result)

								$('#bank_select').val(result)

							},
							onConfirm: function(result) {
								$('#bankname').val(result)

								$('#bank_select').val(result)

							}
						});
					});

				}

				$('.tip').css('width', '50%');
				if(info.id < 10) {

					//$('#erweima_btn').css('display', 'none');
				}
				if(info.get_level > 1) {
					$('.Uplevel').css('display', 'none');
					//					$('.agent').css('display', 'block');
					$('.tip').css('width', '50%');
					$('.myteam').css('display', 'block');

				}
				if(info.get_level != 1) {
					$('.apply_company').css('display', 'none');
				}

				if(info.is_tixian == 1) {
					$('.weituo_txt').html('取消委托');
				}
				if(info.is_tixian == 0) {
					$('.weituo_txt').html('委托拼单');
				}

				$('#user_name').html('账户名称:' + info.user_id);
				$('#user_number').html('会员ID号:' + info.user_number);

				//消费过且没有释放额度了 （复投） 
				//				if(info.limit_money <= 0 && info.cpzj > 0) {
				//					$('.futou').css('display', 'block')
				//				}
				//				//没有消费过且没有释放额度（空点升级）
				//				if(info.limit_money <= 0 && info.cpzj == 0) {
				//					$('.Uplevel1').css('display', 'block')
				//				}
				//				if(info.limit_money > 0) {
				//					$('.Uplevel').css('display', 'block')
				//				}
				//				//r如果有平移额度
				//				if(info.extra_limit_money1 > 0) {
				//					$('.pingyi').css('display', 'block')
				//					$('.Uplevel1').css('display', 'none')
				//				}
				//				if(info.get_level == 0 && info.extra_limit_money1 > 0) {
				//					$('.Uplevel1').css('display', 'none')
				//				}
				//				if(info.get_level == 0) {
				//					$('.futou').css('display', 'none')
				//				}

				if(!info.is_today_fenhong) {
					$('.fenhong_p').css('color', 'red')
					$('.fenhong').html('点击领取')
					$('.fenhong_btn').attr('onclick', 'fenhong()')

					if(info.register_type == 0) {

						$('.fenhong_img').attr('src', '../images/fenhong.png');
					}
				} else {
					$('.fenhong').html('已领取')
					$('.fenhong_btn').removeAttr('onclick')
				}

			} else {

				localStorage.setItem('user', '');
				//				weui.alert(data.msg);
				location.href = data.url;

			}
			////lang()

		},
		error: function(xhr, type, errorThrown) {
			//异常处理；
			console.log(type);
		}
	});

}

function MenberJinjiConfirm() {
	var ulevel = $("input[name='uLevel']:checked").val();
	if(ulevel == undefined) {
		mui.toast('请选择升级级别');
		return;
	}
	var img_url = $('.original_path').val();
	mui.confirm('你确定申请吗？', function(e) {
		if(e.index == 1) {
			$.ajax({
				url: MenberJinjiConfirm_fun,
				data: {
					is_mobile: 1,
					user_id: localStorage.getItem('user_id'),
					ulevel: ulevel,
					img_url: img_url
				},
				dataType: 'json', //服务器返回json格式数据
				type: 'POST', //HTTP请求类型
				timeout: 10000, //超时时间设置为10秒；

				success: function(data) {
					var status = data.status;
					if(status == 1) {
						mui.alert(data.msg, '提示', function() {
							location.reload();
						});

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
	}, function() {});

}

function get_jinji_info(user_id) {
	$.ajax(get_jinji_info_val, {
		data: {
			is_mobile: 1,

			user_id: user_id
		},
		dataType: 'json', //服务器返回json格式数据
		type: 'POST', //HTTP请求类型
		timeout: 10000, //超时时间设置为10秒；

		success: function(data) {
			var status = data.status;
			if(status == 1) {
				var info = data.data;
				$('#get_level1').html(info.out_money)
				$('#get_level').html(info.uLevel);
				if(info.get_level == 7) {
					$('.weui-btn-area').css('display', 'none')
				}
				$.each(data.level, function(index1, content1) {
					if(info.get_level < (content1.id + 1)) {
						var item = '<div class="mui-input-row mui-radio">';
						item += '		 <label>' + content1.title + '【' + content1.money + '】</label>';
						item += '		<input name="uLevel" type="radio"  value="' + (content1.id + 1) + '">';
						item += '		</div>';

						//						var item = '<label class="weui-cell weui-check__label" for="p-' + (content1.id + 1) + '">';
						//						item += '	  <div class="weui-cell__bd">';
						//						item += '	     <p>' + content1.title + '</p>';
						//						item += '	 </div>';
						//						item += '	 <div class="weui-cell__ft">' + content1.money + '';
						//						item += '	     <input id="p-' + (content1.id + 1) + '" name="uLevel" type="radio" class="weui-check" value="' + (content1.id + 1) + '" data-validate="select" />';
						//						item += '	     <span class="weui-icon-checked"></span>';
						//						item += '	  </div>';
						//						item += '	 </label>';
						$('.level_list').append(item);
					}
				});

				$.each(data.bank, function(index1, content1) {

					var item = '<label class="weui-cell weui-check__label" for="p-' + (content1.bankcard) + '">';
					item += '	  <div class="weui-cell__bd">';
					item += '	     <p style="margin-bottom: 0px;">' + content1.bankusername + '</p>';
					item += '	 </div>';
					item += '	 <div class="weui-cell__ft" style="font-size: 15px;" >' + content1.bank + '【' + content1.bankcard + '】';
					item += '	     <input id="p-' + (content1.bankcard) + '"  type="radio" class="weui-check" value="' + (content1.bankcard) + '" data-validate="select" />';
					item += '	    ';
					item += '	  </div>';
					item += '	 </label>';
					$('.bank_list').append(item);

				});

				//选中TAB样式
				$(".weui-tabbar a").eq(0).addClass("weui-bar__item_on");
				//lang()
			}

		},
		error: function(xhr, type, errorThrown) {
			//异常处理；
			console.log(type);
		}
	});

}

function remove_toast() {
	setTimeout(function() { //两秒后跳转  

		$('.weui-toast').remove();
		$('.weui-mask_transparent').remove();
	}, 1000);

}

function get_address_info() {

	$.ajax({
		url: get_address_fun,

		dataType: 'json', //服务器返回json格式数据
		type: 'POST', //HTTP请求类型
		timeout: 10000, //超时时间设置为10秒；

		success: function(data) {
			var status = data.status;
			if(status == 1) {
				//				mui.toast(data.city);
				localStorage.setItem('province', data.province);
				localStorage.setItem('city', data.city);
				$('.select_city').html(data.city)
			}
			////lang()

		},
		error: function(xhr, type, errorThrown) {
			//异常处理；
			console.log(type);
		}
	});

}