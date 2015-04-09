$(function(){
  $('#login-form').submit(function(){
    var username = $('#username').val();
    var password = $('#password').val();
    if(username == ''){
      $('.formerror').html('请输入账号');
      return false;
    }else if(password == ''){
      $('.formerror').html('请输入密码');
      return false;
    }else{
      $('.formerror').html('请验证账号密码信息');
      return false;
    }
  });
});