//预览界面始终显示到最后一行
reviewScrollBottom = function(){
  $('.review_main').scrollTop( $('.review_main')[0].scrollHeight );
};
//基本信息预览
baseInfoReview = function(){
  reviewModel = '<h1 class="review_title">基本信息</h1>' +
                '<div class="review_main">' +
                  '<h2 class="name_title">景点名称</h2>' +
                  '<p class="spots_name"></p>' +
                  '<h2 class="pinyin_title">景点拼音</h2>' +
                  '<p class="spots_pinyin"></p>' +
                  '<h2 class="tel_title">电话</h2>' +
                  '<p class="spots_tel"></p>' +
                  '<h2 class="adress_title">地址</h2>' +
                  '<p class="spots_adress"></p>' +
                  '<h2 class="price_title">票价</h2>' +
                  '<p class="spots_price"></p>' +
                  '<h2 class="open_title">开放时间</h2>' +
                  '<p class="spots_open"></p>' +
              '</div>'; 
                  
                  $('.review-box').show().html(reviewModel);
                  var spots_name = $('#spot-name').val();
                  var spots_pinyin = $('#spot-pinyin').val();
                  var spots_tel = $('#spot-telephone').val();
                  var spots_adress = $('#spot-address').val();
                  var spots_price = $('#spot-ticket-price').val();
                  var spots_open = $('#spot-open-time').val();
                  $('.spots_name').html(spots_name);
                  $('.spots_pinyin').html(spots_pinyin);
                  $('.spots_tel').html(spots_tel);
                  $('.spots_adress').html(spots_adress);
                  $('.spots_price').html(spots_price);
                  $('.spots_open').html(spots_open);
                  spots_name == '' ? $('.name_title,.spots_name').hide() : $('.review_title,.spots_name').show();
                  spots_pinyin == '' ? $('.pinyin_title,.spots_pinyin').hide() : $('.pinyin_title,.spots_pinyin').show();
                  spots_tel == '' ? $('.tel_title,.spots_tel').hide() : $('.tel_title,.spots_tel').show();
                  spots_adress == '' ? $('.adress_title,.spots_adress').hide() : $('.adress_title,.spots_adress').show();
                  spots_price == '' ? $('.price_title,.spots_price').hide() : $('.price_title,.spots_price').show();
                  spots_open == '' ? $('.open_title,.spots_open').hide() : $('.open_title,.spots_open').show();
};

//小编印象预览
effectReview = function(){
  reviewModel = '<h1 class="review_title">小编印象</h1>' +
  '<div class="review_main">' +
    '<p class="description_review"></p>' +
    '<div class="description_review_img"></div>' +
  '</div>';
  $('.review-box').show().html(reviewModel);
  var description_review = $('#description').val();
  $('.description_review').html(description_review);
  if($('#detail_face .images').length >= 1){
    $('.description_review_img').append('<img src="' + $('#detail_face .images').find('img').attr('src') + '"/>');
  };
};

//创建简介预览
introReview = function(){
  reviewModel = '<h1 class="review_title">简介</h1>' +
    '<div class="review_main">' +
      '<div class="review_details_face"></div>' +
      '<p class="intro_title"></p>' +
      '<div class="review_content"></div>' +
  '</div>';
  $('.review-box').show().html(reviewModel);
  var intro_title = $('#intro-title').val();
  var content = "";
  var content_kind = "";
  
  $('.intro_title').html(intro_title);
  if($('#intro_face_list .images').length != 0){
    $('.review_details_face').append('<img src="' + $('#intro_face_list .images').find('img').attr('src') + '" />');
  }
  $('#introduce-box .viewlist').each(function(){
    var type = $(this).attr('type');
    var thismain = "";
    if(type == 0){
      var thisadd = $(this).val();
      thismain = '<p>' + thisadd + '</p>';
    }else{
      var thisadd = $(this).find('img').attr('src');
      thismain = '<img src="'+ thisadd +'"/>';
    }
    content_kind += thismain;
  });
  content = content_kind;
  $('.review_content').append(content);
};

