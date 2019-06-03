var ServerIP = "";
var IP = localStorage.getItem('IP');
var PreUrl = localStorage.getItem('PreUrl');
var UploadUrl = localStorage.getItem('UploadUrl');
/**
 * 获取商品列表
 */
var get_good_list = PreUrl + "Goods/goods";
var goods_detail_list = PreUrl + "Goods/goods_detail_list";
var hot_goods_list = PreUrl + "Goods/hot_goods_list";
var shop_goods_list = PreUrl + "Goods/shop_goods";
var pin_goods_list = PreUrl + "Shop/pin_goods";
/**
 * 获取商品详情
 */
var goods_edit = PreUrl + "Goods/goods_edit";
var pin_goods_edit = PreUrl + "Shop/goods_edit";
var pin_show_edit = PreUrl + "Shop/pin_show";
/**
 * 发布商品
 */
var create_goods_edit = PreUrl + "Goods/goods_edit";
/**
 * 获取商品详情
 */
var seller_edit = PreUrl + "Shop/seller_edit";
/**
 * 获取类别 
 */
var get_main_category_list_val = PreUrl + "Goods/category_list";
var get_category_list_val = PreUrl + "Goods/category_list";
var get_child_category_list_val = PreUrl + "Goods/category_list";
var get_shop_category_list_val = PreUrl + "Shop/category_list";
/**
 * 拼单商品
 */
var crown_goods = PreUrl + "Goods/crown_goods";
var shop_seller = PreUrl + "Shop/seller";

function create_goods() {
	var txt = '';
	var img = '/app/img/logo.png';
	txt = '提交商品';

	var btnArray = ['否', '是'];
	console.log($('#category_id').val())

	mui.confirm(txt + '，您确定吗？', function(e) {
		if(e.index == 1) {

			$.ajax({
				url: create_goods_edit,
				data: {
					func: 'create_goods',
					user_id: localStorage.getItem('user_id'),
					id: localStorage.getItem('goods_id'),
					ddlParentId: $('#category_id').val(),
					title: $('#title').val(),
					price: $('#price').val(),
					stock: $('#stock').val(),
					limit_money: $('#price').val(),
					agent_use: $('#price').val(),
					market_price: $('#price').val(),
					crown_price1: 0,
					agent_bi: $('#price').val(),
					img: $('.input_img').val(),
					img1: $('.input_img1').val(),
					img2: $('.input_img2').val(),
					img3: $('.input_img3').val(),
					content_img1: $('.input_content_img1').val(),
					content_img2: $('.input_content_img2').val(),
					content_img3: $('.input_content_img3').val(),
					content_img4: $('.input_content_img4').val(),
					type: 1,
					is_mobile: 1
				},
				dataType: 'json', //服务器返回json格式数据
				type: 'post', //HTTP请求类型
				timeout: 10000, //超时时间设置为10秒；

				success: function(data) {
					console.log(data.status)
					var status = data.status;

					if(status == 1) {

						mui.toast(data.msg);
						mui.back();
					} else {
						mui.toast(data.msg)
						return;
					}

				},
				error: function(xhr, type, errorThrown) {
					//异常处理；
					console.log(type);
				}
			});

		}
	}, 'div');

}

function get_shop_seller() {
	$.ajax({
		url: shop_seller,
		data: {
			is_mobile: 1,

			city: localStorage.getItem('select_city')
		},
		dataType: 'json', //服务器返回json格式数据
		type: 'post', //HTTP请求类型
		timeout: 10000, //超时时间设置为10秒；

		success: function(data) {
			remove_toast()
			var status = data.status;
			if(status == 1) {
				$('.nodata-default').css('display', 'none');
				$('.ui-tags-ct li').remove();
				var info = data.data;
				//$('.img-list ul').html('');
				if(info == null) {
					$('.nodata-default').css('display', 'block');

				}
				if(info != null) {
					//					$.each(info, function(index, content) {
					//
					//						var item = '<div class="field"   id=' + content.id + '   name="' + content.title + '"  >';
					//						item += '<a href="javascript:void(0)">';
					//						item += '	<div class="avatar"><img src=" ' + IP + content.img + '" data-src-retina="http://p1.meituan.net/waimaipoi/c10169c48840839e94c98ce88fd05445183279.jpg.webp" class="j-poi-pic avatar-img"> </div>';
					//						item += '</a>';
					//						item += '<div class="content">';
					//						item += '	<a href="javascript:void(0)">';
					//						item += '		<div class="shop-title shop-title-icon-0">';
					//						item += '		<div class="shop-na">' + content.title + '</div>';
					//						item += '	</div>';
					//						item += '<div class="shop-mid-line clearfix">';
					//						item += '	<div class="appr-status"><i class="appr-score"></i><i class="appr-score"></i><i class="appr-score"></i><i class="appr-score"></i><i class="appr-score appr-score-half"></i></div>';
					//						item += '	<div class="shop-sold">' + content.price + '/人</div>';
					//						item += '	</div>';
					//
					//						item += '<div class="shop-mid-line clearfix" style="margin-top: 0rem;">';
					//
					//						item += '	<div class="shop-na">收藏数' + content.collect_num + '</div></div>';
					//
					//						item += '</a>';
					//						item += '	</div>';
					//						item += '	</div>';
					//
					//						$('.ui-tags-ct').append(item);
					//
					//					});
					$.each(info, function(index, content) {

						var item = '<li class="group-item status-opend J_group-item J_log field"  id=' + content.id + '   name="' + content.title + '" data-eid="106147418" data-item_track_data="" data-block="贝贝拼团M站">';
						item += '<a href="javascript:void(0)" class="J_group-router" data-iid="29347154">';
						item += '<div class="list-img">';

						item += '<img src=" ' + IP + content.img + '" class="item-img b-lazy b-lazy-ani b-loaded">';

						item += '<div class="buying-info">';
						item += '	<div class="angle-container"></div>';
						item += '<p class="users"><span class="dot"></span>6127人已抢</p>';
						item += '	<div class="dot"></div>';
						item += '	</div>';

						item += '</div>';
						item += '<div class="detail-container">';
						item += '<div class="group-title">';

						item += '<span class="seller-labels">爆款直降</span>';

						item += '<span class="seller-labels">母婴可用</span>';

						item += '<span class="title">';
						item += ' ' + content.title + '</span>';
						item += '	</div>';
						item += '</div>';

						item += '<div class="group-banner">';
						item += '<div class="group-price">';
						item += '	<div class="group-price-inner">';
						item += '	<span class="rmb">¥</span>';
						item += '<span class="price-int">27</span>';
						item += '<span class="price-dec">.9</span>';

						item += '<span class="price-ori">';
						item += '	' + content.price + '';
						item += '</span>';
						item += '	</div>';
						item += '	</div>';
						item += '<div class="list-group-btns have-avatars"STYLE="display:none" >';
						item += '	<div class="list-avatars">';

						item += '	<img class="avatar b-lazy b-lazy-ani b-loaded" src="https://b3.hucdn.com/upload/face/1512/30/a206a306028a2005f4e0203574e88313.jpg!60x60.webp">';

						item += '</div>';
						item += '<div class="list-group-btn" >';
						item += '	<span>去开团</span><i class="iconfont"></i>';
						item += '	</div>';
						item += '	</div>';
						item += '	</div>';
						item += '	</a>';
						item += '	</li>';

						$('.container-list').append(item);

					});

					$(".field").on('click', function() {
						localStorage.setItem('shop_id', $(this).attr('id'));
						localStorage.setItem('title', $(this).attr('name'));
						location.href = 'seller_show.html'
					});
				}
				//lang()
			}

		},
		error: function(xhr, type, errorThrown) {
			//异常处理；
			console.log(type);
		}
	});

}

