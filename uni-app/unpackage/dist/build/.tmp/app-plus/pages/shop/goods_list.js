(global["webpackJsonp"]=global["webpackJsonp"]||[]).push([["pages/shop/goods_list"],{"0def":function(t,e,a){"use strict";var i=a("8f77"),o=a.n(i);o.a},"66c8":function(t,e,a){"use strict";a("feb3");var i=n(a("b0ce")),o=n(a("b9b3"));function n(t){return t&&t.__esModule?t:{default:t}}Page((0,i.default)(o.default))},"8f77":function(t,e,a){},"9f07":function(t,e,a){"use strict";var i=function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("view",{staticClass:"content"},[a("div",{directives:[{name:"title",rawName:"v-title"}],staticClass:"main",attrs:{"data-title":"登录"}}),a("view",{staticClass:"navbar",style:{position:t.headerPosition,top:t.headerTop}},[a("view",{staticClass:"nav-item",class:{current:0===t.filterIndex},attrs:{eventid:"50c57167-0"},on:{click:function(e){t.tabClick(0)}}},[t._v("综合排序")]),a("view",{staticClass:"nav-item",class:{current:1===t.filterIndex},attrs:{eventid:"50c57167-1"},on:{click:function(e){t.tabClick(1)}}},[t._v("销量优先")]),a("view",{staticClass:"nav-item",class:{current:2===t.filterIndex},attrs:{eventid:"50c57167-2"},on:{click:function(e){t.tabClick(2)}}},[a("text",[t._v("价格")]),a("view",{staticClass:"p-box"},[a("text",{staticClass:"yticon icon-shang",class:{active:1===t.priceOrder&&2===t.filterIndex}}),a("text",{staticClass:"yticon icon-shang xia",class:{active:2===t.priceOrder&&2===t.filterIndex}})])]),a("text",{staticClass:"cate-item yticon icon-fenlei1",attrs:{eventid:"50c57167-3"},on:{click:function(e){t.toggleCateMask("show")}}})]),a("mescroll-uni",{attrs:{eventid:"50c57167-5",mpcomid:"50c57167-0"},on:{down:t.downCallback,up:t.upCallback,init:t.mescrollInit}},[a("view",{staticClass:"goods-list"},t._l(t.goodsList,function(e,i){return a("view",{key:i,staticClass:"goods-item"},[a("view",{staticClass:"image-wrapper"},[a("image",{attrs:{src:e.icon,mode:"aspectFill"}})]),a("text",{staticClass:"title clamp"},[t._v(t._s(e.title))]),a("view",{staticClass:"price-box"},[a("text",{staticClass:"price"},[t._v(t._s(e.price))]),a("view",{staticClass:"iconfont",attrs:{id:e.id,"data-img":e.icon,eventid:"50c57167-4-"+i},on:{tap:t.addShopCar}},[t._v("库存:"+t._s(e.stock))])]),a("view",{staticClass:"flex margin-top"},[a("view",{staticClass:"cu-progress round"},[a("view",{staticClass:"bg-green",style:[{width:t.loading?e.percent+"%":""}]})]),a("text",{staticClass:"margin-left"},[t._v(t._s(e.percent)+"%")])])])}))]),a("view",{staticClass:"cate-mask",class:0===t.cateMaskState?"none":1===t.cateMaskState?"show":"",attrs:{eventid:"50c57167-8"},on:{click:t.toggleCateMask}},[a("view",{staticClass:"cate-content",attrs:{eventid:"50c57167-7"},on:{click:function(e){e.stopPropagation(),e.preventDefault(),t.stopPrevent(e)},touchmove:function(e){e.stopPropagation(),e.preventDefault(),t.stopPrevent(e)}}},[a("scroll-view",{staticClass:"cate-list",attrs:{"scroll-y":""}},t._l(t.cateList,function(e,i){return a("view",{key:e.id},[a("view",{staticClass:"cate-item b-b two"},[t._v(t._s(e.title))]),t._l(e.child,function(e,o){return a("view",{key:e.id,staticClass:"cate-item b-b",class:{active:e.id==t.cateId},attrs:{eventid:"50c57167-6-"+i+"-"+o},on:{click:function(a){t.changeCate(e)}}},[t._v(t._s(e.title))])})],2)}))],1)]),a("view",{staticClass:"footer"},[a("view",{staticClass:"price-content"},[a("text",[t._v("商品总数")]),a("text",{staticClass:"price-tip"}),a("text",{staticClass:"price"},[t._v(t._s(t.totalCount))]),a("text",[t._v("商品总金额")]),a("text",{staticClass:"price-tip"}),a("text",{staticClass:"price"},[t._v(t._s(t.totalAmount))])])]),a("shopCarAnimation",{ref:"carAnmation",attrs:{cartx:"0.1",carty:"1.1",mpcomid:"50c57167-1"}})],1)},o=[];a.d(e,"a",function(){return i}),a.d(e,"b",function(){return o})},"9feb":function(t,e,a){"use strict";a.r(e);var i=a("c474"),o=a.n(i);for(var n in i)"default"!==n&&function(t){a.d(e,t,function(){return i[t]})}(n);e["default"]=o.a},b9b3:function(t,e,a){"use strict";a.r(e);var i=a("9f07"),o=a("9feb");for(var n in o)"default"!==n&&function(t){a.d(e,t,function(){return o[t]})}(n);a("0def");var s=a("2877"),c=Object(s["a"])(o["default"],i["a"],i["b"],!1,null,null,null);e["default"]=c.exports},c474:function(t,e,a){"use strict";(function(t){Object.defineProperty(e,"__esModule",{value:!0}),e.default=void 0;var i=c(a("d9c1")),o=c(a("880c")),n=c(a("6511")),s=(c(a("93e0")),c(a("4b50")),a("2f62"));function c(t){return t&&t.__esModule?t:{default:t}}function r(t){for(var e=1;e<arguments.length;e++){var a=null!=arguments[e]?arguments[e]:{},i=Object.keys(a);"function"===typeof Object.getOwnPropertySymbols&&(i=i.concat(Object.getOwnPropertySymbols(a).filter(function(t){return Object.getOwnPropertyDescriptor(a,t).enumerable}))),i.forEach(function(e){l(t,e,a[e])})}return t}function l(t,e,a){return e in t?Object.defineProperty(t,e,{value:a,enumerable:!0,configurable:!0,writable:!0}):t[e]=a,t}var u={components:{MescrollUni:o.default,shopCarAnimation:i.default},computed:r({},(0,s.mapState)(["hasLogin","userInfo","bi","goods_id"])),data:function(){return{cateMaskState:0,mescroll:null,downOption:{use:!0,auto:!0},upOption:{use:!1,auto:!1},headerPosition:"fixed",headerTop:"0px",loadingType:"more",filterIndex:0,Id:0,cateId:0,priceOrder:0,cateList:[],goodsList:[],totalCount:0,totalAmount:0,loading:!0}},onReachBottom:function(){this.mescroll&&this.mescroll.onReachBottom()},onPageScroll:function(t){this.mescroll&&this.mescroll.onPageScroll(t)},onLoad:function(t){wx.setNavigationBarTitle({title:t.title}),this.cateId=t.tid,this.Id=t.sid},methods:{mescrollInit:function(t){this.mescroll=t},downCallback:function(t){t.resetUpScroll()},upCallback:function(t){var e=this;this.getListDataFromNet(n.default.get_goods_listUrl,t.num,t.size,function(a){t.endSuccess(a.data.length),1==t.num&&(e.goodsList=[]),e.goodsList=e.goodsList.concat(a.data),e.cateList=a.category,console.log(a.dui_cart_money),e.totalCount=a.current_count,e.totalAmount=a.totalAmount},function(){t.endErr()})},getListDataFromNet:function(e,a,i,o,n){var s=this;console.log(s.filterIndex),setTimeout(function(){try{t.request({url:e,data:{is_mobile:1,category_id:s.cateId,user_id:s.userInfo.id,page_index:a,page_size:i,keyword:"",goods_type:0,order:s.filterIndex,priceOrder:s.priceOrder},method:"POST",header:{"content-type":"application/x-www-form-urlencoded"},success:function(t){console.log(t.data.data),t.data.status,null==t.data.data&&(t.data.data=[]),o&&o(t.data)}})}catch(c){n&&n()}},10)},tabClick:function(t){this.filterIndex===t&&2!==t||(this.filterIndex=t,this.priceOrder=2===t?1===this.priceOrder?2:1:0,this.mescroll.triggerDownScroll())},toggleCateMask:function(t){var e=this,a="show"===t?10:300,i="show"===t?1:0;this.cateMaskState=2,setTimeout(function(){e.cateMaskState=i},a)},changeCate:function(e){this.cateId=e.id,this.toggleCateMask(),t.pageScrollTo({duration:300,scrollTop:0}),this.mescroll.triggerDownScroll()},navToDetailPage:function(e){var a=e.id;t.navigateTo({url:"/pages/product/product?id=".concat(a)})},stopPrevent:function(){},addShopCar:function(e){console.log("加入购物车");var a=this;console.log(e.currentTarget.id),t.request({url:n.default.dui_cart_goods_add,data:{actiontype:"add",is_mobile:1,user_id:a.userInfo.id,article_id:e.currentTarget.id,goods_id:0,quantity:1,hot_id:0},method:"POST",header:{"content-type":"application/x-www-form-urlencoded"},success:function(t){console.log(t.data.status),1==t.data.status&&(a.$refs.carAnmation.touchOnGoods(e),a.totalAmount=t.data.amount)}})},submit:function(){t.navigateTo({url:"/pages/shop/createOrder"})}}};e.default=u}).call(this,a("6e42")["default"])}},[["66c8","common/runtime","common/vendor"]]]);