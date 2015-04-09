//获取子景点列表
getChildviewList = function(){
  $.ajax({
    type : "GET",
    dataType: "json",
    async: false,
    cache: true,
    data: {
      spots_id: spots_id,
      language: language
    },
    url: "http://115.29.179.17:8500/api/spots/getSubsBySpotsId",
    jsonp: "callback",
    success: function(childviewList){
      if(childviewList.error_code == 0){
        $('#sortable').empty();
        for(var i=0; i<childviewList.data.length; i++){
          var pos = childviewList.data[i].pos;
          var name = childviewList.data[i].name;
          var type_id = childviewList.data[i].type_id;
          var type = childviewList.data[i].type;
          if(type == 1){
            $list = '<li pos="' + pos + '" type="' + type + '" childview-id="' + type_id + '" class="secend-list childview_list">' + name + '<i class="iconfont delete">&#xe604;</i></li>'
          }else{
            $list = '<li pos="' + pos + '" type="' + type + '" childview-id="' + type_id + '" class="secend-list appendix_list">' + name + '<i class="iconfont delete">&#xe604;</i></li>'
          }
          $('#sortable').append($list);
        }
      }else if(childviewList.error_code == 10002){
        $('#childview-list').empty();
      }else{
        alert(childviewList.error_msg);
      }
    },
    error: function(){
      
    }
  });
};

//获取附录列表
getAppendixList = function(){
  $.ajax({
    type : "GET",
    dataType: "json",
    async: false,
    cache: true,
    data: {
      spots_id: spots_id,
      language: language
    },
    url: "http://115.29.179.17:8500/api/spots/getAppendixBySpotsId",
    jsonp: "callback",
    success: function(appendixList){
      $('#sortable2').empty();
      if(appendixList.error_code == 0){
        for(var i=0; i<appendixList.data.length; i++){
          var type = appendixList.data[i].type_id;
          var name = appendixList.data[i].title;
          var type_id = appendixList.data[i].id
          $list = '<li type="' + type + '" appendix-id="' + type_id + '" class="secend-list">' + name + '</li>'
          $('#sortable2').append($list);
        }
      }
    },
    error: function(){
    
    }
  });
};

//拖拽子景点排序
dragChildView = function(){
  $("#sortable").sortable({
    axis: "y",
    stop: function(){
      changeChildPosNum();
    }
  });
};

dragAppendixToChildView = function(){
  $( "#sortable2 li" ).draggable({
    axis: "y",
    helper: "clone",
    stop: function(){
      var appendix_id = $(this).attr('appendix-id')
      $(this).clone().appendTo('#sortable').attr('childview-id',appendix_id).append('<i class="iconfont delete">&#xe604;</i>');
      changeChildPosNum();
    }
  });

  $("#sortable").droppable({
    drop: function(event, ui) {
      $(this).append()
    }
  }); 
};

//调整子景点顺序
changeChildPosNum = function(){
  var link = "";
  var links = "";
  $('#sortable li').each(function(){
    var index = $(this).index();
    var type = $(this).attr('type');
    var childview_id = $(this).attr('childview-id');
    links += type + ',' + childview_id + '|';
  });
  link = links;
  link = link.substr(0,link.length-1);
  $.ajax({
    type : "GET",
    dataType: "json",
    async: false,
    cache: true,
    data: {
      spots_id: spots_id,
      link: link,
      language: language
    },
    url: "http://115.29.179.17:8500/api/spots/bindToSpots",
    jsonp: "callback",
    success: function(bindToSpots){
      if(bindToSpots.error_code != 0){
        console.log(appendixKind.error_msg);
      }else{
        getChildviewList();
      }
    },
    error: function(){
      console.log('调整失败');
    }
  });
};

//获取附录分类
getAppendixKind = function(){
  $.ajax({
    type : "GET",
    dataType: "json",
    async: false,
    cache: true,
    data: {
      language: language
    },
    url: "http://115.29.179.17:8500/api/spots/getAppendixTypes",
    jsonp: "callback",
    success: function(appendixKind){
      if(appendixKind.error_code != 0){
        console.log(appendixKind.error_msg);
      }else{
        for(var i = 0; i < appendixKind.data.length; i++){
          var $appendixKind = '<option type_id="' + appendixKind.data[i].id + '" value="' + appendixKind.data[i].name + '">' + appendixKind.data[i].name + '</option>';
          optionbox.append($appendixKind);
        }
      }
    },
    error: function(){
      console.log('获取失败');
    }
  });
};

