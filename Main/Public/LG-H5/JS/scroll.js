(function () {
  
  var scrollAnimate = {
    animate_1: function(k){
             setTimeout(function(){$(".wideTitle").show();$(".f-phone1").fadeIn(300)});
             setTimeout(function(){$(".f-phone2").fadeIn(300)},500);
             setTimeout(function(){$(".f-left").slideDown(300)},1000);
             setTimeout(function(){$(".f-phone3").fadeIn(300)},1500);
             setTimeout(function(){$(".f-right").slideDown(300)},2000);
             setTimeout(function(){$(".m-animate0").fadeOut(300)},3000);

             setTimeout(function(){$(".page0 .f-phone").fadeIn(300)},3500);
             setTimeout(function(){$(".page0 .f-font").fadeIn(300)},4000);
              setTimeout(function(){$(".page0 .f-font,.page0 .f-phone").fadeOut(300)},4500);

             setTimeout(function(){$(".page1 .f-phone").fadeIn(300)},5500);
             setTimeout(function(){$(".page1 .f-font").fadeIn(300)},5800);
             setTimeout(function(){$(".page1 .f-font,.page1 .f-phone").fadeOut(300)},6900);

             setTimeout(function(){$(".page2 .f-phone").fadeIn(300)},7500);
             setTimeout(function(){$(".page2 .f-font").fadeIn(300)},8000);
             setTimeout(function(){$(".page2 .f-font").fadeOut(300)},9000);

             setTimeout(function(){$(".page0,.page1,.page2").hide(300);$(".page3 .end-pic-left").slideDown(500)},9500);
             setTimeout(function(){$(".page4").fadeIn(300)},10500);
             setTimeout(function(){
                 $(".page3 .end-pic-right").slideDown(300,function(){
                    $animate.eq( k ).attr('data-animate','true');
                 });                               
           },11000);
 
 
    },
    animate_2: function(k){
        setTimeout(function(){$(".animate_02_con_text01").slideDown()},300);

            setTimeout(function(){$(".animate_02_con_text02").slideDown(300)},400);
            setTimeout(function(){$(".animate_02_con_text04").addClass('animate_02_con_text04_animate').show()},1000);
            setTimeout(function(){$(".animate_02_con_text01").fadeOut()},1500);
            setTimeout(function(){$(".animate_02_con_text05").fadeIn(500)},1500);
            setTimeout(function(){
                $('..animate_02_con_text06 img').width(0).height(258);
                $(".animate_02_con_text06").fadeIn(100);
                $('.animate_02_con_text06').animate({
                  'width':'100%'
                },1000);
               $animate.eq( k ).attr('data-animate','true');

              },2000);

    },
    animate_3: function(k){
        setTimeout(function(){$(".animate_03_con_phone1").fadeIn(100)});
        setTimeout(function(){$(".animate_03_con_progress02").fadeIn(100)},500);
        
        setTimeout(function(){$(".animate_03_con_hand02").fadeIn(1000)},1000);
        setTimeout(function(){$(".animate_03_con_hand02").fadeOut(1000)},1000);

        setTimeout(function(){$(".animate_03_con_hand03").fadeIn(1000)},1500);
        setTimeout(function(){$(".animate_03_con_hand03").fadeOut(1000)},1500);

        setTimeout(function(){$(".animate_03_con_hand04").fadeIn(1000)},2000);
 
        setTimeout(function(){$(".animate_03_con_phone2,.animate_03_con_progress02").fadeIn(1000)},2500);
        setTimeout(function(){$(".animate_03_con_phone3,.animate_03_con_progress03").fadeIn(1000)},3000);
        setTimeout(function(){$(".animate_03_con_phone4,.animate_03_con_progress04").fadeIn(1000)},3500);
        
        setTimeout(function(){$(".animate_03_con_hand04").fadeOut(1000)},5700);

        setTimeout(function(){$(".animate_03_con_hand03").fadeIn(1000)},5500);
        setTimeout(function(){$(".animate_03_con_hand03").fadeOut(1000)},5500);

        setTimeout(function(){$(".animate_03_con_hand02").fadeIn(1000)},6000);
        setTimeout(function(){$(".animate_03_con_hand02").fadeOut(1000)},6000);

        setTimeout(function(){$(".animate_03_con_hand01").fadeIn(1000)},6500);
        setTimeout(function(){


           $(".animate_03_con_progress04,.animate_03_con_phone4").fadeOut(500);
           $(".animate_03_con_progress03,.animate_03_con_phone3").fadeIn(500);
         
        },6500);

        setTimeout(function(){
          
          $(".animate_03_con_progress03,.animate_03_con_phone3").fadeOut(500);
          $(".animate_03_con_progress02,.animate_03_con_phone2").fadeIn(100);

        },7500);
         setTimeout(function(){
          $(".animate_03_con_progress02,.animate_03_con_phone2").fadeOut(500);
          $(".animate_03_con_progress01,.animate_03_con_phone1").fadeIn(500);
          $animate.eq( k ).attr('data-animate','true');
        },8500);
           


    },
    animate_4: function(k){
             setTimeout(function(){$(".animate_04_con_text01").fadeIn(300)});
             setTimeout(function(){$(".animate_04_con_hand").show().animate({
              'left': '75px',
              'top': '162px'
             });
           },1000);
             setTimeout(function(){$(".animate_04_con_hand").show().animate({
              'left': '75px',
              'top': '206px'
             });
           },2000);
          

          setTimeout(function(){$(".animate_04_con_text02").fadeIn(100)},2500);
          setTimeout(function(){$(".animate_04_con_hand").show().animate({
              'left': '75px',
              'top': '245px'
             });
           },4000);

          setTimeout(function(){$(".animate_04_con_text03").fadeIn(300)},4500);
          setTimeout(function(){$(".animate_04_con_hand").show().animate({
              'left': '231px',
              'top': '355px'
             });
           },6000);
          setTimeout(function(){$(".animate_04_con_text07").fadeIn(300)},6500); 
          setTimeout(function(){$(".animate_04_con_hand").show().animate({
              'left': '331px',
              'top': '355px'
             });
           },8000);
          setTimeout(function(){$(".animate_04_con_text04").fadeIn(300)},8500);
          setTimeout(function(){$(".animate_04_con_hand").show().animate({
              'left': '468px',
              'top': '251px'
             });
           },9000);
          setTimeout(function(){$(".animate_04_con_text05").fadeIn(300)},9500);
          setTimeout(function(){$(".animate_04_con_text06").fadeIn(300);
            $animate.eq( k ).attr('data-animate','true');
        },11000);
    },
    animate_5: function(k){
             setTimeout(function(){$(".animate_11_con_text06,.animate_11_con_text07").fadeIn()},200);
             setTimeout(function(){$(".animate_11_con_text06").fadeOut()},1000);
             setTimeout(function(){$(".animate_11_con_text01,.animate_11_con_text05").fadeIn(500)},1000);

             setTimeout(function(){

              $(".animate_11_con_text01").fadeOut(800);
              // $(".animate_11_con_text02").fadeIn(800);
              $(".animate_11_con_text03").fadeIn(800);
             },2000);
             setTimeout(function(){
              // $(".animate_11_con_text02").fadeOut(800);
              $(".animate_11_con_text03").fadeOut(800);
              $(".animate_11_con_text04").fadeIn(800);
            },2500);
             setTimeout(function(){
              $(".animate_11_con_text04").fadeOut(800);
              $(".animate_11_con_text08").fadeIn(800);
              $animate.eq( k ).attr('data-animate','true');
            },3000);
 
    },
    animate_6: function(k){
            setTimeout(function(){$(".animate_12_con_text01").slideDown(300)},500);
            setTimeout(function(){
              $(".animate_12_con_text03").show();
              $(".animate_12_con_text02").slideDown(300);
              $animate.eq( k ).attr('data-animate','true');
           },1500);
             
    },
    animate_7: function(k){
             setTimeout(function(){$(".animate_05_con_text01").slideDown(300)});
             setTimeout(function(){$(".animate_05_con_text02").show(300).animate({

                'bottom':'-10px',
                'left': '-150px'
             });
            },500);
            setTimeout(function(){$(".animate_05_con_text02").animate({
                'bottom':'41px',
                'left': '-88px'

             });
             },1000);
             setTimeout(function(){$(".animate_05_con_text03").fadeIn(300)},1500);
             
             setTimeout(function(){$(".animate_05_con_text02").animate({
                'bottom':'155px',
                'left': '-88px'

             });
             },2500);
             setTimeout(function(){$(".animate_05_con_text03").fadeOut()},2500);
             
             setTimeout(function(){$(".animate_05_con_text04").fadeIn(300)},2800);
             setTimeout(function(){$(".animate_05_con_text02").animate({
                'bottom':'96px',
                'left': '-88px'

             });
             },3500);
             setTimeout(function(){$(".animate_05_con_text04").fadeOut()},3500);
             setTimeout(function(){$(".animate_05_con_text05").fadeIn(800)},3800);
             setTimeout(function(){$(".animate_05_con_text02").animate({
                'bottom':'96px',
                'left': '191px'

             });
             $animate.eq( k ).attr('data-animate','true');
             },5000);

     },
    animate_8: function(k){
             setTimeout(function(){$(".animate_06_con_text01").fadeIn()});
             setTimeout(function(){$(".animate_06_con_text02").fadeIn(500)},500);
             setTimeout(function(){$(".animate_06_con_text19").animate({
              'width':'38.5%'
             },1000)},1000);
             setTimeout(function(){$(".animate_06_con_text199").animate({
              'width':'37%'
             },1000)},1500);
             
             setTimeout(function(){$(".animate_06_con_text03").fadeIn(300)},2700);

             setTimeout(function(){$(".animate_06_con_text29").show()},3000);
             setTimeout(function(){$(".animate_06_con_text29").fadeOut()},3300);
             setTimeout(function(){$(".animate_06_con_text04").fadeIn(500)},4000);             

             setTimeout(function(){$(".animate_06_con_text01,.animate_06_con_text02,.animate_06_con_text03,.animate_06_con_text04,.animate_06_con_text19,.animate_06_con_text199").fadeOut(500)},5000);
            

             setTimeout(function(){$(".animate_06_con_text05").fadeIn(500)},5500);
             setTimeout(function(){$(".animate_06_con_text06").fadeIn(500)},6500);
             setTimeout(function(){$(".animate_06_con_text20").show().animate({
              'width':'38.5%'
             },800)},7000);
             setTimeout(function(){$(".animate_06_con_text21").show().animate({
              'width':'37%'
             },800)},8000);
             setTimeout(function(){$(".animate_06_con_text22").show().animate({
              'width':'38.5%'
             },800)},8600);
             setTimeout(function(){$(".animate_06_con_text23").animate({
              'width':'33%'
             },800)},9500);
             
             setTimeout(function(){$(".animate_06_con_text07").fadeIn(500)},11000);

             setTimeout(function(){$(".animate_06_con_text29").addClass("animate_06_con_text30").show()},11500);
             setTimeout(function(){$(".animate_06_con_text29").fadeOut()},11800);

             setTimeout(function(){$(".animate_06_con_text08").fadeIn(500)},12000);
             
             setTimeout(function(){$(".animate_06_con_text05,.animate_06_con_text06,.animate_06_con_text07,.animate_06_con_text08,.animate_06_con_text20,.animate_06_con_text21,.animate_06_con_text22,.animate_06_con_text23").fadeOut(500)},13500);

             setTimeout(function(){$(".animate_06_con_text09").fadeIn(500)},14000);
             setTimeout(function(){$(".animate_06_con_text10").fadeIn(500)},15000);
             setTimeout(function(){$(".animate_06_con_text24").show().animate({
              'width':'38.5%'
             },800)},16000);
             setTimeout(function(){$(".animate_06_con_text25").show().animate({
              'width':'38.5%'
             },800)},17000);
             setTimeout(function(){$(".animate_06_con_text26").show().animate({
              'width':'38.5%'
             },800)},18000);
             setTimeout(function(){$(".animate_06_con_text27").show().animate({
              'width':'38.5%'
             },800)},19000);
 
             
             setTimeout(function(){$(".animate_06_con_text11").fadeIn(500)},20000);

             setTimeout(function(){$(".animate_06_con_text29").addClass("animate_06_con_text31").show()},20500);
             setTimeout(function(){$(".animate_06_con_text29").fadeOut()},20800);

             setTimeout(function(){$(".animate_06_con_text12").fadeIn(500)},21000);        

             setTimeout(function(){$(".animate_06_con_text13").fadeIn(500)},22000);

             setTimeout(function(){$(".animate_06_con_text29").addClass("animate_06_con_text31").show()},22500);
             setTimeout(function(){$(".animate_06_con_text29").fadeOut()},22800);
             setTimeout(function(){$(".animate_06_con_text14").fadeIn(500)},23000);          

             setTimeout(function(){$(".animate_06_con_text15").fadeIn(500)},24000);
             setTimeout(function(){$(".animate_06_con_text29").addClass("animate_06_con_text31").show()},24500);
             setTimeout(function(){$(".animate_06_con_text29").fadeOut()},24800);
             setTimeout(function(){$(".animate_06_con_text16").fadeIn(500)},25000);
   

             setTimeout(function(){$(".animate_06_con_text17").fadeIn(500)},26000);
             setTimeout(function(){$(".animate_06_con_text29").addClass("animate_06_con_text31").show()},26500);
             setTimeout(function(){$(".animate_06_con_text29").fadeOut();},26800);
            
             setTimeout(function(){$(".animate_06_con_text24,.animate_06_con_text25,.animate_06_con_text26,.animate_06_con_text27,.animate_06_con_text30,.animate_06_con_text31").fadeOut(500)},26000);
   
             setTimeout(function(){$(".animate_06_con_text18").fadeIn(500)},27000);
             setTimeout(function(){$(".animate_06_con_text28").slideDown(500);
               $(".animate_06_con_text29").removeClass("animate_06_con_text30 animate_06_con_text31");
              $animate.eq( k ).attr('data-animate','true');
              
           },28000);
             
     }
    
  }


    var $animate = $("[data-animate]"); 
    var $video = $("video"); 
    var windowHeight = $(window).height();
    var docHeight =$(document).height();
    $(".video-pic").mouseenter(function(){
        $(this).fadeOut(300).siblings(".play-btn").hide();
        var index = $(".video-pic").index(this);
          $video.trigger('pause');
          $video.eq( index ).trigger('play');
    });


    $video.on('ended',function(){
      $(this).siblings(".play-btn,.video-end-pic").show();
    });


    $(document).on('click', '.play-btn', function(){
        $(this).fadeOut(300).siblings(".video-end-pic").hide();
        var index = $(".play-btn").index(this);
          $video.trigger('pause');
          $video.eq( index ).trigger('play');


    }).on("mouseenter", "[data-animate]", function(){
      var index = $( "[data-animate]" ).index( $(this) );
      if ( $animate.eq(index).attr('data-animate') === 'true' ){

        $animate.eq( index ).attr( 'data-animate', "false" ).find( '[style]' ).removeAttr( 'style' );
        scrollAnimate['animate_' + (index+1)]( index );
      };
    })

})();


 