textScroll = function(){
  var windowHeight = $(window).height();
  var windowWidth = $(window).width();
  var textlength = $('.scrolltext .scrolltext_main').length;
  $('.scrolltext').css('width',windowWidth * textlength);
  $('.scrolltext_main').css('width',windowWidth);
  
  $('#scroll_func .list').click(function(){
    var index = $(this).index();
    $(this).addClass('active').siblings().removeClass('active');
    $('.scrolltext').css('left',-index * windowWidth);
  });
}
$(function(){
  var windowHeight = $(window).height();
  var windowWidth = $(window).width();
  $('.first').css('height',windowHeight);
  
  
  $(document).scroll(function(){
    var scrollTop = $(document).scrollTop();
    if(scrollTop > 140){
      $('.top_animate').removeClass('animate_hide').addClass('animate_show');
    }else{
      $('.top_animate').removeClass('animate_show').addClass('animate_hide');
    }
  });
  
  $('.mobile_menu').click(function(){
    $(this).siblings('.slidetoggle').slideToggle();
  });
  
  textScroll();  
  
});

$(window).resize(function(){
  var windowHeight = $(window).height();
  $('.first').css('height',windowHeight);
  
  textScroll();
});