//获取子景点类型
getSubspots = function(){
  $.ajax({
    type : "GET",
    dataType: "json",
    async: false,
    cache: true,
    data: {
      language: language
    },
    url: "http://115.29.179.17:8500/api/spots/getSubSpotsTypes",
    jsonp: "callback",
    success: function(subspots){
      if(subspots.error_code != 0){
        alert(subspots.error_msg);
      }else{
        for(var i = 0; i < subspots.data.length; i++){
          var $subspots = '<option type_id="' + subspots.data[i].id + '" value="' + subspots.data[i].name + '">' + subspots.data[i].name + '</option>';
          optionbox.append($subspots);
        }
      }
    },
    error: function(){
      alert('获取失败');
    }
  });
};

//获取下拉列表清除非首选项
clearNotFirst = function(){
  optionbox.children('option').each(function(){
    var index = $(this).index();
    if(index > 0){
      $(this).remove();
    }
  });
};

//获取简介
getIntroduce = function(){
  $.ajax({
    type : "GET",
    dataType: "json",
    async: false,
    cache: true,
    data: {
      spots_id: spots_id,
      language: language
    },
    url: "http://115.29.179.17:8500/api/spots/getScenicSpotsIntroduction",
    jsonp: "callback",
    success: function(introduce){
      if(introduce.error_code == 0){
        $('#introduce-create').remove();
        $('#intro-title').val(introduce.data.title);
        if(introduce.data.audio != ''){
          $audio = '<div class="audio" url="' + introduce.data.audio.url + '" md="' + introduce.data.audio.md + '">语音：' + introduce.data.audio.md + '<i class="iconfont delete">&#xe607;</i></div>';
          $('#intro-sounds_list').append($audio);
        }
        if(introduce.data.face != ''){
            $introFace = '<div class="images oldupload" md="' + introduce.data.face.md + '">' +
            '<img src="' + introduce.data.face.url + '"/>' +
            '<span class="deleteimg"><i class="iconfont">&#xe607;</i></span>' +
          '</div>';
          $('#intro_face_list').append($introFace);
        }
        //图文混排区域
        for(var i=0; i<introduce.data.content.length; i++){
          if(introduce.data.content[i].type == 0){
            if(introduce.data.content[i].content != ''){
              $content = '<textarea class="textbox viewlist" type="0" >' + introduce.data.content[i].content + '</textarea>';
            }
          }else{
            $content = '<div class="images viewlist" md="' + introduce.data.content[i].content.md + '" type="1">' +
              '<img src="' + introduce.data.content[i].content.url + '"><span class="deleteimg"><i class="iconfont">&#xe607;</i></span>' +
            '</div>';
          }
          $('#introduce-box').append($content);
        }

      }else{
        $('#introduce-update').remove();
      }
    },
    error: function(){
      alert('获取失败');
    }
  });
};

//获取景点下素材库中的图片
getResourceImageList = function(){
  $.ajax({
    type : "GET",
    dataType: "json",
    async: true,
    cache: true,
    data: {
      type : 1,
      id: spots_id,
      language: language
    },
    url: "http://115.29.179.17:8500/api/spots/getResourceList",
    jsonp: "callback",
    beforeSend: function(){
      $('#dialog-main').html('<div class="loading">正在加载...</div>');
    },
    success: function(getimage){
      if(getimage.error_code == 10002){
        $('#dialog-main').html('<div class="filed">素材库没有该景点图片</div>');
      }else if(getimage.error_code == 0){
        if(getimage.data.image.length == 0){
          $('#dialog-main').html('<div class="filed">素材库没有该景点图片</div>');
        }else{
          $('#dialog-main').empty();
          for(var i=0; i<getimage.data.image.length; i++){
            $images = '<div class="images" md="' + getimage.data.image[i].md + '">' +
                '<img src="' + getimage.data.image[i].url + '" width="235" />' +
              '</div>';
              $('#dialog-main').append($images);
          };
        }
      }else{
        $('#dialog-main').html(getimage.error_msg);
      }
    },
    error: function(){
      $('#dialog-main').html('<div class="filed">获取失败</div>');
    }
  });
};

