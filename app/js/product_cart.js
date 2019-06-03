var ServerIP = "";
var IP = localStorage.getItem('IP');
var PreUrl = localStorage.getItem('PreUrl');
/**
 * 购物车加入商品
 */
var get_article_goods_info = PreUrl + "Goods/get_article_goods_info";
/**
 * 购物车加入商品
 */
var cart_goods_add = PreUrl + "Goods/cart_goods_add";
/**
 * 购物车加入商品
 */
var cart_goods_update = PreUrl + "Goods/cart_goods_update";
/**
 * 购物车
 */
var cart_items = PreUrl + "Goods/cart_items";
/**
 * 删除购物车
 */
var cart_goods_delete = PreUrl + "Goods/cart_goods_delete";
/**
 * 下单页面
 */
var cart_goods_buy = PreUrl + "Goods/cart_goods_buy";
/**
 * 获取用户地址
 */
var get_user_addr_book_list = PreUrl + "Goods/get_user_addr_book_list";

function get_user_addr_book_list_items() {
	$.ajax({
		type: "POST",
		url: get_user_addr_book_list,
		dataType: "json",
		data: {
			is_mobile: 1,
			user_id: localStorage.getItem('user_id')
		},
		timeout: 20000,
		success: function(data, textStatus) {
			var status = data.status;
			if(status == 1) {
				var info = data.data;

				$('#userAddress .mui-table-view-radio li').remove();
				$('#addressDefault   ul').remove();
				if(info != null) {

					$.each(info, function(index, content) {
						var item = '	<li class="mui-table-view-cell" id="' + content.id + '">';
						item += '	<a class="mui-navigate-right">';
						item += '	  <p>' + content.accept_name + ' <i>' + content.mobile + '</i></p>';
						item += '	  <p>' + content.province + content.city + content.area + content.address + '</p>';
						item += '</a>';
						item += '	 <input name="user_book_id1" type="hidden" value="' + content.id + '" />';
						item += '	 <input name="user_accept_name" type="hidden" value="' + content.accept_name + '" />';
						item += '	 <input name="user_area" type="hidden" value="' + content.province + content.city + content.area + '" />';
						item += '	 <input name="user_address" type="hidden" value="' + content.address + '" />';
						item += '	 <input name="user_mobile" type="hidden" value="' + content.mobile + '" />';
						item += '	 <input name="user_telphone" type="hidden" value="' + content.telphone + '" />';
						item += '	 <input name="user_email" type="hidden" value="' + content.email + '" />';
						item += '	 <input name="user_post_code" type="hidden" value="' + content.post_code + '" />';
						item += '	</li>';

						console.log(content.accept_name)
						$('.address_list').append(item);

					});

					initUserAddress("#userAddress");

					$.each(info, function(index, content) {

						if(index == 0) {
							var mobile = content.mobile.substr(0, 3) + '****' + content.mobile.substr(7);
							var item = '	 ';
							item += '	<ul onclick="open_address()">';
							item += '		<li><strong>' + content.accept_name + ' ' + mobile + '</strong></li>';
							item += '	<li> ' + content.province + content.city + content.area + '' + content.address + ' </li>';
							item += '	<li id="globalSalTip" style="display:none;"><em>目的国/地区如产生关税费用，需客户自行承担，请知悉。</em></li>';
							item += '	<li class="error" style="display: none;">请选择乡镇/街道</li>';
							item += '	</ul>';
							$('#book_id').val(content.id);
							$('#accept_name').val(content.accept_name);
							$('#area').val(content.province + content.city + content.area);
							$('#address').val(content.address);
							$('#mobile').val(content.mobile);
							console.log(content.accept_name)
							$('#addressDefault').append(item);

						}
					});

				}
			}

		},
		error: function(XMLHttpRequest, textStatus, errorThrown) {
			mui.alert("查询出错：" + textStatus + ",提示：" + errorThrown);
		}
	});

}

function open_address() {
	localStorage.setItem('select_address', 1)

	openWindowWithTitle('地址选择', 'useraddress.html')
}

