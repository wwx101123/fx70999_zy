(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["pages-category-category"],{1950:function(t,e,i){"use strict";var n=function(){var t=this,e=t.$createElement,i=t._self._c||e;return i("v-uni-view",{staticClass:"content"},[i("v-uni-scroll-view",{staticClass:"left-aside",attrs:{"scroll-y":""}},t._l(t.flist,function(e){return i("v-uni-view",{key:e.id,staticClass:"f-item b-b",class:{active:e.id===t.currentId},on:{click:function(i){i=t.$handleEvent(i),t.tabtap(e)}}},[t._v(t._s(e.name))])}),1),i("v-uni-scroll-view",{staticClass:"right-aside",attrs:{"scroll-with-animation":"","scroll-y":"","scroll-top":t.tabScrollTop},on:{scroll:function(e){e=t.$handleEvent(e),t.asideScroll(e)}}},t._l(t.slist,function(e){return i("v-uni-view",{key:e.id,staticClass:"s-list",attrs:{id:"main-"+e.id}},[i("v-uni-text",{staticClass:"s-item"},[t._v(t._s(e.name))]),i("v-uni-view",{staticClass:"t-list"},t._l(t.tlist,function(n){return n.pid===e.id?i("v-uni-view",{key:n.id,staticClass:"t-item",on:{click:function(i){i=t.$handleEvent(i),t.navToList(e.id,e.id,e.title)}}},[i("v-uni-image",{attrs:{src:n.picture}}),i("v-uni-text",[t._v(t._s(n.name))])],1):t._e()}),1)],1)}),1)],1)},a=[];i.d(e,"a",function(){return n}),i.d(e,"b",function(){return a})},"1d49":function(t,e,i){"use strict";var n=i("d79e"),a=i.n(n);a.a},"350a":function(t,e,i){e=t.exports=i("2350")(!1),e.push([t.i,'@charset "UTF-8";\n/* 页面左右间距 */\n/* 文字尺寸 */\n/*文字颜色*/\n/* 边框颜色 */\n/* 图片加载中颜色 */\n/* 行为相关颜色 */.content[data-v-052c49ec],uni-page-body[data-v-052c49ec]{height:100%;background-color:#f8f8f8}.content[data-v-052c49ec]{display:-webkit-box;display:-webkit-flex;display:-ms-flexbox;display:flex}.left-aside[data-v-052c49ec]{-webkit-flex-shrink:0;-ms-flex-negative:0;flex-shrink:0;width:%?200?%;height:100%;background-color:#fff}.f-item[data-v-052c49ec]{display:-webkit-box;display:-webkit-flex;display:-ms-flexbox;display:flex;-webkit-box-align:center;-webkit-align-items:center;-ms-flex-align:center;align-items:center;-webkit-box-pack:center;-webkit-justify-content:center;-ms-flex-pack:center;justify-content:center;width:100%;height:%?100?%;font-size:%?28?%;color:#606266;position:relative}.f-item.active[data-v-052c49ec]{color:#fa436a;background:#f8f8f8}.f-item.active[data-v-052c49ec]:before{content:"";position:absolute;left:0;top:50%;-webkit-transform:translateY(-50%);-ms-transform:translateY(-50%);transform:translateY(-50%);height:%?36?%;width:%?8?%;background-color:#fa436a;border-radius:0 4px 4px 0;opacity:.8}.right-aside[data-v-052c49ec]{-webkit-box-flex:1;-webkit-flex:1;-ms-flex:1;flex:1;overflow:hidden;padding-left:%?20?%}.s-item[data-v-052c49ec]{display:-webkit-box;display:-webkit-flex;display:-ms-flexbox;display:flex;-webkit-box-align:center;-webkit-align-items:center;-ms-flex-align:center;align-items:center;height:%?70?%;padding-top:%?8?%;font-size:%?28?%;color:#303133}.t-list[data-v-052c49ec]{display:-webkit-box;display:-webkit-flex;display:-ms-flexbox;display:flex;-webkit-flex-wrap:wrap;-ms-flex-wrap:wrap;flex-wrap:wrap;width:100%;background:#fff;padding-top:%?12?%}.t-list[data-v-052c49ec]:after{content:"";-webkit-box-flex:99;-webkit-flex:99;-ms-flex:99;flex:99;height:0}.t-item[data-v-052c49ec]{-webkit-flex-shrink:0;-ms-flex-negative:0;flex-shrink:0;display:-webkit-box;display:-webkit-flex;display:-ms-flexbox;display:flex;-webkit-box-pack:center;-webkit-justify-content:center;-ms-flex-pack:center;justify-content:center;-webkit-box-align:center;-webkit-align-items:center;-ms-flex-align:center;align-items:center;-webkit-box-orient:vertical;-webkit-box-direction:normal;-webkit-flex-direction:column;-ms-flex-direction:column;flex-direction:column;width:%?176?%;font-size:%?26?%;color:#666;padding-bottom:%?20?%}.t-item uni-image[data-v-052c49ec]{width:%?140?%;height:%?140?%}body.?%PAGE?%[data-v-052c49ec]{background-color:#f8f8f8}',""])},"45a2":function(t,e,i){"use strict";Object.defineProperty(e,"__esModule",{value:!0}),e.default=void 0;var n=s(i("a34a")),a=s(i("6511"));function s(t){return t&&t.__esModule?t:{default:t}}function c(t,e,i,n,a,s,c){try{var l=t[s](c),r=l.value}catch(o){return void i(o)}l.done?e(r):Promise.resolve(r).then(n,a)}function l(t){return function(){var e=this,i=arguments;return new Promise(function(n,a){var s=t.apply(e,i);function l(t){c(s,n,a,l,r,"next",t)}function r(t){c(s,n,a,l,r,"throw",t)}l(void 0)})}}var r={data:function(){return{sizeCalcState:!1,tabScrollTop:0,currentId:1,flist:[],slist:[],tlist:[]}},onLoad:function(){this.loadData()},methods:{loadData:function(){var t=l(n.default.mark(function t(){var e,i;return n.default.wrap(function(t){while(1)switch(t.prev=t.next){case 0:return e=this,t.next=3,this.$api.json("cateList");case 3:i=t.sent,uni.request({url:a.default.category_listUrl,data:{is_mobile:1,user_id:0},method:"POST",header:{"content-type":"application/x-www-form-urlencoded"},success:function(t){console.log(t.data.status),1==t.data.status&&(e.flist=[],e.slist=[],e.tlist=[],i=t.data.data,i.forEach(function(t){0==t.pid?(e.flist.push(t),e.slist.push(t)):1==t.class_layer||e.tlist.push(t)}),e.currentId=t.data.currentId)}});case 5:case"end":return t.stop()}},t,this)}));function e(){return t.apply(this,arguments)}return e}(),tabtap:function(t){this.sizeCalcState||this.calcSize(),this.currentId=t.id;var e=this.slist.findIndex(function(e){return e.fid===t.id});this.tabScrollTop=this.slist[e].top},asideScroll:function(t){this.sizeCalcState||this.calcSize();var e=t.detail.scrollTop,i=this.slist.filter(function(t){return t.top<=e}).reverse();i.length>0&&(this.currentId=i[0].fid)},calcSize:function(){var t=0;this.slist.forEach(function(e){var i=uni.createSelectorQuery().select("#main-"+e.id);i.fields({size:!0},function(i){e.top=t,t+=i.height,e.bottom=t}).exec()}),this.sizeCalcState=!0},navToList:function(t,e,i){uni.navigateTo({url:"/pages/product/list?fid=".concat(this.currentId,"&sid=").concat(t,"&tid=").concat(e,"&title=").concat(i)})}}};e.default=r},9684:function(t,e,i){"use strict";i.r(e);var n=i("45a2"),a=i.n(n);for(var s in n)"default"!==s&&function(t){i.d(e,t,function(){return n[t]})}(s);e["default"]=a.a},d79e:function(t,e,i){var n=i("350a");"string"===typeof n&&(n=[[t.i,n,""]]),n.locals&&(t.exports=n.locals);var a=i("4f06").default;a("ecdb7178",n,!0,{sourceMap:!1,shadowMode:!1})},ec63:function(t,e,i){"use strict";i.r(e);var n=i("1950"),a=i("9684");for(var s in a)"default"!==s&&function(t){i.d(e,t,function(){return a[t]})}(s);i("1d49");var c=i("2877"),l=Object(c["a"])(a["default"],n["a"],n["b"],!1,null,"052c49ec",null);e["default"]=l.exports}}]);