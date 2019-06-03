function left2(){
    /*$(".left2 a").hover(function () {
        $(this).stop().animate({left: -2}, 300);
    }, function () {
        $(this).stop().animate({left: 0}, 300);

    });*/

    $(".top2 li").hover(function () {
        $(this).stop().animate({top: -2}, 300);
    }, function () {
        $(this).stop().animate({top: 0}, 300);

    });



};


function banner(){
/* 控制左右按钮显示 */
jQuery(".fullSlide").hover(function(){ jQuery(this).find(".prev,.next").stop(true,true).fadeTo("show",0.4) },function(){ jQuery(this).find(".prev,.next").fadeOut() });

/* 调用SuperSlide */
jQuery(".fullSlide").slide({ titCell:".hd ul", mainCell:".bd ul", effect:"fold",  autoPlay:true, autoPage:true, trigger:"click",
    startFun:function(i){
        var curLi = jQuery(".fullSlide .bd li").eq(i); /* 当前大图的li */
        if( !!curLi.attr("_src") ){
            curLi.css("background-image",curLi.attr("_src")).removeAttr("_src") /* 将_src地址赋予li背景，然后删除_src */
        }
    }
});
};

function gongg() {
    jQuery(".txtScroll-news").slide({titCell:".hd ul",mainCell:".bd ul",autoPage:true,effect:"top",autoPlay:true,vis:1});

}

/*调用*/
$(function(){
    left2();
    banner();
    gongg();
});