function get_cart_items(type, successCallback) {

	var user_id = localStorage.getItem('user_id');
	var goods_order_type = localStorage.getItem('goods_order_type');
	if(user_id == null || user_id == '') {
		$('.cart_list').css('display', 'none')
		$('.msg-box').css('display', 'block')
	} else {
		$.ajax({
			url: cart_items,
			data: {
				is_mobile: 1,
				user_id: user_id
			},
			dataType: 'json', //服务器返回json格式数据
			type: 'post', //HTTP请求类型
			timeout: 10000, //超时时间设置为10秒；

			success: function(data) {
				var status = data.status;
				var user_addr_book_count = data.user_addr_book_count;
				$('#orderForm').attr('url', PreUrl + "Goods/order_save");
				console.log(localStorage.getItem('jsondata'))
				$('#jsondata').val(localStorage.getItem('jsondata'));
				$('.address_null').css('display', 'none')
				$('.mod_alert').css('display', 'none')
				$('.mod_alert_mask').css('display', 'none')
				var is_pin = localStorage.getItem('is_pin');

				if(user_addr_book_count == 0) {

					$('.address_null').css('display', 'block')
					$('.mod_alert').css('display', 'block')
					$('.mod_alert_mask').css('display', 'block')
				}
				$("#ui_btn_cancel").on('click', function() {
					$('.mod_alert').css('display', 'none')
					$('.mod_alert_mask').css('display', 'none')
				});
				$("#addressNull").on('click', function() {
					localStorage.setItem('is_shopping', 1);
					localStorage.setItem('set_address', 1);

					openWindowWithTitle('编辑地址薄', 'useraddress_edit.html')
				})
				$("#ui_btn_confirm").on('click', function() {
					localStorage.setItem('is_shopping', 1);
					openWindowWithTitle('编辑地址薄', 'useraddress_edit.html')
				})
				console.log(JSON.stringify(data))
				if(status == 1) {
					var info = data.data;
					$('.cart-list ul').html('');

					if(info != null) {
						$('.cart_list').css('display', 'block')
						$('.msg-box').css('display', 'none')

						$.each(info, function(index, content) {
							var item = '	 <li> ';
							item += '	<input name="hideChannelId" type="hidden" value="0" />';
							item += '	<input name="hideArticleId" type="hidden" value="' + content.article_id + '" />';
							item += '	<input name="hideGoodsId" type="hidden" value="' + content.goods_id + '" />';
							item += '	<input name="hideStockQuantity" type="hidden" value="' + content.stock + '" />';
							item += '	<input name="hideGoodsPrice" type="hidden" value="' + content.price + '" />';
							item += '	<input name="hideHotId" type="hidden" value="' + content.hot_id + '" />';
							item += '	 <label class="select">';
							item += '	 <input cart_id="' + content.id + '"   type="checkbox" name="checkall" class="checkall cart_input" onclick="selectCart();" />';
							console.log(content.img_url)
							item += '       </label>   <a href="javascript:;" class="img-box">  <img src="' + content.img_url + '" /></a>';

							item += '      <h2>' + content.title + ' </h2>';
							item += '       <p class="stxt">' + content.spec_text + ' </p>';
							item += '       <div class="note">';
							item += '      <div class="btn-list">';
							item += '           <a href="javascript:;" onclick="updateCart(this,   -1);">-</a>';
							item += '           <input name="goodsQuantity" type="text" value="' + content.quantity + '" onblur="updateCart(this );" onkeydown="return checkNumber(event);" />';
							item += '          <a href="javascript:;" onclick="updateCart(this,   1);">+</a>';
							item += '          <a class="del" href="javascript:;" onclick="deleteCart(  this);">删除</a>';
							item += '      </div>';
							item += '     <i class="price"> ' + content.price + ' 元</i>';
							item += '     </div>';
							item += '    </li>';
							$('.cart-list-items ul').append(item);

						});
						if(type == 'buy') {
							info = JSON.parse($('#jsondata').val());
						}
						var totalAmount = 0;
						$.each(info, function(index, content) {
							var item = '	 <li> ';
							item += '	<a href="javascript:void(0)" class="img-box">';
							item += '	<img src="' + content.img_url + '" /> </a>';
							item += '	<h2>' + content.title + '</h2>';

							item += '	  <p class="stxt">' + content.spec_text + '</p>';
							item += '	  <div class="note">';
							item += '	      <span class="right">';
							item += '	       ×' + content.quantity + '';
							item += '	      </span>';
							item += '	     <i class="price"> ' + content.price + '元</i>';
							item += '	  </div>';

							item += '<ul class="order_info_tips" style="padding-left: 0px;display:none">                                                      <li><img class="icon_optional" src="../images/59af98d4Nb287ee5f.png">支持7天无理由退货</li>                                                                                                                                                                            </ul>';

							item += '	  </li>';
							$('.inset .goods_list').append(item);
							totalAmount += content.price * content.quantity;
							$('#totalAmount').html(totalAmount)
							$('.totalAmount').html(totalAmount)
							$('#goodsAmount').html(totalAmount)
							$('#totalAmount1').html('¥' + totalAmount)

						});
						$('.all').trigger('click');

						var bank = data.bank;
						var bankcard = data.bankcard;
						if(bankcard != null) {
							$.each(bankcard, function(index, content) {
								var item = '	<label class="weui-cell weui-check__label" for="p-{dr[id]}">';
								item += '	 <div class="weui-cell__bd">';
								item += '	  <p>' + bank[index] + '&nbsp&nbsp&nbsp' + content + '【' + data.bankusername[index] + '】<i></i></p>';
								item += '	  </div>';
								item += '	 <div class="weui-cell__ft">';
								item += '	   <input id="p-{dr[id]}"   type="button" class="weui-check" onclick="paymentAmountTotal(this);" price="{poundage_amount}" value="{dr[id]}" data-validate="select" />';
								item += '	 <span class="weui-icon-checked"></span>';
								item += '	  </div>';
								item += '	  </label>';
								$('.bank').append(item);

							});
						}
						//						$('.payment_div').css('display', 'none')
						$('.payment_id').css('display', 'none')
						//						if(goods_order_type == 1) {
						//							$('.payment_div').css('display', 'block')
						$('.payment_id').css('display', 'none')

						$('.payment_list   li').remove();
						if(data.payment != null) {
							$.each(data.payment, function(index, content) {
								var item = '	<li class="mui-table-view-cell" id="' + content.id + '">';
								item += '	<a class="mui-navigate-right">';

								item += '	<img src="../images/logo.png"  style="width:20px;float:left;margin-right:10px" >';

								item += '	  <p style="" > ' + content.title + '</p>';

								item += '</a>';
								item += '	 <input name="payment_id1" type="hidden" value="' + content.id + '" />';
								item += '	</li>';

								$('.payment_list').append(item);
								$('#payment_id').val(content.id)

							});
						}
						if(data.trade == null) {
							$('.nodata').html('填写进货单');
							$('.nodata').click(function() {
								openWindowWithTitle('进货单', 'jinhuo.html')

							})
						}

						$('.payment_list li').addClass('mui-selected')
						initPayment('.payment_list')

						//						}

					} else {
						//						mui.toast('回到首页')
						$('.cart_list').css('display', 'none')
						$('.msg-box').css('display', 'block')

					}

					data = JSON.stringify(data);
					localStorage.setItem('ListData', data)
					data = JSON.parse(data);
					var status = data.status;
					if(status == 1) {

					}
					successCallback && successCallback(data);
				}

			},
			error: function(xhr, type, errorThrown) {
				//异常处理；
				console.log(type);
			}
		});
	}
}

