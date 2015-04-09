$(function(){  
  $(document).area("s_province","s_city","s_county");//调用select插件  
  $('.menu .menulist').menuTab();
  $('.imagesfiles').menuTab();
  $('.add-images').click(function(){
    $('.opacity').fadeIn(100);
    $('#images-dialog').dialog({
      width: '60%',
      title: '选择图片',
      closeText: '<i class="iconfont">&#xe600;</i>',
      close: function(){
        $('.opacity').fadeOut(100);
      }
    });
  });
  
  //基本信息提交验证
  $('#storage_baseinfo').myalert();
  
  $('#phone-number').blur(function(){
    var num = /[\d-]+/;
    if($(this).val() == ''){
      $(this).val('电话号码不能为空');
    }else if(!num.test($(this).val())){
      $(this).val('电话号码只能是数字和－'); 
    }else{
      $(this).val('验证成功');
    }
  });
});