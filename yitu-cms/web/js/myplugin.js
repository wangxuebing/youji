;(function($) {
  //tab菜单切换插件
  $.fn.menuTab = function(){
    $(this).click(function(){
      var thisID = $(this).attr('id');
      var thisIdMain = thisID + '-main';
      $(this).addClass('active').siblings().removeClass('active');
      $('#'+thisIdMain).show().siblings().hide();
    });
  };
  
  $.fn.myalert = function(){
    $(this).click(function(){
      alert('123');
    });
  };
  
})(jQuery); 