/*
*作者：一些事情
*时间：2017-5-5
*购物车方法*需要结合jquery一起使用
----------------------------------------------------------*/
//商品数量加减一
function addCartNum(num) {
	var numObj = $("#commoditySelectNum");
	var selectNum = 0;
	if(numObj.val().length > 0) {
		selectNum = parseInt(numObj.val());
	}
	selectNum += num;
	//最小值
	if(selectNum < 1) {
		selectNum = 1;
	}
	//最大值
	if(selectNum > parseInt(numObj.attr("maxValue"))) {
		selectNum = parseInt(numObj.attr("maxValue"));
	}
	numObj.val(selectNum);
}

//初始化商品规格事件
function initGoodsSpec(sendUrl) {
	//检查是否有规格
	if($("#goodsSpecBox dl").length == 0) {
		$("#confirmButton").prop("disabled", false).removeClass("over");
	}
	//遍历规格属性
	$("#goodsSpecBox dl dd a").each(function() {
		$(this).click(function() {
			if(!$(this).hasClass("selected")) {
				//标签选中状态
				$(this).siblings("a").removeClass("selected");
				$(this).addClass("selected");
				//获取商品价格
				if($("#goodsSpecBox dl dd a.selected").length == $("#goodsSpecBox dl").length) {
					var specIds = '';
					var specText = '';
					$("#goodsSpecBox dl dd a.selected").each(function(i) {
						if(i == 0) {
							specIds = ",";
							specText = '已选择：';
						}
						specIds += $(this).attr("specid") + ',';
						specText += $(this).attr("title") + ',';
					});
					//发送异步请求
					$.ajax({
						type: "POST",
						url: get_article_goods_info,
						dataType: "json",
						data: {
							"article_id": $("#commodityArticleId").val(),
							"user_id": localStorage.getItem('user_id'),
							"is_mobile": 1,
							"ids": specIds
						},
						timeout: 20000,
						success: function(data, textStatus) {
							if(data.status == 1) {
								$("#selectSpecText p").text(specText.substring(0, specText.length - 1));
								$("#commodityGoodsId").val(data.goods_id);
								$("#commodityGoodsNo").text(data.goods_no);
								$("#commodityMarketPrice").text('¥' + data.market_price);
								$("#commoditySellPrice").text('¥' + data.sell_price);
								$("#commodityStockNum").html(data.stock_quantity);
								$("#commodityHot_id").html(data.hot_id);
								$("#commoditySelectNum").attr("maxValue", data.stock_quantity);
								$("#spec_text").val(data.spec_text);
								$(".price").html('¥' + data.sell_price);
								$(".crowd_price").html('¥' + data.crown_price);
								//检查是否足够库存
								if(parseInt(data.stock_quantity) > 0) {
									$("#confirmButton").prop("disabled", false).removeClass("over");
								} else {
									$("#confirmButton").prop("disabled", true).addClass("over");
								}
							} else {
								$("#confirmButton").prop("disabled", true).addClass("over");
								mui.alert(data.msg);
							}
						},
						error: function(XMLHttpRequest, textStatus, errorThrown) {
							mui.alert("查询出错：" + textStatus + ",提示：" + errorThrown);
						}
					});
				}
			}
		});
	});
}