function get_slider() {

	var info = [{
		img: '../images/slides/2018071915382047518.png'
	}, {
		img: '../images/slides/5b4f153dN77b5d17e.jpg'
	}, {
		img: '../images/slides/2018071915391516609.png'
	}]
	$('.category ul').html('');
	$.each(info, function(index, content) {
		var item = '	 <div class="swiper-slide"> ';
		item += '	<a href="javascript:void(0)"  >';
		item += '	<img src="' + content.img + '" /></a></div>';

		$('.slider').append(item);

	});
	var slider1 = {
		img: '../images/slides/4.png'
	};
	$('#swiper-img').attr('src', slider1.img);
	$('#swiper-img').css('width', '100%');
	$('#swiper-img').css('margin-top', '-24px');
	$('#swiper-img').css('z-index', '10000');
	//				info = data.slider1;
	//				$.each(info, function(index, content) {
	//					var item = '	 <div class="swiper-slide"> ';
	//					item += '	<a href="javascript:void(0)"  >';
	//					item += '	<img src="' + IP + content.img + '" /></a></div>';
	//
	//					$('.slider1').append(item);
	//
	//				});

	info = [{
		img: '../images/slides/5.png'
	}, {
		img: '../images/slides/6.png'
	}, {
		img: '../images/slides/7.png'
	}, {
		img: '../images/slides/8.png'
	}, {
		img: '../images/slides/9.png'
	}]

	$.each(info, function(index, content) {
		var item = '	 <div class="swiper-slide"> ';
		item += '	<a href="javascript:void(0)"  >';
		item += '	<img src="' + content.img + '" /></a></div>';

		$('.slider2').append(item);

	});
	slider1 = {
		img: '../images/slides/4.png'
	};
	//	$('#swiper-img1').attr('src',   slider1.img);
	//	$('#swiper-img1').css('width', '100%');
	//	$('#swiper-img1').css('margin-top', '-24px');
	//	$('#swiper-img1').css('z-index', '10000');

	var mySwiper = new Swiper('.swiper-container', {
		calculateHeight: true,
		resizeReInit: true,
		pagination: ".pagination",
		autoplay: 5000,
		paginationClickable: true,
		autoplayDisableOnInteraction: false
	});

	//	$.ajax( {
	//		url:get_good_list,
	//		data: {
	//			is_mobile: 1,
	//			where: '   '
	//		},
	//		dataType: 'json', //服务器返回json格式数据
	//		type: 'post', //HTTP请求类型
	//		timeout: 10000, //超时时间设置为10秒；
	//
	//		success: function(data) {
	//			var status = data.status;
	//			if(status == 1) {
	//				var info = data.slider;
	//				
	//			}
	//
	//		},
	//		error: function(xhr, type, errorThrown) {
	//			//异常处理；
	//			console.log(type);
	//		}
	//	});

}

//获取拼单专区商品
function get_goods_detail_list(page_index, page_num, successCallback) {
	$.ajax(goods_detail_list, {
		data: {
			is_mobile: 1,
			where: '   ',
			user_id: localStorage.getItem('user_id')
		},
		dataType: 'json', //服务器返回json格式数据
		type: 'post', //HTTP请求类型
		timeout: 10000, //超时时间设置为10秒；

		success: function(data) {
			//			localStorage.setItem('keyword', '')
			data = JSON.stringify(data);
			localStorage.setItem('ListData', data)
			data = JSON.parse(data);

			successCallback && successCallback(data);
		},
		error: function(xhr, type, errorThrown) {
			//异常处理；
			console.log(type);
		}
	});

}

