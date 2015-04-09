$(function(){
  $('#login').click(function(){
    alert('账号或密码错误');
  });
  $('.mobile_menu').click(function(){
    $(this).siblings('.slidetoggle').slideToggle();
  });
});