//打开规格选择窗口
function showActionSheet(actiontype, webpath, linkurl, order_id) {
	//显示窗口
	$('#specActionsheet').addClass('weui-actionsheet_toggle');
	$('#specMask').fadeIn(200);
	//添加隐藏事件
	$('#specMask').on('click', hideActionSheet);
	$('#specActionsheetCancel').on('click', hideActionSheet);
	//判断是选择规格|立即购买|加入购物车
	$('#confirmButton').unbind("click"); //移除事件
	if(actiontype == "buy") { //立即购买
		$('#confirmButton').on('click', function() {
			hideActionSheet();
			cartAdd(actiontype, webpath, linkurl);
		});
	} else if(actiontype == "join") {
		localStorage.setItem('pin_order_id', order_id)

		//立即购买
		$('#confirmButton').on('click', function() {
			hideActionSheet();
			cartAdd(actiontype, webpath, linkurl);
		});
	} else if(actiontype == "cart") { //加入购物车
		$('#confirmButton').on('click', function() {
			hideActionSheet();
			cartAdd(actiontype, webpath, linkurl);
		});
	} else { //选择规格
		$('#confirmButton').on('click', hideActionSheet);
	}
}
//隐藏规格选择窗口
function hideActionSheet() {
	$('#specActionsheet').removeClass('weui-actionsheet_toggle');
	$('#specMask').fadeOut(200);
}

