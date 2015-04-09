$(function(){
  //创建任务弹窗
  $('#create').click(function(){
    $('.opacity').fadeIn(100);
    $('#create-dialog').dialog({
      width: 500,
      title: '创建任务',
      dialogClass: 'mydialog',
      closeText: '<i class="iconfont">&#xe600;</i>',
      show: 100,
      close: function(){
        $('.opacity').fadeOut(100);
      }
    });
  });
  
  $(".ui_timepicker").datetimepicker({
      //showOn: "button",
      //buttonImage: "./css/images/icon_calendar.gif",
      //buttonImageOnly: true,
      showSecond: false,
      timeFormat: 'hh:mm',
      stepHour: 1,
      stepMinute: 1,
    stepSecond: 0
  });
  
  $(document).area("s_province","s_city","s_county");//调用select插件
});