changeOver = function(eobj,sobj,hobj){  /*eobj是操作对象 ， sobj是显示的对象 ， hobj是要隐藏的对象*/
  eobj.click(function(){
    hobj.fadeOut();
    sobj.fadeIn();
  });
};
checkForm = function(){
  $('.feedback_text').keyup(function(){
    var feedbackMain = $('.feedback_text').val();
    if(feedbackMain == ""){
      $('.submit').removeClass('do').addClass('dont');
    }else{
      $('.submit').removeClass('dont').addClass('do');
    }
  });
};

appAlert = function(msg){
  var html = '<div class="alert" id="alert">'+ msg +'</div>';
  $('body').append(html);
  var alertHide = function(){
    $('#alert').remove();
  };
  timer = setTimeout(function(){
    alertHide()
  },2000);
};

getQueryString = function(name){ 
  var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)", "i"); 
  var r = window.location.search.substr(1).match(reg); 
  if (r != null) return unescape(r[2]); return null; 
}
submitFeedBack = function(){
  var user_id = this.getQueryString("user_id");
  var device_id= this.getQueryString("device_id");
  var content = $('.feedback_text').val();
  var contact = $('.contact').val();
  $.ajax({
    url: 'http://e-traveltech.com/api/user/addFeedBack',
    data:{
      user_id : user_id,
      contact : contact,
      content: content,
      device_id: device_id,
      from: 3
    },
    type: 'POST',
    jsonp: "callback", 
    dataType: "json",
    async: false,
    cache: true
  })
  .done(function(res){
    if(res.error_code == 0){
        $('#submit_feedback').fadeOut();
        $('#show_feedback').fadeIn('slow', function(){
            appAlert('反馈成功');
        });
    }else{
      appAlert('反馈失败, 请重试');
    }
  })
  .fail(function(res){
    appAlert('反馈失败, 请重试');
  })
};
$(function(){
  var goFeedback       =  $('.feedback');                 /*我要反馈按钮*/
  var goback           =  $('.goback');                   /*返回按钮*/
  var showFeedback     =  $('#show_feedback');
  var submitFeedback   =  $('#submit_feedback');
  changeOver(goFeedback,submitFeedback,showFeedback);
  changeOver(goback,showFeedback,submitFeedback);
  checkForm();
  $('.submit').click(function(){
    if($(this).hasClass('do')){
      submitFeedBack();
    }else{
      return false;
    }
  });
});