//添加进购物车
function cartAdd(actiontype, webpath, linkurl) {

	var channelId = parseInt($("#commodityChannelId").val());
	var articleId = parseInt($("#commodityArticleId").val());
	var goodsId = parseInt($("#commodityGoodsId").val());
	var selectNum = parseInt($("#commoditySelectNum").val());
	var hot_id = parseInt($("#commodityHot_id").val());
	var img_url = ($("#img_url").val());
	var title = ($("#title").val());
	var spec_text = ($("#spec_text").val());
	var goods_order_type = ($("#goods_order_type").val());
	if($(".crowd_price").length > 0) {
		price = $(".crowd_price").html().replace('¥', '');
	} else {

		price = $(".price").html().replace('¥', '');

	}
	console.log(price)

	var is_pin = ($("#is_pin").val());
	var pid = ($("#pid").val());
	localStorage.setItem('pid', pid);
	localStorage.setItem('goods_order_type', goods_order_type);
	localStorage.setItem('is_pin', is_pin);

	//检查文章ID
	if(isNaN(articleId)) {
		mui.alert("商品参数不正确！");
		return false;
	}
	//检查商品是否有规格
	if(goodsId == 0 && $("#goodsSpecBox dl").length > 0) {
		mui.alert("请先选择商品规格！");
		return false;
	}
	//检查购买数量
	if(isNaN(selectNum) || selectNum == 0) {
		mui.alert("购买数量不能为零！");
		return false;
	}
	//检查库存数量
	if(parseInt(selectNum) > parseInt($("#commodityStockNum").text())) {
		mui.alert("库存不足！");
		return false;
	}

	//如果是立即购买
	if(actiontype == "buy") {
		var jsondata = '[{ "article_id":' + articleId + ',"price":' + price + ',"spec_text":"' + spec_text + '","img_url":"' + img_url + '","title":"' + title + '", "goods_id":' + goodsId + ', "quantity":' + selectNum + ', "hot_id":0}]'; //结合商品参数
		$.ajax({
			type: "post",
			url: cart_goods_add,
			data: {
				"actiontype": actiontype,
				"is_mobile": 1,
				"user_id": localStorage.getItem('user_id'),
				"article_id": articleId,
				"goods_id": goodsId,
				"quantity": selectNum,
				"hot_id": hot_id
			},
			dataType: "json",
			success: function(data, textStatus) {
				console
				.log(data)
				if(data.status == 1) {
					localStorage.setItem('shoppingCartCount', data.quantity)
					$("#shoppingCartCount").text(data.quantity);
					localStorage.setItem('jsondata', jsondata)
					localStorage.setItem('order_type', 'buy');

					openWindowWithTitle('确认订单', 'shopping.html')
				} else {
					if(data.url != '') {
						mui.confirm(data.info, function() {
							localStorage.setItem('order_type', '');
							location.href = data.url;

						}, 'div');
					} else {

						mui.alert(data.msg);
					}

				}
			},
			error: function(XMLHttpRequest, textStatus, errorThrown) {
				mui.alert("状态：" + textStatus + "；出错提示：" + errorThrown);
			},
			timeout: 20000
		});
		return false;
	} else
	if(actiontype == "join") {
		var jsondata = '[{ "article_id":' + articleId + ',"price":' + price + ',"spec_text":"' + spec_text + '","img_url":"' + img_url + '","title":"' + title + '", "goods_id":' + goodsId + ', "quantity":' + selectNum + ', "hot_id":0}]'; //结合商品参数
		$.ajax({
			type: "post",
			url: cart_goods_add,
			data: {
				"actiontype": actiontype,
				"is_mobile": 1,
				"user_id": localStorage.getItem('user_id'),
				"article_id": articleId,
				"goods_id": goodsId,
				"quantity": selectNum,
				"hot_id": hot_id
			},
			dataType: "json",
			success: function(data, textStatus) {
				if(data.status == 1) {
					//					localStorage.setItem('shoppingCartCount', data.quantity)
					//					$("#shoppingCartCount").text(data.quantity);
					localStorage.setItem('jsondata', jsondata)
					localStorage.setItem('order_type', 'buy');

					openWindowWithTitle('参与拼团', 'groupdetail.html')
				} else {
					if(data.url != '') {
						mui.confirm(data.info, function() {
							localStorage.setItem('order_type', '');
							location.href = data.url;
						}, 'div');
					} else {

						mui.alert(data.msg);
					}

				}
			},
			error: function(XMLHttpRequest, textStatus, errorThrown) {
				mui.alert("状态：" + textStatus + "；出错提示：" + errorThrown);
			},
			timeout: 20000
		});
		return false;
	} else {
		$.ajax({
			type: "post",
			url: cart_goods_add,
			data: {
				"actiontype": actiontype,
				"is_mobile": 1,
				"user_id": localStorage.getItem('user_id'),
				"article_id": articleId,
				"goods_id": goodsId,
				"quantity": selectNum,
				"hot_id": hot_id
			},
			dataType: "json",
			success: function(data, textStatus) {
				if(data.status == 1) {
					mui.toast('放入购物车成功');
					localStorage.setItem('order_type', '');
					localStorage.setItem('shoppingCartCount', data.quantity)
					$("#shoppingCartCount").text(data.quantity);
					$(".shoppingCartCount").css('display', 'none');
					if(data.quantity > 0) {
						$(".shoppingCartCount").css('display', 'block');
						$(".shoppingCartCount").html(data.quantity);
					}
					//赋值给显示购物车数量的元素
				} else {
					if(data.url != '') {
						mui.confirm(data.info, function(e) {
							if(e.index == 1) {
								openWindowWithTitle('购物车', data.url)
							}
						}, 'div');
					} else {

						mui.alert(data.info);
					}

				}
			},
			error: function(XMLHttpRequest, textStatus, errorThrown) {
				mui.alert("状态：" + textStatus + "；出错提示：" + errorThrown);
			},
			timeout: 20000
		});
		return false;
	}
}

