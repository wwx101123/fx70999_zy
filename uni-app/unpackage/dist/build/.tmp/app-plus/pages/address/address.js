(global["webpackJsonp"]=global["webpackJsonp"]||[]).push([["pages/address/address"],{"29bc":function(t,e,a){"use strict";a.r(e);var n=a("6e50"),s=a("75f8");for(var r in s)"default"!==r&&function(t){a.d(e,t,function(){return s[t]})}(r);a("5f31");var c=a("2877"),i=Object(c["a"])(s["default"],n["a"],n["b"],!1,null,null,null);e["default"]=i.exports},3924:function(t,e,a){"use strict";a("feb3");var n=r(a("b0ce")),s=r(a("29bc"));function r(t){return t&&t.__esModule?t:{default:t}}Page((0,n.default)(s.default))},"5f31":function(t,e,a){"use strict";var n=a("b0d1"),s=a.n(n);s.a},"6e50":function(t,e,a){"use strict";var n=function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("view",{staticClass:"content b-t"},[t._l(t.addressList,function(e,n){return a("view",{key:n,staticClass:"list b-b",attrs:{eventid:"77d3ccc0-1-"+n},on:{click:function(a){t.checkAddress(e)}}},[a("view",{staticClass:"wrapper"},[a("view",{staticClass:"address-box"},[e.default?a("text",{staticClass:"tag"},[t._v("默认")]):t._e(),a("text",{staticClass:"address"},[t._v(t._s(e.address)+" "+t._s(e.addressName))])]),a("view",{staticClass:"u-box"},[a("text",{staticClass:"name"},[t._v(t._s(e.name))]),a("text",{staticClass:"mobile"},[t._v(t._s(e.mobile))])])]),a("text",{staticClass:"yticon icon-bianji",attrs:{eventid:"77d3ccc0-0-"+n},on:{click:function(a){a.stopPropagation(),t.addAddress("edit",e)}}})])}),a("text",{staticStyle:{display:"block",padding:"16rpx 30rpx 10rpx","lihe-height":"1.6",color:"#fa436a","font-size":"24rpx"}},[t._v("重要：添加和修改地址回调仅增加了一条数据做演示，实际开发中将回调改为请求后端接口刷新一下列表即可")]),a("button",{staticClass:"add-btn",attrs:{eventid:"77d3ccc0-2"},on:{click:function(e){t.addAddress("add")}}},[t._v("新增地址")])],2)},s=[];a.d(e,"a",function(){return n}),a.d(e,"b",function(){return s})},"75f8":function(t,e,a){"use strict";a.r(e);var n=a("f07c"),s=a.n(n);for(var r in n)"default"!==r&&function(t){a.d(e,t,function(){return n[t]})}(r);e["default"]=s.a},b0d1:function(t,e,a){},f07c:function(t,e,a){"use strict";(function(t){Object.defineProperty(e,"__esModule",{value:!0}),e.default=void 0;var n=r(a("6511")),s=a("2f62");function r(t){return t&&t.__esModule?t:{default:t}}function c(t){for(var e=1;e<arguments.length;e++){var a=null!=arguments[e]?arguments[e]:{},n=Object.keys(a);"function"===typeof Object.getOwnPropertySymbols&&(n=n.concat(Object.getOwnPropertySymbols(a).filter(function(t){return Object.getOwnPropertyDescriptor(a,t).enumerable}))),n.forEach(function(e){i(t,e,a[e])})}return t}function i(t,e,a){return e in t?Object.defineProperty(t,e,{value:a,enumerable:!0,configurable:!0,writable:!0}):t[e]=a,t}var o={provide:function(){return{reload:this.reload}},data:function(){return{source:0,addressList:[]}},inject:["reload"],onLoad:function(e){var a=this;console.log(this.userInfo.id),t.request({url:n.default.get_user_addr_bookUrl,data:{is_mobile:1,user_id:this.userInfo.id},method:"POST",header:{"content-type":"application/x-www-form-urlencoded"},success:function(t){1==t.data.status&&(console.log(JSON.stringify(t.data.data)),a.addressList=t.data.data)}}),this.source=e.source},computed:c({},(0,s.mapState)(["hasLogin","userInfo","bi"])),methods:{checkAddress:function(e){1==this.source&&(this.$api.prePage().addressData=e,t.navigateBack())},addAddress:function(e,a){t.navigateTo({url:"/pages/address/addressManage?type=".concat(e,"&data=").concat(JSON.stringify(a))})},refreshList:function(t,e){console.log(t,e)}}};e.default=o}).call(this,a("6e42")["default"])}},[["3924","common/runtime","common/vendor"]]]);