//获取素材库中的全景图列表
getResourcePanoramaList = function(){
  $.ajax({
    type : "GET",
    dataType: "json",
    async: true,
    cache: true,
    data: {
      type : 1,
      id: spots_id,
      language: language
    },
    url: "http://115.29.179.17:8500/api/spots/getResourceList",
    jsonp: "callback",
    beforeSend: function(){
      $('#dialog-main').html('<div class="loading">正在加载...</div>');
    },
    success: function(getimage){
      if(getimage.error_code == 10002){
        $('#dialog-main').html('<div class="filed">素材库没有该景点全景图</div>');
      }else if(getimage.error_code == 0){
        if(getimage.data.panorama.length != 0){
          $('#dialog-main').empty();
          for(var i=0; i<getimage.data.panorama.length; i++){
            $images = '<div class="images" md="' + getimage.data.panorama[i].md + '">' +
                '<img src="' + getimage.data.panorama[i].url + '" width="235" />' +
              '</div>';
              $('#dialog-main').append($images);
          };
        }else{
          $('#dialog-main').empty();
          $('#dialog-main').html('<div class="filed">素材库没有该景点全景图</div>');
        }
      }else{
        $('#dialog-main').html(getimage.error_msg);
      }
    },
    error: function(){
      $('#dialog-main').html('<div class="filed">获取失败</div>');
    }
  });
};

//弹窗model
dialogModelShow = function(){
  $('#dialog-main').empty();
  $('.opacity').fadeIn(100);
  $('#source-dialog').dialog({
    width: '60%',
    title: title,
    closeText: '<i class="iconfont">&#xe600;</i>',
    close: function(){
      $('.opacity').fadeOut(100);
      $('#source-dialog').find('#dialog-main').attr('class','dialog-main');
      $('#source-dialog').find('.queding').attr('class','queding');
      $('#dialog-main').empty();
    }
  });
};

//附录详情model
getAppendixDetails = function(){
  var detailsModel =
    '<h2 class="title">附录详情</h2>' + //标题
    '<div class="info top15">' +
      '<div class="info-main">' +
        '<p>附录名称</p>' +
        '<div class="viewnamebox clearfix">' +
          '<input type="text" class="inputbox" id="appendix_update_title">' +
          '<select class="viewfind" id="appendix_update_kind">' +
            '<option>请选择附录类型</option>' +
          '</select>' +
        '</div>' +
      '</div>' +
    '</div>' + //第一部分结束
    '<div class="info">' +
      '<p>子详情页首图</p>' +
      '<div class="upload-box clearfix">' +
        '<button class="keep add-face" id="appendix_update-face">更换图片</button>' +
      '</div>' +
      '<div class="imglist clearfix" id="appendix_update_face_list"></div>' +
    '</div>' + //插入子详情页首图部分
    '<div class="info">' +
      '<div class="add-info">' +
        '<div class="button-box">' +
          '<div class="add-source">' +
            '<button class="add-images"><i class="iconfont">&#xe603;</i>插入图片</button>' +
            '<div class="add-way">' +
              '<span id="appendix-update-upload">从本地上传</span>' +
              '<span id="appendix-update-box-image">从图库选择</span>' +
            '</div>' +
          '</div>' +
          '<button class="add-text"><i class="iconfont">&#xe603;</i>插入文字</button>' +
        '</div>' +
        '<div class="addinfo-main" id="appendix_update-box"></div>' +
      '</div>' +
    '</div>' +
    '<div class="info">' +
      '<button class="keep" id="appendix_update">修改</button>' +
    '</div>';
  $('#appendix-list-main').show().html(detailsModel).siblings().hide();
  $('#appendix_update_kind').bind('focus',function(){
    optionbox = $('#appendix_update_kind');
    clearNotFirst();
    getAppendixKind();
  });

};
//子景点详情model
getChildViewDetails = function(){
  var detailsModel = 
  '<h2 class="title">子景点详情</h2>' +
  '<div class="info top15">' +
    '<div class="info-main">' +
      '<p>子景点名称</p>' +
      '<div class="viewnamebox clearfix">' +
        '<input type="text" class="inputbox" id="childview-update-name" disabled="true">' +
        '<select class="viewfind" id="subspots_update" disabled="disabled">' +
          '<option type_id="0">请选择子景点类型</option>' +
        '</select>' +
      '</div>' +
    '</div>' +
  '</div>' +
  '<div class="info member">' +
    '<p>中心坐标</p>' +
    '<div class="route-list clearfix">' +
      '<input type="text" placeholder="纬度" value="" class="latitude" id="child-update-latitude">' +
      '<input type="text" placeholder="经度" value="" class="longitude" id="child-update-longitude">' +
    '</div>' +
  '</div>' +
  '<div class="info member">' +
    '<p>周围坐标</p>' +
    '<div id="child_around_coordinates_update" class="coordinates"></div>' +
  '</div>' +
  '<div class="info">' +
    '<p>标题</p>' +
    '<input type="text" class="inputbox" id="childview-update-title">' +
  '</div>' +
  '<div class="info">' +
    '<p>选择语音</p>' +
    '<div class="upload-box clearfix">' +
      '<button class="keep add-sounds" id="childview-update-sounds">插入语音</button>' +
    '</div>' +
    '<div class="audio-list clearfix" id="childview-sounds_update_list"></div>' +
  '</div>' +
  '<div class="info">' +
    '<p>子详情页首图</p>' +
    '<div class="upload-box clearfix">' +
      '<button class="keep add-face" id="childview-update-face">更换图片</button>' +
    '</div>' +
    '<div class="imglist clearfix" id="childview_face_update_list"></div>' +
  '</div>' +
  '<div class="info">' +
    '<div class="add-info">' +
      '<div class="button-box">' +
        '<div class="add-source">' +
          '<button class="add-images"><i class="iconfont">&#xe603;</i>插入图片</button>' +
          '<div class="add-way">' +
            '<span id="childview-upload-image">从本地上传</span>' +
            '<span id="childview-box-update-image">从图库选择</span>' +
          '</div>' +
        '</div>' +
        '<div class="add-source">' +
          '<button class="add-images"><i class="iconfont">&#xe603;</i>插入全景图</button>' +
          '<div class="add-way">' +
            '<span id="childview-upload-panorama">从本地上传</span>' +
            '<span id="childview-box-update-panorama">从图库选择</span>' +
          '</div>' +
        '</div>' +
        '<button id="add_update_news"><i class="iconfont">&#xe603;</i>插入趣闻</button>' +
        '<button class="add-text" id="childview-update-text"><i class="iconfont">&#xe603;</i>插入文字</button>' +
      '</div>' +
      '<div class="addinfo-main" id="childview-update-box">' +
        '<div class="childview-info viewlist clearfix" import_flag="0" id="childview-update-content"></div>' +
      '</div>' +
    '</div>' +
  '</div>' +
  '<div class="info">' +
    '<button class="keep" id="childview-update">修改</button>' +
  '</div>';
  $('#childview-list-main').show().html(detailsModel).siblings().hide();
};
//路线详情model
getLineDetails = function(){
  var detailsModel = '<h2 class="title">路线详情</h2>' +
    '<div class="info top15">' +
      '<p>线路名称</p>' +
      '<input type="text" class="introbox" id="line_name_update">' +
    '</div>' +
    '<div class="info">' +
      '<p>线路说明</p>' +
      '<input type="text" class="introbox" id="line_intro_update">' +
    '</div>' +
    '<div class="info">' +
      '<p>游览时间</p>' +
      '<input type="text" class="introbox" id="line_time_update">' +
    '</div>' +
    '<div class="info">' +
      '<p>路线坐标</p>' +
      '<div id="lines_list_update" class="coordinates"></div>' +
    '</div>' +
    '<div class="info top15">' +
      '<button class="keep" id="line_update">修改</button>' +
    '</div>';
    $('#line-list-main').show().html(detailsModel).siblings().hide();
};