function gotoCart() {
	location.href = "cart.html";
}
//修改购物车数量
function updateCart(obj, num) {
	var objInput;
	var goodsQuantity; //购买数量
	var stockQuantity = parseInt($(obj).parents("li").find("input[name='hideStockQuantity']").val()); //库存数量
	var channelId = $(obj).parents("li").find("input[name='hideChannelId']").val(); //频道ID
	var articleId = $(obj).parents("li").find("input[name='hideArticleId']").val(); //文章ID
	var goodsId = $(obj).parents("li").find("input[name='hideGoodsId']").val(); //商品ID
	var goodsPrice = $(obj).parents("li").find("input[name='hideGoodsPrice']").val(); //商品单价
	var goodsPoint = $(obj).parents("li").find("input[name='hidePoint']").val(); //商品元

	var objInput = $(obj);
	goodsQuantity = parseInt(objInput.val());

	console.log(objInput.val())

	if(isNaN(goodsQuantity)) {
		mui.alert('商品数量只能输入数字!');
		return false;
	}
	//	if(isNaN(stockQuantity)) {
	//		mui.alert('检测不到商品库存数量!');
	//		return false;
	//	}
	if(goodsQuantity < 1) {
		goodsQuantity = 1;
	}
	if(goodsQuantity > stockQuantity) {
		mui.alert('购买数量不能大于库存数量!');
		goodsQuantity = stockQuantity;
	}
	$.ajax({
		type: "post",
		url: cart_goods_update,
		data: {
			"is_mobile": 1,
			"user_id": localStorage.getItem('user_id'),
			"channel_id": channelId,
			"article_id": articleId,
			"goods_id": goodsId,
			"quantity": goodsQuantity
		},
		dataType: "json",
		beforeSend: function(XMLHttpRequest) {
			//发送前动作
		},
		success: function(data, textStatus) {
			if(data.status == 1) {
				objInput.val(goodsQuantity);
				cartAmountTotal(); //重新统计
			} else {
				mui.alert(data.msg);
			}
		},
		error: function(XMLHttpRequest, textStatus, errorThrown) {
			mui.alert("状态：" + textStatus + "；出错提示：" + errorThrown);
		},
		timeout: 20000
	});
	return false;
}

