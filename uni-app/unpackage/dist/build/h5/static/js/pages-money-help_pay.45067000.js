(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["pages-money-help_pay"],{"42c1":function(e,t,i){"use strict";i.r(t);var a=i("47b5"),n=i.n(a);for(var r in a)"default"!==r&&function(e){i.d(t,e,function(){return a[e]})}(r);t["default"]=n.a},"47b5":function(e,t,i){"use strict";Object.defineProperty(t,"__esModule",{value:!0}),t.default=void 0;var a,n=o(i("6511")),r=i("2f62");function o(e){return e&&e.__esModule?e:{default:e}}function s(e){for(var t=1;t<arguments.length;t++){var i=null!=arguments[t]?arguments[t]:{},a=Object.keys(i);"function"===typeof Object.getOwnPropertySymbols&&(a=a.concat(Object.getOwnPropertySymbols(i).filter(function(e){return Object.getOwnPropertyDescriptor(i,e).enumerable}))),a.forEach(function(t){c(e,t,i[t])})}return e}function c(e,t,i){return t in e?Object.defineProperty(e,t,{value:i,enumerable:!0,configurable:!0,writable:!0}):e[t]=i,e}var l=(a={data:function(){return{order:{},type:0,recommend_url:"",re_share_sub_title:"",re_share_title:"",payType:0,orderInfo:{},swiperList:[],providerList:[],dotStyle:!1,towerStart:0,direction:""}},computed:{},onLoad:function(e){var t=this;console.log(e.id);var i=this;uni.request({url:n.default.order_edit,data:{is_mobile:1,id:e.id},method:"POST",header:{"content-type":"application/x-www-form-urlencoded"},success:function(e){1==e.data.status&&(i.orderInfo=e.data.data,i.swiperList=e.data.swiperList,i.recommend_url=n.default.PreUrl+"Reg/register/rid/"+i.userInfo.id,i.re_share_sub_title=e.data.re_pay_sub_title,i.re_share_title=e.data.re_pay_title,i.re_share_title=i.re_share_title.replace("{0}",i.userInfo.user_name),i.re_share_title=i.re_share_title.replace("{1}",i.orderInfo.order_amount))}}),uni.getProvider({service:"share",success:function(e){for(var i=[],a=0;a<e.provider.length;a++)switch(e.provider[a]){case"weixin":i.push({name:"分享到微信好友",id:"weixin"}),i.push({name:"分享到微信朋友圈",id:"weixin",type:"WXSenceTimeline"});break;default:break}t.providerList=i},fail:function(e){console.log("获取登录通道失败"+JSON.stringify(e))}})}},c(a,"computed",s({},(0,r.mapState)(["hasLogin","userInfo","bi"]))),c(a,"methods",{changePayType:function(e){this.payType=e,console.log(e)},share:function(){var e=this.payType;if(0!==this.providerList.length){this.providerList.map(function(e){return e.name});var t=this;uni.share({provider:this.providerList[e].id,scene:this.providerList[e].type&&"WXSenceTimeline"===this.providerList[e].type?"WXSenceTimeline":"WXSceneSession",type:this.type,title:t.re_share_title,summary:t.re_share_sub_title,imageUrl:"../../static/logo.png",href:t.recommend_url,success:function(e){console.log("success:"+JSON.stringify(e))},fail:function(e){uni.showModal({content:e.errMsg,showCancel:!1})}})}else uni.showModal({title:"当前环境无分享渠道!",showCancel:!1})}}),a);t.default=l},4927:function(e,t,i){"use strict";i.r(t);var a=i("90ef"),n=i("42c1");for(var r in n)"default"!==r&&function(e){i.d(t,e,function(){return n[e]})}(r);i("799d");var o=i("2877"),s=Object(o["a"])(n["default"],a["a"],a["b"],!1,null,"7a4b7996",null);t["default"]=s.exports},"799d":function(e,t,i){"use strict";var a=i("f5c4"),n=i.n(a);n.a},"90ef":function(e,t,i){"use strict";var a=function(){var e=this,t=e.$createElement,i=e._self._c||t;return i("v-uni-view",{staticClass:"app"},[i("v-uni-swiper",{staticClass:"screen-swiper",class:e.dotStyle?"square-dot":"round-dot",attrs:{"indicator-dots":!0,circular:!0,autoplay:!0,interval:"5000",duration:"500"}},e._l(e.swiperList,function(t,a){return i("v-uni-swiper-item",{key:a},["image"==t.type?i("v-uni-image",{attrs:{src:t.url,mode:"aspectFill"}}):e._e(),"video"==t.type?i("v-uni-video",{attrs:{src:t.url,autoplay:"",loop:"",muted:"","show-play-btn":!1,controls:!1,objectFit:"cover"}}):e._e()],1)}),1),i("v-uni-view",{staticClass:"cu-bar bg-white solid-bottom margin-top"},[i("v-uni-view",{staticClass:"action"},[i("v-uni-text",{staticClass:"icon-title text-orange"}),e._v("通过微信、QQ等将代付请求发送给好友,即可让您的好友为您买单!")],1),i("v-uni-view",{staticClass:"action"})],1),i("v-uni-view",{staticClass:"price-box"},[i("v-uni-text",[e._v("支付金额")]),i("v-uni-text",{staticClass:"price"},[e._v(e._s(e.orderInfo.order_amount))])],1),i("v-uni-view",{staticClass:"cu-form-group  margin-top"},[i("v-uni-view",{staticClass:"title"},[e._v("捎一句话")]),i("v-uni-textarea")],1),i("v-uni-view",{staticClass:"pay-type-list"},[i("v-uni-view",{staticClass:"type-item b-b",on:{click:function(t){t=e.$handleEvent(t),e.changePayType(0)}}},[i("v-uni-image",{staticClass:"icon_logo",attrs:{src:"../../static/img/weact.png"}}),i("v-uni-view",{staticClass:"con"},[i("v-uni-text",{staticClass:"tit"},[e._v("微信分享")]),i("v-uni-text",[e._v("推荐使用微信分享")])],1),i("v-uni-label",{staticClass:"radio"},[i("v-uni-radio",{attrs:{value:"",color:"#fa436a",checked:0==e.payType}})],1)],1),i("v-uni-view",{staticClass:"type-item b-b",on:{click:function(t){t=e.$handleEvent(t),e.changePayType(1)}}},[i("v-uni-image",{staticClass:"icon_logo",attrs:{src:"../../static/img/qq.png"}}),i("v-uni-view",{staticClass:"con"},[i("v-uni-text",{staticClass:"tit"},[e._v("QQ分享")])],1),i("v-uni-label",{staticClass:"radio"},[i("v-uni-radio",{attrs:{value:"",color:"#fa436a",checked:1==e.payType}})],1)],1)],1),i("v-uni-text",{staticClass:"mix-btn",on:{click:function(t){t=e.$handleEvent(t),e.share(t)}}},[e._v("请好友帮我付款")])],1)},n=[];i.d(t,"a",function(){return a}),i.d(t,"b",function(){return n})},b74e:function(e,t,i){t=e.exports=i("2350")(!1),t.push([e.i,'@charset "UTF-8";\n/* 页面左右间距 */\n/* 文字尺寸 */\n/*文字颜色*/\n/* 边框颜色 */\n/* 图片加载中颜色 */\n/* 行为相关颜色 */.app[data-v-7a4b7996]{width:100%}.icon_logo[data-v-7a4b7996]{width:20px;height:20px;margin-right:10px}.price-box[data-v-7a4b7996]{background-color:#fff;height:%?265?%;display:-webkit-box;display:-webkit-flex;display:-ms-flexbox;display:flex;-webkit-box-orient:vertical;-webkit-box-direction:normal;-webkit-flex-direction:column;-ms-flex-direction:column;flex-direction:column;-webkit-box-pack:center;-webkit-justify-content:center;-ms-flex-pack:center;justify-content:center;-webkit-box-align:center;-webkit-align-items:center;-ms-flex-align:center;align-items:center;font-size:%?28?%;color:#909399}.price-box .price[data-v-7a4b7996]{font-size:%?50?%;color:#303133;margin-top:%?12?%}.price-box .price[data-v-7a4b7996]:before{content:"\\FFE5";font-size:%?40?%}.pay-type-list[data-v-7a4b7996]{margin-top:%?20?%;background-color:#fff;padding-left:%?60?%}.pay-type-list .type-item[data-v-7a4b7996]{height:%?120?%;padding:%?20?% 0;display:-webkit-box;display:-webkit-flex;display:-ms-flexbox;display:flex;-webkit-box-pack:justify;-webkit-justify-content:space-between;-ms-flex-pack:justify;justify-content:space-between;-webkit-box-align:center;-webkit-align-items:center;-ms-flex-align:center;align-items:center;padding-right:%?60?%;font-size:%?30?%;position:relative}.pay-type-list .icon[data-v-7a4b7996]{width:%?100?%;font-size:%?52?%}.pay-type-list .icon-erjiye-yucunkuan[data-v-7a4b7996]{color:#fe8e2e}.pay-type-list .icon-weixinzhifu[data-v-7a4b7996]{color:#36cb59}.pay-type-list .icon-alipay[data-v-7a4b7996]{color:#01aaef}.pay-type-list .tit[data-v-7a4b7996]{font-size:%?32?%;color:#303133;margin-bottom:%?4?%}.pay-type-list .con[data-v-7a4b7996]{-webkit-box-flex:1;-webkit-flex:1;-ms-flex:1;flex:1;display:-webkit-box;display:-webkit-flex;display:-ms-flexbox;display:flex;-webkit-box-orient:vertical;-webkit-box-direction:normal;-webkit-flex-direction:column;-ms-flex-direction:column;flex-direction:column;font-size:%?24?%;color:#909399}.mix-btn[data-v-7a4b7996]{display:-webkit-box;display:-webkit-flex;display:-ms-flexbox;display:flex;-webkit-box-align:center;-webkit-align-items:center;-ms-flex-align:center;align-items:center;-webkit-box-pack:center;-webkit-justify-content:center;-ms-flex-pack:center;justify-content:center;width:%?630?%;height:%?80?%;margin:%?80?% auto %?30?%;font-size:%?32?%;color:#fff;background-color:#fa436a;border-radius:%?10?%;-webkit-box-shadow:1px 2px 5px rgba(219,63,96,.4);box-shadow:1px 2px 5px rgba(219,63,96,.4)}',""])},f5c4:function(e,t,i){var a=i("b74e");"string"===typeof a&&(a=[[e.i,a,""]]),a.locals&&(e.exports=a.locals);var n=i("4f06").default;n("665c8862",a,!0,{sourceMap:!1,shadowMode:!1})}}]);