addNewsModel = function(){
  var searchbox = '<div class="search-header"><input type="text" class="searchbox" id="news-kw"><button class="searchbtn" id="search-news">搜索</button></div>' +
    '<div class="newsbox"></div>';
    $('#source-dialog').delegate('#search-news','click',function(){
      var kw = $('#news-kw').val();
      $.ajax({
        type : "GET",
        dataType: "json",
        async: true,
        cache: true,
        data: {
          kw : kw,
          sn: 0,
          nu: 10000,
          language: language
        },
        url: "http://115.29.179.17:8500/api/spots/getContentMetasByKeyword",
        jsonp: "callback",
        beforeSend: function(){
          $('.newsbox').html('<div class="search_loading">正在搜索...</div>');
        },
        success: function(getnews){
          if(getnews.error_code == 0){
            $('.newsbox').empty();
            for(var i=0; i<getnews.data.length; i++){
              var $list = '<div class="audio_list" news_id="' + getnews.data[i].id + '" type_name="' + getnews.data[i].type_name + '">' + getnews.data[i].title + '</div>';
              $('.newsbox').append($list);
            }
          }else if(getnews.error_code == 10002){
            $('.newsbox').html('<div style="height:290px; text-align:center; line-height:290px; font-size:20px;" class="search_filed">结果为空</div>');
          }else{
            $('.newsbox').html('<div style="height:290px; text-align:center; line-height:290px; font-size:20px;" class="search_filed">' + getnews.error_msg + '</div>');
          }
        },
        error: function(){
          $('.newsbox').html('搜索失败');
        }
      });
    });
  $('#dialog-main').append(searchbox);
  $('#source-dialog').delegate('.queding.somenews','click',function(){
    $('#dialog-main .audio_list.selected').each(function(){
       var newsId = $(this).attr('news_id');
       var newsTitle = $(this).text();
       var type_name = $(this).attr('type_name');
       var $content = '<div class="addlist viewlist" import_id="' + newsId + '" import_flag="1">' +
         '<span class="list-title">' + type_name + '： ' + newsTitle + '</span>' +
         '<span class="remove-list"><i class="iconfont">&#xe604;</i></span>' +
       '</div>';
       newsbox.append($content);
     });
     $('#source-dialog').dialog('close');
  });
};