//删除购物车商品
function deleteCart(obj, type) {
	if(type == 'all') {
		var cart_ids = localStorage.getItem('cart_ids');
		if(cart_ids == 0) {
			mui.alert('请选择要删除的商品')
			return;
		}
	}
	mui.confirm('您确认要从购物车中移除吗？', function(e) {
		if(e.index == 1) {
			//组合参数
			var datastr;
			var arglength = arguments.length; //参数个数
			if(arglength == 0) {
				datastr = {
					"clear": 1
				}
			} else {
				var channelId = $(obj).parents("li").find("input[name='hideChannelId']").val();
				var articleId = $(obj).parents("li").find("input[name='hideArticleId']").val();
				var goodsId = $(obj).parents("li").find("input[name='hideGoodsId']").val();
				if(type != 'all') {
					cart_ids = $(obj).parents("li").find(".cart_input").attr('cart_id');
				}
				console.log(cart_ids)
				datastr = {
					"is_mobile": 1,
					"user_id": localStorage.getItem('user_id'),
					"channel_id": channelId,
					"article_id": articleId,
					"goods_id": goodsId,
					"cart_ids": cart_ids
				}
			}
			$.ajax({
				type: "post",
				url: cart_goods_delete,
				data: datastr,
				dataType: "json",
				beforeSend: function(XMLHttpRequest) {
					//发送前动作
				},
				success: function(data, textStatus) {
					if(data.status == 1) {
						localStorage.setItem('shoppingCartCount', data.quantity)

						if(arglength == 1) {
							location.reload();
						} else {
							var $parentObj = $(obj).parents("li").parent();
							$(obj).parents("li").remove();
							if($parentObj.find("li").length == 0) {
								location.reload(); //没有商品刷新页面
							} else {
								cartAmountTotal(); //重新统计
							}
						}
					} else {
						mui.alert(data.msg);
					}
				},
				error: function(XMLHttpRequest, textStatus, errorThrown) {
					mui.alert("状态：" + textStatus + "；出错提示：" + errorThrown);
				},
				timeout: 20000
			});
			return false;
		}
	}, 'div');

}
var cart_ids = '0';
//选择商品
function selectCart(obj) {
	var arglength = arguments.length; //参数个数
	if(arglength == 1) {
		if($(obj).children("span").text() == "全选") {
			$(obj).children("span").text("取消");
			$(".checkall").prop("checked", true);
		} else {
			$(obj).children("span").text("全选");
			$(".checkall").prop("checked", false);
		}
	}
	cart_ids = '0';
	localStorage.setItem('cart_ids', cart_ids);
	$('.cart-list-items').find("li").each(function() {
		//初始化值
		if($(this).find(".cart_input").prop("checked") == true) {
			cart_ids = cart_ids + ',' + $(this).find(".cart_input").attr('cart_id');
			localStorage.setItem('cart_ids', cart_ids);
		}
	});
	cartAmountTotal(); //统计商品
}

//计算购物车金额
function cartAmountTotal() {
	var jsondata = ""; //商品组合参数
	var totalAmount = 0; //商品总计
	var totalNum = 0; //商品总计
	$(".checkall:checked").each(function(i) {
		var hotId = $(this).parents("li").find("input[name='hideHotId']").val(); //频道ID
		var channelId = $(this).parents("li").find("input[name='hideChannelId']").val(); //频道ID
		var articleId = $(this).parents("li").find("input[name='hideArticleId']").val(); //文章ID
		var goodsId = $(this).parents("li").find("input[name='hideGoodsId']").val(); //商品ID
		var goodsPrice = $(this).parents("li").find("input[name='hideGoodsPrice']").val(); //商品单价
		var goodsQuantity = $(this).parents("li").find("input[name='goodsQuantity']").val(); //购买数量
		totalAmount += parseFloat(goodsPrice) * parseFloat(goodsQuantity);
		totalNum += parseFloat(goodsQuantity);
		jsondata += '{"hot_id":"' + hotId + '","channel_id":"' + channelId + '", "article_id":"' + articleId + '", "goods_id":"' + goodsId + '", "quantity":"' + goodsQuantity + '"}';
		if(i < $(".checkall:checked").length - 1) {
			jsondata += ',';
		}
	});
	$("#totalQuantity").text($(".checkall:checked").length);
	$("#totalAmount").text(totalAmount.toFixed(2));
	if(jsondata.length > 0) {
		jsondata = '[' + jsondata + ']';
	}
	$("#jsondata").val(jsondata);

	localStorage.setItem('shoppingCartCount', totalNum)
	init_cart_num()
}

//进入结算中心
function formSubmit(obj, url) {
	var jsondata = $("#jsondata").val();
	if(jsondata == "") {
		mui.alert("未选中要购买的商品，请选中后提交！");
		return false;
	}
	//记住按钮文字
	var buttonText = $(obj).text();
	//加入购物清单
	$.ajax({
		type: "post",
		url: cart_goods_buy,
		data: {
			"is_mobile": 1,
			"jsondata": jsondata
		},
		dataType: "json",
		beforeSend: function(XMLHttpRequest) {
			//发送前动作
			$(obj).text("请稍候...");
		},
		success: function(data, textStatus) {
			if(data.status == 1) {
				localStorage.setItem('hot_id', 1)
				localStorage.setItem('jsondata', data.jsondata)
				localStorage.getItem('order_type', '')
				location.href = url;
			} else {
				$(obj).text(buttonText);
				mui.alert("尝试进入结算中心失败，请重试！");
			}
		},
		error: function(XMLHttpRequest, textStatus, errorThrown) {
			$(obj).text(buttonText);
			mui.alert("状态：" + textStatus + "；出错提示：" + errorThrown);
		},
		timeout: 20000
	});
	return false;
}

