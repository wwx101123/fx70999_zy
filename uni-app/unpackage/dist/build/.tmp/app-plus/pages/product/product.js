(global["webpackJsonp"]=global["webpackJsonp"]||[]).push([["pages/product/product"],{"334f":function(t,e,s){"use strict";var a=s("8c04"),i=s.n(a);i.a},"3d74":function(t,e,s){"use strict";var a=function(){var t=this,e=t.$createElement,s=t._self._c||e;return s("view",{staticClass:"container"},[s("view",{staticClass:"carousel"},[s("swiper",{attrs:{"indicator-dots":"",circular:"true",duration:"400"}},t._l(t.imgList,function(t,e){return s("swiper-item",{key:e,staticClass:"swiper-item",attrs:{mpcomid:"05eb5096-0-"+e}},[s("view",{staticClass:"image-wrapper"},[s("image",{staticClass:"loaded",attrs:{src:t.src,mode:"aspectFill"}})])])}))],1),s("view",{staticClass:"introduce-section"},[s("text",{staticClass:"title"},[t._v(t._s(t.goods.title))]),s("view",{staticClass:"price-box"},[s("text",{staticClass:"price-tip"},[t._v("¥")]),s("text",{staticClass:"price"},[t._v(t._s(t.goods.market_price))]),s("text",{staticClass:"m-price"},[t._v("¥"+t._s(t.goods.price))])]),s("view",{staticClass:"bot-row"},[s("text",[t._v("销量: "+t._s(t.goods.sell_count))]),s("text",[t._v("库存: "+t._s(t.goods.stock))]),s("text",[t._v("浏览量: "+t._s(t.goods.click))])])]),s("view",{staticClass:"share-section",attrs:{eventid:"05eb5096-0"},on:{click:t.share}},[t._m(0),s("text",{staticClass:"tit"},[t._v("该商品分享可领49减10红包")]),s("text",{staticClass:"yticon icon-bangzhu1"}),t._m(1)]),s("view",{staticClass:"cu-list menu-avatar eva-section"},[s("view",{staticClass:"cu-item b-b"},[s("view",{staticClass:"cu-avatar round lg",staticStyle:{"background-image":"url(../../static/logo.png)"}}),s("view",{staticClass:"content flex-sub"},[s("view",{staticClass:"text-grey",staticStyle:{color:"black"}},[t._v(t._s(t.seller.title))]),s("view",{staticClass:"text-gray text-sm flex justify-between"},[t._v("商品数量:"+t._s(t.seller.goods_num))])]),s("button",{staticClass:"cu-btn bg-green shadow",staticStyle:{float:"right",width:"80px"},attrs:{eventid:"05eb5096-1"},on:{click:function(e){t.navTo(t.seller.url)}}},[t._v("进店逛逛")])],1),t._m(2)]),s("view",{staticClass:"c-list"}),t._m(3),s("view",{staticClass:"detail-desc"},[t._m(4),s("rich-text",{staticClass:"content",attrs:{nodes:t.desc,mpcomid:"05eb5096-1"}})],1),s("view",{staticClass:"page-bottom"},[s("navigator",{staticClass:"p-b-btn",attrs:{url:"/pages/index/index","open-type":"switchTab"}},[s("text",{staticClass:"yticon icon-xiatubiao--copy"}),s("text",[t._v("首页")])]),s("navigator",{staticClass:"p-b-btn",attrs:{url:"/pages/cart/cart","open-type":"switchTab"}},[s("view",{staticClass:"cu-tag badge"},[t._v(t._s(t.cart_count))]),s("text",{staticClass:"yticon icon-gouwuche"}),s("text",[t._v("购物车")])]),s("view",{staticClass:"p-b-btn",class:{active:t.favorite},attrs:{eventid:"05eb5096-2"},on:{click:t.toFavorite}},[s("text",{staticClass:"yticon icon-shoucang"}),s("text",[t._v("收藏")])]),s("view",{staticClass:"action-btn-group"},[s("button",{staticClass:" action-btn no-border buy-now-btn",attrs:{type:"primary",eventid:"05eb5096-3"},on:{click:t.buy}},[t._v("立即购买")]),s("button",{staticClass:" action-btn no-border add-cart-btn",attrs:{type:"primary","data-img":t.goods.cart_img,eventid:"05eb5096-4"},on:{click:t.addShopCar}},[t._v("加入购物车")])],1)],1),s("view",{staticClass:"popup spec",class:t.specClass,attrs:{eventid:"05eb5096-8"},on:{touchmove:function(e){e.stopPropagation(),e.preventDefault(),t.stopPrevent(e)},click:t.toggleSpec}},[s("view",{staticClass:"mask"}),s("view",{staticClass:"layer attr-content",attrs:{eventid:"05eb5096-7"},on:{click:function(e){e.stopPropagation(),t.stopPrevent(e)}}},[s("view",{staticClass:"a-t"},[s("image",{attrs:{src:"https://gd3.alicdn.com/imgextra/i3/0/O1CN01IiyFQI1UGShoFKt1O_!!0-item_pic.jpg_400x400.jpg"}}),s("view",{staticClass:"right"},[s("text",{staticClass:"price"},[t._v("¥328.00")]),s("text",{staticClass:"stock"},[t._v("库存：188件")]),s("view",{staticClass:"selected"},[t._v("已选："),t._l(t.specSelected,function(e,a){return s("text",{key:a,staticClass:"selected-text"},[t._v(t._s(e.name))])})],2)])]),t._l(t.specList,function(e,a){return s("view",{key:a,staticClass:"attr-list"},[s("text",[t._v(t._s(e.name))]),s("view",{staticClass:"item-list"},t._l(t.specChildList,function(i,c){return i.pid===e.id?s("text",{key:c,staticClass:"tit",class:{selected:i.selected},attrs:{eventid:"05eb5096-5-"+a+"-"+c},on:{click:function(e){t.selectSpec(c,i.pid)}}},[t._v(t._s(i.name))]):t._e()}))])}),s("button",{staticClass:"btn",attrs:{eventid:"05eb5096-6"},on:{click:t.toggleSpec}},[t._v("完成")])],2)]),s("share",{ref:"share",attrs:{contentHeight:580,shareList:t.shareList,mpcomid:"05eb5096-2"}}),s("shopCarAnimation",{ref:"carAnmation",attrs:{cartx:"0.1",carty:"1.1",mpcomid:"05eb5096-3"}})],1)},i=[function(){var t=this,e=t.$createElement,s=t._self._c||e;return s("view",{staticClass:"share-icon"},[s("text",{staticClass:"yticon icon-xingxing"}),t._v("返")])},function(){var t=this,e=t.$createElement,s=t._self._c||e;return s("view",{staticClass:"share-btn"},[t._v("立即分享"),s("text",{staticClass:"yticon icon-you"})])},function(){var t=this,e=t.$createElement,s=t._self._c||e;return s("view",{staticClass:"padding flex flex-wrap justify-between align-center bg-white"},[s("view",{staticClass:"cu-capsule round"},[s("view",{staticClass:"cu-tag bg-blue "},[t._v("描述相符")]),s("view",{staticClass:"cu-tag line-blue"},[t._v("高")])]),s("view",{staticClass:"cu-capsule round"},[s("view",{staticClass:"cu-tag bg-brown "},[t._v("服务态度")]),s("view",{staticClass:"cu-tag line-brown"},[t._v("高")])]),s("view",{staticClass:"cu-capsule round"},[s("view",{staticClass:"cu-tag bg-red "},[t._v("物流服务")]),s("view",{staticClass:"cu-tag line-red"},[t._v("高")])])])},function(){var t=this,e=t.$createElement,s=t._self._c||e;return s("view",{staticClass:"eva-section"},[s("view",{staticClass:"e-header"},[s("text",{staticClass:"tit"},[t._v("评价")]),s("text",[t._v("(86)")]),s("text",{staticClass:"tip"},[t._v("好评率 100%")]),s("text",{staticClass:"yticon icon-you"})]),s("view",{staticClass:"eva-box"},[s("image",{staticClass:"portrait",attrs:{src:"http://img3.imgtn.bdimg.com/it/u=1150341365,1327279810&fm=26&gp=0.jpg",mode:"aspectFill"}}),s("view",{staticClass:"right"},[s("text",{staticClass:"name"},[t._v("Leo yo")]),s("text",{staticClass:"con"},[t._v("商品收到了，79元两件，质量不错，试了一下有点瘦，但是加个外罩很漂亮，我很喜欢")]),s("view",{staticClass:"bot"},[s("text",{staticClass:"attr"},[t._v("购买类型：XL 红色")]),s("text",{staticClass:"time"},[t._v("2019-04-01 19:21")])])])])])},function(){var t=this,e=t.$createElement,s=t._self._c||e;return s("view",{staticClass:"d-header"},[s("text",[t._v("图文详情")])])}];s.d(e,"a",function(){return a}),s.d(e,"b",function(){return i})},6843:function(t,e,s){"use strict";s.r(e);var a=s("a10c"),i=s.n(a);for(var c in a)"default"!==c&&function(t){s.d(e,t,function(){return a[t]})}(c);e["default"]=i.a},"8c04":function(t,e,s){},a10c:function(t,e,s){"use strict";(function(t){Object.defineProperty(e,"__esModule",{value:!0}),e.default=void 0;var a=r(s("a34a")),i=r(s("d9c1")),c=r(s("6511")),n=r(s("ffa9")),o=s("2f62");function r(t){return t&&t.__esModule?t:{default:t}}function l(t,e,s,a,i,c,n){try{var o=t[c](n),r=o.value}catch(l){return void s(l)}o.done?e(r):Promise.resolve(r).then(a,i)}function d(t){return function(){var e=this,s=arguments;return new Promise(function(a,i){var c=t.apply(e,s);function n(t){l(c,a,i,n,o,"next",t)}function o(t){l(c,a,i,n,o,"throw",t)}n(void 0)})}}function u(t){for(var e=1;e<arguments.length;e++){var s=null!=arguments[e]?arguments[e]:{},a=Object.keys(s);"function"===typeof Object.getOwnPropertySymbols&&(a=a.concat(Object.getOwnPropertySymbols(s).filter(function(t){return Object.getOwnPropertyDescriptor(s,t).enumerable}))),a.forEach(function(e){v(t,e,s[e])})}return t}function v(t,e,s){return e in t?Object.defineProperty(t,e,{value:s,enumerable:!0,configurable:!0,writable:!0}):t[e]=s,t}var f={components:{share:n.default,shopCarAnimation:i.default},computed:u({},(0,o.mapState)(["hasLogin","userInfo","bi","goods_id"])),data:function(){return{cart_count:0,seller:{},goods:{},goods_id:0,specClass:"none",specSelected:[],favorite:!0,shareList:[],imgList:[],desc:"",specList:[{id:1,name:"尺寸"},{id:2,name:"颜色"}],specChildList:[{id:1,pid:1,name:"XS"},{id:2,pid:1,name:"S"},{id:3,pid:1,name:"M"},{id:4,pid:1,name:"L"},{id:5,pid:1,name:"XL"},{id:6,pid:1,name:"XXL"},{id:7,pid:2,name:"白色"},{id:8,pid:2,name:"珊瑚粉"},{id:9,pid:2,name:"草木绿"}]}},onLoad:function(){var e=d(a.default.mark(function e(s){var i,c,n;return a.default.wrap(function(e){while(1)switch(e.prev=e.next){case 0:i=this,console.log(i.hasLogin),i.hasLogin||(c="/pages/public/login",t.navigateTo({url:c})),n=s.id,n&&(i.goods_id=n),i.detail(i.userInfo,n);case 6:case"end":return e.stop()}},e,this)}));function s(t){return e.apply(this,arguments)}return s}(),methods:{navTo:function(e){t.navigateTo({url:e})},detail:function(e,s){var a,i=this;t.request({url:c.default.goods_editUrl,data:(a={is_mobile:1,user_id:0,func:"show"},v(a,"user_id",e.id),v(a,"id",s),a),method:"POST",header:{"content-type":"application/x-www-form-urlencoded"},success:function(t){if(1==t.data.status){var e=t.data.data.slider;i.imgList=e,i.goods=t.data.data,i.seller=t.data.seller,console.log(i.seller.id),i.goods_id=t.data.data.id,i.desc=t.data.data.pc_content,console.log(t.data.cart_count),i.cart_count=t.data.cart_count}}}),i.specList.forEach(function(t){var e=!0,s=!1,a=void 0;try{for(var c,n=i.specChildList[Symbol.iterator]();!(e=(c=n.next()).done);e=!0){var o=c.value;if(o.pid===t.id){i.$set(o,"selected",!0),i.specSelected.push(o);break}}}catch(r){s=!0,a=r}finally{try{e||null==n.return||n.return()}finally{if(s)throw a}}})},toggleSpec:function(){var t=this;"show"===this.specClass?(this.specClass="hide",setTimeout(function(){t.specClass="none"},250)):"none"===this.specClass&&(this.specClass="show")},selectSpec:function(t,e){var s=this,a=this.specChildList;a.forEach(function(t){t.pid===e&&s.$set(t,"selected",!1)}),this.$set(a[t],"selected",!0),this.specSelected=[],a.forEach(function(t){!0===t.selected&&s.specSelected.push(t)})},share:function(){this.$refs.share.toggleMask()},toFavorite:function(){this.favorite=!this.favorite},buy:function(){var e=this;console.log(e.userInfo.id),t.request({url:c.default.cart_goods_addUrl,data:{actiontype:"buy",is_mobile:1,user_id:e.userInfo.id,article_id:e.goods.id,goods_id:0,quantity:1,hot_id:0},method:"POST",header:{"content-type":"application/x-www-form-urlencoded"},success:function(e){console.log(e.data.status),1==e.data.status&&t.navigateTo({url:"/pages/order/createOrder"})}})},addShopCar:function(e){console.log("加入购物车");var s=this;console.log(s.userInfo.id),t.request({url:c.default.cart_goods_addUrl,data:{actiontype:"add",is_mobile:1,user_id:s.userInfo.id,article_id:s.goods.id,goods_id:0,quantity:1,hot_id:0},method:"POST",header:{"content-type":"application/x-www-form-urlencoded"},success:function(t){console.log(t.data.status),1==t.data.status&&(s.$refs.carAnmation.touchOnGoods(e),s.detail(s.userInfo,s.goods_id))}})},stopPrevent:function(){},refresh:function(){var t=this;this.detail(t.userInfo,t.goods_id)}}};e.default=f}).call(this,s("6e42")["default"])},ba82:function(t,e,s){"use strict";var a=s("e3e7"),i=s.n(a);i.a},cf6b:function(t,e,s){"use strict";s("feb3");var a=c(s("b0ce")),i=c(s("d730"));function c(t){return t&&t.__esModule?t:{default:t}}Page((0,a.default)(i.default))},d730:function(t,e,s){"use strict";s.r(e);var a=s("3d74"),i=s("6843");for(var c in i)"default"!==c&&function(t){s.d(e,t,function(){return i[t]})}(c);s("334f"),s("ba82");var n=s("2877"),o=Object(n["a"])(i["default"],a["a"],a["b"],!1,null,"3cbb5afe",null);e["default"]=o.exports},e3e7:function(t,e,s){}},[["cf6b","common/runtime","common/vendor"]]]);