!function(e,t,i){var a=0;e.Lazyload=e.Class.extend({init:function(t,i){this.container=this.element=t,this.options=e.extend({selector:"",diff:!1,force:!1,autoDestroy:!0,duration:100},i),this._key=0,this._containerIsNotDocument=9!==this.container.nodeType,this._callbacks={},this._init()},_init:function(){this._initLoadFn(),this.addElements(),this._loadFn(),e.ready(function(){this._loadFn()}.bind(this)),this.resume()},_initLoadFn:function(){var t=this;t._loadFn=this._buffer(function(){t.options.autoDestroy&&0==t._counter&&e.isEmptyObject(t._callbacks)&&t.destroy(),t._loadItems()},t.options.duration,t)},_createLoader:function(e){var t,i,a,n=[];return function(o){return i||(i=!0,e(function(e){for(t=e;a=n.shift();)try{a&&a.apply(null,[t])}catch(e){setTimeout(function(){throw e},0)}})),t?(o&&o.apply(null,[t]),t):(o&&n.push(o),t)}},_buffer:function(t,i,a){function n(){o&&(o.cancel(),o=0),r=e.now(),t.apply(a||this,arguments),s=e.now()}var o,r=0,s=0,i=i||150;return e.extend(function(){!r||s>=r&&e.now()-s>i||s<r&&e.now()-r>8*i?n():(o&&o.cancel(),o=e.later(n,i,null,arguments))},{stop:function(){o&&(o.cancel(),o=0)}})},_getBoundingRect:function(i){var a,n,o,r;if(void 0!==i){a=i.offsetHeight,n=i.offsetWidth;var s=e.offset(i);o=s.left,r=s.top}else a=t.innerHeight,n=t.innerWidth,o=0,r=t.pageYOffset;var d=this.options.diff,l=!1===d?n:d,c=l,u=!1===d?a:d,m=u,p=o+n,f=r+a;return o-=0,p+=c,r-=0,f+=m,{left:o,top:r,right:p,bottom:f}},_cacheWidth:function(e){return e._mui_lazy_width?e._mui_lazy_width:e._mui_lazy_width=e.offsetWidth},_cacheHeight:function(e){return e._mui_lazy_height?e._mui_lazy_height:e._mui_lazy_height=e.offsetHeight},_isCross:function(e,t){var i={};return i.top=Math.max(e.top,t.top),i.bottom=Math.min(e.bottom,t.bottom),i.left=Math.max(e.left,t.left),i.right=Math.min(e.right,t.right),i.bottom>=i.top&&i.right>=i.left},_elementInViewport:function(t,i,a){if(!t.offsetWidth)return!1;var n,o=e.offset(t),r=!0,s=o.left,d=o.top,l={left:s,top:d,right:s+this._cacheWidth(t),bottom:d+this._cacheHeight(t)};return n=this._isCross(i,l),n&&a&&(r=this._isCross(a,l)),r&&n},_loadItems:function(){var t=this;t._containerIsNotDocument&&!t.container.offsetWidth||(t._windowRegion=t._getBoundingRect(),t._containerIsNotDocument&&(t._containerRegion=t._getBoundingRect(this.container)),e.each(t._callbacks,function(e,i){i&&t._loadItem(e,i)}))},_loadItem:function(e,t){var i=this;if(!(t=t||i._callbacks[e]))return!0;var a=t.el,n=!1,o=t.fn;if(i.options.force||i._elementInViewport(a,i._windowRegion,i._containerRegion))try{n=o.call(i,a,e)}catch(e){setTimeout(function(){throw e},0)}return!1!==n&&delete i._callbacks[e],n},addCallback:function(t,i){var a=this,n=a._callbacks,o={el:t,fn:i||e.noop},r=++this._key;n[r]=o,a._windowRegion?a._loadItem(r,o):a.refresh()},addElements:function(t){var i=this;i._counter=i._counter||0;var n=[];!t&&i.options.selector?n=i.container.querySelectorAll(i.options.selector):e.each(t,function(t,a){n=n.concat(e.qsa(i.options.selector,a))}),e.each(n,function(e,t){t.getAttribute("data-lazyload-id")||i.addElement(t)&&(t.setAttribute("data-lazyload-id",a++),i.addCallback(t,i.handle))})},addElement:function(e){return!0},handle:function(){},refresh:function(e){e&&this.addElements(),this._loadFn()},pause:function(){var e=this._loadFn;this._destroyed||(t.removeEventListener("scroll",e),t.removeEventListener("touchmove",e),t.removeEventListener("resize",e),this._containerIsNotDocument&&(this.container.removeEventListener("scrollend",e),this.container.removeEventListener("scroll",e),this.container.removeEventListener("touchmove",e)))},resume:function(){var e=this._loadFn;this._destroyed||(t.addEventListener("scroll",e,!1),t.addEventListener("touchmove",e,!1),t.addEventListener("resize",e,!1),this._containerIsNotDocument&&(this.container.addEventListener("scrollend",e,!1),this.container.addEventListener("scroll",e,!1),this.container.addEventListener("touchmove",e,!1)))},destroy:function(){var t=this;t.pause(),t._callbacks={},e.trigger(this.container,"destory",t),t._destroyed=1}})}(mui,window,document),function(e,t,i){var a=e.Lazyload.extend({init:function(e,t){this._super(e,t)},_init:function(){this.options.selector="[data-lazyload]",this._super()},_set:function(e,t){"IMG"===e.tagName?e.src=t:e.style.backgroundImage="url("+t+")"},_hasPlaceholder:function(e){return!!e.offsetWidth&&("IMG"===e.tagName?!!e.src:!!e.style.backgroundImage)},_addPlaceHolder:function(e){var t=this;"IMG"===e.tagName?(t._counter++,e.onload=function(){t._counter--,t.addCallback(e,t.handle),this.onload=null},t.onPlaceHolder(function(i){t._set(e,i)})):e.style.backgroundImage="url("+t.options.placeholder+")"},addElement:function(e){var t=this;return!!e.getAttribute("data-lazyload")&&(t._hasPlaceholder(e)?t.addCallback(e,t.handle):(t.onPlaceHolder=t._createLoader(function(e){var i=new Image,a=t.options.placeholder;i.src=a,i.onload=i.onerror=function(){e(a)}}),t._addPlaceHolder(e)),!0)},set:function(t,i){var a=this,n=new Image;n.onload=function(){a._set(t,i),e.trigger(a.element,"success",{element:t,uri:i})},n.onerror=function(){e.trigger(a.element,"error",{element:t,uri:i})},n.src=i,t.removeAttribute("data-lazyload")},handle:function(e,t){var i=e.getAttribute("data-lazyload");i&&this.set(e,i)},destroy:function(){this._super(),this.element.removeAttribute("data-imageLazyload")}});e.fn.imageLazyload=function(n){var o=[];return this.each(function(){var r=this,s=null;r!==i&&r!==t||(r=i.body);var d=r.getAttribute("data-imageLazyload");d?s=e.data[d]:(d=++e.uuid,e.data[d]=s=new a(r,n),r.setAttribute("data-imageLazyload",d)),o.push(s)}),1===o.length?o[0]:o}}(mui,window,document);var mainBanner=doc.getElementById("mainBanner"),entrance=doc.getElementById("J-entrance"),announced=doc.getElementById("J-announced"),biding=doc.getElementById("biding"),mybids=doc.getElementById("mybids"),myfocus=doc.getElementById("myfocus"),deallistWrap=doc.getElementById("J-deallistWrap"),deallistWrapper=doc.getElementById("J-deallistWrapper"),deallistRoll=doc.getElementById("J-deallistRoll"),mainTags=doc.getElementById("J-mainTags"),deallistRollHeight=deallistWrap.clientHeight,loadNum,loaded,pIdArrObj,priceObj,pageObj,currentTag="biding",changeTag=!1,freshTime=1e3,tlArrObj,stateObj,aHeight=-9.45*win.rem,tiRoll,tiAc,tiPr,tiRollGet,cTi=null,colorTi={},fixed=[!1,!1],fixedTop=[-5.55*win.rem,-8.45*win.rem],_init=popdown=0,main={init:function(){win._vid&&(_init=1,loadNum=loaded=0,pIdArrObj={biding:[],mybids:[],myfocus:[]},tlArrObj={biding:{},mybids:{},myfocus:{}},stateObj={},priceObj={},pageObj={biding:1,mybids:1,myfocus:1},main.getBanner(),main.getEntrance(),main.getAnnounced(1),main.getGoodsList("biding",1),_user.id>0&&(main.getGoodsList("mybids"),main.getGoodsList("myfocus")),clearInterval(tiPr),tiPr=setInterval(function(){main.getBidPrice()},freshTime),main.popDownload())},loadData:function(){pageObj[currentTag]++,main.getGoodsList(currentTag,0,1)},isLoaded:function(){++loaded==loadNum&&(mui("#mainContainer").pullRefresh().endPulldownToRefresh(),mui("#mainContainer").pullRefresh().refresh(!0),mui("#mainContainer").pullRefresh().endPullupToRefresh(!1))},countDown:function(){mui.each(tlArrObj[currentTag],function(e,t){var i=doc.querySelector("#period"+e+"_"+currentTag+" .timeline");t>1e3?(t-=1e3,tlArrObj[currentTag][e]=t,i.innerHTML=_p.getTimeLine(t),1==stateObj[e]?i.classList.add("tips"):i.classList.remove("tips")):0==stateObj[e]&&(tlArrObj[currentTag][e]=0,i.innerHTML=_p.getTimeLine(0))})},getBanner:function(){loadNum++,api.url="1",api.method="get",mui.post("api.php",api,function(e){if(main.isLoaded(),"0000"==e.code){var t=navs=imgs_first=imgs_last="",i=e.data.length;mui.each(e.data,function(a,n){var o=_p.getPageUrl(n.function,n.params);t+='<div class="mui-slider-item"><a href="'+o+'" target="_top"><img src="'+envConfig.imgHost+n.img+'"></a></div>',0==a?(i>1&&(imgs_last='<div class="mui-slider-item mui-slider-item-duplicate"><a href="'+o+'"><img src="'+envConfig.imgHost+n.img+'"></a></div>'),navs+='<div class="mui-indicator mui-active"></div>'):a==e.data.length-1?(imgs_first='<div class="mui-slider-item mui-slider-item-duplicate"><a href="'+o+'"><img src="'+envConfig.imgHost+n.img+'"></a></div>',navs+='<div class="mui-indicator"></div>'):navs+='<div class="mui-indicator"></div>'}),mainBanner.innerHTML='<div class="mui-slider"><div class="mui-slider-group'+(i>1?" mui-slider-loop":"")+'">'+imgs_first+t+imgs_last+'</div><div class="mui-slider-indicator">'+navs+"</div></div>",mui("#mainBanner .mui-slider").slider({interval:3e3})}else"vid"==e.code?(_init=0,_getingVid||_p.vid(main.getBanner)):mui.toast(e.message)},"json")},getEntrance:function(){loadNum++,api.url="3",api.method="get",mui.post("api.php",api,function(e){if(main.isLoaded(),"0000"==e.code){var t="";mui.each(e.data,function(e,i){var a=_p.getPageUrl(i.function,i.params);t+='<a href="'+a+'" target="_top"><img src="'+envConfig.imgHost+i.img+'"><span>'+i.title+"</span></a>"}),entrance.innerHTML=t}else"vid"==e.code?(_init=0,_getingVid||_p.vid(main.getEntrance)):mui.toast(e.message)},"json")},getAnnounced:function(e){var t=arguments.callee;api.url="4",api.method="get",api.limit=3,mui.post("api.php",api,function(e){if(e)if("0000"==e.code){var i=scrollHtml="";mui.each(e.data,function(e,t){i+='<a class="ui-mark-'+t.bid_step+'" href="detail.html?id='+t.id+'"><span class="cover ui-traded"><img src="'+envConfig.imgHost+t.img_cover+'" /></span><span class="price">￥'+t.bid_price+'</span><span class="username hidelong">'+t.nickname+"</span></a>",scrollHtml+='<a class="hidelong" href="detail.html?id='+t.id+'">恭喜<b>'+t.nickname+"</b>以<em>￥"+t.bid_price+"</em>拍到"+t.title+'<img src="'+envConfig.imgHost+t.img_cover+'?imageView2/1/w/50/h/50"></a>'}),deallistRoll.innerHTML+=scrollHtml,api.url="98",api.method="get",mui.post("api.php",api,function(e){"0000"==e.code&&1==e.data.value&&(announced.innerHTML=i,announced.style.display="block",popdown?fixedTop[1]=aHeight:fixedTop[1]=-8.45*win.rem)},"json"),clearTimeout(tiRollGet),tiRollGet=setTimeout(t,3e3*(deallistRoll.children.length-1)),clearInterval(tiRoll),tiRoll=setInterval(function(){deallistRoll.setAttribute("style","transform:translate3d(0,0,0); -webkit-transform:translate3d(0,0,0); transition-duration: 0; -webkit-transition-duration: 0;"),deallistRoll.setAttribute("style","transform:translate3d(0,-"+deallistRollHeight+"px, 0); -webkit-transform:translate3d(0,-"+deallistRollHeight+"px, 0); transition-duration: .3s; -webkit-transition-duration: .3s;");var e=function(){deallistRoll.removeEventListener("webkitTransitionEnd",e),deallistRoll.setAttribute("style","transform:translate3d(0,0,0); -webkit-transform:translate3d(0,0,0); transition-duration: 0; -webkit-transition-duration: 0;"),deallistRoll.children.length>1&&deallistRoll.removeChild(deallistRoll.children[0])};deallistRoll.addEventListener("webkitTransitionEnd",e)},3e3)}else"vid"==e.code?_getingVid||_p.vid(t):mui.toast(e.message)},"json")},getGoodsList:function(e,t){var i,e=e||"biding";switch(t||loadNum++,e){case"mybids":api.url="6",i=mybids;break;case"biding":default:api.url="7",i=biding;break;case"myfocus":api.url="46",i=myfocus}api.method="get",api.limit=20,api.offset=(pageObj[e]-1)*api.limit,mui.post("api.php",api,function(a){if(t||main.isLoaded(),"0000"==a.code)if(a.data.length>0){var n=_p.goodsList(a.data,e,pIdArrObj[e]);pIdArrObj[e].length>0?(pIdArrObj[e]=pIdArrObj[e].concat(n.arr),i.innerHTML+=n.html):(pIdArrObj[e]=n.arr,i.innerHTML=n.html),_p.getFocus(n.gid,i),pIdArrObj[e].length>0&&main.getBidPrice(),e==currentTag&&(a.data.length<api.limit?1==pageObj[e]?mui("#mainContainer").pullRefresh().disablePullupToRefresh():mui("#mainContainer").pullRefresh().endPullupToRefresh(!0):(mui("#mainContainer").pullRefresh().endPullupToRefresh(!1),mui("#mainContainer").pullRefresh().enablePullupToRefresh()))}else 1==pageObj[e]&&(i.innerHTML='<div class="nodata-default"><a href="category.html">去逛逛</a></div>'),e==currentTag&&(pageObj[currentTag]--,mui("#mainContainer").pullRefresh().disablePullupToRefresh(),mui("#mainContainer").pullRefresh().endPullupToRefresh(!0));else"vid"==a.code?_getingVid||_p.vid():"9997"==a.code||mui.toast(a.message)},"json")},getBidPrice:function(){var e=pIdArrObj[currentTag];e.length>0&&(api.url="8",api.method="get",api.periods=e.join(),mui.post("api.php",api,function(e){e&&("0000"==e.code?(mui.each(e.data,function(e,t){var i="period"+t.a+"_"+currentTag,a=doc.getElementById(i);if(a){if(changeTag||priceObj[t.a]!=t.d){var n=a.querySelector(".price_cur");priceObj[t.a]&&!changeTag&&(clearTimeout(colorTi[i]),n.className="price_cur tipin",colorTi[i]=setTimeout(function(){n.className="price_cur tipout"},20)),n.innerHTML="￥ "+t.d,(!tlArrObj[currentTag][t.a]||t.c>tlArrObj[currentTag][t.a])&&(0==t.b?tlArrObj[currentTag][t.a]=t.c+1e3:tlArrObj[currentTag][t.a]=(t.c>1e3*t.i?1e3*t.i:t.c)+1e3),priceObj[t.a]=t.d}else 0==stateObj[t.a]&&1==t.b&&(tlArrObj[currentTag][t.a]=1e3*t.i+1e3);a.querySelector(".price_tip").innerHTML=t.h||"当前价",stateObj[t.a]=t.b,2==t.b||3==t.b?(a.querySelector(".price_wrap").className="price_wrap",a.classList.add("traded"),a.querySelector(".cover").classList.add("ui-traded"),pIdArrObj[currentTag].removeValue(t.a),a.querySelector(".timeline").innerHTML=_p.getTimeLine(0),tlArrObj[currentTag][t.a]=0):1==t.b?(a.querySelector(".price_wrap").className="price_wrap tip",a.querySelector(".timeline").className="timeline tip"):a.querySelector(".price_wrap").className="price_wrap"}}),changeTag=!1,cTi||(cTi=setInterval(main.countDown,1e3))):"vid"==e.code?_p.vid():"9997"==e.code||mui.toast(e.message))},"json"))},popDownload:function(){"0"==_p.queryStr("allow_download")||"ios"==api.mobileos&&parseFloat(api.mobileosversion)<8||"mozilla/5.0 (linux; u; android 4.4; en-us; nexus 5 build/jop24g) applewebkit/534.30 (khtml, like gecko) version/4.0 mobile safari/534.30"==ua||sessionStorage.hideDownload||(doc.querySelector(".pop_download").style.display="block",popdown=1,fixedTop[0]-=.92*win.rem,fixedTop[1]-=.92*win.rem,sessionStorage.hideJumpApp||_p.openApp())}};mui.ready(function(){setTimeout(function(){_p.tabbar(),main.init()},5);var e=doc.getElementById("goodsList"),t=doc.getElementById("J-mainCt");mui.init({pullRefresh:{container:"#mainContainer",down:{callback:main.init},up:{height:0,callback:main.loadData}}}),doc.querySelector(".mui-scroll-wrapper").addEventListener("scroll",function(i){0!=i.detail.y&&(i.detail.y<=fixedTop[0]?fixed[0]||(fixed[0]=!fixed[0],deallistWrapper.className="deallist_roll fixed",wrap.appendChild(deallistWrapper)):fixed[0]&&(fixed[0]=!fixed[0],deallistWrapper.className="",deallistWrap.appendChild(deallistWrapper)),i.detail.y<=fixedTop[1]?fixed[1]||(fixed[1]=!fixed[1],e.classList.add("fixed"),mainTags.classList.add("fixed"),wrap.appendChild(mainTags)):fixed[1]&&(fixed[1]=!fixed[1],e.classList.remove("fixed"),mainTags.classList.remove("fixed"),e.insertBefore(mainTags,t)))}),mui(".wrap").on("tap","a",function(){this.href&&(location.href=this.href)}),mui(".pop_download").on("tap","a",function(){var e=this.parentNode,t=this.className;if("icon-close-x"==this.className)e.style.display="none",fixedTop[0]+=.92*win.rem,fixedTop[1]+=.92*win.rem;else if("btn"==this.className)if(ua.match(/alipay/i)){var t=txt="";ua.match(/android/i)?(t="wechat_tip android",txt="在浏览器中打开"):(t="wechat_tip ios",txt="在Safari中打开");var i=doc.createElement("div");i.className=t,i.style.display="block",i.innerHTML="<h1>无法打开链接？</h1><p>请点击右上角 <b>●●●</b><br>选择“<span>"+txt+"</span>”</p>",wrap.appendChild(i),mui(".wrap").on("tap",".wechat_tip",function(){this.style.display="none"})}else doc.location="https://static.gogobids.com/other/deeplink.html"}),mui("#J-mainTags").on("tap","a",function(){var e=this.parentNode,t=this.getAttribute("data-href");if(!_user.id&&("mybids"==t||"myfocus"==t))return _p.changeTag(win,mainTags.children[0]),location.href="login.html?redirect=index.html",!1;currentTag!=t&&(mui("#mainContainer").pullRefresh().enablePullupToRefresh(),mui("#mainContainer").pullRefresh().refresh(!0),currentTag=t,changeTag=!0,clearInterval(cTi),cTi=setInterval(main.countDown,1e3)),"ui-tags-tit fixed"==e.className&&mui("#mainContainer").pullRefresh().scrollTo(0,-8.6*win.rem,0)}),mui("#J-mainCt").on("tap","a",function(){var e=this,t=e.className;switch("cover"==t||_user.id||(win.top.location="login.html?redirect=index.html"),t){case"add_focus":e.className="add_focus ed",e.innerHTML="已收藏",api.url="9",api.method="post",api.product_id=e.parentNode.getAttribute("data-gid"),mui.post("api.php",api,function(t){if("0000"==t.code){pIdArrObj.myfocus=[],tlArrObj.myfocus={},pageObj.myfocus=1,main.getGoodsList("myfocus",0);var i=e.parentNode.getAttribute("data-pid"),a=doc.querySelector("#period"+i+"_biding .add_focus"),n=doc.querySelector("#period"+i+"_biding .add_focus");n&&(n.className="add_focus ed",n.innerHTML="已收藏"),a&&(a.className="add_focus ed",a.innerHTML="已收藏")}else"vid"==t.code?(_p.vid(),mui.toast("网络异常，请重试"),e.className="add_focus",e.innerHTML="收藏"):"9997"==t.code?win.top.location="login.html?redirect=index.html":mui.toast(t.message)},"json");break;case"add_focus ed":var i=["否","是"];mui.confirm("",'<p class="tac">确认要取消收藏吗？</p>',i,function(t){1==t.index&&(e.className="add_focus",e.innerHTML="收藏",api.url="10",api.method="post",api.product_id=e.parentNode.getAttribute("data-gid"),mui.post("api.php",api,function(t){if("0000"==t.code){pIdArrObj.myfocus=[],tlArrObj.myfocus={},pageObj.myfocus=1,main.getGoodsList("myfocus",0);var i=e.parentNode.getAttribute("data-pid"),a=doc.querySelector("#period"+i+"_mybids .add_focus"),n=doc.querySelector("#period"+i+"_biding .add_focus");n&&(n.className="add_focus",n.innerHTML="收藏"),a&&(a.className="add_focus",a.innerHTML="收藏")}else"vid"==t.code?(_p.vid(),mui.toast("网络异常，请重试"),e.className="add_focus ed",e.innerHTML="已收藏"):"9997"==t.code?win.top.location="login.html?redirect=index.html":mui.toast(t.message)},"json"))});break;case"cover":case"cover ui-traded":case"bid_btn":var a=e.parentNode;"bid_btn"==t?a.classList.contains("traded")||(location.href="detail.html?id="+(a.getAttribute("data-pid")||"")):location.href="detail.html?id="+(a.getAttribute("data-pid")||"");break;case"bid_btn locked":case"bid_tool locked":var n=this.getAttribute("data-lock");_p.popupLock(n)}})});