/*初始化收货地址*/
function initUserAddress(obj) {
	//初始化省市区
	//	var mypcas = new PCAS("province,所属省份", "city,所属城市", "area,所属地区");
	//初始化地址
	$(obj).find("li").each(function() {
		//初始化值
		if($(this).find("input[name='user_book_id']").prop("checked") == true) {
			$("#book_id").val($(this).find("input[name='user_book_id1']").val());
			$("#accept_name").val($(this).find("input[name='user_accept_name']").val());
			$("#address").val($(this).find("input[name='user_address']").val());
			$("#mobile").val($(this).find("input[name='user_mobile']").val());
			$("#telphone").val($(this).find("input[name='user_telphone']").val());
			$("#email").val($(this).find("input[name='user_email']").val());
			$("#post_code").val($(this).find("input[name='user_post_code']").val());
			$(this).find("input[name='user_book_id']").prop("checked", true); //设置选中
			//改变省市区
			var areaArr = $(this).find("input[name='user_area']").val().split(",");
			$("#city-picker").val(areaArr)
			$("#area").val(areaArr)
			//			if(areaArr.length == 3) {
			//				mypcas.SetValue(areaArr[0], areaArr[1], areaArr[2]);
			//			} else {
			//				mypcas.SetValue();
			//			}
		}
		//初始化事件
		$(this).click(function() {
			$("#book_id").val($(this).find("input[name='user_book_id1']").val());
			$("#accept_name").val($(this).find("input[name='user_accept_name']").val());
			$("#address").val($(this).find("input[name='user_address']").val());
			$("#mobile").val($(this).find("input[name='user_mobile']").val());
			$("#telphone").val($(this).find("input[name='user_telphone']").val());
			$("#email").val($(this).find("input[name='user_email']").val());
			$("#post_code").val($(this).find("input[name='user_post_code']").val());
			//改变省市区
			var areaArr = $(this).find("input[name='user_area']").val().split(",");
			$("#city-picker").val(areaArr)
			$("#area").val(areaArr)

			//			if(areaArr.length == 3) {
			//				mypcas.SetValue(areaArr[0], areaArr[1], areaArr[2]);
			//			} else {
			//				mypcas.SetValue();
			//			}
		});
	});
}

/*初始化收货地址*/
function initPayment(obj) {
	//初始化省市区
	//	var mypcas = new PCAS("province,所属省份", "city,所属城市", "area,所属地区");
	//初始化地址
	$(obj).find("li").each(function() {

		//初始化事件
		$(this).click(function() {
			$("#payment_id").val($(this).find("input[name='payment_id1']").val());

		});
	});
}

//计算支付手续费总金额
function paymentAmountTotal(obj) {
	var paymentPrice = $(obj).attr("price");
	$("#paymentFee").text(parseFloat(paymentPrice).toFixed(2)); //运费
	orderAmountTotal();
}
//计算配送费用总金额
function freightAmountTotal(obj) {
	var expressPrice = $(obj).attr("price");
	$("#expressFee").text(parseFloat(expressPrice).toFixed(2)); //运费
	orderAmountTotal();
}

//计算税金
function taxAmoutTotal(obj) {
	var taxesFee = 0 //税费
	if($(obj).prop("checked") == true) {
		taxesFee = parseFloat($(obj).attr("price"));
		$("#invoiceBox").show();
	} else {
		$("#invoiceBox").hide();
	}
	$("#taxesFee").text(taxesFee.toFixed(2));
	orderAmountTotal();
}

//计算订单总金额
function orderAmountTotal() {
	var goodsAmount = $("#goodsAmount").text(); //商品总金额
	var paymentFee = $("#paymentFee").text(); //手续费
	var expressFee = $("#expressFee").text(); //运费
	var taxesFee = 0 //税费
	if($("#is_invoice").prop("checked") == true) {
		taxesFee = parseFloat($("#is_invoice").attr("price"));
	}
	//订单总金额 = 商品金额 + 手续费 + 运费 + 税费
	var totalAmount = parseFloat(goodsAmount) + parseFloat(paymentFee) + parseFloat(expressFee) + parseFloat(taxesFee);
	$("#totalAmount").text(totalAmount.toFixed(2));
}