//获取抢购专区商品
function get_hot_goods(is_hot) {
	$.ajax(hot_goods_list, {
		data: {
			is_mobile: 1,
			user_id: localStorage.getItem('user_id'),
			category_id: localStorage.getItem('category_id'),
			is_hot: is_hot
		},
		dataType: 'json', //服务器返回json格式数据
		type: 'post', //HTTP请求类型
		timeout: 10000, //超时时间设置为10秒；

		success: function(data) {
			remove_toast()
			var status = data.status;
			if(status == 1) {
				$('#biding .nodata-default').css('display', 'none');
				var info = data.data;
				//$('.img-list ul').html('');
				if(info == null) {
					$('#biding .nodata-default').css('display', 'block');

				}
				if(info != null) {
					$.each(info, function(index, content) {
						//onclick="showActionSheet(\'buy\',' + content.id + ',' + content.price + ',\'' + content.img + '\',\'' + content.title + '\',' + content.hot_id + ')" 
						var item = '	<div class="goods     goods_item  "    name="' + content.title + '" id="' + content.id + '" hot_id="' + content.hot_id + '">';
						item += '		<a class="add_focus" style="display:none">收藏</a>';
						item += '		<a class="cover"><img src="' + IP + content.img + '"  > </a>';
						//						item += '	<div class="timeline tip">00:00:04</div>';
						item += '	<div class="price_wrap tip" style="    margin-top: 20px;">';
						item += '		<div class="price_cur tipout">￥ ' + content.price + '</div>';
						item += '	</div>';
						item += '	<div class="price_tip hidelong" style="margin-top: 0px;" >' + content.title + '</div>';
						//						item += '		<a class="bid_btn">参与抢购</a>';
						item += '		</div>';

						$('#biding').append(item);

					});

					$.each(info, function(index, content) {

						var item = '<li class="similar-li   goods_item"   name="' + content.title + '" id="' + content.id + '"hot_id="' + content.hot_id + '">';

						item += '	<div class="similar-product">';
						item += '	<div class="similar-posre"><img class="Jschangewidth opa1"   src="' + IP + content.img + '" style=" height: 178px; opacity: 1;"></div><span class="similar-product-text"> ' + content.title + '</span>';
						item += '	<p class="similar-product-info"><span class="similar-product-price">¥&nbsp;<span class="big-price">' + content.price + '</span> </span>';
						item += '	</p>';
						item += '	<p class="praise-info"><span class="praise-num"><span>好评率98%</span></span><span class="guess-button" data-eventparam="1_19679010263_1" page_name="index" data-href="//home.m.jd.com/myjd/similar/list.action?skuId=19679010263" onclick="likeSimilarities(this)">抢购</span></p>';
						item += '		</div>';

						item += '		<div class="recommend-ad-mark"></div>';
						item += '	</li>';

						$('#recommendList').append(item);

					});

					$(".goods_item").on('click', function() {
						localStorage.setItem('goods_id', $(this).attr('id'));
						localStorage.setItem('hot_id', $(this).attr('hot_id'));
						localStorage.setItem('title', $(this).attr('name'));

						openWindowWithTitle($(this).attr('name'), 'product_show.html')
					});
					//清空搜索
					$("#searchland8").on('click', function() {

					});
				}
				//lang()
			}

		},
		error: function(xhr, type, errorThrown) {
			//异常处理；
			console.log(type);
		}
	});

}

