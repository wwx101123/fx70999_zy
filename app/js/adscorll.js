/**
 * Created by Administrator on 2017/7/11 0011.
 */
$(document).ready(function(){
    var deviceWidth=$(window).outerWidth();
    if(deviceWidth>720){
        $("html").css("font-size","100px");
    }else{
        $("html").css("font-size",deviceWidth/720*100+'px');
    }
});

function AutoScroll(obj) {
    $(obj).find("ul:first").animate({
            marginTop: "-22px"
        },
        500,
        function() {
            $(this).css({
                marginTop: "0px"
            }).find("li:first").appendTo(this);
        });
}
$(document).ready(function() {
    setInterval('AutoScroll("#demo")', 1000)
})
