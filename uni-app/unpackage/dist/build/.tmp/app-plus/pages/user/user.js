(global["webpackJsonp"]=global["webpackJsonp"]||[]).push([["pages/user/user"],{"132a":function(t,i,e){"use strict";e.r(i);var s=e("85d6"),a=e.n(s);for(var n in s)"default"!==n&&function(t){e.d(i,t,function(){return s[t]})}(n);i["default"]=a.a},"1cb4":function(t,i,e){"use strict";var s=e("bbf0"),a=e.n(s);a.a},"257c":function(t,i,e){},"2beb":function(t,i,e){"use strict";e.r(i);var s=e("452b"),a=e("a092");for(var n in a)"default"!==n&&function(t){e.d(i,t,function(){return a[t]})}(n);e("1cb4");var o=e("2877"),r=Object(o["a"])(a["default"],s["a"],s["b"],!1,null,null,null);i["default"]=r.exports},"352c":function(t,i,e){"use strict";var s=e("9e9e"),a=e.n(s);a.a},"452b":function(t,i,e){"use strict";var s=function(){var t=this,i=t.$createElement,e=t._self._c||i;return e("view",{staticClass:"container",staticStyle:{background:"#f5f5f5"}},[e("view",{staticClass:"user-section"},[e("image",{staticClass:"bg",attrs:{src:"/static/user-bg.jpg"}}),e("view",{staticClass:"user-info-box"},[e("view",{staticClass:"portrait-box"},[e("avatar",{attrs:{selWidth:"150px",selHeight:"150px",avatarSrc:t.url,avatarStyle:"width: 150upx; height: 150upx; border-radius: 100%;",eventid:"613c9cd4-0",mpcomid:"613c9cd4-0"},on:{upload:t.myUpload}})],1),e("view",{staticClass:"info-box"},[e("text",{staticClass:"username"},[t._v(t._s(t.userInfo.user_id||"游客"))])])]),e("view",{staticClass:"vip-card-box"},[e("image",{staticClass:"card-bg",attrs:{src:"/static/vip-card-bg.png",mode:""}}),e("view",{staticClass:"b-btn",attrs:{eventid:"613c9cd4-1"},on:{click:function(i){t.navTo("/pages/shop/edit")}}},[t._v("编辑店铺")]),e("view",{staticClass:"tit"},[e("text",{staticClass:"yticon icon-iLinkapp-"}),t._v(t._s(t.userInfo.uLevel||""))]),e("text",{staticClass:"e-m"},[t._v("DCloud Union")]),e("text",{staticClass:"e-b"},[t._v("开通会员开发无bug 一测就上线")])])]),e("view",{staticClass:"cover-container",style:[{transform:t.coverTransform,transition:t.coverTransition}],attrs:{eventid:"613c9cd4-19"},on:{touchstart:t.coverTouchstart,touchmove:t.coverTouchmove,touchend:t.coverTouchend}},[e("image",{staticClass:"arc",attrs:{src:"/static/arc.png"}}),e("view",{staticClass:"tj-sction"},[e("view",{staticClass:"tj-item",attrs:{eventid:"613c9cd4-2"},on:{click:function(i){t.navTo("/pages/user/usermoney?type=buy_point&&title="+t.bi.buy_point)}}},[e("text",{staticClass:"num"},[t._v(t._s(t.userInfo.buy_point||""))]),e("text",[t._v(t._s(t.bi.buy_point||""))])]),e("view",{staticClass:"tj-item",attrs:{eventid:"613c9cd4-3"},on:{click:function(i){t.navTo("/pages/user/usermoney?type=agent_use&&title="+t.bi.agent_use)}}},[e("text",{staticClass:"num"},[t._v(t._s(t.userInfo.agent_use||""))]),e("text",[t._v(t._s(t.bi.agent_use||""))])]),e("view",{staticClass:"tj-item",attrs:{eventid:"613c9cd4-4"},on:{click:function(i){t.navTo("/pages/user/usermoney?type=agent_cash&&title="+t.bi.agent_cash)}}},[e("text",{staticClass:"num"},[t._v(t._s(t.userInfo.agent_cash||""))]),e("text",[t._v(t._s(t.bi.agent_cash||""))])])]),e("view",{staticClass:"order-section"},[e("view",{staticClass:"order-item",attrs:{"hover-class":"common-hover","hover-stay-time":50,eventid:"613c9cd4-5"},on:{click:function(i){t.navTo("/pages/order/order?state=1")}}},[e("text",{staticClass:"yticon icon-shouye"}),e("text",[t._v("全部订单")])]),e("view",{staticClass:"order-item",attrs:{"hover-class":"common-hover","hover-stay-time":50,eventid:"613c9cd4-6"},on:{click:function(i){t.navTo("/pages/order/order?state=2")}}},[e("text",{staticClass:"yticon icon-daifukuan"}),e("text",[t._v("待付款")])]),e("view",{staticClass:"order-item",attrs:{"hover-class":"common-hover","hover-stay-time":50,eventid:"613c9cd4-7"},on:{click:function(i){t.navTo("/pages/order/order?state=3")}}},[e("text",{staticClass:"yticon icon-yishouhuo"}),e("text",[t._v("待发货")])]),e("view",{staticClass:"order-item",attrs:{"hover-class":"common-hover","hover-stay-time":50,eventid:"613c9cd4-8"},on:{click:function(i){t.navTo("/pages/order/order?state=4")}}},[e("text",{staticClass:"yticon icon-shouhoutuikuan"}),e("text",[t._v("待收货")])])]),e("view",{staticClass:"history-section icon"},[t.userInfo.goods_show_list_count>0?e("view",{staticClass:"sec-header"},[e("text",{staticClass:"yticon icon-lishijilu"}),e("text",[t._v("浏览历史")])]):t._e(),e("scroll-view",{staticClass:"h-list",attrs:{"scroll-x":""}},t._l(t.goods_show_list,function(i,s){return e("image",{key:s,attrs:{src:i.icon,mode:"aspectFill",eventid:"613c9cd4-9-"+s},on:{click:function(e){t.navTo(i.url)}}})})),e("list-cell",{attrs:{icon:"icon-iconfontweixin",iconColor:"#e07472",title:"店铺商品",tips:"",eventid:"613c9cd4-10",mpcomid:"613c9cd4-1"},on:{eventClick:function(i){t.navTo("/pages/shop/goods_list")}}}),e("list-cell",{attrs:{icon:"icon-iconfontweixin",iconColor:"#e07472",title:"兑换商品",tips:"",eventid:"613c9cd4-11",mpcomid:"613c9cd4-2"},on:{eventClick:function(i){t.navTo("/pages/shop/dui_list")}}}),e("list-cell",{attrs:{icon:"icon-iconfontweixin",iconColor:"#e07472",title:"我要提现",tips:"",eventid:"613c9cd4-12",mpcomid:"613c9cd4-3"},on:{eventClick:function(i){t.navTo("/pages/user/tiqu")}}}),e("list-cell",{attrs:{icon:"icon-iconfontweixin",iconColor:"#e07472",title:"个人信息",tips:"",eventid:"613c9cd4-13",mpcomid:"613c9cd4-4"},on:{eventClick:function(i){t.navTo("/pages/user/bank")}}}),e("list-cell",{attrs:{icon:"icon-iconfontweixin",iconColor:"#e07472",title:"密码修改",tips:"",eventid:"613c9cd4-14",mpcomid:"613c9cd4-5"},on:{eventClick:function(i){t.navTo("/pages/user/password")}}}),e("list-cell",{attrs:{icon:"icon-dizhi",iconColor:"#5fcda2",title:"地址管理",eventid:"613c9cd4-15",mpcomid:"613c9cd4-6"},on:{eventClick:function(i){t.navTo("/pages/address/address")}}}),e("list-cell",{attrs:{icon:"icon-share",iconColor:"#9789f7",title:"分享",tips:"邀请好友赢10万大礼",eventid:"613c9cd4-16",mpcomid:"613c9cd4-7"},on:{eventClick:function(i){t.navTo("/pages/user/zhiwen-share")}}}),e("list-cell",{directives:[{name:"show",rawName:"v-show",value:t.isShow,expression:"isShow"}],ref:"version",attrs:{icon:"icon-shezhi1",iconColor:"#e07472",title:"检查版本",tips:"",eventid:"613c9cd4-17",mpcomid:"613c9cd4-8"},on:{eventClick:t.update_version}}),e("list-cell",{attrs:{icon:"icon-share",iconColor:"#9789f7",title:"退出登录",tips:"",eventid:"613c9cd4-18",mpcomid:"613c9cd4-9"},on:{eventClick:t.toLogout}})],1)])])},a=[];e.d(i,"a",function(){return s}),e.d(i,"b",function(){return a})},4987:function(t,i,e){"use strict";e.r(i);var s=e("97f0"),a=e("132a");for(var n in a)"default"!==n&&function(t){e.d(i,t,function(){return a[t]})}(n);e("352c");var o=e("2877"),r=Object(o["a"])(a["default"],s["a"],s["b"],!1,null,null,null);i["default"]=r.exports},"57b2":function(t,i,e){"use strict";(function(t){Object.defineProperty(i,"__esModule",{value:!0}),i.default=void 0;var s=c(e("ca04")),a=c(e("6511")),n=c(e("4987")),o=c(e("5dff")),r=e("2f62");function c(t){return t&&t.__esModule?t:{default:t}}function h(t){for(var i=1;i<arguments.length;i++){var e=null!=arguments[i]?arguments[i]:{},s=Object.keys(e);"function"===typeof Object.getOwnPropertySymbols&&(s=s.concat(Object.getOwnPropertySymbols(e).filter(function(t){return Object.getOwnPropertyDescriptor(e,t).enumerable}))),s.forEach(function(i){l(t,i,e[i])})}return t}function l(t,i,e){return i in t?Object.defineProperty(t,i,{value:e,enumerable:!0,configurable:!0,writable:!0}):t[i]=e,t}var u=0,d=0,v=!0,f={components:{listCell:n.default,avatar:s.default},data:function(){return{coverTransform:"translateY(0px)",coverTransition:"0s",moving:!1,isShow:!1,update_tips:"",goods_show_list:[],url:"../../static/logo.png",basicArr:[]}},onLoad:function(){void 0==this.userInfo&&(console.log(this.userInfo),this.navTo("/pages/public/login")),this.url=this.userInfo.portrait,console.log(a.default.IP+this.userInfo.portrait),this.goods_show_list=this.userInfo.goods_show_list;var i=this;if("android"==a.default.get_client()||"ios"==a.default.get_client()){var e=t.getSystemInfoSync();""==e.brand||"iPhone"==e.brand?(i.show_update(!0),plus.runtime.getProperty(plus.runtime.appid,function(s){console.log(plus.device.vendor);t.getNetworkType();t.getNetworkType({success:function(s){var n=s.networkType;t.request({url:a.default.checkUrl,data:{is_mobile:1,device_model:e.model,device_connection_type:n,device_vendor:plus.device.vendor,device_version:e.system,version:plus.runtime.version,apk_version:plus.runtime.version,client:e.platform,user_id:i.userInfo.id,IP:a.default.IP},method:"POST",header:{"content-type":"application/x-www-form-urlencoded"},success:function(t){if(1==t.data.status){i.update_tips="有新版本",i.$refs.version.tips="有新版本";var e=t.data.update_tip.join("\n");console.log(i.$refs.version.tips),i.init_update_version(e,t.data.url)}else i.update_tips="最新版",i.$refs.version.tips="最新版"}})}})})):i.show_update(!1)}},onNavigationBarButtonTap:function(t){var i=t.index;0===i&&this.navTo("/pages/notice/notice"),1===i&&this.navTo("/pages/notice/notice")},computed:h({},(0,r.mapState)(["hasLogin","userInfo","bi"])),methods:h({},(0,r.mapMutations)(["logout"]),{myUpload:function(i){var e=this;t.uploadFile({url:a.default.Upload,filePath:i.path,name:"file",formData:{is_mobile:1,user_id:e.userInfo.id},success:function(t){var i=JSON.parse(t.data);1==i.status?(e.$api.msg(i.info),e.url=i.icon):e.$api.msg(i.info)}})},navTo:function(i){this.hasLogin||(i="/pages/public/login"),t.navigateTo({url:i})},coverTouchstart:function(t){!1!==v&&(this.coverTransition="transform .1s linear",u=t.touches[0].clientY)},coverTouchmove:function(t){d=t.touches[0].clientY;var i=d-u;i<0?this.moving=!1:(this.moving=!0,i>=80&&i<100&&(i=80),i>0&&i<=80&&(this.coverTransform="translateY(".concat(i,"px)")))},coverTouchend:function(){!1!==this.moving&&(this.moving=!1,this.coverTransition="transform 0.3s cubic-bezier(.21,1.93,.53,.64)",this.coverTransform="translateY(0px)")},init_update_version:function(t,i){o.default.init({packageUrl:i,content:"更新内容:\n"+t,contentAlign:"left",cancel:"取消该版本",cancelColor:"#007fff"})},update_version:function(){var t=this;t.$refs.version.tips=t.update_tips,o.default.show()},show_update:function(t){this.isShow=t},toLogout:function(){var i=this;console.log(22222),t.showModal({title:"提示",content:"确定要退出登录么？",success:function(e){e.confirm&&(i.logout(),setTimeout(function(){var i="/pages/public/login";t.navigateTo({url:i})},200))}})}})};i.default=f}).call(this,e("6e42")["default"])},"85d6":function(t,i,e){"use strict";Object.defineProperty(i,"__esModule",{value:!0}),i.default=void 0;var s={data:function(){return{typeList:{left:"icon-zuo",right:"icon-you",up:"icon-shang",down:"icon-xia"}}},props:{icon:{type:String,default:""},title:{type:String,default:"标题"},tips:{type:String,default:""},navigateType:{type:String,default:"right"},border:{type:String,default:"b-b"},hoverClass:{type:String,default:"cell-hover"},iconColor:{type:String,default:"#333"}},methods:{eventClick:function(){this.$emit("eventClick")}}};i.default=s},"97f0":function(t,i,e){"use strict";var s=function(){var t=this,i=t.$createElement,e=t._self._c||i;return e("view",{staticClass:"content"},[e("view",{staticClass:"mix-list-cell",class:t.border,attrs:{"hover-class":"cell-hover","hover-stay-time":50,eventid:"afcb9516-0"},on:{click:t.eventClick}},[t.icon?e("text",{staticClass:"cell-icon yticon",class:t.icon,style:[{color:t.iconColor}]}):t._e(),e("text",{staticClass:"cell-tit clamp"},[t._v(t._s(t.title))]),t.tips?e("text",{staticClass:"cell-tip"},[t._v(t._s(t.tips))]):t._e(),e("text",{staticClass:"cell-more yticon",class:t.typeList[t.navigateType]})])])},a=[];e.d(i,"a",function(){return s}),e.d(i,"b",function(){return a})},"9e9e":function(t,i,e){},a092:function(t,i,e){"use strict";e.r(i);var s=e("57b2"),a=e.n(s);for(var n in s)"default"!==n&&function(t){e.d(i,t,function(){return s[t]})}(n);i["default"]=a.a},bbf0:function(t,i,e){},ca04:function(t,i,e){"use strict";e.r(i);var s=e("ed99"),a=e("cd9e");for(var n in a)"default"!==n&&function(t){e.d(i,t,function(){return a[t]})}(n);e("d675");var o=e("2877"),r=Object(o["a"])(a["default"],s["a"],s["b"],!1,null,null,null);i["default"]=r.exports},cd9e:function(t,i,e){"use strict";e.r(i);var s=e("d82d"),a=e.n(s);for(var n in s)"default"!==n&&function(t){e.d(i,t,function(){return s[t]})}(n);i["default"]=a.a},d675:function(t,i,e){"use strict";var s=e("257c"),a=e.n(s);a.a},d82d:function(t,i,e){"use strict";(function(t){Object.defineProperty(i,"__esModule",{value:!0}),i.default=void 0;var s=a(e("a34a"));function a(t){return t&&t.__esModule?t:{default:t}}function n(t,i,e,s,a,n,o){try{var r=t[n](o),c=r.value}catch(h){return void e(h)}r.done?i(c):Promise.resolve(c).then(s,a)}function o(t){return function(){var i=this,e=arguments;return new Promise(function(s,a){var o=t.apply(i,e);function r(t){n(o,s,a,r,c,"next",t)}function c(t){n(o,s,a,r,c,"throw",t)}r(void 0)})}}var r=50,c={name:"yq-avatar",data:function(){return{cvsStyleHeight:"0px",styleDisplay:"none",styleTop:"-10000px",prvTop:"-10000px",imgStyle:{},showOper:!0,imgSrc:{imgSrc:""},btnWidth:"24%",btnDsp:"flex"}},watch:{avatarSrc:function(){this.imgSrc.imgSrc=this.avatarSrc}},props:{avatarSrc:"",avatarStyle:"",selWidth:"",selHeight:"",minWidth:"",minHeight:"",minScale:"",maxScale:"",canScale:"",canRotate:"",lockWidth:"",lockHeight:"",stretch:"",lock:"",noTab:"",inner:"",quality:"",index:""},created:function(){var i=this;this.ctxCanvas=t.createCanvasContext("avatar-canvas",this),this.ctxCanvasOper=t.createCanvasContext("oper-canvas",this),this.ctxCanvasPrv=t.createCanvasContext("prv-canvas",this),this.qlty=parseInt(this.quality)||.9,this.imgSrc.imgSrc=this.avatarSrc,this.letRotate="false"===this.canRotate||"true"===this.inner?0:1,this.letScale="false"===this.canScale?0:1,this.isin="true"===this.inner?1:0,this.indx=this.index||void 0,this.mnScale=this.minScale||.3,this.mxScale=this.maxScale||4,this.noBar="true"===this.noTab?1:0,this.isin&&(this.btnWidth="30%",this.btnDsp="none"),this.noBar?(this.moreHeight=0,this.fWindowResize()):t.showTabBar({complete:function(t){i.moreHeight="showTabBar:ok"===t.errMsg?50:0,i.fWindowResize()}})},methods:{fGetImgData:function(){var i=this;return new Promise(function(e,s){var a=i.prvX,n=i.prvY,o=i.prvWidth,r=i.prvHeight;a*=i.pixelRatio,n*=i.pixelRatio,o*=i.pixelRatio,r*=i.pixelRatio,t.canvasGetImageData({canvasId:"prv-canvas",x:a,y:n,width:o,height:r,success:function(t){e(t.data)},fail:function(t){s(t)}},i)})},fColorChange:function(){var i=o(s.default.mark(function i(e){var a,n,o,r,c,h,l,u,d,v,f,p,g,m,w,y,x,b,_,S,C,k,T,I,H,W,P;return s.default.wrap(function(i){while(1)switch(i.prev=i.next){case 0:if(a=Date.now(),!(a-this.prvTm<100)){i.next=3;break}return i.abrupt("return");case 3:if(this.prvTm=a,t.showLoading({mask:!0}),this.prvImgData){i.next=11;break}return i.next=8,this.fGetImgData().catch(function(i){t.showLoading({title:"error_read",duration:2e3})});case 8:if(this.prvImgData=i.sent){i.next=10;break}return i.abrupt("return");case 10:this.target=new Uint8ClampedArray(this.prvImgData.length);case 11:if(n=this.prvImgData,o=this.target,r=e.detail.value,0===r)o=n;else for(r=(r+100)/200,r<.005&&(r=0),r>.995&&(r=1),C=n.length-1;C>=0;C-=4)c=n[C-3]/255,h=n[C-2]/255,l=n[C-1]/255,y=Math.max(c,h,l),w=Math.min(c,h,l),p=y-w,y===w?d=0:y===c&&h>=l?d=(h-l)/p*60:y===c&&h<l?d=(h-l)/p*60+360:y===h?d=(l-c)/p*60+120:y===l&&(d=(c-h)/p*60+240),f=(y+w)/2,0===f||y===w?v=0:0<f&&f<=.5?v=p/(2*f):f>.5&&(v=p/(2-2*f)),n[C]&&(u=n[C]),r<.5?v=v*r/.5:r>.5&&(v=2*v+2*r-v*r/.5-1),0===v?c=h=l=Math.round(255*f):(f<.5?m=f*(1+v):f>=.5&&(m=f+v-f*v),g=2*f-m,x=d/360,b=x+1/3,_=x,S=x-1/3,k=function(t){return t<0?t+1:t>1?t-1:t},T=function(t){return t<1/6?g+6*(m-g)*t:t>=1/6&&t<.5?m:t>=.5&&t<2/3?g+6*(m-g)*(2/3-t):g},c=b=Math.round(255*T(k(b))),h=_=Math.round(255*T(k(_))),l=S=Math.round(255*T(k(S)))),u&&(o[C]=u),o[C-3]=c,o[C-2]=h,o[C-1]=l;I=this.prvX,H=this.prvY,W=this.prvWidth,P=this.prvHeight,this.ctxCanvasPrv.setFillStyle("black"),this.ctxCanvasPrv.fillRect(I,H,W,P),this.ctxCanvasPrv.draw(!0),I*=this.pixelRatio,H*=this.pixelRatio,W*=this.pixelRatio,P*=this.pixelRatio,t.canvasPutImageData({canvasId:"prv-canvas",x:I,y:H,width:W,height:P,data:o,success:function(t){},fail:function(){t.showLoading({title:"error_put",duration:2e3})},complete:function(i){t.hideLoading()}},this);case 22:case"end":return i.stop()}},i,this)}));function e(t){return i.apply(this,arguments)}return e}(),fWindowResize:function(){var i=this,e=t.getSystemInfoSync();this.platform=e.platform,this.pixelRatio=e.pixelRatio,this.windowWidth=e.windowWidth,"android"===this.platform?(this.windowHeight=e.screenHeight+e.statusBarHeight,this.cvsStyleHeight=this.windowHeight-r+"px"):(this.windowHeight=e.windowHeight+this.moreHeight,this.cvsStyleHeight=this.windowHeight-r+6+"px"),this.pxRatio=this.windowWidth/750;var s=this.avatarStyle;if(s&&!0!==s&&(s=s.trim())){s=s.split(";");var a={},n=!0,o=!1,c=void 0;try{for(var h,l=s[Symbol.iterator]();!(n=(h=l.next()).done);n=!0){var u=h.value;if(u){if(u=u.trim().split(":"),u[1].indexOf("upx")>=0){var d=u[1].trim().split(" ");for(var v in d)d[v]&&d[v].indexOf("upx")>=0&&(d[v]=parseFloat(d[v])*this.pxRatio+"px");u[1]=d.join(" ")}a[u[0].trim()]=u[1].trim()}}}catch(f){o=!0,c=f}finally{try{n||null==l.return||l.return()}finally{if(o)throw c}}this.imgStyle=a}s=this.selStyle||{},this.selWidth&&this.selHeight&&(s.width=this.selWidth.indexOf("upx")>=0?parseInt(this.selWidth)*this.pxRatio+"px":this.selWidth,s.height=this.selHeight.indexOf("upx")>=0?parseInt(this.selHeight)*this.pxRatio+"px":this.selHeight),s.top=(this.windowHeight-parseInt(s.height)-r)/2+"px",s.left=(this.windowWidth-parseInt(s.width))/2+"px",this.selStyle=s,"flex"===this.styleDisplay&&setTimeout(function(){i.fDrawInit(!0)},200),this.fHideImg()},fUpload:function(){var i=this;if(!this.fUploading){this.fUploading=!0,setTimeout(function(){i.fUploading=!1},1e3);var e=this.selStyle,s=parseInt(e.left),a=parseInt(e.top),n=parseInt(e.width),o=parseInt(e.height);t.showLoading({mask:!0}),this.styleDisplay="none",this.styleTop="-10000px",this.fHideImg(),t.canvasToTempFilePath({x:s,y:a,width:n,height:o,destWidth:n,destHeight:o,canvasId:"avatar-canvas",fileType:"png",quality:this.qlty,success:function(t){t=t.tempFilePath,t={index:i.indx,path:t,avatar:i.imgSrc},i.$emit("upload",t)},fail:function(){t.showLoading({title:"error1",duration:2e3})},complete:function(){t.hideLoading(),i.noBar||t.showTabBar()}},this)}},fPrvUpload:function(){var i=this;if(!this.fPrvUploading){this.fPrvUploading=!0,setTimeout(function(){i.fPrvUploading=!1},1e3);var e=this.selStyle,s=(parseInt(e.width),parseInt(e.height),this.prvX),a=this.prvY,n=this.prvWidth,o=this.prvHeight;t.showLoading({mask:!0}),this.styleDisplay="none",this.styleTop="-10000px",this.fHideImg(),t.canvasToTempFilePath({x:s,y:a,width:n,height:o,destWidth:n,destHeight:o,canvasId:"prv-canvas",fileType:"png",quality:this.qlty,success:function(t){t=t.tempFilePath,t={index:i.indx,path:t,avatar:i.imgSrc},i.$emit("upload",t)},fail:function(){t.showLoading({title:"error_prv",duration:2e3})},complete:function(){t.hideLoading(),i.noBar||t.showTabBar()}},this)}},fDrawImage:function(){var t=Date.now();if(!(t-this.drawTm<20)){this.drawTm=t;var i=this.ctxCanvas;i.fillRect(0,0,this.windowWidth,this.windowHeight-r),i.translate(this.posWidth+this.useWidth/2,this.posHeight+this.useHeight/2),i.scale(this.scaleSize,this.scaleSize),i.rotate(this.rotateDeg*Math.PI/180),i.drawImage(this.imgPath,-this.useWidth/2,-this.useHeight/2,this.useWidth,this.useHeight),i.draw(!1)}},fHideImg:function(){this.prvImg="",this.prvTop="-10000px",this.showOper=!0,this.prvImgData=null,this.target=null},fPreview:function(){var i=this;if(!this.fPreviewing){this.fPreviewing=!0,setTimeout(function(){i.fPreviewing=!1},1e3);var e=this.selStyle,s=parseInt(e.left),a=parseInt(e.top),n=parseInt(e.width),o=parseInt(e.height);t.showLoading({mask:!0}),t.canvasToTempFilePath({x:s,y:a,width:n,height:o,canvasId:"avatar-canvas",fileType:"png",quality:this.qlty,success:function(t){i.prvImgTmp=t=t.tempFilePath;var e=i.ctxCanvasPrv,s=i.windowWidth,a=parseInt(i.cvsStyleHeight),n=parseInt(i.selStyle.width),o=parseInt(i.selStyle.height),r=s-40,c=a-80,h=r/n,l=o*h;l<c?(n=r,o=l):(h=c/o,n*=h,o=c),e.setFillStyle("black"),e.fillRect(0,0,s,a),i.prvX=s=(s-n)/2,i.prvY=a=(a-o)/2,i.prvWidth=n,i.prvHeight=o,e.drawImage(t,s,a,n,o),e.draw(!1),"android"!=i.platform&&(i.showOper=!1),i.prvTop="0"},fail:function(){t.showLoading({title:"error2",duration:2e3})},complete:function(){t.hideLoading()}},this)}},fDrawInit:function(){var t=arguments.length>0&&void 0!==arguments[0]&&arguments[0],i=this.windowWidth,e=this.windowHeight,s=this.imgWidth,a=this.imgHeight,n=s/a,o=i-40,c=e-r-80,h=(this.pixelRatio,parseInt(this.selStyle.width)),l=parseInt(this.selStyle.height);switch(this.fixWidth=0,this.fixHeight=0,this.lckWidth=0,this.lckHeight=0,this.stretch){case"x":this.fixWidth=1;break;case"y":this.fixHeight=1;break;case"long":n>1?this.fixWidth=1:this.fixHeight=1;break;case"short":n>1?this.fixHeight=1:this.fixWidth=1;break;case"longSel":h>l?this.fixWidth=1:this.fixHeight=1;break;case"shortSel":h>l?this.fixHeight=1:this.fixWidth=1;break}switch(this.lock){case"x":this.lckWidth=1;break;case"y":this.lckHeight=1;break;case"long":n>1?this.lckWidth=1:this.lckHeight=1;break;case"short":n>1?this.lckHeight=1:this.lckWidth=1;break;case"longSel":h>l?this.lckWidth=1:this.lckHeight=1;break;case"shortSel":h>l?this.lckHeight=1:this.lckWidth=1;break}this.fixWidth?(o=h,c=o/n):this.fixHeight?(c=l,o=c*n):n<1?a<c?(o=s,c=a):(c=c,o=c*n):s<o?(o=s,c=a):(o=o,c=o/n),this.isin&&(this.scaleWidth=0,this.scaleHeight=0,o<h&&(o=h,c=o/n),c<l&&(c=l,o=c*n)),this.scaleSize=1,this.rotateDeg=0,this.posWidth=(i-o)/2,this.posHeight=(e-c-r)/2,this.useWidth=o,this.useHeight=c;var u=this.selStyle,d=parseInt(u.left),v=parseInt(u.top),f=parseInt(u.width),p=parseInt(u.height),g=(this.canvas,this.canvasOper,this.ctxCanvas),m=this.ctxCanvasOper;m.setLineWidth(3),m.setStrokeStyle("grey"),m.setGlobalAlpha(.3),m.setFillStyle("grey"),m.strokeRect(d,v,f,p),m.fillRect(0,0,this.windowWidth,v),m.fillRect(0,v,d,p),m.fillRect(0,v+p,this.windowWidth,this.windowHeight-p-v-r),m.fillRect(d+f,v,this.windowWidth-f-d,p),m.setStrokeStyle("red"),m.moveTo(d+20,v),m.lineTo(d,v),m.lineTo(d,v+20),m.moveTo(d+f-20,v),m.lineTo(d+f,v),m.lineTo(d+f,v+20),m.moveTo(d+20,v+p),m.lineTo(d,v+p),m.lineTo(d,v+p-20),m.moveTo(d+f-20,v+p),m.lineTo(d+f,v+p),m.lineTo(d+f,v+p-20),m.stroke(),m.draw(!1),t&&(this.styleDisplay="flex",this.styleTop="0",g.setFillStyle("black"),this.fDrawImage())},fChooseImg:function(){var t=arguments.length>0&&void 0!==arguments[0]?arguments[0]:void 0;this.indx=t,this.fSelect()},fRotate:function(){var t=this;if("android"===this.platform){if(this.fRotateing)return;this.fRotateing=!0,setTimeout(function(){t.fRotateing=!1},500)}this.letRotate&&(this.rotateDeg+=90-this.rotateDeg%90,this.fDrawImage())},fSelect:function(){var i=this;this.fSelecting||(this.fSelecting=!0,setTimeout(function(){i.fSelecting=!1},500),t.chooseImage({count:1,sizeType:["original","compressed"],sourceType:["album","camera"],success:function(e){t.showLoading({mask:!0});var s=i.imgPath=e.tempFilePaths[0];t.getImageInfo({src:s,success:function(e){i.imgWidth=e.width,i.imgHeight=e.height,i.path=s,i.noBar?setTimeout(function(){i.fDrawInit(!0)},200):t.hideTabBar({complete:function(){setTimeout(function(){i.fDrawInit(!0)},200)}})},fail:function(){t.showLoading({title:"error3",duration:2e3})},complete:function(){t.hideLoading()}})}}))},fStart:function(t){var i=t.touches,e=i[0],s=i[1];if(this.touch0=e,this.touch1=s,s){var a=s.x-e.x,n=s.y-e.y;this.fgDistance=Math.sqrt(a*a+n*n)}},fMove:function(t){var i=t.touches,e=i[0],s=i[1];if(s){var a=s.x-e.x,n=s.y-e.y,o=Math.sqrt(a*a+n*n),r=.005*(o-this.fgDistance),c=this.scaleSize+r;do{if(!this.letScale)break;if(this.minWidth&&beWidth<this.minWidth)break;if(this.minHeight&&beHeight<this.minHeight)break;if(c<this.mnScale)break;if(c>this.mxScale)break;if(this.isin){var h=this.useWidth*c,l=this.useHeight*c,u=this.posWidth+this.useWidth/2,d=this.posHeight+this.useHeight/2,v=u-h/2,f=d-l/2,p=v+h,g=f+l,m=parseInt(this.selStyle.left),w=parseInt(this.selStyle.top),y=parseInt(this.selStyle.width),x=parseInt(this.selStyle.height);if(m<v||m+y>p||w<f||w+x>g)break;this.scaleWidth=(this.useWidth-h)/2,this.scaleHeight=(this.useHeight-l)/2}this.scaleSize=c}while(0);this.fgDistance=o,s.x!==e.x&&this.letRotate&&(a=(this.touch1.y-this.touch0.y)/(this.touch1.x-this.touch0.x),n=(s.y-e.y)/(s.x-e.x),this.rotateDeg+=180*Math.atan((n-a)/(1+a*n))/Math.PI,this.touch0=e,this.touch1=s),this.fDrawImage()}else if(this.touch0){var b=e.x-this.touch0.x,_=e.y-this.touch0.y,S=this.posWidth+b,C=this.posHeight+_;if(this.isin){var k=this.useWidth*this.scaleSize,T=this.useHeight*this.scaleSize,I=S+this.useWidth/2,H=C+this.useHeight/2,W=I-k/2,P=H-T/2,D=W+k,R=P+T,L=parseInt(this.selStyle.left),O=parseInt(this.selStyle.top),M=parseInt(this.selStyle.width),U=parseInt(this.selStyle.height);!this.lckWidth&&Math.abs(b)<100&&(L>=W&&L+M<=D?this.posWidth=S:L<W?this.posWidth=L-this.scaleWidth:L+M>D&&(this.posWidth=L-(k-M)-this.scaleWidth)),!this.lckHeight&&Math.abs(_)<100&&(O>=P&&O+U<=R?this.posHeight=C:O<P?this.posHeight=O-this.scaleHeight:O+U>R&&(this.posHeight=O-(T-U)-this.scaleHeight))}else Math.abs(b)<100&&!this.lckWidth&&(this.posWidth=S),Math.abs(_)<100&&!this.lckHeight&&(this.posHeight=C);this.touch0=e,this.fDrawImage()}},fEnd:function(t){var i=t.touches,e=i&&i[0];i&&i[1];e?this.touch0=e:(this.touch0=null,this.touch1=null)},btop:function(t){return new Promise(function(i,e){var s=t.split(","),a=s[0].match(/:(.*?);/)[1],n=atob(s[1]),o=n.length,r=new Uint8Array(o);while(o--)r[o]=n.charCodeAt(o);return i((window.URL||window.webkitURL).createObjectURL(new Blob([r],{type:a})))})}}};i.default=c}).call(this,e("6e42")["default"])},e751:function(t,i,e){"use strict";e("feb3");var s=n(e("b0ce")),a=n(e("2beb"));function n(t){return t&&t.__esModule?t:{default:t}}Page((0,s.default)(a.default))},ed99:function(t,i,e){"use strict";var s=function(){var t=this,i=t.$createElement,e=t._self._c||i;return e("view",[e("image",{staticClass:"my-avatar",style:[t.imgStyle],attrs:{src:t.imgSrc.imgSrc,eventid:"1379bd42-0"},on:{click:t.fSelect}}),e("canvas",{staticClass:"my-canvas",style:{top:t.styleTop,height:t.cvsStyleHeight},attrs:{"canvas-id":"avatar-canvas","disable-scroll":"false"}}),e("canvas",{staticClass:"oper-canvas",style:{top:t.styleTop,height:t.cvsStyleHeight},attrs:{"canvas-id":"oper-canvas","disable-scroll":"false",eventid:"1379bd42-1"},on:{touchstart:t.fStart,touchmove:t.fMove,touchend:t.fEnd}}),e("canvas",{staticClass:"prv-canvas",style:{height:t.cvsStyleHeight,top:t.prvTop},attrs:{"canvas-id":"prv-canvas","disable-scroll":"false",eventid:"1379bd42-2"},on:{touchstart:t.fHideImg}}),e("view",{staticClass:"oper-wrapper",style:{display:t.styleDisplay}},[e("view",{staticClass:"oper"},[t.showOper?e("view",{staticClass:"btn-wrapper"},[e("view",{style:{width:t.btnWidth},attrs:{"hover-class":"hover",eventid:"1379bd42-3"},on:{click:t.fSelect}},[e("text",[t._v("重选")])]),e("view",{style:{width:t.btnWidth,display:t.btnDsp},attrs:{"hover-class":"hover",eventid:"1379bd42-4"},on:{click:t.fRotate}},[e("text",[t._v("旋转")])]),e("view",{style:{width:t.btnWidth},attrs:{"hover-class":"hover",eventid:"1379bd42-5"},on:{click:t.fPreview}},[e("text",[t._v("预览")])]),e("view",{style:{width:t.btnWidth},attrs:{"hover-class":"hover",eventid:"1379bd42-6"},on:{click:t.fUpload}},[e("text",[t._v("上传")])])]):e("view",{staticClass:"clr-wrapper"},[e("slider",{staticClass:"my-slider",attrs:{"block-size":"25",value:"0",min:"-100",max:"100",activeColor:"red",backgroundColor:"green","block-color":"grey","show-value":"",eventid:"1379bd42-7"},on:{change:t.fColorChange}}),e("view",{style:{width:t.btnWidth},attrs:{"hover-class":"hover",eventid:"1379bd42-8"},on:{click:t.fPrvUpload}},[e("text",[t._v("上传")])])])])])])},a=[];e.d(i,"a",function(){return s}),e.d(i,"b",function(){return a})}},[["e751","common/runtime","common/vendor"]]]);