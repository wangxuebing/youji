$(function(){
  $('.files').delegate('tr .delete','click',function(){
    $(this).parent('td').parent('tr').remove();
  });
  var closeUploadWindows = function(){
    $('#upload_box,.opacity').hide();
  };
  var openUploadWindows = function(){
    $('.opacity,#upload_box').show();
  };
  $('.hide_upload').click(function(){
    closeUploadWindows();
    $('.uoload_status').show();
  });
  $('.show_upload').click(function(){
    $('.uoload_status').hide();
    $('.opacity,#upload_box').show();
  });
  $('.close_upload').click(function(){
    if(confirm('您是否确定取消上传')){
      $('#upload_box,.opacity,.uoload_status').hide();
      $('#loadfiles').empty();
    }
  });
  var uploadStyle = '<link rel="stylesheet" href="/css/upload/bootstrap.min.css">' +
                    '<link rel="stylesheet" href="/css/upload/jquery.fileupload.css">' +
                    '<link rel="stylesheet" href="css/upload/jquery.fileupload-ui.css">' +
                    '<noscript><link rel="stylesheet" href="css/upload/jquery.fileupload-noscript.css"></noscript>' +
                    '<noscript><link rel="stylesheet" href="css/upload/jquery.fileupload-ui-noscript.css"></noscript>';
                    
  var uploadScript = '<script src="/js/upload/vendor/jquery.ui.widget.js"></script>' +
                      '<script src="js/upload/vendor/jquery.ui.widget.js"></script>' +
                      '<script src="js/upload/tmpl.min.js"></script>' +
                      '<script src="js/upload/load-image.all.min.js"></script>' +
                      '<script src="js/upload/canvas-to-blob.min.js"></script>' +
                      '<script src="js/upload/bootstrap.min.js"></script>' +
                      '<script src="js/upload/jquery.blueimp-gallery.min.js"></script>' +
                      '<script src="js/upload/jquery.iframe-transport.js"></script>' +
                      '<script src="js/upload/jquery.fileupload.js"></script>' +
                      '<script src="js/upload/jquery.fileupload-process.js"></script>' +
                      '<script src="js/upload/jquery.fileupload-image.js"></script>' +
                      '<script src="js/upload/jquery.fileupload-audio.js"></script>' +
                      '<script src="js/upload/jquery.fileupload-video.js"></script>' +
                      '<script src="js/upload/jquery.fileupload-validate.js"></script>' +
                      '<script src="js/upload/jquery.fileupload-ui.js"></script>' +
                    '<script src="js/upload/main.js"></script>';
                    
  var appendCommon = function(){
    $('head').append(uploadStyle);
    $('#loadfiles').append(uploadScript);
  }
  //所有地方的上传代码                  
  $('#upload_cover').click(function(){
    openUploadWindows();
    upload_type = 'changeimg';
    $('#files_type').attr('value','image');
    $('#upload_spots_type').attr('value',1);
    $('#spots_id').attr('value',spots_id);
    uploadObj = $('#face');
    appendCommon();
  });
  
  //自详情页通用图上传代码
  $('#upload_content_face').click(function(){
    openUploadWindows();
    upload_type = 'changeimg';
    $('#files_type').attr('value','image');
    $('#upload_spots_type').attr('value',1);
    $('#spots_id').attr('value',spots_id);
    uploadObj = $('#content_face');
    appendCommon();
  });
  
  //上传大景点图片
  $('#upload_images').click(function(){
    openUploadWindows();
    upload_type = 'addimg';
    $('#files_type').attr('value','image');
    $('#upload_spots_type').attr('value',1);
    $('#spots_id').attr('value',spots_id);
    uploadObj = $('#spots_images');
    appendCommon();
  });
  
  //大景点上传全景图
  $('#upload_panorama').click(function(){
    openUploadWindows();
    upload_type = 'addimg';
    $('#files_type').attr('value','panorama');
    $('#upload_spots_type').attr('value',1);
    $('#spots_id').attr('value',spots_id);
    uploadObj = $('#panorama_images');
    appendCommon();
  });
  
  //大景点上传视频
  $('#upload_video').click(function(){
    openUploadWindows();
    upload_type = 'addvideo';
    $('#files_type').attr('value','video');
    $('#upload_spots_type').attr('value',1);
    $('#spots_id').attr('value',spots_id);
    uploadObj = $('#video_list');
    appendCommon();
  });
  
  //大景点上传语音
  $('#upload_audio').click(function(){
    openUploadWindows();
    upload_type = 'addaudio';
    $('#files_type').attr('value','audio');
    $('#upload_spots_type').attr('value',1);
    $('#spots_id').attr('value',spots_id);
    uploadObj = $('#audio_list');
    appendCommon();
  });
  
  //小编印象上传图片
  $('#upload_detail_face').click(function(){
    openUploadWindows();
    upload_type = 'changeimg';
    $('#files_type').attr('value','image');
    $('#upload_spots_type').attr('value',1);
    $('#spots_id').attr('value',spots_id);
    uploadObj = $('#detail_face');
    appendCommon();
  });
  
  //创建子景点从本地上传图片
  $('#childview-upload-image').click(function(){
    openUploadWindows();
    upload_type = 'addimg';
    $('#files_type').attr('value','image');
    $('#upload_spots_type').attr('value',1);
    $('#spots_id').attr('value',spots_id);
    uploadObj = $('#childview-content');
    appendCommon();
  });
  
  //创建子景点从本地上传全景图
  $('#childview-upload-panorama').click(function(){
    openUploadWindows();
    upload_type = 'addimg';
    $('#files_type').attr('value','panorama');
    $('#upload_spots_type').attr('value',1);
    $('#spots_id').attr('value',spots_id);
    uploadObj = $('#childview-content');
    appendCommon();
  });
  //修改子景点详情从本地上传图片
  $('#childview-list-main').delegate('#childview-upload-image','click',function(){
    openUploadWindows();
    upload_type = 'addimg';
    $('#files_type').attr('value','image');
    $('#upload_spots_type').attr('value',1);
    $('#spots_id').attr('value',spots_id);
    uploadObj = $('#childview-update-content');
    appendCommon();
  });
  
  
  //修改子景点从本地上传全景图
  $('#childview-list-main').delegate('#childview-upload-panorama','click',function(){
    openUploadWindows();
    upload_type = 'addimg';
    $('#files_type').attr('value','panorama');
    $('#upload_spots_type').attr('value',1);
    $('#spots_id').attr('value',spots_id);
    uploadObj = $('#childview-update-content');
    appendCommon();
  });
  
  //创建附录从本地上传图片
  $('#appendix-main').delegate('#appendix-upload','click',function(){
    openUploadWindows();
    upload_type = 'addimg';
    $('#files_type').attr('value','image');
    $('#upload_spots_type').attr('value',1);
    $('#spots_id').attr('value',spots_id);
    uploadObj = $('#appendix-box');
    appendCommon();
  });
  //修改附录详情从本地上传图片
  $('#appendix-list-main').delegate('#appendix-update-upload','click',function(){
    openUploadWindows();
    upload_type = 'addimg';
    $('#files_type').attr('value','image');
    $('#upload_spots_type').attr('value',1);
    $('#spots_id').attr('value',spots_id);
    uploadObj = $('#appendix_update-box');
    appendCommon();
  });
  
  //创建简介从本地上传图片
  $('#intro-main').delegate('#intro-upload-image','click',function(){
    openUploadWindows();
    upload_type = 'addimg';
    $('#files_type').attr('value','image');
    $('#upload_spots_type').attr('value',1);
    $('#spots_id').attr('value',spots_id);
    uploadObj = $('#introduce-box');
    appendCommon();
  });
});