//获取路线列表
getLineList = function(){
  $.ajax({
    type : "GET",
    dataType: "json",
    async: true,
    cache: true,
    data: {
      spots_id: spots_id,
      language: language
    },
    url: "http://115.29.179.17:8500/api/line/getLinesBySpotsId",
    jsonp: "callback",
    success: function(getlines){
      if(getlines.error_code == 0){
        $('#line-list').empty();
        for(var i=0; i<getlines.data.length; i++){
          var $list = '<div class="secend-list" line_id="' + getlines.data[i].id + '">' + getlines.data[i].name + '</div>'
          $('#line-list').append($list);
        }
      }
    },
    error: function(){
    }
  });
};

//删除资源
deleteResource = function(){
  $.ajax({
    type : "GET",
    dataType: "json",
    async: false,
    cache: true,
    data: {
      type: 1,
      id: spots_id,
      md: thisMd,
      language: language
    },
    url: "http://115.29.179.17:8500/api/index/deleteResource",
    jsonp: "callback",
    success: function(deleteResource){
      if(deleteResource.error_code == 0){
        alert('删除成功');
      }
    },
    error: function(){
      alert('删除失败');
    }
  });
};

//获取景点音频
getSpotsAudio = function(){
  $.ajax({
    type : "GET",
    dataType: "json",
    async: true,
    cache: true,
    data: {
      type : 1,
      id: spots_id,
      language: language
    },
    url: "http://115.29.179.17:8500/api/spots/getResourceList",
    jsonp: "callback",
    beforeSend: function(){
      $('#dialog-main').html('<div class="loading">正在加载...</div>');
    },
    success: function(getSpotsAudio){
      if(getSpotsAudio.error_code == 10002){
        $('#dialog-main').html('<div class="filed">素材库没有该景点音频</div>');
      }else if(getSpotsAudio.error_code == 0){
        if(getSpotsAudio.data.audio.length == 0){
          $('#dialog-main').html('<div class="filed">素材库没有该景点音频</div>');
        }else{
          $('#dialog-main').empty();
          for(var i=0; i<getSpotsAudio.data.audio.length; i++){
            $content = '<div class="audio_list" url="' + getSpotsAudio.data.audio[i].url + '" md="' + getSpotsAudio.data.audio[i].md + '" name="' + getSpotsAudio.data.audio[i].name + '">语音：' + getSpotsAudio.data.audio[i].name + '<i class="iconfont">&#xe60c;</i></div>';
            $('#dialog-main').append($content);
          }
        }
      }else{
        $('#dialog-main').html(getSpotsAudio.error_msg);
      }
    },
    error: function(){
      $('#dialog-main').html('<div class="filed">获取失败</div>');
    }
  });
};

//文本框绑定失去焦点事件
textareaBlur = function(){
  $('.textbox').bind('blur',function(){
    var thisval = $(this).val();
    var thisNoPlace = thisval.replace(/\ +/g,"");          //去掉空格
    var thisNoEnter = thisval.replace(/[\r\n]/g,"");       //去掉回车换行
    if(thisval == '' || thisNoPlace == '' || thisNoEnter == ''){
      $(this).remove().unbind('blur');                     //移除时解绑事件
    }
  });
};

//移除坐标公用代码
removeCoordinates = function(){
  $('body').delegate('.coordinates .route-list .removeone','click',function(){
    var objLength = $(this).parent('.route-list').parent().find('.route-list').length;
    var removeObj = $(this).parent('.route-list').parent('.coordinates');
    if(objLength > 1){
      $(this).parent('.route-list').remove();
      removeObj.find('.route-list').coordinateActive();
    }else{
      return false;
    }
  });
};
//添加坐标公用代码
addCoordinates = function(){
  $('body').delegate('.coordinates .route-list .addone','click',function(){
    var addObj = $(this).parent('.route-list').parent('.coordinates');
    addObj.find('.route-list').coordinateActive();
  });
};