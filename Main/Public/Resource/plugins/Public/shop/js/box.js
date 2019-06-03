/*添加收藏
<a title="收藏本站" href="javascript:;" onClick="addFavoritepage('H-ui前端框架','http://www.h-ui.net/');">收藏本站</a>
*/
/*收藏主站*/
function addFavorite(name,site){
	try{window.external.addFavorite(site,name);}
	catch(e){
		try{window.sidebar.addPanel(name,site,"");}
			catch(e){alert("加入收藏失败，请使用Ctrl+D进行添加");}
	}
}
/*收藏页面
<a title="收藏本页" href="javascript:addFavoritepage(0);">收藏本页</a>
*/
function addFavoritepage(){var sURL=window.location.href;var sTitle=document.title;try{window.external.addFavorite(sURL,sTitle);}catch(e){try{window.sidebar.addPanel(sTitle,sURL,"");}catch(e){alert("加入收藏失败，请使用Ctrl+D进行添加");}}}

/*设为首页*/
function setHome(obj){
  try{obj.style.behavior="url(#default#homepage)";obj.setHomePage(webSite);}
  catch(e){if(window.netscape){
	  try {netscape.security.PrivilegeManager.enablePrivilege("UniversalXPConnect");}
	  catch(e){alert("此操作被浏览器拒绝！\n请在浏览器地址栏输入\"about:config\"并回车\n然后将 [signed.applets.codebase_principal_support]的值设置为'true',双击即可。");}
	  var prefs = Components.classes['@mozilla.org/preferences-service;1'].getService(Components.interfaces.nsIPrefBranch);
	  prefs.setCharPref('browser.startup.homepage',url);}}
	  
}

//banner
	jQuery("#iFocus li a").hover(function(){ $(this).css("opacity",1).siblings().css("opacity",0.7) },function(){ $(this).css("opacity",1).siblings().css("opacity",1) });//鼠标移到图片上时聚焦效果
	jQuery("#iFocus").slide({ titCell:".btn span", mainCell:"ul",effect:"left", autoPlay:true});
//产品	
/*jQuery(".picScroll-left").slide({titCell:".hd ul",mainCell:".bd ul",autoPage:true,effect:"left",autoPlay:true,vis:5,trigger:"click",});*/
		jQuery(".picScroll-left").slide({mainCell:".bd ul",autoPlay:true,effect:"leftMarquee",vis:5,interTime:35,trigger:"click"});
			jQuery(".slideGroup").slide({titCell:".parHd li",mainCell:".parBd",prevCell:".sPrev2",nextCell:".sNext2",delayTime:100});


$(document).ready(function () {
    $('.picScroll-left li').each(function (m) {
        $(this).find('.tag_txt').css('top', -220);
        $(this).hover(function () {
            $(this).find('.tag_txt').animate({			
                'top': '0'
            },
            200)
        },
        function () {
            $(this).find('.tag_txt').animate({
                'top': 150
            },
            {
                duration: 200,
                complete: function () {
                    $(this).css('top', -220)
                }
            })
        })
    }); 
});	
$(document).ready(function () {
    $('.picScroll-left li').each(function (m) {
        $(this).find('.tag_txt2').css('top', -220);
        $(this).hover(function () {
            $(this).find('.tag_txt2').animate({			
                'top': '0'
            },
            200)
        },
        function () {
            $(this).find('.tag_txt2').animate({
                'top': 150
            },
            {
                duration: 200,
                complete: function () {
                    $(this).css('top', -220)
                }
            })
        })
    }); 
});			
//购物车抛物线
$(function(){
   $('.Q-buy-btn').shoping(); //调用shoping函数
});

//产品详情切换
jQuery(".slideTxtBox2").slide({delayTime:0});

//选项按钮