//创建子景点预览
childviewReview = function(){
  reviewModel = '<h1 class="review_title">创建子景点</h1>' +
    '<div class="review_main">' +
      '<div class="review_child_face"></div>' +
      '<p class="child_name"></p>' +
      '<p class="child_title"></p>' +
      '<div class="review_content"></div>' +
  '</div>';
  $('.review-box').show().html(reviewModel);
  var child_name = $('#childview-name').val();
  var child_title = $('#childview-title').val();
  var content = "";
  var content_kind = "";
  $('.child_name').html(child_name);
  $('.child_title').html(child_title);
  if($('#childview_face_list .images').length != 0){
    $('.review_child_face').append('<img src="' + $('#childview_face_list .images').find('img').attr('src') + '" />');
  }
  $('#childview-box').find('.viewcontent').each(function(){
    var type = $(this).attr('type');
    var thismain = "";
    if(type == 0){
      var thisadd = $(this).val();
      thismain = '<p>' + thisadd + '</p>';
    }else{
      var thisadd = $(this).find('img').attr('src');
      thismain = '<img src="'+ thisadd +'"/>';
    }
    content_kind += thismain;
  });
  content = content_kind;
  $('.review_content').append(content);
  reviewScrollBottom();
};

//修改子景点预览
updateChildReview = function(){
  reviewModel = '<h1 class="review_title">子景点详情</h1>' +
    '<div class="review_main">' +
      '<div class="review_child_face"></div>' +
      '<p class="child_name"></p>' +
      '<p class="child_title"></p>' +
      '<div class="review_content"></div>' +
  '</div>';
  $('.review-box').show().html(reviewModel);
  var child_title = $('#childview-update-title').val();
  var child_name = $('#childview-update-name').val()
  var content = "";
  var content_kind = "";
  $('.child_name').html(child_name);
  $('.child_title').html(child_title);
  if($('#childview_face_update_list .images').length != 0){
    $('.review_child_face').append('<img src="' + $('#childview_face_update_list .images').find('img').attr('src') + '" />');
  };
  $('#childview-update-box').find('.viewcontent').each(function(){
    var type = $(this).attr('type');
    var thismain = "";
    if(type == 0){
      var thisadd = $(this).val();
      thismain = '<p>' + thisadd + '</p>';
    }else{
      var thisadd = $(this).find('img').attr('src');
      thismain = '<img src="'+ thisadd +'"/>';
    }
    content_kind += thismain;
  });
  content = content_kind;
  $('.review_content').append(content);
};

//创建附录预览
appendixReview = function(){
  reviewModel = '<h1 class="review_title">创建附录</h1>' +
    '<div class="review_main">' +
      '<div class="review_appendix_face"></div>' +
      '<p class="appendix_name"></p>' +
      '<div class="review_content"></div>' +
  '</div>';
  $('.review-box').show().html(reviewModel);
  var appendix_name = $('#appendix_title').val();
  var content = "";
  var content_kind = "";
  $('.appendix_name').html(appendix_name);
  if($('#appendix_face_list .images').length != 0){
    $('.review_appendix_face').append('<img src="' + $('#appendix_face_list .images').find('img').attr('src') + '" />');
  }
  $('#appendix-box').find('.viewlist').each(function(){
    var type = $(this).attr('type');
    var thismain = "";
    if(type == 0){
      var thisadd = $(this).val();
      thismain = '<p>' + thisadd + '</p>';
    }else{
      var thisadd = $(this).find('img').attr('src');
      thismain = '<img src="'+ thisadd +'"/>';
    }
    content_kind += thismain;
  });
  content = content_kind;
  $('.review_content').append(content);
};

//修改附录预览
updateAppendixReview = function(){
  reviewModel = '<h1 class="review_title">创建附录</h1>' +
    '<div class="review_main">' +
      '<div class="review_appendix_face"></div>' +
      '<p class="appendix_name"></p>' +
      '<div class="review_content"></div>' +
  '</div>';
  $('.review-box').show().html(reviewModel);
  var appendix_name = $('#appendix_update_title').val();
  var content = "";
  var content_kind = "";
  $('.appendix_name').html(appendix_name);
  if($('#appendix_update_face_list .images').length != 0){
    $('.review_appendix_face').append('<img src="' + $('#appendix_update_face_list .images').find('img').attr('src') + '" />');
  }
  $('#appendix_update-box').find('.viewlist').each(function(){
    var type = $(this).attr('type');
    var thismain = "";
    if(type == 0){
      var thisadd = $(this).val();
      thismain = '<p>' + thisadd + '</p>';
    }else{
      var thisadd = $(this).find('img').attr('src');
      thismain = '<img src="'+ thisadd +'"/>';
    }
    content_kind += thismain;
  });
  content = content_kind;
  $('.review_content').append(content);
};

