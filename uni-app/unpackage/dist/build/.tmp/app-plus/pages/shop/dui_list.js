(global["webpackJsonp"]=global["webpackJsonp"]||[]).push([["pages/shop/dui_list"],{"4e59":function(t,e,a){"use strict";(function(t){Object.defineProperty(e,"__esModule",{value:!0}),e.default=void 0;var i=c(a("d9c1")),n=c(a("880c")),o=c(a("6511")),s=(c(a("93e0")),c(a("4b50")),a("2f62"));function c(t){return t&&t.__esModule?t:{default:t}}function r(t){for(var e=1;e<arguments.length;e++){var a=null!=arguments[e]?arguments[e]:{},i=Object.keys(a);"function"===typeof Object.getOwnPropertySymbols&&(i=i.concat(Object.getOwnPropertySymbols(a).filter(function(t){return Object.getOwnPropertyDescriptor(a,t).enumerable}))),i.forEach(function(e){l(t,e,a[e])})}return t}function l(t,e,a){return e in t?Object.defineProperty(t,e,{value:a,enumerable:!0,configurable:!0,writable:!0}):t[e]=a,t}var d={components:{MescrollUni:n.default,shopCarAnimation:i.default},computed:r({},(0,s.mapState)(["hasLogin","userInfo","bi","goods_id"])),data:function(){return{cateMaskState:0,mescroll:null,downOption:{use:!0,auto:!0},upOption:{use:!1,auto:!1},headerPosition:"fixed",headerTop:"0px",loadingType:"more",filterIndex:0,Id:0,cateId:0,priceOrder:0,cateList:[],goodsList:[],totalAmount:0}},onReachBottom:function(){this.mescroll&&this.mescroll.onReachBottom()},onPageScroll:function(t){this.mescroll&&this.mescroll.onPageScroll(t)},onLoad:function(t){wx.setNavigationBarTitle({title:t.title}),this.cateId=t.tid,this.Id=t.sid},methods:{mescrollInit:function(t){this.mescroll=t},downCallback:function(t){t.resetUpScroll()},upCallback:function(t){var e=this;this.getListDataFromNet(o.default.get_goods_listUrl,t.num,t.size,function(a){t.endSuccess(a.data.length),1==t.num&&(e.goodsList=[]),e.goodsList=e.goodsList.concat(a.data),e.cateList=a.category,console.log(a.dui_cart_money),e.totalAmount=a.dui_cart_money},function(){t.endErr()})},getListDataFromNet:function(e,a,i,n,o){var s=this;console.log(s.filterIndex),setTimeout(function(){try{t.request({url:e,data:{is_mobile:1,category_id:s.cateId,user_id:s.userInfo.id,page_index:a,page_size:i,keyword:"",goods_type:1,order:s.filterIndex,priceOrder:s.priceOrder},method:"POST",header:{"content-type":"application/x-www-form-urlencoded"},success:function(t){console.log(t.data.data),t.data.status,null==t.data.data&&(t.data.data=[]),n&&n(t.data)}})}catch(c){o&&o()}},10)},tabClick:function(t){this.filterIndex===t&&2!==t||(this.filterIndex=t,this.priceOrder=2===t?1===this.priceOrder?2:1:0,this.mescroll.triggerDownScroll())},toggleCateMask:function(t){var e=this,a="show"===t?10:300,i="show"===t?1:0;this.cateMaskState=2,setTimeout(function(){e.cateMaskState=i},a)},changeCate:function(e){this.cateId=e.id,this.toggleCateMask(),t.pageScrollTo({duration:300,scrollTop:0}),this.mescroll.triggerDownScroll()},navToDetailPage:function(e){var a=e.id;t.navigateTo({url:"/pages/product/product?id=".concat(a)})},stopPrevent:function(){},addShopCar:function(e){console.log("加入购物车");var a=this;console.log(e.currentTarget.id),t.request({url:o.default.dui_cart_goods_add,data:{actiontype:"add",is_mobile:1,user_id:a.userInfo.id,article_id:e.currentTarget.id,goods_id:0,quantity:1,hot_id:0},method:"POST",header:{"content-type":"application/x-www-form-urlencoded"},success:function(t){console.log(t.data.status),1==t.data.status&&(a.$refs.carAnmation.touchOnGoods(e),a.totalAmount=t.data.amount)}})},submit:function(){t.navigateTo({url:"/pages/shop/createOrder"})}}};e.default=d}).call(this,a("6e42")["default"])},"4fb7":function(t,e,a){"use strict";a("feb3");var i=o(a("b0ce")),n=o(a("5afb"));function o(t){return t&&t.__esModule?t:{default:t}}Page((0,i.default)(n.default))},5994:function(t,e,a){"use strict";var i=a("c041"),n=a.n(i);n.a},"5afb":function(t,e,a){"use strict";a.r(e);var i=a("d57d"),n=a("b351");for(var o in n)"default"!==o&&function(t){a.d(e,t,function(){return n[t]})}(o);a("5994");var s=a("2877"),c=Object(s["a"])(n["default"],i["a"],i["b"],!1,null,null,null);e["default"]=c.exports},b351:function(t,e,a){"use strict";a.r(e);var i=a("4e59"),n=a.n(i);for(var o in i)"default"!==o&&function(t){a.d(e,t,function(){return i[t]})}(o);e["default"]=n.a},c041:function(t,e,a){},d57d:function(t,e,a){"use strict";var i=function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("view",{staticClass:"content"},[a("div",{directives:[{name:"title",rawName:"v-title"}],staticClass:"main",attrs:{"data-title":"登录"}}),a("view",{staticClass:"navbar",style:{position:t.headerPosition,top:t.headerTop}},[a("view",{staticClass:"nav-item",class:{current:0===t.filterIndex},attrs:{eventid:"3de9b805-0"},on:{click:function(e){t.tabClick(0)}}},[t._v("综合排序")]),a("view",{staticClass:"nav-item",class:{current:1===t.filterIndex},attrs:{eventid:"3de9b805-1"},on:{click:function(e){t.tabClick(1)}}},[t._v("销量优先")]),a("view",{staticClass:"nav-item",class:{current:2===t.filterIndex},attrs:{eventid:"3de9b805-2"},on:{click:function(e){t.tabClick(2)}}},[a("text",[t._v("价格")]),a("view",{staticClass:"p-box"},[a("text",{staticClass:"yticon icon-shang",class:{active:1===t.priceOrder&&2===t.filterIndex}}),a("text",{staticClass:"yticon icon-shang xia",class:{active:2===t.priceOrder&&2===t.filterIndex}})])]),a("text",{staticClass:"cate-item yticon icon-fenlei1",attrs:{eventid:"3de9b805-3"},on:{click:function(e){t.toggleCateMask("show")}}})]),a("mescroll-uni",{attrs:{eventid:"3de9b805-5",mpcomid:"3de9b805-0"},on:{down:t.downCallback,up:t.upCallback,init:t.mescrollInit}},[a("view",{staticClass:"goods-list"},t._l(t.goodsList,function(e,i){return a("view",{key:i,staticClass:"goods-item"},[a("view",{staticClass:"image-wrapper"},[a("image",{attrs:{src:e.icon,mode:"aspectFill"}})]),a("text",{staticClass:"title clamp"},[t._v(t._s(e.title))]),a("view",{staticClass:"price-box"},[a("text",{staticClass:"price"},[t._v(t._s(e.price))]),a("view",{staticClass:"pb-car iconfont",attrs:{id:e.id,"data-img":e.icon,eventid:"3de9b805-4-"+i},on:{tap:t.addShopCar}},[a("image",{attrs:{src:"../../static/jiaru.png"}})])])])}))]),a("view",{staticClass:"cate-mask",class:0===t.cateMaskState?"none":1===t.cateMaskState?"show":"",attrs:{eventid:"3de9b805-8"},on:{click:t.toggleCateMask}},[a("view",{staticClass:"cate-content",attrs:{eventid:"3de9b805-7"},on:{click:function(e){e.stopPropagation(),e.preventDefault(),t.stopPrevent(e)},touchmove:function(e){e.stopPropagation(),e.preventDefault(),t.stopPrevent(e)}}},[a("scroll-view",{staticClass:"cate-list",attrs:{"scroll-y":""}},t._l(t.cateList,function(e,i){return a("view",{key:e.id},[a("view",{staticClass:"cate-item b-b two"},[t._v(t._s(e.title))]),t._l(e.child,function(e,n){return a("view",{key:e.id,staticClass:"cate-item b-b",class:{active:e.id==t.cateId},attrs:{eventid:"3de9b805-6-"+i+"-"+n},on:{click:function(a){t.changeCate(e)}}},[t._v(t._s(e.title))])})],2)}))],1)]),a("view",{staticClass:"footer"},[a("view",{staticClass:"price-content"},[a("text",[t._v("已选择")]),a("text",{staticClass:"price-tip"},[t._v("￥")]),a("text",{staticClass:"price"},[t._v(t._s(t.totalAmount))])]),a("text",{staticClass:"submit",attrs:{eventid:"3de9b805-9"},on:{click:t.submit}},[t._v("查看兑换")])]),a("shopCarAnimation",{ref:"carAnmation",attrs:{cartx:"0.1",carty:"1.1",mpcomid:"3de9b805-1"}})],1)},n=[];a.d(e,"a",function(){return i}),a.d(e,"b",function(){return n})}},[["4fb7","common/runtime","common/vendor"]]]);