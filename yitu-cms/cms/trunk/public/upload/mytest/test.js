$(function(){
  $('.files').delegate('tr .delete','click',function(){
    $(this).parent('td').parent('tr').remove();
  });
  // var uploadModel = '<div id="upload_box" class="upload_box">上传插件</div>';
  var uploadStyle = '<link rel="stylesheet" href="css/jquery.fileupload.css">' +
                    '<link rel="stylesheet" href="css/jquery.fileupload-ui.css">' +
                    '<noscript><link rel="stylesheet" href="css/jquery.fileupload-noscript.css"></noscript>' +
                    '<noscript><link rel="stylesheet" href="css/jquery.fileupload-ui-noscript.css"></noscript>';
  $('#upload_files').click(function(){
    $('head').append(uploadStyle);
    $('#upload_box').show();
  });
});