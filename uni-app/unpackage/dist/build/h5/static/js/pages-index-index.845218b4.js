(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["pages-index-index"],{"8cde":function(t,i,e){"use strict";var a=function(){var t=this,i=t.$createElement,e=t._self._c||i;return e("v-uni-view",{staticClass:"container index-content"},[e("v-uni-view",{staticClass:"carousel-section"},[e("v-uni-view",{staticClass:"titleNview-placing"}),e("v-uni-view",{staticClass:"titleNview-background",style:{backgroundColor:t.titleNViewBackground}}),e("v-uni-swiper",{staticClass:"carousel",attrs:{circular:""},on:{change:function(i){i=t.$handleEvent(i),t.swiperChange(i)}}},t._l(t.carouselList,function(i,a){return e("v-uni-swiper-item",{key:a,staticClass:"carousel-item",on:{click:function(e){e=t.$handleEvent(e),t.navToDetailPage(i.id)}}},[e("v-uni-image",{attrs:{src:i.src}})],1)}),1),e("v-uni-view",{staticClass:"swiper-dots"},[e("v-uni-text",{staticClass:"num"},[t._v(t._s(t.swiperCurrent+1))]),e("v-uni-text",{staticClass:"sign"},[t._v("/")]),e("v-uni-text",{staticClass:"num"},[t._v(t._s(t.swiperLength))])],1)],1),t.cateList.length>0?e("v-uni-view",{staticClass:"cate-section"},t._l(t.cateList,function(i,a){return e("v-uni-view",{key:a,staticClass:"cate-item",on:{click:function(e){e=t.$handleEvent(e),t.navToGoodsListPage(i.parent_id,i.id,i.title)}}},[e("v-uni-image",{attrs:{src:i.icon}}),e("v-uni-text",[t._v(t._s(i.title))])],1)}),1):t._e(),e("v-uni-view",{directives:[{name:"show",rawName:"v-show",value:!1,expression:"false"}],staticClass:"seckill-section m-t"},[e("v-uni-view",{staticClass:"s-header"},[e("v-uni-image",{staticClass:"s-img",attrs:{src:"/static/temp/secskill-img.jpg",mode:"widthFix"}}),e("v-uni-text",{staticClass:"tip"},[t._v("8点场")]),e("v-uni-text",{staticClass:"hour timer"},[t._v("07")]),e("v-uni-text",{staticClass:"minute timer"},[t._v("13")]),e("v-uni-text",{staticClass:"second timer"},[t._v("55")]),e("v-uni-text",{staticClass:"yticon icon-you"})],1),e("v-uni-scroll-view",{staticClass:"floor-list",attrs:{"scroll-x":""}},[e("v-uni-view",{staticClass:"scoll-wrapper"},t._l(t.hotList[0].list,function(i,a){return e("v-uni-view",{key:a,staticClass:"floor-item"},[e("v-uni-image",{attrs:{src:i.image,mode:"aspectFill"}}),e("v-uni-text",{staticClass:"title clamp"},[t._v(t._s(i.title))]),e("v-uni-text",{staticClass:"price"},[t._v("￥"+t._s(i.price))])],1)}),1)],1)],1),e("v-uni-view",{directives:[{name:"show",rawName:"v-show",value:!1,expression:"false"}],staticClass:"f-header m-t"},[e("v-uni-image",{attrs:{src:"/static/temp/h1.png"}}),e("v-uni-view",{staticClass:"tit-box"},[e("v-uni-text",{staticClass:"tit"},[t._v("精品团购")]),e("v-uni-text",{staticClass:"tit2"},[t._v("Boutique Group Buying")])],1),e("v-uni-text",{staticClass:"yticon icon-you"})],1),e("v-uni-view",{directives:[{name:"show",rawName:"v-show",value:!1,expression:"false"}],staticClass:"group-section"},[e("v-uni-swiper",{staticClass:"g-swiper",attrs:{duration:500}},t._l(t.hotList[0].list,function(i,a){return a%2===0?e("v-uni-swiper-item",{key:a,staticClass:"g-swiper-item"},[e("v-uni-view",{staticClass:"g-item left"},[e("v-uni-image",{attrs:{src:i.image,mode:"aspectFill"}}),e("v-uni-view",{staticClass:"t-box"},[e("v-uni-text",{staticClass:"title clamp"},[t._v(t._s(i.title))]),e("v-uni-view",{staticClass:"price-box"},[e("v-uni-text",{staticClass:"price"},[t._v("￥"+t._s(i.price))]),e("v-uni-text",{staticClass:"m-price"},[t._v("￥188")])],1),e("v-uni-view",{staticClass:"pro-box"},[e("v-uni-view",{staticClass:"progress-box"},[e("v-uni-progress",{attrs:{percent:"72",activeColor:"#fa436a",active:"","stroke-width":"6"}})],1),e("v-uni-text",[t._v("6人成团")])],1)],1)],1),e("v-uni-view",{staticClass:"g-item right"},[e("v-uni-image",{attrs:{src:t.hotList[0].list[a+1].image,mode:"aspectFill"}}),e("v-uni-view",{staticClass:"t-box"},[e("v-uni-text",{staticClass:"title clamp"},[t._v(t._s(t.hotList[0].list[a+1].title))]),e("v-uni-view",{staticClass:"price-box"},[e("v-uni-text",{staticClass:"price"},[t._v("￥"+t._s(t.hotList[0].list[a+1].price))]),e("v-uni-text",{staticClass:"m-price"},[t._v("￥188")])],1),e("v-uni-view",{staticClass:"pro-box"},[e("v-uni-view",{staticClass:"progress-box"},[e("v-uni-progress",{attrs:{percent:"72",activeColor:"#fa436a",active:"","stroke-width":"6"}})],1),e("v-uni-text",[t._v("10人成团")])],1)],1)],1)],1):t._e()}),1)],1),e("v-uni-view",{directives:[{name:"show",rawName:"v-show",value:!1,expression:"false"}],staticClass:"f-header m-t"},[e("v-uni-image",{attrs:{src:"/static/temp/h1.png"}}),e("v-uni-view",{staticClass:"tit-box"},[e("v-uni-text",{staticClass:"tit"},[t._v("分类精选")]),e("v-uni-text",{staticClass:"tit2"},[t._v("Competitive Products For You")])],1),e("v-uni-text",{staticClass:"yticon icon-you"})],1),e("v-uni-view",{directives:[{name:"show",rawName:"v-show",value:!1,expression:"false"}],staticClass:"hot-floor"},[e("v-uni-view",{staticClass:"floor-img-box"},[e("v-uni-image",{staticClass:"floor-img",attrs:{src:"https://timgsa.baidu.com/timg?image&quality=80&size=b9999_10000&sec=1553409398864&di=4a12763adccf229133fb85193b7cc08f&imgtype=0&src=http%3A%2F%2Fb-ssl.duitang.com%2Fuploads%2Fitem%2F201703%2F19%2F20170319150032_MNwmn.jpeg",mode:"scaleToFill"}})],1),e("v-uni-scroll-view",{staticClass:"floor-list",attrs:{"scroll-x":""}},[e("v-uni-view",{staticClass:"scoll-wrapper"},[t._l(t.hotList[0].list,function(i,a){return e("v-uni-view",{key:a,staticClass:"floor-item"},[e("v-uni-image",{attrs:{src:i.image,mode:"aspectFill"}}),e("v-uni-text",{staticClass:"title clamp"},[t._v(t._s(i.title))]),e("v-uni-text",{staticClass:"price"},[t._v("￥"+t._s(i.price))])],1)}),e("v-uni-view",{staticClass:"more"},[e("v-uni-text",[t._v("查看全部")]),e("v-uni-text",[t._v("More+")])],1)],2)],1)],1),e("v-uni-view",{directives:[{name:"show",rawName:"v-show",value:!1,expression:"false"}],staticClass:"hot-floor"},[e("v-uni-view",{staticClass:"floor-img-box"},[e("v-uni-image",{staticClass:"floor-img",attrs:{src:"https://timgsa.baidu.com/timg?image&quality=80&size=b9999_10000&sec=1553409984228&di=dee176242038c2d545b7690b303d65ea&imgtype=0&src=http%3A%2F%2Fhbimg.b0.upaiyun.com%2F5ef4da9f17faaf4612f0d5046f4161e556e9bbcfdb5b-rHjf00_fw658",mode:"scaleToFill"}})],1),e("v-uni-scroll-view",{staticClass:"floor-list",attrs:{"scroll-x":""}},[e("v-uni-view",{staticClass:"scoll-wrapper"},[t._l(t.hotList[0].list,function(i,a){return e("v-uni-view",{key:a,staticClass:"floor-item"},[e("v-uni-image",{attrs:{src:i.image3,mode:"aspectFill"}}),e("v-uni-text",{staticClass:"title clamp"},[t._v(t._s(i.title))]),e("v-uni-text",{staticClass:"price"},[t._v("￥"+t._s(i.price))])],1)}),e("v-uni-view",{staticClass:"more"},[e("v-uni-text",[t._v("查看全部")]),e("v-uni-text",[t._v("More+")])],1)],2)],1)],1),e("v-uni-view",{directives:[{name:"show",rawName:"v-show",value:!1,expression:"false"}],staticClass:"hot-floor"},[e("v-uni-view",{staticClass:"floor-img-box"},[e("v-uni-image",{staticClass:"floor-img",attrs:{src:"https://timgsa.baidu.com/timg?image&quality=80&size=b9999_10000&sec=1553409794730&di=12b840ec4f5748ef06880b85ff63e34e&imgtype=0&src=http%3A%2F%2Fimg.zcool.cn%2Fcommunity%2F01dc03589ed568a8012060c82ac03c.jpg%40900w_1l_2o_100sh.jpg",mode:"scaleToFill"}})],1),e("v-uni-scroll-view",{staticClass:"floor-list",attrs:{"scroll-x":""}},[e("v-uni-view",{staticClass:"scoll-wrapper"},[t._l(t.hotList[0].list,function(i,a){return e("v-uni-view",{key:a,staticClass:"floor-item"},[e("v-uni-image",{attrs:{src:i.icon,mode:"aspectFill"}}),e("v-uni-text",{staticClass:"title clamp"},[t._v(t._s(i.title))]),e("v-uni-text",{staticClass:"price"},[t._v("￥"+t._s(i.price))])],1)}),e("v-uni-view",{staticClass:"more"},[e("v-uni-text",[t._v("查看全部")]),e("v-uni-text",[t._v("More+")])],1)],2)],1)],1),e("v-uni-view",{staticClass:"f-header m-t"},[e("v-uni-image",{attrs:{src:"/static/temp/h1.png"}}),e("v-uni-view",{staticClass:"tit-box"},[e("v-uni-text",{staticClass:"tit"},[t._v("猜你喜欢")]),e("v-uni-text",{staticClass:"tit2"},[t._v("Guess You Like It")])],1),e("v-uni-text",{staticClass:"yticon icon-you"})],1),e("v-uni-view",{staticClass:"guess-section"},t._l(t.hotList,function(i,a){return e("v-uni-view",{key:a,staticClass:"guess-item",on:{click:function(e){e=t.$handleEvent(e),t.navToGoodsDetailPage(i.id)}}},[e("v-uni-view",{staticClass:"image-wrapper"},[e("v-uni-image",{attrs:{src:i.icon,mode:"aspectFill"}})],1),e("v-uni-text",{staticClass:"title clamp"},[t._v(t._s(i.title))]),e("v-uni-text",{staticClass:"price"},[t._v("￥"+t._s(i.price))])],1)}),1)],1)},s=[];e.d(i,"a",function(){return a}),e.d(i,"b",function(){return s})},"96c3":function(t,i,e){"use strict";Object.defineProperty(i,"__esModule",{value:!0}),i.default=void 0;var a=o(e("a34a")),s=o(e("6511"));e("2f62");function o(t){return t&&t.__esModule?t:{default:t}}function n(t,i,e,a,s,o,n){try{var l=t[o](n),c=l.value}catch(r){return void e(r)}l.done?i(c):Promise.resolve(c).then(a,s)}function l(t){return function(){var i=this,e=arguments;return new Promise(function(a,s){var o=t.apply(i,e);function l(t){n(o,a,s,l,c,"next",t)}function c(t){n(o,a,s,l,c,"throw",t)}l(void 0)})}}var c={data:function(){return{titleNViewBackground:"",swiperCurrent:0,swiperLength:0,carouselList:[],cateList:[],hotList:[{},{}]}},onLoad:function(){void 0==this.userInfo&&(console.log(this.userInfo),uni.navigateTo({url:"/pages/public/login"})),this.loadData()},onBackPress:function(){var t=this;if(void 0==t.userInfo)return console.log(t.userInfo),this.showMask?(this.showMask=!1,!0):(uni.showModal({title:"提示",content:"是否退出uni-app？",success:function(t){t.confirm?plus.runtime.quit():t.cancel&&console.log("用户点击取消")}}),!0)},methods:{loadData:function(){var t=l(a.default.mark(function t(){var i,e;return a.default.wrap(function(t){while(1)switch(t.prev=t.next){case 0:return i=this,t.next=3,this.$api.json("carouselList");case 3:e=t.sent,uni.request({url:s.default.get_goods_listUrl,data:{is_mobile:1,category_id:0,page_index:1,page_num:1e5,user_id:0,keyword:""},method:"POST",header:{"content-type":"application/x-www-form-urlencoded"},success:function(t){if(console.log(t.data.status),1==t.data.status){e=t.data.slider;var a=t.data.category;i.titleNViewBackground=e[0].background,i.swiperLength=e.length,i.carouselList=e,i.cateList=a;var s=t.data.data;i.hotList=s}}});case 5:case"end":return t.stop()}},t,this)}));function i(){return t.apply(this,arguments)}return i}(),swiperChange:function(t){var i=t.detail.current;this.swiperCurrent=i,this.titleNViewBackground=this.carouselList[i].background},navToDetailPage:function(){uni.navigateTo({url:"/pages/detail/detail"})},navToGoodsListPage:function(t,i,e){uni.navigateTo({url:"/pages/product/list?fid=".concat(t,"&sid=").concat(t,"&tid=").concat(i,"&title=").concat(e)})},navToGoodsDetailPage:function(t){var i=t;uni.navigateTo({url:"/pages/product/product?id="+i})}},onNavigationBarSearchInputClicked:function(){var t=l(a.default.mark(function t(i){return a.default.wrap(function(t){while(1)switch(t.prev=t.next){case 0:this.$api.msg("点击了搜索框");case 1:case"end":return t.stop()}},t,this)}));function i(i){return t.apply(this,arguments)}return i}(),onNavigationBarButtonTap:function(t){var i=t.index;0===i?this.$api.msg("点击了扫描"):1===i&&this.$api.msg("点击了消息, 红点新消息提示已清除")}};i.default=c},"98d9":function(t,i,e){var a=e("cb9b");"string"===typeof a&&(a=[[t.i,a,""]]),a.locals&&(t.exports=a.locals);var s=e("4f06").default;s("c62ba4c2",a,!0,{sourceMap:!1,shadowMode:!1})},b34d:function(t,i,e){"use strict";e.r(i);var a=e("96c3"),s=e.n(a);for(var o in a)"default"!==o&&function(t){e.d(i,t,function(){return a[t]})}(o);i["default"]=s.a},b69f:function(t,i,e){"use strict";e.r(i);var a=e("8cde"),s=e("b34d");for(var o in s)"default"!==o&&function(t){e.d(i,t,function(){return s[t]})}(o);e("ccb6");var n=e("2877"),l=Object(n["a"])(s["default"],a["a"],a["b"],!1,null,"ac825a60",null);i["default"]=l.exports},cb9b:function(t,i,e){i=t.exports=e("2350")(!1),i.push([t.i,'@charset "UTF-8";\n/* 页面左右间距 */\n/* 文字尺寸 */\n/*文字颜色*/\n/* 边框颜色 */\n/* 图片加载中颜色 */\n/* 行为相关颜色 */uni-page-body[data-v-ac825a60]{background:#f5f5f5}.m-t[data-v-ac825a60]{margin-top:%?16?%}\n/* 头部 轮播图 */.carousel-section[data-v-ac825a60]{position:relative;padding-top:10px}.carousel-section .titleNview-placing[data-v-ac825a60]{height:0;padding-top:44px;-webkit-box-sizing:content-box;box-sizing:content-box}.carousel-section .titleNview-background[data-v-ac825a60]{position:absolute;top:0;left:0;width:100%;height:%?426?%;-webkit-transition:.4s;-o-transition:.4s;transition:.4s}.carousel[data-v-ac825a60]{width:100%;height:%?350?%}.carousel .carousel-item[data-v-ac825a60]{width:100%;height:100%;padding:0 %?28?%;overflow:hidden}.carousel uni-image[data-v-ac825a60]{width:100%;height:100%;border-radius:%?10?%}.swiper-dots[data-v-ac825a60]{display:-webkit-box;display:-webkit-flex;display:-ms-flexbox;display:flex;position:absolute;left:%?60?%;bottom:%?15?%;width:%?72?%;height:%?36?%;background-image:url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAMgAAABkCAYAAADDhn8LAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAyZpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuNi1jMTMyIDc5LjE1OTI4NCwgMjAxNi8wNC8xOS0xMzoxMzo0MCAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wTU09Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9tbS8iIHhtbG5zOnN0UmVmPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvc1R5cGUvUmVzb3VyY2VSZWYjIiB4bWxuczp4bXA9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC8iIHhtcE1NOkRvY3VtZW50SUQ9InhtcC5kaWQ6OTk4MzlBNjE0NjU1MTFFOUExNjRFQ0I3RTQ0NEExQjMiIHhtcE1NOkluc3RhbmNlSUQ9InhtcC5paWQ6OTk4MzlBNjA0NjU1MTFFOUExNjRFQ0I3RTQ0NEExQjMiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENDIDIwMTcgKFdpbmRvd3MpIj4gPHhtcE1NOkRlcml2ZWRGcm9tIHN0UmVmOmluc3RhbmNlSUQ9InhtcC5paWQ6Q0E3RUNERkE0NjExMTFFOTg5NzI4MTM2Rjg0OUQwOEUiIHN0UmVmOmRvY3VtZW50SUQ9InhtcC5kaWQ6Q0E3RUNERkI0NjExMTFFOTg5NzI4MTM2Rjg0OUQwOEUiLz4gPC9yZGY6RGVzY3JpcHRpb24+IDwvcmRmOlJERj4gPC94OnhtcG1ldGE+IDw/eHBhY2tldCBlbmQ9InIiPz4Gh5BPAAACTUlEQVR42uzcQW7jQAwFUdN306l1uWwNww5kqdsmm6/2MwtVCp8CosQtP9vg/2+/gY+DRAMBgqnjIp2PaCxCLLldpPARRIiFj1yBbMV+cHZh9PURRLQNhY8kgWyL/WDtwujjI8hoE8rKLqb5CDJaRMJHokC6yKgSCR9JAukmokIknCQJpLOIrJFwMsBJELFcKHwM9BFkLBMKFxNcBCHlQ+FhoocgpVwwnv0Xn30QBJGMC0QcaBVJiAMiec/dcwKuL4j1QMsVCXFAJE4s4NQA3K/8Y6DzO4g40P7UcmIBJxbEesCKWBDg8wWxHrAiFgT4fEGsB/CwIhYE+AeBAAdPLOcV8HRmWRDAiQVcO7GcV8CLM8uCAE4sQCDAlHcQ7x+ABQEEAggEEAggEEAggEAAgQACASAQQCCAQACBAAIBBAIIBBAIIBBAIABe4e9iAe/xd7EAJxYgEGDeO4j3EODp/cOCAE4sYMyJ5cwCHs4rCwI4sYBxJ5YzC84rCwKcXxArAuthQYDzC2JF0H49LAhwYUGsCFqvx5EF2T07dMaJBetx4cRyaqFtHJ8EIhK0i8OJBQxcECuCVutxJhCRoE0cZwMRyRcFefa/ffZBVPogePihhyCnbBhcfMFFEFM+DD4m+ghSlgmDkwlOgpAl4+BkkJMgZdk4+EgaSCcpVX7bmY9kgXQQU+1TgE0c+QJZUUz1b2T4SBbIKmJW+3iMj2SBVBWz+leVfCQLpIqYbp8b85EskIxyfIOfK5Sf+wiCRJEsllQ+oqEkQfBxmD8BBgA5hVjXyrBNUQAAAABJRU5ErkJggg==);background-size:100% 100%}.swiper-dots .num[data-v-ac825a60]{width:%?36?%;height:%?36?%;border-radius:50px;font-size:%?24?%;color:#fff;text-align:center;line-height:%?36?%}.swiper-dots .sign[data-v-ac825a60]{position:absolute;top:0;left:50%;line-height:%?36?%;font-size:%?12?%;color:#fff;-webkit-transform:translateX(-50%);-ms-transform:translateX(-50%);transform:translateX(-50%)}\n/* 分类 */.cate-section[data-v-ac825a60]{display:-webkit-box;display:-webkit-flex;display:-ms-flexbox;display:flex;-webkit-justify-content:space-around;-ms-flex-pack:distribute;justify-content:space-around;-webkit-box-align:center;-webkit-align-items:center;-ms-flex-align:center;align-items:center;-webkit-flex-wrap:wrap;-ms-flex-wrap:wrap;flex-wrap:wrap;padding:%?30?% %?22?%;background:#fff\n  /* 原图标颜色太深,不想改图了,所以加了透明度 */}.cate-section .cate-item[data-v-ac825a60]{display:-webkit-box;display:-webkit-flex;display:-ms-flexbox;display:flex;-webkit-box-orient:vertical;-webkit-box-direction:normal;-webkit-flex-direction:column;-ms-flex-direction:column;flex-direction:column;-webkit-box-align:center;-webkit-align-items:center;-ms-flex-align:center;align-items:center;font-size:%?26?%;color:#303133;width:20%;height:%?150?%}.cate-section uni-image[data-v-ac825a60]{width:%?88?%;height:%?88?%;margin-bottom:%?14?%;border-radius:50%}.ad-1[data-v-ac825a60]{width:100%;height:%?210?%;padding:%?10?% 0;background:#fff}.ad-1 uni-image[data-v-ac825a60]{width:100%;height:100%}\n/* 秒杀专区 */.seckill-section[data-v-ac825a60]{padding:%?4?% %?30?% %?24?%;background:#fff}.seckill-section .s-header[data-v-ac825a60]{display:-webkit-box;display:-webkit-flex;display:-ms-flexbox;display:flex;-webkit-box-align:center;-webkit-align-items:center;-ms-flex-align:center;align-items:center;height:%?92?%;line-height:1}.seckill-section .s-header .s-img[data-v-ac825a60]{width:%?140?%;height:%?30?%}.seckill-section .s-header .tip[data-v-ac825a60]{font-size:%?28?%;color:#909399;margin:0 %?20?% 0 %?40?%}.seckill-section .s-header .timer[data-v-ac825a60]{display:inline-block;width:%?40?%;height:%?36?%;text-align:center;line-height:%?36?%;margin-right:%?14?%;font-size:%?26?%;color:#fff;border-radius:2px;background:rgba(0,0,0,.8)}.seckill-section .s-header .icon-you[data-v-ac825a60]{font-size:%?32?%;color:#909399;-webkit-box-flex:1;-webkit-flex:1;-ms-flex:1;flex:1;text-align:right}.seckill-section .floor-list[data-v-ac825a60]{white-space:nowrap}.seckill-section .scoll-wrapper[data-v-ac825a60]{display:-webkit-box;display:-webkit-flex;display:-ms-flexbox;display:flex;-webkit-box-align:start;-webkit-align-items:flex-start;-ms-flex-align:start;align-items:flex-start}.seckill-section .floor-item[data-v-ac825a60]{width:%?150?%;margin-right:%?20?%;font-size:%?26?%;color:#303133;line-height:1.8}.seckill-section .floor-item uni-image[data-v-ac825a60]{width:%?150?%;height:%?150?%;border-radius:%?6?%}.seckill-section .floor-item .price[data-v-ac825a60]{color:#fa436a}.f-header[data-v-ac825a60]{display:-webkit-box;display:-webkit-flex;display:-ms-flexbox;display:flex;-webkit-box-align:center;-webkit-align-items:center;-ms-flex-align:center;align-items:center;height:%?140?%;padding:%?6?% %?30?% %?8?%;background:#fff}.f-header uni-image[data-v-ac825a60]{-webkit-flex-shrink:0;-ms-flex-negative:0;flex-shrink:0;width:%?80?%;height:%?80?%;margin-right:%?20?%}.f-header .tit-box[data-v-ac825a60]{-webkit-box-flex:1;-webkit-flex:1;-ms-flex:1;flex:1;display:-webkit-box;display:-webkit-flex;display:-ms-flexbox;display:flex;-webkit-box-orient:vertical;-webkit-box-direction:normal;-webkit-flex-direction:column;-ms-flex-direction:column;flex-direction:column}.f-header .tit[data-v-ac825a60]{font-size:%?34?%;color:#font-color-dark;line-height:1.3}.f-header .tit2[data-v-ac825a60]{font-size:%?24?%;color:#909399}.f-header .icon-you[data-v-ac825a60]{font-size:%?34?%;color:#909399}\n/* 团购楼层 */.group-section[data-v-ac825a60]{background:#fff}.group-section .g-swiper[data-v-ac825a60]{height:%?650?%;padding-bottom:%?30?%}.group-section .g-swiper-item[data-v-ac825a60]{width:100%;padding:0 %?30?%;display:-webkit-box;display:-webkit-flex;display:-ms-flexbox;display:flex}.group-section uni-image[data-v-ac825a60]{width:100%;height:%?460?%;border-radius:4px}.group-section .g-item[data-v-ac825a60]{display:-webkit-box;display:-webkit-flex;display:-ms-flexbox;display:flex;-webkit-box-orient:vertical;-webkit-box-direction:normal;-webkit-flex-direction:column;-ms-flex-direction:column;flex-direction:column;overflow:hidden}.group-section .left[data-v-ac825a60]{-webkit-box-flex:1.2;-webkit-flex:1.2;-ms-flex:1.2;flex:1.2;margin-right:%?24?%}.group-section .left .t-box[data-v-ac825a60]{padding-top:%?20?%}.group-section .right[data-v-ac825a60]{-webkit-box-flex:0.8;-webkit-flex:0.8;-ms-flex:0.8;flex:0.8;-webkit-box-orient:vertical;-webkit-box-direction:reverse;-webkit-flex-direction:column-reverse;-ms-flex-direction:column-reverse;flex-direction:column-reverse}.group-section .right .t-box[data-v-ac825a60]{padding-bottom:%?20?%}.group-section .t-box[data-v-ac825a60]{height:%?160?%;font-size:%?30?%;color:#303133;line-height:1.6}.group-section .price[data-v-ac825a60]{color:#fa436a}.group-section .m-price[data-v-ac825a60]{font-size:%?26?%;text-decoration:line-through;color:#909399;margin-left:%?8?%}.group-section .pro-box[data-v-ac825a60]{display:-webkit-box;display:-webkit-flex;display:-ms-flexbox;display:flex;-webkit-box-align:center;-webkit-align-items:center;-ms-flex-align:center;align-items:center;margin-top:%?10?%;font-size:%?24?%;color:%?28?%;padding-right:%?10?%}.group-section .progress-box[data-v-ac825a60]{-webkit-box-flex:1;-webkit-flex:1;-ms-flex:1;flex:1;border-radius:10px;overflow:hidden;margin-right:%?8?%}\n/* 分类推荐楼层 */.hot-floor[data-v-ac825a60]{width:100%;overflow:hidden;margin-bottom:%?20?%}.hot-floor .floor-img-box[data-v-ac825a60]{width:100%;height:%?320?%;position:relative}.hot-floor .floor-img-box[data-v-ac825a60]:after{content:"";position:absolute;left:0;top:0;width:100%;height:100%;background:-webkit-gradient(linear,left top,left bottom,color-stop(30%,hsla(0,0%,100%,.06)),to(#f8f8f8));background:-o-linear-gradient(hsla(0,0%,100%,.06) 30%,#f8f8f8);background:linear-gradient(hsla(0,0%,100%,.06) 30%,#f8f8f8)}.hot-floor .floor-img[data-v-ac825a60]{width:100%;height:100%}.hot-floor .floor-list[data-v-ac825a60]{white-space:nowrap;padding:%?20?%;padding-right:%?50?%;border-radius:%?6?%;margin-top:%?-140?%;margin-left:%?30?%;background:#fff;-webkit-box-shadow:1px 1px 5px rgba(0,0,0,.2);box-shadow:1px 1px 5px rgba(0,0,0,.2);position:relative;z-index:1}.hot-floor .scoll-wrapper[data-v-ac825a60]{display:-webkit-box;display:-webkit-flex;display:-ms-flexbox;display:flex;-webkit-box-align:start;-webkit-align-items:flex-start;-ms-flex-align:start;align-items:flex-start}.hot-floor .floor-item[data-v-ac825a60]{width:%?180?%;margin-right:%?20?%;font-size:%?26?%;color:#303133;line-height:1.8}.hot-floor .floor-item uni-image[data-v-ac825a60]{width:%?180?%;height:%?180?%;border-radius:%?6?%}.hot-floor .floor-item .price[data-v-ac825a60]{color:#fa436a}.hot-floor .more[data-v-ac825a60]{display:-webkit-box;display:-webkit-flex;display:-ms-flexbox;display:flex;-webkit-box-align:center;-webkit-align-items:center;-ms-flex-align:center;align-items:center;-webkit-box-pack:center;-webkit-justify-content:center;-ms-flex-pack:center;justify-content:center;-webkit-box-orient:vertical;-webkit-box-direction:normal;-webkit-flex-direction:column;-ms-flex-direction:column;flex-direction:column;-webkit-flex-shrink:0;-ms-flex-negative:0;flex-shrink:0;width:%?180?%;height:%?180?%;border-radius:%?6?%;background:#f3f3f3;font-size:%?28?%;color:#909399}.hot-floor .more uni-text[data-v-ac825a60]:first-child{margin-bottom:%?4?%}\n/* 猜你喜欢 */.guess-section[data-v-ac825a60]{display:-webkit-box;display:-webkit-flex;display:-ms-flexbox;display:flex;-webkit-flex-wrap:wrap;-ms-flex-wrap:wrap;flex-wrap:wrap;padding:0 %?30?%;background:#fff}.guess-section .guess-item[data-v-ac825a60]{display:-webkit-box;display:-webkit-flex;display:-ms-flexbox;display:flex;-webkit-box-orient:vertical;-webkit-box-direction:normal;-webkit-flex-direction:column;-ms-flex-direction:column;flex-direction:column;-webkit-box-flex:1;-webkit-flex:1;-ms-flex:1;flex:1;max-width:50%;min-width:40%;padding-bottom:%?40?%}.guess-section .guess-item[data-v-ac825a60]:nth-child(odd){margin-right:%?20?%}.guess-section .image-wrapper[data-v-ac825a60]{width:100%;height:%?330?%;border-radius:3px;overflow:hidden}.guess-section .image-wrapper uni-image[data-v-ac825a60]{width:100%;height:100%;opacity:1}.guess-section .title[data-v-ac825a60]{font-size:%?32?%;color:#303133;line-height:%?80?%}.guess-section .price[data-v-ac825a60]{font-size:%?32?%;color:#fa436a;line-height:1}body.?%PAGE?%[data-v-ac825a60]{background:#f5f5f5}',""])},ccb6:function(t,i,e){"use strict";var a=e("98d9"),s=e.n(a);s.a}}]);