function get_new_goods(page_index, page_num, successCallback) {
	console.log(localStorage.getItem('category_id'))
	console.log(localStorage.getItem('keyword'))
	$.ajax(get_good_list, {
		data: {
			is_mobile: 1,
			goods_type: localStorage.getItem('goods_type'),
			is_hot: localStorage.getItem('is_hot'),
			where: ' ',
			order: localStorage.getItem('order'),
			keyword: localStorage.getItem('keyword'),
			page_index: page_index,
			page_num: page_num,
			category_id: localStorage.getItem('category_id'),
			lat: localStorage.getItem('lat'),
			lng: localStorage.getItem('lng'),
			user_id: localStorage.getItem('user_id')
		},
		dataType: 'json', //服务器返回json格式数据
		type: 'post', //HTTP请求类型
		timeout: 10000, //超时时间设置为10秒；

		success: function(data) {
			//			localStorage.setItem('keyword', '')
			data = JSON.stringify(data);
			localStorage.setItem('ListData', data)
			data = JSON.parse(data);

			successCallback && successCallback(data);
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

function get_goods_show(init_view) {
	var id = localStorage.getItem('goods_id');
	$.ajax(goods_edit, {
		data: {
			is_mobile: 1,
			url: 'http://' + localStorage.getItem('ServerIP'),
			user_id:  localStorage.getItem('user_id'),
			func: 'show',
			id: id
		},
		dataType: 'json', //服务器返回json格式数据
		type: 'post', //HTTP请求类型
		timeout: 10000, //超时时间设置为10秒；  
		success: function(data) {
			var status = data.status;
			if(status == 1) {   
				var info = data.data;
 
				$('#commodityArticleId').val(id)
				$('.swiper-container').css('height', $('body').height() / 0.5);
				$('.swiper-wrapper').html('');
				var height = $('.swiper-container').height();
				var goods_albums = info.goods_albums;
				//				if(goods_albums != null) {
				//					if(goods_albums.length > 1) {
				//						$.each(goods_albums, function(index, content) {
				//							var item = '	 <div class="swiper-slide"> ';
				//							item += '	<img src="' + IP + content.original_path + '"  style="height:' + height + 'px"/> </div>';
				//							if(index > 0) {
				//								$('.swiper-wrapper').append(item);
				//							}
				//						});
				//					}
				//				}

				var item = '	 <div class="swiper-slide"> ';
				item += '	<img src="' + IP + info.img1 + '"  style="height:' + height + 'px"/> </div>';
				if(info.img1 != '') {
					$('.swiper-wrapper').append(item);
				}
				item = '	 <div class="swiper-slide"> ';
				item += '	<img src="' + IP + info.img2 + '"  style="height:' + height + 'px"/> </div>';
				if(info.img2 != '') {
					$('.swiper-wrapper').append(item);
				}
				item = '	 <div class="swiper-slide"> ';
				item += '	<img src="' + IP + info.img3 + '"  style="height:' + height + 'px"/> </div>';
				if(info.img3 != '') {
					$('.swiper-wrapper').append(item);
				}
				var mySwiper = new Swiper('.swiper-container', {
					calculateHeight: true,
					resizeReInit: true,
					pagination: ".pagination",
					autoplay: 5000,
					paginationClickable: true
				});
				$('.main_title').html(info.title)
				$('.goods-head h2').html(info.title)
				$('.current').html('剩余量:' + info.remain_crowd_price)
				$('.market span').html(info.market_price)
				$('.time_line').html('市场价' + info.market_price)
				//				$('.sub_btn em').html(info.every_crowd_price + 'apat')
				$('.goods-head .price').html(info.price)
				$('.btns .price').html('¥' + info.price)

				$('.btns .crowd_price').html('¥' + info.crowd_price)
				$('#commodityGoodsNo').html(info.goods_no)
				$('#commoditySelectNum').attr('maxValue', info.stock)
				$('#commodityMarketPrice').html(info.market_price)
				$('#commodityStockNum').html(info.stock)
				$('#commodityHot_id').val(localStorage.getItem('hot_id'))
				$('#img_url').val(info.img)
				$('#title').val(info.title)
				$('#price').val(info.price)

				$('.price').val('¥' + info.price)
				$('.price').html('¥' + info.price)
				//				$('.fan').html('返现金额:' + info.agent_use)
				$('.pin_num').html('' + info.pinstr)
				$('.pin_goods_num').html('' + info.pin_goods_num)
				$('#add_time').html('上架时间：' + info.addtime_str)

				//				var content = decodeURIComponent(info.content)
				var str = info.content;

				var reg = /src="/g;

				str = str.replace(reg, 'src="' + IP);


				$('#J-bidMore em').html(info.goods_crowd_users_count + '条')
				$('#crowd_price').val(info.every_crowd_price);
				$('.crowd_price').html('¥' + info.crowd_price);
				$('.comment_num').html('' + info.comment_num);
				//规格
				var spec = data.data.goods_spec;
				if(spec != null) {
					$.each(spec, function(index, content) {

						var item = '	<dl> <dt>' + content.title + '</dt><dd>';
						$.each(spec, function(index1, content1) {
							if(content1.parent_id == content.spec_id) {
								item += '<a specid="' + content1.spec_id + '" title="' + content1.title + '" href="javascript:;">';
								item += '			<span>' + content1.title + '</span>';
								item += '		</a>';
							}
						});
						item += '	<dd><!--规格选项--><!--/规格选项--></dd></dl>';
						if(content.parent_id == 0) {
							$('.btn-box').before(item);
						}
					});
				}

				//规格
				var goods_crowd_users = data.data.goods_crowd_users;
				if(goods_crowd_users != null) {
					$.each(goods_crowd_users, function(index, content) {
						var mphone = content.user_name.substr(0, 3) + '****' + content.user_name.substr(7);

						var item = '';
						item += '<li><em class="nickname hidelong"><i class="icon-mobile-s"></i>' + mphone + '</em><em class="hidelong" style="width:60PX">' + content.percent + '%</em><em class="area hidelong">' + content.area + '</em><em>' + content.crowd_price * content.num + ' ' + content.agent_use + '</em></li>';

						$('#J-bidList .list').append(item);

					});
				}

				if(data.data.crowd_status == 0) {
					$('#J-subBtn').html('拼单已满');
					$('#J-subBtn').css('background', '#999');
					$('#J-subBtn').removeAttr('onclick');
				}

				init_view && init_view(data);

			}

		},
		error: function(xhr, type, errorThrown) {
			//异常处理；
			console.log(type);
		}
	});

}

function get_seller_show() {
	var id = localStorage.getItem('shop_id');
	$.ajax(seller_edit, {
		data: {
			is_mobile: 1,
			id: id
		},
		dataType: 'json', //服务器返回json格式数据
		type: 'post', //HTTP请求类型
		timeout: 10000, //超时时间设置为10秒；  
		success: function(data) {
			var status = data.status;
			if(status == 1) {
				var info = data.data;
				$('.swiper-container').css('height', $('body').height() / 3);
				$('.swiper-wrapper').html('');
				var height = $('.swiper-container').height();

				var seller_albums = info.seller_albums;
				if(seller_albums != null) {
					if(seller_albums.length > 1) {
						$.each(seller_albums, function(index, content) {
							var item = '	 <div class="swiper-slide"> ';
							item += '	<img src="' + IP + content.original_path + '"  style="height:' + height + 'px"/> </div>';
							if(index > 0) {
								$('.swiper-wrapper').append(item);
							}
						});
					}
				}

				var mySwiper = new Swiper('.swiper-container', {
					calculateHeight: true,
					resizeReInit: true,
					pagination: ".pagination",
					autoplay: 5000,
					paginationClickable: true
				});
				$('.main_title').html(info.title)
				$('.goods-head h2').html(info.title)
				$('.current').html('剩余量:' + info.remain_crowd_price)
				$('.market span').html(info.market_price)
				$('.time_line').html('市场价' + info.market_price)
				//				$('.sub_btn em').html(info.every_crowd_price + 'apat')
				$('.goods-head .price').html(info.price)
				if(info.lng != null) {
					$('.map_btn').attr('url', 'https://m.amap.com/navi/?dest=' + info.lng + ',' + info.lat + '&destName=' + info.title + '&hideRouteIcon=1&key=' + localStorage.getItem('gd_address'))
					$('.map_btn').click(function() {
						window.open($(this).attr('url'))

					})
				}

				$('#commodityGoodsNo').html(info.goods_no)
				$('#commodityArticleId').val(info.id)
				$('#commoditySelectNum').attr('maxValue', info.stock)
				$('#commodityMarketPrice').html(info.market_price)
				$('#commodityStockNum').html(info.stock)
				$('#commodityHot_id').val(localStorage.getItem('hot_id'))
				$('#img_url').val(info.img)
				$('#title').val(info.title)
				$('#price').html(info.price)
				$('.address').html(info.address)
				$('#add_time').html('上架时间：' + info.addtime_str)
				$('.entry').html((info.content))
				$('#J-bidMore em').html(info.goods_crowd_users_count + '条')
				$('#crowd_price').val(info.every_crowd_price);
				$('.crowd_price').html(info.crowd_price);
				$('.phone').attr('href', 'tel:+' + info.phone)

				if(info.goods != null) {
					$('#itemList').css('display', 'block')
					$('.s_null').css('display', 'none')
					$.each(info.goods, function(index, content) {

						var item = '<div class="search_prolist cols_1" id="itemList" goods_id="' + content.id + '" title="' + content.title + '" >';
						item += '<div class="search_prolist_item" skuid="5256855" cid="670~699~700" rd="0-4-1">';
						item += '	<div class="search_prolist_item_inner J_ping" id="link_5256855" lb="0" ind="1" page="1" rd="0-4-1" pos="1" vid="1000004123" tourl="//wq.jd.com/item/view?sku=5256855&amp;price=109.00&amp;fs=1" csid="5a1fc1b243afa01f0e52e9826c7a68e1_1532399105480_1_1532399105480" ss_projid="0" ss_expid="0" ss_ruleid="0" ss_sexpid="0" ss_sruleid="0" symbol="25" ifad="1" ver="2" eventid="135" report-eventid="MList_Product" report-eventlevel="4" report-eventparam="0_670_699_700_5256855___0_1_0_1_____2_0_0_##__135_NA5256855_1870382429_0_0_0_0_0_0_0_1_0_0_0_1____0_">';
						item += '	<div class="search_prolist_cover" rd="0-4-1">';
						item += '	<img class="photo" rd="0-4-1" src="' + IP + content.img + '" onload="window._PFM_TIMING[4]=new Date();" init_src="jfs/t10024/200/279949351/35206/4363fc1e/59cb0455N3838e1df.jpg" inited="1" skuid="5256855" lpmark="0" onerror="__reloadResource(this)">';
						item += '<div class="mod_good_decoration_v3"><img class="mod_good_decoration_bg_big" src="//img30.360buyimg.com/jgsq-productsoa/jfs/t14332/56/2342045055/8025/c40894c7/5a990af1N2719ee6f.png"><img class="mod_good_decoration_bg_small" src="//img30.360buyimg.com/jgsq-productsoa/jfs/t18370/238/573389987/5920/39b8d114/5a990afdNe1820d1f.png">';
						item += '	<div class="mod_good_decoration_belt" st="1532361600000" et="1532620799000">';
						item += '	<div class="mod_good_decoration_belt_text type_tiny" strlen="19" fsize1="11" fsize2="7" style="text-align: center; font-size: 7px;">自营<span mark="ydtime"></span></div>';
						item += '</div>';
						item += '</div>';
						item += '</div>';
						item += '<div class="search_prolist_info" rd="0-4-1">';
						item += '<div class="search_prolist_title" data-line="2" rd="0-4-1">';
						item += '	' + content.title + '</div>';
						item += '<div class="search_prolist_attr"> <span class="word">家用路由</span> <span class="word">1200M</span> <span class="word">无线路由器</span>';
						item += '	<div></div>';
						item += '</div>';
						item += '<div class="search_prolist_price" id="price_5256855" rd="0-4-1">';
						item += '<strong rd="0-4-1">';
						item += '    <em id="dp_J_5256855" rd="0-4-1" pri="109.00">¥ <span class="int">' + content.price + '</span>.00</em>';
						item += '  </strong>';
						item += '	</div>';
						item += '	<div class="search_prolist_line" rd="0-4-1" id="nprice_5256855" pro="1" can-fill="1">';
						item += '	<i class="mod_tag"><img src="../images/599cf3f5N7dbe95e5.png"></i></div>';
						item += '	<div class="search_prolist_other text_small" rd="0-4-1" id="comtag_5256855"><i class="mod_tag" rd="0-4-1"><img src="../images/599cf545Na1f3ceb4.png"></i>';
						item += '	<span class="search_prolist_comment" rd="0-4-1"><span id="com_5256855" rd="0-4-1">139万</span>条评价</span>';
						item += '	<span class="search_prolist_rate" rd="0-4-1">好评率<span id="rate_5256855">98</span>%</span>';
						item += '	</div>';
						item += '<div class="search_prolist_other text_small hide" rd="0-4-1" id="bomtag_5256855"></div>';
						item += '	</div>';
						item += '	</div>';
						item += '</div>';

						item += '<div class="search_placeholder"></div>';
						item += '<div class="search_interlude hide" after="20">';
						item += '	<div class="search_keywordlist type_list hide" id="hotTagList2"></div>';
						item += '</div>';
						item += '<div class="search_placeholder"></div>';
						item += '	</div>';

						$('.goodlist').prepend(item);
					});

					$(".goodlist #itemList").on('click', function() {
						localStorage.setItem('goods_id', $(this).attr('goods_id'));
						localStorage.setItem('goods_title', $(this).attr('title'));
						location.href = 'seller_goods_show.html';
					})

				} else {
					$('#itemList').css('display', 'none')
					$('.s_null').css('display', 'block')
				}

			}

		},
		error: function(xhr, type, errorThrown) {
			//异常处理；
			console.log(type);
		}
	});

}

function get_seller_goods_show() {
	var id = localStorage.getItem('goods_id');
	$.ajax(goods_edit, {
		data: {
			is_mobile: 1,
			id: id,
			user_id: localStorage.getItem('user_id')
		},
		dataType: 'json', //服务器返回json格式数据
		type: 'post', //HTTP请求类型
		timeout: 10000, //超时时间设置为10秒；  
		success: function(data) {
			var status = data.status;
			if(status == 1) {
				var info = data.data;
				$('.swiper-container').css('height', $('body').height() / 3);
				$('.swiper-wrapper').html('');
				var height = $('.swiper-container').height();

				var goods_albums = info.goods_albums;
				if(goods_albums != null) {
					if(goods_albums.length > 1) {
						$.each(goods_albums, function(index, content) {
							var item = '	 <div class="swiper-slide"> ';
							item += '	<img src="' + IP + content.original_path + '"  style="height:' + height + 'px"/> </div>';
							if(index > 0) {
								$('.swiper-wrapper').append(item);
							}
						});
					}
				}
				var mySwiper = new Swiper('.swiper-container', {
					calculateHeight: true,
					resizeReInit: true,
					pagination: ".pagination",
					autoplay: 5000,
					paginationClickable: true
				});
				$('.main_title').html(info.title)
				$('.goods-head h2').html(info.title)
				$('.current').html('剩余量:' + info.remain_crowd_price)
				$('.market span').html(info.market_price)
				$('.time_line').html('市场价' + info.market_price)
				//				$('.sub_btn em').html(info.every_crowd_price + 'apat')
				$('.goods-head .price').html(info.price)
				if(info.lng != null) {
					$('.map_btn').attr('url', 'https://m.amap.com/navi/?dest=' + info.lng + ',' + info.lat + '&destName=' + info.title + '&hideRouteIcon=1&key=' + localStorage.getItem('gd_address'))
					$('.map_btn').click(function() {
						window.open($(this).attr('url'))

					})
				}

				$('#commodityGoodsNo').html(info.goods_no)
				$('#commodityArticleId').val(info.id)
				$('#commoditySelectNum').attr('maxValue', info.stock)
				$('#commodityMarketPrice').html(info.market_price)
				$('#commodityStockNum').html(info.stock)
				//				$('#commodityHot_id').val(localStorage.getItem('hot_id'))
				$('#img_url').val(info.img)
				$('#title').val(info.title)
				$('#price').html(info.price)
				$('.address').html(info.address)
				$('#add_time').html('上架时间：' + info.addtime_str)
				$('.entry').html((info.content))
				$('#J-bidMore em').html(info.goods_crowd_users_count + '条')
				$('#crowd_price').val(info.every_crowd_price);
				$('.crowd_price').html(info.crowd_price);

			}

		},
		error: function(xhr, type, errorThrown) {
			//异常处理；
			console.log(type);
		}
	});

}

function get_main_category_list(top) {

	$.ajax(get_main_category_list_val, {
		data: {
			is_mobile: 1,
			top: top,
			is_hot: 1,
			parent_id: 0,
			user_id: localStorage.getItem('user_id')
		},
		dataType: 'json', //服务器返回json格式数据
		type: 'POST', //HTTP请求类型
		timeout: 10000, //超时时间设置为10秒；

		success: function(data) {
			var status = data.status;
			if(status == 1) {
				var info = data.data;
				var hotlist = data.hotlist;

				if(hotlist != null) {

					$.each(info, function(index, content) {

						var item = ' <a href="javascript:;" class="weui-grid category_info" id="' + content.id + '" name="' + content.title + '" style="width:20%; padding: 0 0 ;">';
						item += '  <div class="weui-grid__icon" style="padding: .2REM;">';
						item += ' <img src="' + IP + content.img_url + '" /> ';

						//item += '<i class="iconfont icon-news" style="background: url('+IP+content.img_url+')" ></i>';
						item += '</div>';
						item += '<p class="weui-grid__label" style="font-size:.2REM;margin-bottom: 0.1rem;margin-top: 0rem; ">' + content.title + '</p>';
						item += '</a>';

						$('.nav-list').prepend(item);
					});
					$.each(hotlist, function(index, content) {

						var item = '<a class="quick-entry-link fz12 J_ping" id="' + content.id + '" report-eventlevel="1" report-eventid="MHome_BIcons" report-eventparam="京东超市" page_name="index"  >';
						item += '	<span class="quick-box"><img width="45" height="45" src="' + IP + content.user_img_url + '"><span style="color:#000000">' + content.category_title + '</span></span>';
						item += '</a>';

						$('.quick-entry-nav').prepend(item);
					});
					$.each(hotlist, function(index, content) {

						var item = '<div class="gray-text"><span class="gray-layout"><span class="gray-text-img"></span>' + content.category_title + '</span></div>';
						item += '<ul class="find-similar-ul cf" data-floor="Mhome_BRecommend" id="recommendList">';
						if(content.goods != null) {
							$.each(content.goods, function(index1, content1) {

								item += '<li class="similar-li   goods_item"   name="' + content1.title + '" id="' + content1.id + '"hot_id="' + content1.hot_id + '">';

								item += '	<div class="similar-product">';
								item += '	<div class="similar-posre"><img class="Jschangewidth opa1"   src="' + IP + content1.img + '" style=" height: 178px; opacity: 1;"></div><span class="similar-product-text"> ' + content1.title + '</span>';
								item += '	<p class="similar-product-info"><span class="similar-product-price">¥&nbsp;<span class="big-price">' + content1.price + '</span> </span>';
								item += '	</p>';
								item += '	<p class="praise-info"><span class="praise-num"><span>好评率98%</span></span><span class="guess-button" data-eventparam="1_19679010263_1" page_name="index" data-href="//home.m.jd.com/myjd/similar/list.action?skuId=19679010263" onclick="likeSimilarities(this)">抢购</span></p>';
								item += '		</div>';

								item += '		<div class="recommend-ad-mark"></div>';
								item += '	</li>';

							});
						}
						item += '</ul>';

						$('#recommend').prepend(item);
						$(".goods_item").on('click', function() {
							localStorage.setItem('goods_id', $(this).attr('id'));
							localStorage.setItem('hot_id', $(this).attr('hot_id'));
							localStorage.setItem('title', $(this).attr('name'));

							openWindowWithTitle($(this).attr('name'), 'product_show.html')
						});
					});

					$.each(info, function(index, content) {
						var item = '<li class="" m_index="' + index + '" is_hot="0" id="' + content.id + '" id="category6" commonused="false">';
						item += '<a class="J_ping" report-eventparam="2006_home" report-eventid="MCategory_1st" report-eventlevel="2" ptag="137886.1.10" href="javascript:void(0);">' + content.title + '</a>';
						item += '	</li>';
						if(content.parent_id == 0) {
							$('#category2').append(item);
						}
					});

					$.each(info, function(index, content) {

						var item = ' <a href="javascript:void(0)" data-href="list27" cate_id="' + content.id + '" onclick="get_shop_goods_list(' + content.id + ',3)" >' + content.title + '</a>';

						$('.ui-tags-tit').append(item);
					});
					$(".category_info").on('click', function() {
						localStorage.setItem('category_id', $(this).attr('id'));
						localStorage.setItem('category_title', $(this).attr('name'));
						openWindowWithTitle($(this).attr('name'), 'goods_list.html')
					})
					$('.ui-tags-tit a').click(function() {
						$('.ui-tags-tit a').removeClass('active')
						$(this).addClass('active')

					})

					$('.quick-entry-nav a').click(function() {

						localStorage.setItem('keyword', '');
						localStorage.setItem('s_category_id', $(this).attr('id'));
						openWindowWithTitle('搜索', 'search.html')
					})
					$('#category2 li').click(function() {
						$('#category2 li').removeClass('cur')
						$(this).addClass('cur')
						var parent_id = $(this).attr('id')
						var is_hot = $(this).attr('is_hot')
						get_child_category_list(parent_id, is_hot);
					})
					var nav_height = $('.quick-entry-nav').height();

					$('.quick-nav-box').css('min-height', nav_height)

					get_child_category_list(0, 1);

					$('.recent-search-tags span').remove();
					$('.recent-search').css('display', 'none');
					var keyword_list = data.keyword_list;

					if(keyword_list != null) {
						$('.recent-search').css('display', 'block');
						$.each(keyword_list, function(index, content) {
							if(index < 10) {
								var item = '<span><a id="searchland_searchHistoryList_0" searchland_index="0" href="javascript:void(0);">' + content.keywords + '</a></span>';

								$('.recent-search-tags').append(item);
							}
						});
					}

				}

			}

		},
		error: function(xhr, type, errorThrown) {
			//异常处理；
			console.log(type);
		}
	});

}

function get_category_list(top, successCallback) {

	$.ajax(get_category_list_val, {
		data: {
			is_mobile: 1,
			top: top,
			parent_id: 0,
			user_id: localStorage.getItem('user_id')
		},
		dataType: 'json', //服务器返回json格式数据
		type: 'POST', //HTTP请求类型
		timeout: 10000, //超时时间设置为10秒；

		success: function(data) {
			var status = data.status;
			if(status == 1) {
				var info = data.data;

				if(info != null) {

					$.each(info, function(index, content) {

						var item = ' <a href="javascript:;" class="weui-grid category_info" id="' + content.id + '" name="' + content.category_title + '" style="width:20%; padding: 0 0 ;">';
						item += '  <div class="weui-grid__icon" style="padding: .2REM;">';
						item += ' <img src="' + IP + content.img_url + '" /> ';

						//item += '<i class="iconfont icon-news" style="background: url('+IP+content.img_url+')" ></i>';
						item += '</div>';
						item += '<p class="weui-grid__label" style="font-size:.2REM;margin-bottom: 0.1rem;margin-top: 0rem; ">' + content.category_title + '</p>';
						item += '</a>';

						$('.nav-list').prepend(item);
					});
					$.each(info, function(index, content) {

						var item = '<a class="quick-entry-link fz12 J_ping" report-eventlevel="1" report-eventid="MHome_BIcons" report-eventparam="京东超市" page_name="index" href="javacript:void(0);">';
						item += '	<span class="quick-box"><img width="45" height="45" src="' + IP + content.img_url + '"><span style="color:#000000">' + content.category_title + '</span></span>';
						item += '</a>';

						$('.quick-entry-nav').prepend(item);
					});
					$.each(info, function(index, content) {
						var item = '<li class="" m_index="' + index + '" is_hot="0" id="' + content.id + '" id="category6" commonused="false">';
						item += '<a class="J_ping" report-eventparam="2006_home" report-eventid="MCategory_1st" report-eventlevel="2" ptag="137886.1.10" href="javascript:void(0);">' + content.category_title + '</a>';
						item += '	</li>';
						if(content.parent_id == 0) {
							$('#category2').append(item);
						}
					});

					$.each(info, function(index, content) {

						var item = ' <a href="javascript:void(0)" data-href="list27" cate_id="' + content.id + '" onclick="get_shop_goods_list(' + content.id + ',3)" >' + content.category_title + '</a>';

						$('.ui-tags-tit').append(item);
					});
					$(".category_info").on('click', function() {
						localStorage.setItem('category_id', $(this).attr('id'));
						localStorage.setItem('category_title', $(this).attr('name'));
						openWindowWithTitle($(this).attr('name'), 'goods_list.html')
					})
					$('.ui-tags-tit a').click(function() {
						$('.ui-tags-tit a').removeClass('active')
						$(this).addClass('active')

					})

					$('#category2 li').click(function() {
						$('#category2 li').removeClass('cur')
						$(this).addClass('cur')
						var parent_id = $(this).attr('id')
						var is_hot = $(this).attr('is_hot')
						get_child_category_list(parent_id, is_hot);
					})
					var head_height = $('#m_common_header').height();
					var bottom_height = $('#commonNav').height();
					var cate_height = $('body').height() - head_height - bottom_height;
					$('#category3').css('height', cate_height)

					//					get_child_category_list(0, 1);
				}

			}
			data = JSON.stringify(data);
//			localStorage.setItem('ListData', data)
			data = JSON.parse(data);

			successCallback && successCallback(data);
		},
		error: function(xhr, type, errorThrown) {
			//异常处理；
			console.log(type);
		}
	});

}

function get_child_category_list(parent_id, is_hot, successCallback) {

	$.ajax(get_child_category_list_val, {
		data: {
			is_mobile: 1,
			parent_id: parent_id,
			is_hot: is_hot,
			user_id: localStorage.getItem('user_id')
		},
		dataType: 'json', //服务器返回json格式数据
		type: 'POST', //HTTP请求类型
		timeout: 10000, //超时时间设置为10秒；

		success: function(data) {

			data = JSON.stringify(data);
			localStorage.setItem('ListData', data)
			data = JSON.parse(data);

			successCallback && successCallback(data);

		},
		error: function(xhr, type, errorThrown) {
			//异常处理；
			console.log(type);
		}
	});

}

function get_shop_category_list(top) {

	$.ajax({
		url: get_shop_category_list_val,
		data: {
			is_mobile: 1,
			top: top,
			is_hot: 1,
			parent_id: 0,
			user_id: localStorage.getItem('user_id')
		},
		dataType: 'json', //服务器返回json格式数据
		type: 'POST', //HTTP请求类型
		timeout: 10000, //超时时间设置为10秒；

		success: function(data) {
			var status = data.status;
			if(status == 1) {
				var info = data.data;

				if(info != null) {
					$('.main_category li').remove();
					$.each(info, function(index, content) {

						var item = ' <li href="javascript:void(0)" ><img src="' + IP + content.img_url + '"><span>' + content.title + '</span></li>';

						//						if(content.parent_id == 0) {
						$('.main_category').prepend(item);
						//						}
					});
					//					$('.ui-tags-ct').css('margin-top',$('.main_category').height())
					var count = 0;
					$.each(info, function(index, content) {

						var item = ' <div href="javascript:void(0)" ><img style="" src="' + IP + content.img_url + '"><span>' + content.title + '</span></div>';

						if(content.parent_id != 0 && count < 5) {
							//							$('.child_category1').prepend(item);
							count++;
						}
						if(content.parent_id != 0 && count < 10 && count >= 5) {
							//							$('.child_category2').prepend(item);
							count++;
						}
					});
					$('.main_category li').click(function() {

						location.href = 'shop_goods_list.html';
					})
				}

			}

		},
		error: function(xhr, type, errorThrown) {
			//异常处理；
			console.log(type);
		}
	});

}
//获取商品列表
function get_goods_list(page_index, page_num, successCallback) {

	var keyword = localStorage.getItem('keyword')

	$.ajax({
		url: get_good_list,
		data: {
			is_mobile: 1,
			category_id: 0,
			page_index: page_index,
			page_num: page_num,
			user_id: localStorage.getItem('user_id'),
			keyword: keyword
		},
		dataType: 'json', //服务器返回json格式数据
		type: 'post', //HTTP请求类型
		timeout: 10000, //超时时间设置为10秒；

		success: function(data) {
			console.log(data)
			data = JSON.stringify(data);
			localStorage.setItem('ListData', data)
			data = JSON.parse(data);

			successCallback && successCallback(data);

		},
		error: function(xhr, type, errorThrown) {
			//异常处理；
			console.log(type);
		}
	});

}

function get_shop_goods_list(cate_id, type) {
	$.ajax({
		url: shop_goods_list,
		data: {
			is_mobile: 1,
			category_id: cate_id,
			city: localStorage.getItem('select_city'),
			type: type
		},
		dataType: 'json', //服务器返回json格式数据
		type: 'post', //HTTP请求类型
		timeout: 10000, //超时时间设置为10秒；

		success: function(data) {
			remove_toast()
			var status = data.status;
			if(status == 1) {
				$('#J-mainCt .nodata-default').css('display', 'none');
				$('.ui-tags-ct li').remove();
				var info = data.data;
				//$('.img-list ul').html('');
				if(info == null) {
					$('#J-mainCt .nodata-default').css('display', 'block');

				}
				if(info != null) {
					$.each(info, function(index, content) {

						var item = '	<li style="display:block" id="list0"  onclick="showActionSheet(\'buy\',' + content.id + ',' + content.price + ',\'' + content.img + '\',\'' + content.title + '\',' + 0 + ')">';
						item += '<div class="goods /*ui-mark-10*/" id="period3798594_list0" data-pid="3798594" data-gid="636">';
						item += '<a class="cover"><img src="' + IP + content.img + '"></a>';
						item += '	<div class="title">' + content.title + '</div>';
						item += '<div class="price_wrap">';
						item += '	<div class="price_cur tipout">￥ ' + content.price + '</div>';
						item += '</div>';
						item += '<a class="bid_btn"></a>';
						item += '</div>';

						item += '</li>';

						$('.ui-tags-ct').append(item);

					});
				}
				//lang()
			}

		},
		error: function(xhr, type, errorThrown) {
			//异常处理；
			console.log(type);
		}
	});

}
var Goods_Search_List_val = PreUrl + "Goods/Goods_Search_List";

function Goods_Search_List(successCallback) {
	$.ajax({
		url: Goods_Search_List_val,
		data: {
			is_mobile: 1
		},
		dataType: 'json', //服务器返回json格式数据
		type: 'post', //HTTP请求类型
		timeout: 10000, //超时时间设置为10秒；

		success: function(data) {
			console.log(data)
			data = JSON.stringify(data);
			localStorage.setItem('ListData', data)
			data = JSON.parse(data);

			successCallback && successCallback(data);

		},
		error: function(xhr, type, errorThrown) {
			//异常处理；
			console.log(type);
		}
	});

}
/*搜索查询*/
function SiteSearch(divTgs) {
	var str = $(divTgs).val();
	if(str == '') {

		mui.alert('请输入关键字');
		return;
	}
	localStorage.setItem('keyword', str);

	get_goods_list(0, 1);
}

function remove_toast() {
	setTimeout(function() { //两秒后跳转  

		$('.weui-toast').remove();
		$('.weui-mask_transparent').remove();
	}, 1000);

}

function corwn_submit() {
	var num = $('#J-bidNums').val();
	var goods_id = localStorage.getItem('goods_id');
	var crowd_price = $('#crowd_price').val();
	//	layui.use('layer', function() { 
	//		layer.confirm('拼单此商品，您确定吗？', {
	//			shade: 0,
	//			icon: 3,
	//			title: '提示'
	//		}, function(index) {
	//			$url = self.attr('href');
	//			$.get($url, function(data) {
	//				if(data.status) {
	//					layer.msg(data.info, {
	//						time: 1000,
	//						end: function() {
	//							if(data.url) {
	//								location.href = data.url;
	//							} else {
	//								location.reload();
	//							}
	//						}
	//					});
	//				} else {
	//					layer.msg(data.info);
	//				}
	//			});
	//		});
	//
	//	});
	var btnArray = ['取消', '确定'];
	var index = 0;
	mui.confirm('确定拼单?', '提示', btnArray, function(e) {
		if(e.index == 1) {
			if(index == 0) {
				index++;
				$.ajax({
					url: crown_goods,
					data: {
						is_mobile: 1,
						goods_id: goods_id,
						num: num,
						crowd_price: crowd_price,
						user_id: localStorage.getItem('user_id')
					},
					dataType: 'json', //服务器返回json格式数据
					type: 'POST', //HTTP请求类型
					timeout: 10000, //超时时间设置为10秒；

					success: function(data) {
						var status = data.status;
						if(status == 1) {
							mui.alert(data.msg, function() {

								location.reload();

							});

						} else {
							mui.alert(data.msg, function() {

								location.href = data.url;

							});

						}
					},
					error: function(xhr, type, errorThrown) {
						//异常处理；
						console.log(type);
					}
				});
			}
		} else {

		}
	})

}

var intDiff = parseInt(60); //倒计时总秒数量
function timer(intDiff, obj) {
	window.setInterval(function() {
		var day = 0,
			hour = 0,
			minute = 0,
			second = 0; //时间默认值        
		if(intDiff > 0) {
			day = Math.floor(intDiff / (60 * 60 * 24));
			hour = Math.floor(intDiff / (60 * 60)) - (day * 24);
			minute = Math.floor(intDiff / 60) - (day * 24 * 60) - (hour * 60);
			second = Math.floor(intDiff) - (day * 24 * 60 * 60) - (hour * 60 * 60) - (minute * 60);
		}
		if(minute <= 9) minute = '0' + minute;
		if(second <= 9) second = '0' + second;
		$(obj).html(hour + ':' + minute + ':' + second + '');
		intDiff--;
	}, 1000);
}




var my_goods_list = PreUrl + "Goods/myGoods";
//获取商品列表
function get_my_goods_list( pageNum, pageSize, successCallback) {

	var keyword = localStorage.getItem('keyword')
	var check_status = localStorage.getItem('check_status')
	console.log(localStorage.getItem('user_id'))

	$.ajax({
		url: my_goods_list, 
		data: {
			is_mobile: 1,
			page_index: pageNum,
			page_size: pageSize,
			user_id: localStorage.getItem('user_id'),
			check_status:check_status,
			keyword: keyword
		},
		dataType: 'json', //服务器返回json格式数据
		type: 'post', //HTTP请求类型
		timeout: 10000, //超时时间设置为10秒；

		success: function(data) {
			remove_toast()
			data = JSON.stringify(data);
			//				console.log(data)
			localStorage.setItem('ListData', data)
			data = JSON.parse(data);
			var status = data.status;

			successCallback && successCallback(data);

		},
		error: function(xhr, type, errorThrown) {
			//异常处理；
			console.log(2222);
		}
	});

}