//滚动信息
jQuery(".txtScroll-top").slide({titCell:".hd ul",mainCell:".bd ul",autoPage:true,effect:"top",autoPlay:true,vis:1,interTime:5000});
//资料切换
jQuery(".slideTxtBox3").slide({trigger:"click",delayTime:0});		
//登录
$(document).ready(function(){
    var k=!0;

    $(".loginmask").css("opacity",0.8);

    if($.browser.version <= 6){
        $('#reg_setp,.loginmask').height($(document).height());
    }

    $(".thirdlogin ul li:odd").css({marginRight:0});

    $(".openlogin").click(function(){
        k&&"0px"!=$("#loginalert").css("top")&& ($("#loginalert").show(),$(".loginmask").fadeIn(500),$("#loginalert").animate({top:0},400,"easeOutQuart"))
    });

    $(".loginmask,.closealert").click(function(){
        k&&(k=!1,$("#loginalert").animate({top:-600},400,"easeOutQuart",function(){$("#loginalert").hide();k=!0}),$(".loginmask").fadeOut(500))
    });


    $("#sigup_now,.reg a").click(function(){
        $("#reg_setp,#setp_quicklogin").show();
        $("#reg_setp").animate({left:0},500,"easeOutQuart")
    });

    $(".back_setp").click(function(){
        "block"==$("#setp_quicklogin").css("display")&&$("#reg_setp").animate({left:"100%"},500,"easeOutQuart",function(){$("#reg_setp,#setp_quicklogin").hide()})
    });

});

//验证表单
$(function(){
    $('.skin-minimal input').iCheck({
        checkboxClass: 'icheckbox-orange',
        radioClass: 'iradio-orange',
        increaseArea: '20%'
    });
    $("#demoform-2").Validform({
        tiptype:2,
        usePlugin:{
            datepicker:{},//日期控件校验;
            passwordstrength:{
                minLen:6,//设置密码长度最小值，默认为0;
                maxLen:18,//设置密码长度最大值，默认为30;
                trigger:function(obj,error){
                    //该表单元素的keyup和blur事件会触发该函数的执行;
                    //obj:当前表单元素jquery对象;
                    //error:所设密码是否符合验证要求，验证不能通过error为true，验证通过则为false;
                    //console.log(error);
                    if(error){
                        obj.parent().find(".Validform_checktip").show();
                        obj.parent().find(".passwordStrength").hide();
                    }else{
                        obj.parent().find(".passwordStrength").show();
                    }
                }
            }
        }
    });


    $("#demoform-4").Validform({
        tiptype:2,
        usePlugin:{
            datepicker:{},//日期控件校验;
            passwordstrength:{
                minLen:6,//设置密码长度最小值，默认为0;
                maxLen:18,//设置密码长度最大值，默认为30;
                trigger:function(obj,error){
                    //该表单元素的keyup和blur事件会触发该函数的执行;
                    //obj:当前表单元素jquery对象;
                    //error:所设密码是否符合验证要求，验证不能通过error为true，验证通过则为false;
                    //console.log(error);
                    if(error){
                        obj.parent().find(".Validform_checktip").show();
                        obj.parent().find(".passwordStrength").hide();
                    }else{
                        obj.parent().find(".passwordStrength").show();
                    }
                }
            }
        }
    });


    $("#demoform-3").Validform({
        tiptype:2,
        usePlugin:{
            datepicker:{},//日期控件校验;
            passwordstrength:{
                minLen:6,//设置密码长度最小值，默认为0;
                maxLen:18,//设置密码长度最大值，默认为30;
                trigger:function(obj,error){
                    //该表单元素的keyup和blur事件会触发该函数的执行;
                    //obj:当前表单元素jquery对象;
                    //error:所设密码是否符合验证要求，验证不能通过error为true，验证通过则为false;
                    //console.log(error);
                    if(error){
                        obj.parent().find(".Validform_checktip").show();
                        obj.parent().find(".passwordStrength").hide();
                    }else{
                        obj.parent().find(".passwordStrength").show();
                    }
                }
            }
        }
    });

});


