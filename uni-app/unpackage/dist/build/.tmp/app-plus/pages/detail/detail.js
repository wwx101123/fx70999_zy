(global["webpackJsonp"]=global["webpackJsonp"]||[]).push([["pages/detail/detail"],{"060c":function(t,e,a){},"27c2":function(t,e,a){"use strict";a.r(e);var s=a("3e2d"),i=a("566b");for(var n in i)"default"!==n&&function(t){a.d(e,t,function(){return i[t]})}(n);a("a8db");var c=a("2877"),r=Object(c["a"])(i["default"],s["a"],s["b"],!1,null,null,null);e["default"]=r.exports},"3e2d":function(t,e,a){"use strict";var s=function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("view",[a("swiper",{staticClass:"carousel",attrs:{"indicator-dots":"true",circular:"true",interval:"3000",duration:"700"}},t._l(t.data.imgList,function(e,s){return a("swiper-item",{key:s,attrs:{mpcomid:"1cb77256-0-"+s}},[a("view",{staticClass:"image-wrapper"},[a("image",{class:e.loaded,attrs:{src:e.src,mode:"aspectFill",eventid:"1cb77256-0-"+s},on:{load:function(e){t.imageOnLoad("imgList",s)}}})])])})),a("view",{staticClass:"scroll-view-wrapper"},[a("scroll-view",{staticClass:"episode-panel",class:{Skeleton:!t.loaded},attrs:{"scroll-x":""}},t._l(t.data.episodeList,function(e,s){return a("view",{key:s,class:{current:t.currentEpd===e},attrs:{eventid:"1cb77256-1-"+s},on:{click:function(e){t.changeEpd(s)}}},[t._v(t._s(e))])}))],1),a("view",{staticClass:"info"},[a("view",{staticClass:"title"},[a("text",{class:{Skeleton:!t.loaded}},[t._v(t._s(t.data.title))]),a("text",{class:{Skeleton:!t.loaded}},[t._v(t._s(t.data.title2))])]),a("text",{staticClass:"yticon icon-xia"})]),a("view",{staticClass:"actions"},[a("text",{staticClass:"yticon icon-fenxiang2",attrs:{eventid:"1cb77256-2"},on:{click:t.share}}),a("text",{staticClass:"yticon icon-Group-"}),a("text",{staticClass:"yticon icon-shoucang",class:{active:t.data.favorite},attrs:{eventid:"1cb77256-3"},on:{click:t.favorite}})]),a("view",{staticClass:"guess"},[a("view",{staticClass:"section-tit"},[t._v("猜你喜欢")]),a("view",{staticClass:"guess-list"},t._l(t.data.guessList,function(e,s){return a("view",{key:s,staticClass:"guess-item"},[a("view",{staticClass:"image-wrapper"},[a("image",{class:e.loaded,attrs:{src:e.src,mode:"aspectFill",eventid:"1cb77256-4-"+s},on:{load:function(e){t.imageOnLoad("guessList",s)}}})]),a("text",{staticClass:"title clamp",class:{Skeleton:!t.loaded}},[t._v(t._s(e.title))]),a("text",{staticClass:"clamp",class:{Skeleton:!t.loaded}},[t._v(t._s(e.title2))])])}))]),a("view",{staticClass:"evalution"},[a("view",{staticClass:"section-tit"},[t._v("评论")]),a("view",{staticClass:"eva-list",class:{Skeleton:!t.loaded}},t._l(t.data.evaList,function(e,s){return a("view",{key:s,staticClass:"eva-item"},[a("image",{attrs:{src:e.src,mode:"aspectFill"}}),a("view",{staticClass:"eva-right"},[a("text",[t._v(t._s(e.nickname))]),a("text",[t._v(t._s(e.time))]),a("view",{staticClass:"zan-box"},[a("text",[t._v(t._s(e.zan))]),a("text",{staticClass:"yticon icon-shoucang"})]),a("text",{staticClass:"content"},[t._v(t._s(e.content))])])])}))]),a("share",{ref:"share",attrs:{contentHeight:580,shareList:t.shareList,mpcomid:"1cb77256-1"}})],1)},i=[];a.d(e,"a",function(){return s}),a.d(e,"b",function(){return i})},4758:function(t,e,a){"use strict";(function(t){Object.defineProperty(e,"__esModule",{value:!0}),e.default=void 0;var s=n(a("a34a")),i=n(a("ffa9"));function n(t){return t&&t.__esModule?t:{default:t}}function c(t,e,a,s,i,n,c){try{var r=t[n](c),o=r.value}catch(l){return void a(l)}r.done?e(o):Promise.resolve(o).then(s,i)}function r(t){return function(){var e=this,a=arguments;return new Promise(function(s,i){var n=t.apply(e,a);function r(t){c(n,s,i,r,o,"next",t)}function o(t){c(n,s,i,r,o,"throw",t)}r(void 0)})}}var o={components:{share:i.default},data:function(){return{loaded:!1,currentEpd:1,data:{guessList:[{},{},{},{}]},shareList:[]}},onLoad:function(){var e=r(s.default.mark(function e(){var a,i;return s.default.wrap(function(e){while(1)switch(e.prev=e.next){case 0:return e.next=2,this.$api.json("detailData");case 2:return a=e.sent,e.next=5,this.$api.json("shareList");case 5:i=e.sent,this.loaded=!0,this.data=a,this.shareList=i,t.setNavigationBarTitle({title:a.title});case 10:case"end":return e.stop()}},e,this)}));function a(){return e.apply(this,arguments)}return a}(),methods:{imageOnLoad:function(t,e){this.$set(this.data[t][e],"loaded","loaded")},changeEpd:function(t){var e=this.data.episodeList,a=e[t];this.$api.msg("切换到第".concat(a,"项")),this.currentEpd=a},share:function(){this.$refs.share.toggleMask()},favorite:function(){this.data.favorite=!this.data.favorite}},onBackPress:function(){if(this.$refs.share.show)return this.$refs.share.toggleMask(),!0}};e.default=o}).call(this,a("6e42")["default"])},"566b":function(t,e,a){"use strict";a.r(e);var s=a("4758"),i=a.n(s);for(var n in s)"default"!==n&&function(t){a.d(e,t,function(){return s[t]})}(n);e["default"]=i.a},a8db:function(t,e,a){"use strict";var s=a("060c"),i=a.n(s);i.a},cdb0:function(t,e,a){"use strict";a("feb3");var s=n(a("b0ce")),i=n(a("27c2"));function n(t){return t&&t.__esModule?t:{default:t}}Page((0,s.default)(i.default))}},[["cdb0","common/runtime","common/vendor"]]]);