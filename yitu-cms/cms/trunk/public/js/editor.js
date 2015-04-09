$(function(){
  $('.menu .menulist').menuTab();
  getChildviewList(); //获取子景点列表
  getAppendixList(); //获取附录列表
  dragChildView();
  dragAppendixToChildView();
  //点击弹窗取消关闭弹窗
  $('.close_dialog').click(function(){
    $('#source-dialog').dialog('close');
    $('.queding').attr('class','queding');
    $('#source-dialog').find('#dialog-main').attr('class','dialog-main');
  });
  
  if(language == 1){
    $('#spot-name').attr('disabled','disabled');
  }else{
    $('#spot-name').attr('disabled');
  }
  //token验证失效返回登录页
  tokenOverdue = function(r){
    if(r.error_code == 30001){
      location.href = "/login";
    }else{
      alert(r.error_msg)
    }
  };
  
  //基本信息周围坐标html
  var around_coordinatesHtml = '<div class="route-list clearfix">' +
          '<input type="text" placeholder="纬度" value="{0}" class="latitude">' +
          '<input type="text" placeholder="经度" value="{1}" class="longitude">' +
          '<span class="removeone"><i class="iconfont">&#xe60a;</i></span>' +
          '<span class="addone"><i class="iconfont">&#xe603;</i></span>' +
    '</div>';
  
  //获取景点基本信息
  $.ajax({
    type : "GET",
    dataType: "json",
    async: false,
    cache: true,
    data: {
      type : 1,
      id: spots_id,
      language: language
    },
    url: "http://115.29.179.17:8500/api/spots/getSpotsDetail",
    jsonp: "callback",
    success: function(spotInfo){
      if(spotInfo.error_code == 0){
        $('#spot-name').val(spotInfo.data.name);
        $('#spot-pinyin').val(spotInfo.data.pinyin);
        $('#spot-telephone').val(spotInfo.data.telephone);
        $('#spot-address').val(spotInfo.data.address);
        $('#spot-ticket-price').val(spotInfo.data.ticket_price);
        $('#spot-open-time').val(spotInfo.data.open_time);
        $('#description').val(spotInfo.data.description);
        $('#latitude').val(spotInfo.data.latitude);
        $('#longitude').val(spotInfo.data.longitude);
        //封面图
        if(spotInfo.data.face != ''){
          $('#face').append('<div class="images oldupload" md="' + spotInfo.data.face.md + '">' + '<img src="' + spotInfo.data.face.url + '" width="235">' + '<span class="deleteimg"><i class="iconfont">&#xe607;</i></span>' + '</div>');
        }
        //子详情页通用图
        if(spotInfo.data.content_face != ''){
          $('#content_face').append('<div class="images oldupload" md="' + spotInfo.data.content_face.md + '">' + '<img src="' + spotInfo.data.content_face.url + '" width="235">' + '<span class="deleteimg"><i class="iconfont">&#xe607;</i></span>' + '</div>');
        }
        //小编印象子详情页通用图
        if(spotInfo.data.detail_face != ''){
          $('#detail_face').append('<div class="images oldupload" md="' + spotInfo.data.detail_face.md + '">' + '<img src="' + spotInfo.data.detail_face.url + '" width="235">' + '<span class="deleteimg"><i class="iconfont">&#xe607;</i></span>' + '</div>');
        }

        //获取周围坐标
        if(spotInfo.data.around_coordinates.length < 1){
          var html = around_coordinatesHtml.format('','');
          $('#around_coordinates').append(html);
        }else{
          for(var i=0; i<spotInfo.data.around_coordinates.length; i++){
            var html = "";
            html += around_coordinatesHtml.format(
              spotInfo.data.around_coordinates[i].latitude,
              spotInfo.data.around_coordinates[i].longitude
            );
            $('#around_coordinates').append(html);
            $('#around_coordinates .route-list').coordinateActive();
          }
        }
      
        if(spotInfo.data.province == ''){
          $('#s_province').empty().append('<option area_id="000000">请选择省</option>');
        }else{
          var $option = '<option area_id="'+ spotInfo.data.province_id +'" value="'+ spotInfo.data.province +'">' + spotInfo.data.province + '</option>';
          $('#s_province').empty().append($option);
        }
        if(spotInfo.data.city == ''){
          $('#s_city').empty().append('<option area_id="000000">请选择市</option>');
        }else{
          var $option = '<option area_id="'+ spotInfo.data.city_id +'" value="'+ spotInfo.data.city +'">' + spotInfo.data.city + '</option>';
          $('#s_city').empty().append($option);
        }
        if(spotInfo.data.area == ''){
          $('#s_county').empty().append('<option area_id="000000">请选择区/县</option>');
        }else{
          var $option = '<option area_id="'+ spotInfo.data.area_id +'" value="'+ spotInfo.data.area +'">' + spotInfo.data.area + '</option>';
          $('#s_county').empty().append($option);
        }
      }else{
        tokenOverdue(spotInfo)
      }
    },
    error: function(spotInfo){
      $('#taskslist ul').html(spotInfo.error_msg)
    }
  });

  $('#s_province').focus(function(){
    pid = 0;
    area = $(this);
    var $option = '<option area_id="000000">请选择省</option>';
    area.empty();
    area.append($option);
    selectionArea();
  });
  $('#s_province').change(function(){
    var $option = '<option area_id="000000">请选择市</option>';
    var $option_area = '<option area_id="000000">请选择区/县</option>';
    $('#s_city').empty().append($option);
    $('#s_county').empty().append($option_area);
  });

  $('#s_city').focus(function(){
    pid = $("#s_province").find("option:selected").attr("area_id");
    area = $(this);
    if(pid != '000000'){
      area.empty()
      selectionArea();
    }else{
      area.empty().append('<option area_id="000000">请选择市</option>');
    }
  });
  $('#s_city').change(function(){
    var $option_area = '<option area_id="000000">请选择区/县</option>';
    $('#s_county').empty().append($option_area);
  });
  $('#s_county').focus(function(){
    pid = $("#s_city").find("option:selected").attr("area_id");
    area = $(this);
    if(pid != '000000'){
      area.empty()
      selectionArea();
    }else{
      area.empty().append('<option area_id="000000">请选择区/县</option>');
    }
  });
  
  //小编印象获取素材库中的图片
  $('#detail_face_images').click(function(){
    title = '选择图片';
    dialogModelShow();
    $('#dialog-main').addClass('effect-image');
    $('.queding').addClass('effect-image');
    $('.imgnum').html('只能选择一张');
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
      success: function(resourceList){        
        if(resourceList.error_code == 10002){
          $('#dialog-main').html('<div class="filed">素材库没有该景点图片</div>');
        }else if(resourceList.error_code == 0){
          if(resourceList.data.image.length == 0){
            $('#dialog-main').html('<div class="filed">素材库没有该景点图片</div>');
          }else{
            $('#dialog-main').empty();
            for(var i=0; i<resourceList.data.image.length; i++){
              $images = '<div class="images" md="' + resourceList.data.image[i].md + '">' +
                  '<img src="' + resourceList.data.image[i].url + '" width="235" />' +
                '</div>';
                $('#dialog-main').append($images);
            };
          }
        }else{
          $('#dialog-main').html(resourceList.error_msg);
        }
      },
      error: function(resourceList){
        $('#dialog-main').html('<div class="filed">获取失败</div>');
      }
    });
  });
  
  $('#source-dialog').delegate('.effect-image .images','click',function(){
    var selectdIcon = '<span class="chooseimg"><i class="iconfont">&#xe60c;</i></span>';
    $(this).append(selectdIcon);
    $(this).addClass('selected');
    $(this).siblings('.images').removeClass('selected').find('.chooseimg').remove();
    var selectedLength = $('.selected').length;
    $('.imgnum').html('选择了'+ selectedLength +'张');
  });
  
  $('#source-dialog').delegate('.effect-image .images.selected','click',function(){
    $(this).find('.chooseimg').remove();
    $(this).removeClass('selected');
    var selectedLength = $('.selected').length;
    $('.imgnum').html('选择了'+ selectedLength +'张');
  });
  
  $('#source-dialog').delegate('.effect-image.queding','click',function(){
    $('#dialog-main .images.selected').each(function(){
       var detail_face_md = $(this).attr('md');
       var detail_face_src = $(this).find('img').attr('src');
       var $content = '<div class="images oldupload" md="' + detail_face_md + '">' +
         '<img src="' + detail_face_src + '" />' +
         '<span class="deleteimg"><i class="iconfont">&#xe607;</i></span>' +
       '</div>';
       $('#detail_face').empty();
       $('#detail_face').append($content);
       $('.description_review_img').empty();
       $('.description_review_img').append('<img src="' + detail_face_src + '"/>');
     });
     $('#source-dialog').dialog('close');
  });
  
  //移除小编印象中已经选择的图片
  
  $('#around_coordinates').delegate('.addone','click',function(){
    var $content = '<div class="route-list clearfix">' +
                  '<input type="text" placeholder="纬度" value="" class="latitude">' +
                  '<input type="text" placeholder="经度" value="" class="longitude">' +
                  '<span class="removeone"><i class="iconfont">&#xe60a;</i></span>' +
                  '<span class="addone"><i class="iconfont">&#xe603;</i></span>' +
            '</div>';
            $(this).parent('.route-list').after($content);
  });
  
  //基本信息页控制输入内容
  //电话号码只允许输入数字和横线
  $('#spot-telephone').keydown(function(event){
    var keyCode = event.keyCode || event.which;
    if((keyCode >= 48 && keyCode <= 57) || (keyCode == 189 && event.shiftKey != 1) || (keyCode >= 96 && keyCode <= 105) || (keyCode == 109) || (keyCode == 8)){
      return true;
    }else{
      return false;
    }
  });
  //拼音仅能输入英文字母，下划线，空格，横线
  $('#spot-pinyin').keydown(function(){
    var keyCode = event.keyCode || event.which;
    if((keyCode >= 65 && keyCode <= 90) || (keyCode == 189 && event.shiftKey != 1) || (keyCode == 189 && event.shiftKey == 1) || (keyCode == 32) || (keyCode == 8)){
      return true;
    }else{
      return false;
    }
  });
  
  //中心坐标和周围坐标
  $('body').delegate('.latitude,.longitude','keydown',function(){
    var keyCode = event.keyCode || event.which;
    if((keyCode >= 48 && keyCode <= 57) || (keyCode >= 96 && keyCode <= 105) || (keyCode == 110) || (keyCode == 190 && event.shiftKey != 1) || (keyCode == 8)){
      return true;
    }else{
      return false;
    }
  });
  
  //移除选中的图片
  $('#content_face,#face,#detail_face,#detail_face,#spots_images,#intro_face_list,#appendix_face_list').delegate('.images.oldupload .deleteimg','click',function(){
    $(this).parent('.images').remove();
  });
  
  //删除新上传的图片
  $('#content_face,#face,#detail_face,#detail_face,#spots_images').delegate('.images.newupload .deleteimg','click',function(){
    thisMd = $(this).parent('.images').attr('md');
    if(confirm('确定删除这张图片吗？')){
      deleteResource();
      $(this).parent('.images').remove();
    }
  });

  
  //基本信息更新
  $('.recovery').click(function(){
    var pinyin = $('#spot-pinyin').val();
    var telephone = $('#spot-telephone').val();
    var address = $('#spot-address').val();
    var ticket_price = $('#spot-ticket-price').val();
    var open_time = $('#spot-open-time').val();
    var province_id = $('#s_province option:selected').attr('area_id');
    var province = $('#s_province option:selected').text();
    var city_id = $('#s_city option:selected').attr('area_id');
    var city = $('#s_city option:selected').text();
    var area_id = $('#s_county option:selected').attr('area_id');
    var area = $('#s_county option:selected').text();
    var face = $('#face').find('.images').attr('md');
    var content_face = $('#content_face').find('.images').attr('md');
    var description = $('#description').val();
    var latitude = $('#latitude').val();
    var longitude = $('#longitude').val();
    var around_coordinates = "";
    var latitudeStr = "";
    $('#around_coordinates .route-list').each(function(){
        var mylatitude = $(this).find('.latitude').val();
        var mylongitude = $(this).find('.longitude').val();
        latitudeStr += mylatitude+","+mylongitude + '|';
    });
    around_coordinates = latitudeStr;
    around_coordinates = around_coordinates.substr(0,around_coordinates.length-1)
    
    if($('#detail_face .images').length != 0){
      $('#detail_face .images').each(function(){
        detail_face = $(this).attr('md');
      });
    }else{
      detail_face = '';
    }
    if(pinyin == '' && telephone == '' && address == '' && ticket_price == '' && open_time == '' && province_id == 000000 && city_id == 000000 && area_id == 000000 && $('#content_face .images').length == 0 && $('#face .images').length == 0){
      alert('请填写至少一个字段再提交');
    }else{
      $.ajax({
        type : "GET",
        dataType: "json",
        async: false,
        cache: true,
        data: {
          spots_id: spots_id,
          pinyin: pinyin,
          telephone: telephone,
          address: address,
          ticket_price: ticket_price,
          open_time: open_time,
          province_id: province_id,
          province: province,
          city_id: city_id,
          city: city,
          area_id: area_id,
          area: area,
          face: face,
          content_face: content_face,
          description: description,
          task_id: taskId,
          latitude: latitude,
          longitude: longitude,
          around_coordinates: around_coordinates,
          detail_face: detail_face,
          language: language
        },
        url: "http://115.29.179.17:8500/api/spots/updateSpotsInfo",
        success: function(updateinfo){
          if(updateinfo.error_code == 0){
            alert('保存成功');
          }else{
            alert(updateinfo.error_code);
          }
        },
        error: function(){
          alert('保存失败');
        }
      });
    }

  });
  
  
  //获取子景点类型
  $('#subspots').focus(function(){
    optionbox = $('#subspots');
    clearNotFirst();
    getSubspots();
  });
   
  //获取简介语音
  $('#intro-sounds').click(function(){
    title = '选择语音';
    dialogModelShow();
    $('.imgnum').html('只能选择一条语音');
    $('#dialog-main').addClass('intro-sounds');
    $('.queding').addClass('intro-sounds');
    getSpotsAudio();
  });
  
  $('#dialog-main').delegate('.audio_list','click',function(){
    $(this).toggleClass('selected');
  });
  
  $('#source-dialog').delegate('.queding.intro-sounds','click',function(){
    var listLength = $('#dialog-main .audio_list.selected').length;
    if(listLength != 0){
      $('#dialog-main .audio_list.selected').each(function(){
        var md = $(this).attr('md');
        var name = $(this).attr('name');
        var $content = '<div class="audio" md="' + md + '">语音：' + name + '<i class="iconfont delete">&#xe607;</i></div>';
        $('#intro-sounds_list').append($content);
      });
    }
    $('#source-dialog').dialog('close');
  });
  
  //创建子景点
  $('#childview-create').click(function(){
    var submit = false
    var name = $('#childview-name').val();
    var title = $('#childview-title').val();
    var type_id = $('#subspots').find("option:selected").attr('type_id');
    var audio = $('#childview-sounds_list').find('.audio-list').attr('md');
    var face = $('#childview_face_list').find('.images').attr('md');
    var longitude = $('#child-longitude').val();
    var latitude = $('#child-latitude').val();
    var around_coordinates = "";
    var latitudeStr = "";
    $('#around_coordinates .route-list').each(function(){
        var mylatitude = $(this).find('.latitude').val();
        var mylongitude = $(this).find('.longitude').val();
        latitudeStr += mylatitude+","+mylongitude + '|';
    });
    around_coordinates = latitudeStr;
    around_coordinates = around_coordinates.substr(0,around_coordinates.length-1)
    
    
    var metas = Array();
    $('#childview-box .viewlist').each(function(){
      var import_flag = $(this).attr('import_flag');
      var type_id = 12;//内容type_id
      var meta = {};
      meta['id'] = '';
      meta['pos'] = '';
      if(import_flag == 1){
        meta['import_flag'] = 1;
        meta['import_id'] = $(this).attr('import_id');
      }else{
        meta['import_flag'] = 0;
        meta['type_id'] = type_id;
        meta['name'] = name;
        meta['title'] = title;
        var contents = Array();
        $('#childview-box').find('.viewcontent').each(function(){
          var type = $(this).attr('type');
          var content = {};
          if(type == 0){
            content['type'] = 0;  //0表示文本
            content['content'] = $(this).val();
            content['face_md'] = '';
          }else if(type == 1){
            content['type'] = 1;  //1表示图片和全景图
            content['content'] = $(this).attr('md');
            content['face_md'] = '';
          }else if(type == 2){
            content['type'] = 2;  //2表示视频
            content['content'] = $(this).attr('md');
            content['face_md'] = '';
          }else{
            content['type'] = 3;  //3表示音频
            content['content'] = $(this).attr('md');
            content['face_md'] = '';
          }
          contents.push(content);
        });
        meta['content'] = contents;
      }
      metas.push(meta);
    });
    if(name == ''){
      alert('标题不能为空');
    }else if(type_id == 0){
      alert('请选择子景点类型');
    }else{
      submit = true;
    }
    if(submit == true){
      $.ajax({
        type : "GET",
        dataType: "json",
        async: false,
        cache: true,
        data: {
          spots_id: spots_id,
          type_id: type_id,
          name: name,
          audio: audio,
          face: face,
          longitude: longitude,
          latitude: latitude,
          around_coordinates: around_coordinates,
          metas: metas,
          language: language
        },
        url: "http://115.29.179.17:8500/api/spots/createSubScenicSpots",
        jsonp: "callback",
        success: function(createChildView){
          if(createChildView.error_code != 0){
            console.log(createChildView.error_msg);
          }else{
            alert('创建成功');
            getChildviewList(); //获取子景点列表
          }
        },
        error: function(){
          console.log('创建失败');
        }
      });
    }else{
      return false;
    }
  });
  
  //子景点插入音频
  $('#childview-sounds').click(function(){
    title = '选择语音';
    dialogModelShow();
    $('.imgnum').html('只能选择一条语音');
    $('#dialog-main').addClass('childview-sounds');
    $('.queding').addClass('childview-sounds');
    getSpotsAudio();
  });

  $('#source-dialog').delegate('.queding.childview-sounds','click',function(){
    var listLength = $('#dialog-main .audio_list.selected').length;
    if(listLength != 0){
      $('#dialog-main .audio_list.selected').each(function(){
        var md = $(this).attr('md');
        var name = $(this).attr('name');
        var $content = '<div class="audio" md="' + md + '">语音：' + name + '<i class="iconfont delete">&#xe607;</i></div>';
        $('#childview-sounds_list').append($content);
      });
    }
    $('#source-dialog').dialog('close');
  });
  
  //子景点插入子详情页首图
  $('#childview-main').delegate('#childview-face','click',function(){
    title = '选择子详情页首图';  //弹窗标题
    dialogModelShow(); //弹窗模版
    $('.imgnum').html('请只选择一张');
    $('#dialog-main').addClass('childview-face');
    $('.queding').addClass('childview-face');
    
    //获取景点图片列表
    getResourceImageList();
    
    $('#source-dialog').delegate('.childview-face .images','click',function(){
      var selectdIcon = '<span class="chooseimg"><i class="iconfont">&#xe60c;</i></span>';
      $(this).append(selectdIcon);
      $(this).addClass('selected').siblings('.images').removeClass('selected').find('.chooseimg').remove();
    });
    
    $('#source-dialog').delegate('.childview-face .images.selected','click',function(){
      $(this).find('.chooseimg').remove();
      $(this).removeClass('selected');
    });
    
    //点击确定将选中的图片添加到列表
    $('#source-dialog').delegate('.queding.childview-face','click',function(){
      $('#dialog-main .images.selected').each(function(){
         var intro_face_md = $(this).attr('md');
         var intro_face_src = $(this).find('img').attr('src');
         var $content = '<div class="images" md="' + intro_face_md + '">' +
           '<img src="' + intro_face_src + '" />' +
           '<span class="deleteimg"><i class="iconfont">&#xe607;</i></span>' +
         '</div>';
         $('#childview_face_list').empty().append($content);
         $('.review_child_face').empty().append('<img src="' + intro_face_src + '"/>');
       });
       $('#source-dialog').dialog('close');
    });
  });
  
  //子景点内容区插入图片
  $('#childview-box-image').click(function(){
    title = '选择图片';
    dialogModelShow();
    $('#dialog-main').addClass('childview-image');
    $('.imgnum').html('已选择0张');
    $('.queding').addClass('childview-image');
    getResourceImageList();
  });
  //点击图片选中
  $('#source-dialog').delegate('.childview-image .images','click',function(){
    var selectdIcon = '<span class="chooseimg"><i class="iconfont">&#xe60c;</i></span>';
    $(this).append(selectdIcon);
    $(this).addClass('selected');
    var selectedLength = $('.selected').length;
    $('.imgnum').html('选择了'+ selectedLength +'张');
  });
  //点击选中图片取消选中
  $('#source-dialog').delegate('.childview-image .images.selected','click',function(){
    $(this).find('.chooseimg').remove();
    $(this).removeClass('selected');
    var selectedLength = $('.selected').length;
    $('.imgnum').html('选择了'+ selectedLength +'张');
  });
  //点击确定将选中图片插入到内容区
  $('#source-dialog').delegate('.childview-image.queding','click',function(){
    $('#dialog-main .images.selected').each(function(){
       var detail_face_md = $(this).attr('md');
       var detail_face_src = $(this).find('img').attr('src');
       var $content = '<div class="images viewcontent" md="' + detail_face_md + '" type="1" >' +
         '<img src="' + detail_face_src + '" />' +
         '<span class="deleteimg"><i class="iconfont">&#xe607;</i></span>' +
       '</div>';
       $('#childview-content').append($content);
       childviewReview(); //刷新预览界面
     });
     $('#source-dialog').dialog('close');
  });
  
  $('#childview-main').delegate('.images .deleteimg','click',function(){
    $(this).parent('.images').remove();
  });
  
  //子景点内容区插入文字
  $('#childview-text').click(function(){
    var textbox = '<textarea class="textbox viewcontent" type="0"></textarea>';
    $('#childview-content').append(textbox);
    $('.textbox').focus();
    //文本框失去焦点对文本框内容进行判断，如果为空或者只有空格或回车，则当前文本框去掉
    textareaBlur();
    $('.textbox').keyup(function(){
      childviewReview(); //刷新预览界面
    });
  });
  
  //子景点内容区插入趣闻
  $('#add_news').click(function(){
    newsbox = $('#childview-box');
    title = '插入趣闻';
    dialogModelShow();
    $('#dialog-main').addClass('somenews');
    addNewsModel();
    $('.imgnum').html('请选择趣闻');
    $('.queding').addClass('somenews');
  });
  
  //趣闻点击取消
  $('#childview-box').delegate('.remove-list','click',function(){
    $(this).parent('.addlist').remove();
  });
  
  //子景点内容区插入全景图
  $('#childview-box-panorama').click(function(){
    title = '插入全景图';
    dialogModelShow();
    $('#dialog-main').addClass('panorama');
    $('.imgnum').html('请选择全景图');
    $('.queding').addClass('panorama');
    getResourcePanoramaList();
  });
  
  //点击图片选中
  $('#source-dialog').delegate('.panorama .images','click',function(){
    var selectdIcon = '<span class="chooseimg"><i class="iconfont">&#xe60c;</i></span>';
    $(this).append(selectdIcon);
    $(this).addClass('selected');
    var selectedLength = $('.selected').length;
    $('.imgnum').html('选择了'+ selectedLength +'张');
  });
  //点击选中图片取消选中
  $('#source-dialog').delegate('.panorama .images.selected','click',function(){
    $(this).find('.chooseimg').remove();
    $(this).removeClass('selected');
    var selectedLength = $('.selected').length;
    $('.imgnum').html('选择了'+ selectedLength +'张');
  });
  //点击确定将选中图片插入到内容区
  $('#source-dialog').delegate('.panorama.queding','click',function(){
    $('#dialog-main .images.selected').each(function(){
       var detail_face_md = $(this).attr('md');
       var detail_face_src = $(this).find('img').attr('src');
       var $content = '<div class="images viewcontent" md="' + detail_face_md + '" type="1" >' +
         '<img src="' + detail_face_src + '" />' +
         '<span class="deleteimg"><i class="iconfont">&#xe607;</i></span>' +
       '</div>';
       $('#childview-content').append($content);
       childviewReview();
     });
     $('#source-dialog').dialog('close');
  });
  
  $('#childview-main').delegate('.images .deleteimg','click',function(){
    $(this).parent('.images').remove();
  });
  
  
  //修改子景点
  $('#childview-list-main').delegate('#childview-update','click',function(){
    var submit = false;
    var child_id = $('#childview-update-name').attr('child_id');
    var name = $('#childview-update-name').val();
    var title = $('#childview-update-title').val();
    var type_id = $('#subspots_update').find("option:selected").attr('type_id');
    var audio = $('#childview-sounds_update_list').find('.audio').attr('md');
    var face = $('#childview_face_update_list').find('.images').attr('md');
    var longitude = $('#child-update-longitude').val();
    var latitude = $('#child-update-latitude').val();
    var around_coordinates = "";
    var latitudeStr = "";
    $('#child_around_coordinates_update .route-list').each(function(){
        var mylatitude = $(this).find('.latitude').val();
        var mylongitude = $(this).find('.longitude').val();
        latitudeStr += mylatitude+","+mylongitude + '|';
    });
    around_coordinates = latitudeStr;
    around_coordinates = around_coordinates.substr(0,around_coordinates.length-1)
    
    
    var metas = Array();
    $('#childview-update-box .viewlist').each(function(){
      var import_flag = $(this).attr('import_flag');
      var type_id = 12;//内容type_id
      var meta = {};
      meta['id'] = $(this).attr('import_id');
      meta['pos'] = '';
      if(import_flag == 1){
        meta['import_flag'] = 1;
        meta['import_id'] = $(this).attr('import_id');
      }else{
        meta['import_flag'] = 0;
        meta['type_id'] = type_id;
        meta['name'] = name;
        meta['title'] = title;
        var contents = Array();
        $('#childview-update-box').find('.viewcontent').each(function(){
          var type = $(this).attr('type');
          var content = {};
          if(type == 0){
            content['type'] = 0;  //0表示文本
            content['content'] = $(this).val();
            content['face_md'] = '';
          }else if(type == 1){
            content['type'] = 1;  //1表示图片和全景图
            content['content'] = $(this).attr('md');
            content['face_md'] = '';
          }else if(type == 2){
            content['type'] = 2;  //2表示视频
            content['content'] = $(this).attr('md');
            content['face_md'] = '';
          }else{
            content['type'] = 3;  //3表示音频
            content['content'] = $(this).attr('md');
            content['face_md'] = '';
          }
          contents.push(content);
        });
        meta['content'] = contents;
      }
      metas.push(meta);
    });
    if(name == ''){
      alert('标题不能为空');
    }else if(type_id == 0){
      alert('请选择子景点类型');
    }else{
      submit = true;
    }
    if(submit == true){
      $.ajax({
        type : "GET",
        dataType: "json",
        async: false,
        cache: true,
        data: {
          id: child_id,
          type_id: type_id,
          audio: audio,
          face: face,
          longitude: longitude,
          latitude: latitude,
          around_coordinates: around_coordinates,
          metas: metas,
          language: language
        },
        url: "http://115.29.179.17:8500/api/spots/updateSubScenicSpots",
        jsonp: "callback",
        success: function(updateChildView){
          if(updateChildView.error_code != 0){
            console.log(updateChildView.error_msg);
          }else{
            alert('修改成功');
            getChildviewList(); //获取子景点列表
          }
        },
        error: function(){
          console.log('修改失败');
        }
      });
    }else{
      return false;
    }
  });
    
  //修改子景点插入语音
  $('#childview-list-main').delegate('#childview-update-sounds','click',function(){
    title = '选择语音';
    dialogModelShow();
    $('.imgnum').html('只能选择一条语音');
    $('#dialog-main').addClass('childview-update-sounds');
    $('.queding').addClass('childview-update-sounds');
    getSpotsAudio();
  });
  
  $('#source-dialog').delegate('.queding.childview-update-sounds','click',function(){
    var listLength = $('#dialog-main .audio_list.selected').length;
    if(listLength != 0){
      $('#dialog-main .audio_list.selected').each(function(){
        var md = $(this).attr('md');
        var name = $(this).attr('name');
        var $content = '<div class="audio" md="' + md + '">语音：' + name + '<i class="iconfont delete">&#xe607;</i></div>';
        $('#childview-sounds_update_list').append($content);
      });
    }
    $('#source-dialog').dialog('close');
  });
  
  //修改子景点子详情页首图
  $('#childview-list-main').delegate('#childview-update-face','click',function(){
    title = '选择子详情页首图';  //弹窗标题
    dialogModelShow(); //弹窗模版
    $('.imgnum').html('请只选择一张');
    $('#dialog-main').addClass('childview-update-face');
    $('.queding').addClass('childview-update-face');
  
    //获取景点图片列表
    getResourceImageList();
  
    $('#source-dialog').delegate('.childview-update-face .images','click',function(){
      var selectdIcon = '<span class="chooseimg"><i class="iconfont">&#xe60c;</i></span>';
      $(this).append(selectdIcon);
      $(this).addClass('selected').siblings('.images').removeClass('selected').find('.chooseimg').remove();
    });
  
    $('#source-dialog').delegate('.childview-update-face .images.selected','click',function(){
      $(this).find('.chooseimg').remove();
      $(this).removeClass('selected');
    });
  
    //点击确定将选中的图片添加到列表
    $('#source-dialog').delegate('.queding.childview-update-face','click',function(){
      $('#dialog-main .images.selected').each(function(){
         var intro_face_md = $(this).attr('md');
         var intro_face_src = $(this).find('img').attr('src');
         var $content = '<div class="images" md="' + intro_face_md + '">' +
           '<img src="' + intro_face_src + '" />' +
           '<span class="deleteimg"><i class="iconfont">&#xe607;</i></span>' +
         '</div>';
         $('#childview_face_update_list').empty().append($content);
       });
       $('#source-dialog').dialog('close');
    });
  });
  
  //修改子景点提示下拉框
  $('#childview-list-main').delegate('.add-source .add-images','click',function(){
    $(this).siblings('.add-way').slideToggle();
  });
  //修改子景点从图库选择图片
  $('#childview-list-main').delegate('#childview-box-update-image','click',function(){
    title = '选择图片';
    dialogModelShow();
    $('#dialog-main').addClass('childview-update-image');
    $('.imgnum').html('已选择0张');
    $('.queding').addClass('childview-update-image');
    getResourceImageList();
  });
  //点击图片选中
  $('#source-dialog').delegate('.childview-update-image .images','click',function(){
    var selectdIcon = '<span class="chooseimg"><i class="iconfont">&#xe60c;</i></span>';
    $(this).append(selectdIcon);
    $(this).addClass('selected');
    var selectedLength = $('.selected').length;
    $('.imgnum').html('选择了'+ selectedLength +'张');
  });
  //点击选中图片取消选中
  $('#source-dialog').delegate('.childview-update-image .images.selected','click',function(){
    $(this).find('.chooseimg').remove();
    $(this).removeClass('selected');
    var selectedLength = $('.selected').length;
    $('.imgnum').html('选择了'+ selectedLength +'张');
  });
  //点击确定将选中图片插入到内容区
  $('#source-dialog').delegate('.childview-update-image.queding','click',function(){
    $('#dialog-main .images.selected').each(function(){
       var detail_face_md = $(this).attr('md');
       var detail_face_src = $(this).find('img').attr('src');
       var $content = '<div class="images viewcontent" md="' + detail_face_md + '" type="1" >' +
         '<img src="' + detail_face_src + '" />' +
         '<span class="deleteimg"><i class="iconfont">&#xe607;</i></span>' +
       '</div>';
       $('#childview-update-content').append($content);
       updateChildReview(); //刷新预览内容
     });
     $('#source-dialog').dialog('close');
  });

  //修改子景点从图库选择全景图
  $('#childview-list-main').delegate('#childview-box-update-panorama','click',function(){
    title = '插入全景图';
    dialogModelShow();
    $('#dialog-main').addClass('panorama-update');
    $('.imgnum').html('请选择全景图');
    $('.queding').addClass('panorama-update');
    getResourcePanoramaList();
  
    //点击图片选中
    $('#source-dialog').delegate('.panorama-update .images','click',function(){
      var selectdIcon = '<span class="chooseimg"><i class="iconfont">&#xe60c;</i></span>';
      $(this).append(selectdIcon);
      $(this).addClass('selected');
      var selectedLength = $('.selected').length;
      $('.imgnum').html('选择了'+ selectedLength +'张');
    });
    //点击选中图片取消选中
    $('#source-dialog').delegate('.panorama-update .images.selected','click',function(){
      $(this).find('.chooseimg').remove();
      $(this).removeClass('selected');
      var selectedLength = $('.selected').length;
      $('.imgnum').html('选择了'+ selectedLength +'张');
    });
    //点击确定将选中图片插入到内容区
    $('#source-dialog').delegate('.panorama-update.queding','click',function(){
      $('#dialog-main .images.selected').each(function(){
         var detail_face_md = $(this).attr('md');
         var detail_face_src = $(this).find('img').attr('src');
         var $content = '<div class="images viewcontent" md="' + detail_face_md + '" type="1" >' +
           '<img src="' + detail_face_src + '" />' +
           '<span class="deleteimg"><i class="iconfont">&#xe607;</i></span>' +
         '</div>';
         $('#childview-update-content').append($content);
         updateChildReview(); //刷新预览内容
       });
       $('#source-dialog').dialog('close');
    });
  });
  //修改子景点插入趣闻
  $('#childview-list-main').delegate('#add_update_news','click',function(){
    newsbox = $('#childview-update-box');
    title = '插入趣闻';
    dialogModelShow();
    $('#dialog-main').addClass('somenews');
    addNewsModel();
    $('.imgnum').html('请选择趣闻');
    $('.queding').addClass('somenews');
  });
  
  //修改子景点插入文字
  $('#childview-list-main').delegate('#childview-update-text','click',function(){
    var textbox = '<textarea class="textbox viewcontent" type="0"></textarea>';
    $('#childview-update-content').append(textbox);
    $('.textbox').focus();
    //文本框失去焦点对文本框内容进行判断，如果为空或者只有空格或回车，则当前文本框去掉
    textareaBlur();
  });

  //修改子景点对一插入图片的操作
  $('#childview-list-main').delegate('.images .deleteimg','click',function(){
    $(this).parent('.images').remove();
  });
  //修改子景点对一插入趣闻的操作
  $('#childview-list-main').delegate('.remove-list','click',function(){
    $(this).parent('.addlist').remove();
  });
  $('#childview-list-main').delegate('.textbox','keyup',function(){
    updateChildReview(); //刷新预览界面
    reviewScrollBottom();
  });
  
  //创建附录
  $('#appendix_create').click(function(){
    var submit = false
    var appendixTitle = $('#appendix_title').val();
    var type_id = $('#appendix_kind').find("option:selected").attr('type_id');
    var face = $('#appendix_face_list').find('.images').attr('md');
    var contents = new Array();
    $('#appendix-box').find('.viewlist').each(function(){
      //type=0:文本,content是文本内容
      //type=1:图片,content是图片的md
      //type=2:视频,content是视频的md
      //type=3:音频,content是音频的md
      var type = $(this).attr('type');
      var content = {};
      if(type == 0){
        content['type'] = 0;
        content['content'] = $(this).val();
        content['face_md'] = '';
      }else if(type == 1){
        content['type'] = 1;
        content['content'] = $(this).attr('md');
        content['face_md'] = '';
      }else if(type == 3){
        content['type'] = 3;
        content['content'] = $(this).attr('md');
        content['face_md'] = '';
      }
      contents.push(content);
    });
    var contents = contents;
    if(appendixTitle == ''){
      alert('标题不能为空');
    }else{
      submit = true
    }
    if(submit == true){
      $.ajax({
        type : "GET",
        dataType: "json",
        async: false,
        cache: true,
        data: {
          spots_id: spots_id,
          type_id: type_id,
          title: appendixTitle,
          face: face,
          content: contents,
          language: language
        },
        url: "http://115.29.179.17:8500/api/spots/createAppendix",
        jsonp: "callback",
        success: function(createAppendix){
          if(createAppendix.error_code != 0){
            console.log(createAppendix.error_msg);
          }else{
            alert('创建成功');
            getAppendixList(); //获取最新附录列表
          }
        },
        error: function(){
          console.log('创建失败');
        }
      });
    }else{
      return false;
    }
  });
  
  //当焦点在附录类型时，获取附录类型
  $('#appendix_kind').focus(function(){
    optionbox = $('#appendix_kind');
    clearNotFirst();
    getAppendixKind();
  });
  
  $('.add-source').click(function(){
    $(this).children('.add-way').slideToggle();
  });
  //点击插入文字，动态添加文本框
  $('#appendix-text').click(function(){
    var textbox = '<textarea class="textbox viewlist" type="0"></textarea>';
    $('#appendix-box').append(textbox);
    $('.textbox').focus();
    //文本框失去焦点对文本框内容进行判断，如果为空或者只有空格或回车，则当前文本框去掉
    textareaBlur();
    reviewScrollBottom();
  });
  
  
  $('#appendix-box-image').click(function(){
    title = '选择图片';
    dialogModelShow();
    getResourceImageList();
    $('#dialog-main').addClass('appendix-image');
    $('.imgnum').html('已选择0张');
    $('.queding').addClass('appendix-image');
  });
  
  $('#source-dialog').delegate('.appendix-image .images','click',function(){
    var selectdIcon = '<span class="chooseimg"><i class="iconfont">&#xe60c;</i></span>';
    $(this).append(selectdIcon);
    $(this).addClass('selected');
    var selectedLength = $('.selected').length;
    $('.imgnum').html('选择了'+ selectedLength +'张');
  });
  
  $('#source-dialog').delegate('.appendix-image .images.selected','click',function(){
    $(this).find('.chooseimg').remove();
    $(this).removeClass('selected');
    var selectedLength = $('.selected').length;
    $('.imgnum').html('选择了'+ selectedLength +'张');
  });
  
  $('#source-dialog').delegate('.appendix-image.queding','click',function(){
    $('#dialog-main .images.selected').each(function(){
       var detail_face_md = $(this).attr('md');
       var detail_face_src = $(this).find('img').attr('src');
       var $content = '<div class="images viewlist" md="' + detail_face_md + '"  type="1" >' +
         '<img src="' + detail_face_src + '" />' +
         '<span class="deleteimg"><i class="iconfont">&#xe607;</i></span>' +
       '</div>';
       $('#appendix-box').append($content);
       appendixReview();
       reviewScrollBottom();
     });
     $('#source-dialog').dialog('close');
  });
  
  $('#appendix-main').delegate('#appendix-box .images .deleteimg','click',function(){
    $(this).parent('.images').remove();
  });
  
  //获取附录详情
  $('#appendix-list').delegate('.secend-list','click',function(){
    var appendixId = $(this).attr('appendix-id');
    $('#appendix').addClass('active').siblings().removeClass('active');
    getAppendixDetails();
    $.ajax({
      type : "GET",
      dataType: "json",
      async: false,
      cache: true,
      data: {
        id: appendixId,
        language: language
      },
      url: "http://115.29.179.17:8500/api/spots/getAppendixInfo",
      jsonp: "callback",
      success: function(appendixDetails){
        if(appendixDetails.error_code == 0){
          $('#appendix_update_title').val(appendixDetails.data.title).attr('appendix-id',appendixDetails.data.id);//标题
          //附录类型区域
          if(appendixDetails.data.type_id == ''){
            $('#appendix_update_kind').empty().append('<option type_id="0">请选择附录类型</option>');
          }else{
            var $option = '<option type_id="'+ appendixDetails.data.type_id +'" value="'+ appendixDetails.data.type_name +'">' + appendixDetails.data.type_name + '</option>';
            $('#appendix_update_kind').empty().append($option);
          }
          //子详情页首图
          if(appendixDetails.data.face != ''){
            var faceImage = '<div class="images" md="' + appendixDetails.data.face.md + '">' +
              '<img src="' + appendixDetails.data.face.url + '"/>' +
              '<span class="deleteimg"><i class="iconfont">&#xe607;</i></span>' +
            '</div>';
            $('#appendix_update_face_list').append(faceImage);
          };
          //混排内容区域
          if(appendixDetails.data.content.length > 0){
            for(var i=0; i<appendixDetails.data.content.length; i++){
              if(appendixDetails.data.content[i].type == 0){
                $content = '<textarea class="textbox viewlist" type="0">' + appendixDetails.data.content[i].content + '</textarea>';
              }else{
                $content = '<div class="images viewlist" md="' + appendixDetails.data.content[i].content.md + '" type="1">' +
                            '<img src="' + appendixDetails.data.content[i].content.url + '"><span class="deleteimg"><i class="iconfont">&#xe607;</i></span>' +
                          '</div>';
              };
              $('#appendix_update-box').append($content);
              textareaBlur();
            };
          };
        };
      },
      error: function(){
        
      }
    });
  });
  
  //获取子景点列表里面的附录详情
  $('#childview-list').delegate('.appendix_list','click',function(){
    var appendixId = $(this).attr('childview-id');
    $('#childview').addClass('active').siblings().removeClass('active');
    getAppendixDetails();
    $.ajax({
      type : "GET",
      dataType: "json",
      async: false,
      cache: true,
      data: {
        id: appendixId,
        language: language
      },
      url: "http://115.29.179.17:8500/api/spots/getAppendixInfo",
      jsonp: "callback",
      success: function(appendixDetails){
        if(appendixDetails.error_code == 0){
          $('#appendix_update_title').val(appendixDetails.data.title).attr('appendix-id',appendixDetails.data.id);//标题
          //附录类型区域
          if(appendixDetails.data.type_id == ''){
            $('#appendix_update_kind').empty().append('<option type_id="0">请选择附录类型</option>');
          }else{
            var $option = '<option type_id="'+ appendixDetails.data.type_id +'" value="'+ appendixDetails.data.type_name +'">' + appendixDetails.data.type_name + '</option>';
            $('#appendix_update_kind').empty().append($option);
          }
          //子详情页首图
          if(appendixDetails.data.face != ''){
            var faceImage = '<div class="images" md="' + appendixDetails.data.face.md + '">' +
              '<img src="' + appendixDetails.data.face.url + '"/>' +
              '<span class="deleteimg"><i class="iconfont">&#xe607;</i></span>' +
            '</div>';
            $('#appendix_update_face_list').append(faceImage);
          };
          //混排内容区域
          if(appendixDetails.data.content.length > 0){
            for(var i=0; i<appendixDetails.data.content.length; i++){
              if(appendixDetails.data.content[i].type == 0){
                $content = '<textarea class="textbox viewlist" type="0" >' + appendixDetails.data.content[i].content + '</textarea>';
              }else{
                $content = '<div class="images viewlist" md="' + appendixDetails.data.content[i].content.md + '" type="1">' +
                            '<img src="' + appendixDetails.data.content[i].content.url + '"><span class="deleteimg"><i class="iconfont">&#xe607;</i></span>' +
                          '</div>';
              };
              $('#appendix_update-box').append($content);
              textareaBlur();
            };
          };
        };
      },
      error: function(){
        
      }
    });
  });
  
  
  //修改附录模块儿
  $('#appendix-list-main').delegate('#appendix_update','click',function(){
    var submit = false
    var appendixTitle = $('#appendix_update_title').val();
    var appendix_id = $('#appendix_update_title').attr('appendix-id');
    var type_id = $('#appendix_update_kind').find("option:selected").attr('type_id');
    var face = $('#appendix_update_face_list').find('.images').attr('md');
    var contents = new Array();
    $('#appendix_update-box').find('.viewlist').each(function(){
      //type=0:文本,content是文本内容
      //type=1:图片,content是图片的md
      //type=2:视频,content是视频的md
      //type=3:音频,content是音频的md
      var type = $(this).attr('type');
      var content = {};
      if(type == 0){
        content['type'] = 0;
        content['content'] = $(this).val();
        content['face_md'] = '';
      }else if(type == 1){
        content['type'] = 1;
        content['content'] = $(this).attr('md');
        content['face_md'] = '';
      }else if(type == 3){
        content['type'] = 3;
        content['content'] = $(this).attr('md');
        content['face_md'] = '';
      }
      contents.push(content);
    });
    var contents = contents;
    if(appendixTitle == ''){
      alert('标题不能为空');
    }else{
      submit = true
    }
    if(submit == true){
      $.ajax({
        type : "GET",
        dataType: "json",
        async: true,
        cache: true,
        data: {
          id: appendix_id,
          type_id: type_id,
          title: appendixTitle,
          face: face,
          content: contents,
          language: language
        },
        url: "http://115.29.179.17:8500/api/spots/updateAppendix",
        jsonp: "callback",
        success: function(updateAppendix){
          if(updateAppendix.error_code != 0){
            console.log(updateAppendix.error_msg);
          }else{
            alert('修改成功');
            getAppendixList(); //获取最新附录列表
          }
        },
        error: function(){
          console.log('修改失败');
        }
      });
    }else{
      return false;
    }
  });
  
  //修改子详情页首图
  $('#appendix-list-main').delegate('#appendix_update-face','click',function(){
    title = '选择子详情页首图';  //弹窗标题
    dialogModelShow(); //弹窗模版
    $('.imgnum').html('请只选择一张');
    $('#dialog-main').addClass('appendix_face');
    $('.queding').addClass('appendix_face');
    
    //获取景点图片列表
    getResourceImageList();
    
    $('#source-dialog').delegate('.appendix_face .images','click',function(){
      var selectdIcon = '<span class="chooseimg"><i class="iconfont">&#xe60c;</i></span>';
      $(this).append(selectdIcon);
      $(this).addClass('selected').siblings('.images').removeClass('selected').find('.chooseimg').remove();
    });
    
    $('#source-dialog').delegate('.appendix_face .images.selected','click',function(){
      $(this).find('.chooseimg').remove();
      $(this).removeClass('selected');
    });
    
    //点击确定将选中的图片添加到列表
    $('#source-dialog').delegate('.queding.appendix_face','click',function(){
      $('#dialog-main .images.selected').each(function(){
         var intro_face_md = $(this).attr('md');
         var intro_face_src = $(this).find('img').attr('src');
         var $content = '<div class="images" md="' + intro_face_md + '">' +
           '<img src="' + intro_face_src + '" />' +
           '<span class="deleteimg"><i class="iconfont">&#xe607;</i></span>' +
         '</div>';
         $('#appendix_update_face_list').empty().append($content);
       });
       $('#source-dialog').find('#dialog-main').attr('class','dialog-main');
       $('#source-dialog').find('.queding').attr('queding');
       $('#source-dialog').dialog('close');
    });
  });
  
  //选择插入图片方式
  $('#appendix-list-main').delegate('.add-source','click',function(){
    $(this).children('.add-way').slideToggle();
  });
  
  //点击插入文字，动态添加文本框
  $('#appendix-list-main').delegate('.add-text','click',function(){
    var textbox = '<textarea class="textbox viewlist" type="0"></textarea>';
    $(this).parent('.button-box').siblings('.addinfo-main').append(textbox);
    $('.textbox').focus();
    //文本框失去焦点对文本框内容进行判断，如果为空或者只有空格或回车，则当前文本框去掉
    textareaBlur();
  });
  
  $('#appendix-list-main').delegate('#appendix-update-box-image','click',function(){
    title = '选择图片';
    dialogModelShow();
    getResourceImageList();
    $('#dialog-main').addClass('appendix-update-image');
    $('.imgnum').html('已选择0张');
    $('.queding').addClass('appendix-update-image');
  });
  
  $('#source-dialog').delegate('.appendix-update-image .images','click',function(){
    var selectdIcon = '<span class="chooseimg"><i class="iconfont">&#xe60c;</i></span>';
    $(this).append(selectdIcon);
    $(this).addClass('selected');
    var selectedLength = $('.selected').length;
    $('.imgnum').html('选择了'+ selectedLength +'张');
  });
  
  $('#source-dialog').delegate('.appendix-update-image .images.selected','click',function(){
    $(this).find('.chooseimg').remove();
    $(this).removeClass('selected');
    var selectedLength = $('.selected').length;
    $('.imgnum').html('选择了'+ selectedLength +'张');
  });
  
  $('#source-dialog').delegate('.appendix-update-image.queding','click',function(){
    $('#dialog-main .images.selected').each(function(){
       var detail_face_md = $(this).attr('md');
       var detail_face_src = $(this).find('img').attr('src');
       var $content = '<div class="images viewlist" md="' + detail_face_md + '"  type="1" >' +
         '<img src="' + detail_face_src + '" />' +
         '<span class="deleteimg"><i class="iconfont">&#xe607;</i></span>' +
       '</div>';
       $('#appendix_update-box').append($content);
       updateAppendixReview();
     });
     $('#dialog-main').removeClass('appendix-update-image');
     $('.queding').removeClass('appendix-update-image');
     $('#source-dialog').dialog('close');
  });
  
  $('#appendix-list-main').delegate('#appendix_update_face_list .images .deleteimg','click',function(){
    $(this).parent('.images').remove();
  });
  
  $('#appendix-list-main').delegate('#appendix_update-box .images .deleteimg','click',function(){
    $(this).parent('.images').remove();
  });
  
  
  //获取子景点详情
  $('#childview-list').delegate('.childview_list','click',function(){
    var childviewId = $(this).attr('childview-id');
    $('#childview').addClass('active').siblings().removeClass('active');
    getChildViewDetails();
    $.ajax({
      type : "GET",
      dataType: "json",
      async: false,
      cache: true,
      data: {
        id: childviewId,
        language: language
      },
      url: "http://115.29.179.17:8500/api/spots/getSubSpotsInfo",
      jsonp: "callback",
      success: function(childViewDetails){
        if(childViewDetails.error_code == 0){
          var name = childViewDetails.data.name;
          var face_md = childViewDetails.data.face.md;
          var face_url = childViewDetails.data.face.url;
          var count = childViewDetails.data.metas.count;
          var child_id = childViewDetails.data.id;
          $('#childview-update-name').val(name).attr('child_id',child_id);
          //子景点类型区域
          if(childViewDetails.data.type_id == ''){
            $('#subspots_update').empty().append('<option type_id="0">请选择子景点类型</option>');
          }else{
            var $option = '<option type_id="'+ childViewDetails.data.type_id +'" value="'+ childViewDetails.data.type_name +'">' + childViewDetails.data.type_name + '</option>';
            $('#subspots_update').empty().append($option);
          }
          if(childViewDetails.data.face != ''){
            var faceImage = '<div class="images" md="' + face_md + '">' +
              '<img src="' + face_url + '"/>' +
              '<span class="deleteimg"><i class="iconfont">&#xe607;</i></span>' +
            '</div>';
            $('#childview_face_update_list').append(faceImage);
          };
          if(childViewDetails.data.audio != ''){
            var md = childViewDetails.data.audio.md;
            var url = childViewDetails.data.audio.url;
            var audio = '<div md="' + md + '" url="' + url + '" class="audio">语音：' + md + '<i class="iconfont delete">&#xe607;</i></div>';
            $('#childview-sounds_update_list').append(audio);
          }
          //中心坐标
          $('#child-update-latitude').val(childViewDetails.data.latitude);
          $('#child-update-longitude').val(childViewDetails.data.longitude);
          //周围坐标
          if(childViewDetails.data.around_coordinates.length < 1){
            $content = '<div class="route-list clearfix">' +
                      '<input type="text" placeholder="纬度" value="" class="latitude">' +
                      '<input type="text" placeholder="经度" value="" class="longitude">' +
                      '<span class="removeone"><i class="iconfont">&#xe60a;</i></span>' +
                      '<span class="addone"><i class="iconfont">&#xe603;</i></span>' +
                '</div>';
            $('#child_around_coordinates_update').append($content);
          }else{
            for(var i=0; i<childViewDetails.data.around_coordinates.length; i++){
              var $content = '<div class="route-list clearfix"><input type="text" placeholder="纬度" value="' + childViewDetails.data.around_coordinates[i].latitude + '" class="latitude">' +
                  '<input type="text" placeholder="经度" value="' + childViewDetails.data.around_coordinates[i].longitude + '" class="longitude">' +
                  '<span class="addone"><i class="iconfont">&#xe603;</i></span><span class="removeone"><i class="iconfont">&#xe60a;</i></span></div>';
                  $('#child_around_coordinates_update').append($content);
                  $('#child_around_coordinates_update .route-list').coordinateActive();
            }
          }
          //混排内容区域
          if(childViewDetails.data.metas.count != 0){
            for(var i=0;i<childViewDetails.data.metas.data[0].content.length; i++){
              var import_id = childViewDetails.data.metas.data[0].id;
              if(childViewDetails.data.metas.data[0].content[i].type == 0){
                $content = '<textarea class="textbox viewcontent" type="0" >' + childViewDetails.data.metas.data[0].content[i].content + '</textarea>';
              }else{
                $content = '<div class="images viewcontent" md="' + childViewDetails.data.metas.data[0].content[i].content.md + '" type="1">' +
                  '<img src="' + childViewDetails.data.metas.data[0].content[i].content.url + '"><span class="deleteimg"><i class="iconfont"></i></span>' +
                '</div>';
              }
              $('#childview-update-content').attr('import_id',import_id).append($content);
              textareaBlur();
            };
            for(var j=1; j<childViewDetails.data.metas.data.length; j++){
                $content1 = '<div class="addlist viewlist" import_id="' + childViewDetails.data.metas.data[j].id + '" import_flag="1">' +
                  '<span class="list-title">' + childViewDetails.data.metas.data[j].type_name + '： ' + childViewDetails.data.metas.data[j].title + '</span>' +
                  '<span class="remove-list"><i class="iconfont">&#xe604;</i></span>' +
                '</div>';
             $('#childview-update-box').append($content1);
            };
          }
        };
      },
      error: function(){
        
      }
    });
  });
  
  //删除子景点
  $('#childview-list').delegate('.childview_list .delete','click',function(){
    var childviewId = $(this).parent('.secend-list').attr('childview-id');
    if(confirm('确定删除该子景点吗？')){
      $.ajax({
        type : "GET",
        dataType: "json",
        async: true,
        cache: true,
        data: {
          id: childviewId,
          language: language
        },
        url: "http://115.29.179.17:8500/api/spots/deleteSubScenicSpots",
        jsonp: "callback",
        success: function(deleteChildView){
          if(deleteChildView.error_code != 0){
            console.log(deleteChildView.error_error_msg);
          }else{
            alert('删除成功');
            getChildviewList(); //获取子景点列表
          }
        }
      });
    }
  });
  
  //删除子景点列表里面的附录
  $('#childview-list').delegate('.appendix_list .delete','click',function(){
    $(this).parent('.appendix_list').remove();
    alert('删除成功');
    changeChildPosNum();
  });
  
  //添加简介
  $('#introduce-create').click(function(){
    var submit = false
    var introduceTitle = $('#intro-title').val();
    var audio = $('#intro-sounds_list').find('.audio').attr('md');
    var face = $('#intro_face_list').find('.images').attr('md');
    var contents = new Array();
    $('#introduce-box').find('.viewlist').each(function(){
      //type值分别代表什么
      //0:文本,content是文本内容
      //1:图片,content是图片的md
      //3:音频,content是音频的md
      var type = $(this).attr('type');
      var content = {};
      if(type == 0){
        content['type'] = 0;
        content['content'] = $(this).val();
        content['face_md'] = '';
      }else{
        content['type'] = 1;
        content['content'] = $(this).attr('md');
        content['face_md'] = '';
      }
      contents.push(content);
    });
    var contents = contents;
    if(introduceTitle == ''){
      alert('标题不能为空');
    }else{
      submit = true
    }
    if(submit == true){
      $.ajax({
        type : "GET",
        dataType: "json",
        async: true,
        cache: true,
        data: {
          spots_id: spots_id,
          title: introduceTitle,
          audio: audio,
          face: face,
          content: contents,
          language: language
        },
        url: "http://115.29.179.17:8500/api/spots/createIntroduction",
        jsonp: "callback",
        success: function(createIntroduce){
          if(createIntroduce.error_code != 0){
            console.log(createIntroduce.error_msg);
          }else{
            alert('添加成功');
          }
        },
        error: function(){
          console.log('添加失败');
        }
      });
    }else{
      return false;
    }
  });
  //简介插入文字
  $('#intro-text').click(function(){
    var textbox = '<textarea class="textbox viewlist" type="0"></textarea>';
    $('#introduce-box').append(textbox);
    $('.textbox').focus();
    //文本框失去焦点对文本框内容进行判断，如果为空或者只有空格或回车，则当前文本框去掉
    textareaBlur();
  });
  //简介插入图片
  $('#intro-box-image').click(function(){
    title = '选择图片';
    dialogModelShow();
    getResourceImageList();
    $('#dialog-main').addClass('intro-image');
    $('.imgnum').html('已选择0张');
    $('.queding').addClass('intro-image');
  });
  //点击图片选中
  $('#source-dialog').delegate('.intro-image .images','click',function(){
    var selectdIcon = '<span class="chooseimg"><i class="iconfont">&#xe60c;</i></span>';
    $(this).append(selectdIcon);
    $(this).addClass('selected');
    var selectedLength = $('.selected').length;
    $('.imgnum').html('选择了'+ selectedLength +'张');
  });
  //点击选中图片取消选中
  $('#source-dialog').delegate('.intro-image .images.selected','click',function(){
    $(this).find('.chooseimg').remove();
    $(this).removeClass('selected');
    var selectedLength = $('.selected').length;
    $('.imgnum').html('选择了'+ selectedLength +'张');
  });
  //点击确定将选中图片插入到内容区
  $('#source-dialog').delegate('.intro-image.queding','click',function(){
    $('#dialog-main .images.selected').each(function(){
       var detail_face_md = $(this).attr('md');
       var detail_face_src = $(this).find('img').attr('src');
       var $content = '<div class="images viewlist" md="' + detail_face_md + '" type="1" >' +
         '<img src="' + detail_face_src + '" />' +
         '<span class="deleteimg"><i class="iconfont">&#xe607;</i></span>' +
       '</div>';
       $('#introduce-box').append($content);
     });
     $('#source-dialog').dialog('close');
  });
  
  $('#introduce-box').delegate('.images .deleteimg','click',function(){
    $(this).parent('.images').remove();
  });
  //简介插入全景图
  $('#intro-box-panorama').click(function(){
    title = '选择全景图';
    dialogModelShow();
    getResourcePanoramaList();
    $('#dialog-main').addClass('intro-panorama');
    $('.imgnum').html('已选择0张');
    $('.queding').addClass('intro-panorama');
  });
  //点击图片选中
  $('#source-dialog').delegate('.intro-panorama .images','click',function(){
    var selectdIcon = '<span class="chooseimg"><i class="iconfont">&#xe60c;</i></span>';
    $(this).append(selectdIcon);
    $(this).addClass('selected');
    var selectedLength = $('.selected').length;
    $('.imgnum').html('选择了'+ selectedLength +'张');
  });
  //点击选中图片取消选中
  $('#source-dialog').delegate('.intro-panorama .images.selected','click',function(){
    $(this).find('.chooseimg').remove();
    $(this).removeClass('selected');
    var selectedLength = $('.selected').length;
    $('.imgnum').html('选择了'+ selectedLength +'张');
  });
  //点击确定将选中图片插入到内容区
  $('#source-dialog').delegate('.intro-panorama.queding','click',function(){
    $('#dialog-main .images.selected').each(function(){
       var detail_face_md = $(this).attr('md');
       var detail_face_src = $(this).find('img').attr('src');
       var $content = '<div class="images viewlist" md="' + detail_face_md + '" type="1" >' +
         '<img src="' + detail_face_src + '" />' +
         '<span class="deleteimg"><i class="iconfont">&#xe607;</i></span>' +
       '</div>';
       $('#introduce-box').append($content);
     });
     $('#source-dialog').dialog('close');
  });
  
  //获取简介
  getIntroduce();
  
  //更改简介
  $('#introduce-update').click(function(){
    var submit = false
    var introduceTitle = $('#intro-title').val();
    var audio = $('#intro-sounds_list').find('.audio').attr('md');
    var face = $('#intro_face_list').find('.images').attr('md');
    var contents = new Array();
    $('#introduce-box').find('.viewlist').each(function(){
      //type值分别代表什么
      //0:文本,content是文本内容
      //1:图片,content是图片的md
      //3:音频,content是音频的md
      var type = $(this).attr('type');
      var content = {};
      if(type == 0){
        content['type'] = 0;
        content['content'] = $(this).val();
        content['face_md'] = '';
      }else{
        content['type'] = 1;
        content['content'] = $(this).attr('md');
        content['face_md'] = '';
      }
      contents.push(content);
    });
    var contents = contents;
    if(introduceTitle == ''){
      alert('标题不能为空');
    }else{
      submit = true
    }
    if(submit == true){
      $.ajax({
        type : "GET",
        dataType: "json",
        async: true,
        cache: true,
        data: {
          spots_id: spots_id,
          title: introduceTitle,
          audio: audio,
          face: face,
          content: contents,
          language: language
        },
        url: "http://115.29.179.17:8500/api/spots/updateIntroduction",
        jsonp: "callback",
        success: function(updateIntroduce){
          if(updateIntroduce.error_code != 0){
            console.log(updateIntroduce.error_msg);
          }else{
            alert('修改成功');
          }
        },
        error: function(){
          console.log('修改失败');
        }
      });
    }else{
      return false;
    }
  });
  
  //简介里面插入子详情页首图
  $('#intro-face').click(function(){
    title = '选择子详情页首图'; //弹窗标题
    dialogModelShow();  //弹窗模版
    $('.imgnum').html('只能选择一张');
    $('#dialog-main').addClass('introimg');
    $('.queding').addClass('introimg');
    getResourceImageList();
  });
  
  $('#source-dialog').delegate('.introimg .images','click',function(){
    var selectdIcon = '<span class="chooseimg"><i class="iconfont">&#xe60c;</i></span>';
    $(this).append(selectdIcon);
    $(this).addClass('selected').siblings('.images').removeClass('selected').find('.chooseimg').remove();
  });
  
  $('#source-dialog').delegate('.introimg .images.selected','click',function(){
    $(this).find('.chooseimg').remove();
    $(this).removeClass('selected');
  });
  
  //点击确定将选中的图片添加到列表
  $('#source-dialog').delegate('.queding.introimg','click',function(){
    $('#dialog-main .images.selected').each(function(){
       var intro_face_md = $(this).attr('md');
       var intro_face_src = $(this).find('img').attr('src');
       var $content = '<div class="images oldupload" md="' + intro_face_md + '">' +
         '<img src="' + intro_face_src + '" />' +
         '<span class="deleteimg"><i class="iconfont">&#xe607;</i></span>' +
       '</div>';
       $('#intro_face_list').empty().append($content);
     });
     $('#source-dialog').dialog('close');
  });

  
  //移除已经插入的语音
  $('body').delegate('.audio .delete','click',function(){
    $(this).parent('.audio').remove();
  });
  
  //附录插入子详情页首图
  $('#appendix-face').click(function(){
    title = '选择子详情页首图';  //弹窗标题
    dialogModelShow(); //弹窗模版
    $('.imgnum').html('请只选择一张');
    $('#dialog-main').addClass('appendix_face');
    $('.queding').addClass('appendix_face');
    
    //获取景点图片列表
    getResourceImageList();
    
    $('#source-dialog').delegate('.appendix_face .images','click',function(){
      var selectdIcon = '<span class="chooseimg"><i class="iconfont">&#xe60c;</i></span>';
      $(this).append(selectdIcon);
      $(this).addClass('selected').siblings('.images').removeClass('selected').find('.chooseimg').remove();
    });
    
    $('#source-dialog').delegate('.appendix_face .images.selected','click',function(){
      $(this).find('.chooseimg').remove();
      $(this).removeClass('selected');
    });
    
    //点击确定将选中的图片添加到列表
    $('#source-dialog').delegate('.queding.appendix_face','click',function(){
      $('#dialog-main .images.selected').each(function(){
         var intro_face_md = $(this).attr('md');
         var intro_face_src = $(this).find('img').attr('src');
         var $content = '<div class="images oldupload" md="' + intro_face_md + '">' +
           '<img src="' + intro_face_src + '" />' +
           '<span class="deleteimg"><i class="iconfont">&#xe607;</i></span>' +
         '</div>';
         $('#appendix_face_list').empty().append($content);
         appendixReview();
         reviewScrollBottom();
       });
       $('#source-dialog').dialog('close');
    });
  });
  
  
  //获取路线列表
  getLineList();
  
  //添加线路
  $('#line_create').click(function(){
    var name = $('#line_name').val();
    var description = $('#line_intro').val();
    var time = $('#line_time').val();
    var line = "";
    var lines = "";
    $('#lines_list .route-list').each(function(){
        var lineLatitude = $(this).find('.latitude').val();
        var lineLongitude = $(this).find('.longitude').val();
        var lineId = $(this).find('.viewname option:selected').attr('view_id');
        lines += lineLatitude +"," + lineLongitude + "," + lineId + '|';
    });
    line = lines;
    line = line.substr(0,line.length-1);
    $.ajax({
      type : "GET",
      dataType: "json",
      async: true,
      cache: true,
      data: {
        spots_id: spots_id,
        name: name,
        description: description,
        time: time,
        line: line,
        language: language
      },
      url: "http://115.29.179.17:8500/api/line/createLine",
      jsonp: "callback",
      success: function(createLines){
        if(createLines.error_code == 0){
          alert('创建成功');
          getLineList();
        }else{
          alert('创建失败');
        }
      },
      error: function(){
      }
    });
  });
  
  //获取线路详细信息
  $('#line-list').delegate('.secend-list','click',function(){
    var line_id = $(this).attr('line_id');
    $.ajax({
      type : "GET",
      dataType: "json",
      async: true,
      cache: true,
      data: {
        id: line_id,
        language: language
      },
      url: "http://115.29.179.17:8500/api/line/getLineDetail",
      jsonp: "callback",
      success: function(getLineDetail){
        if(getLineDetail.error_code == 0){
          getLineDetails();
          var name = getLineDetail.data.name;
          var intro = getLineDetail.data.description;
          var time = getLineDetail.data.time;
          var line = getLineDetail.data.line;
          var line_id = getLineDetail.data.id;
          $('#line_name_update').val(name).attr('line_id',line_id);
          $('#line_intro_update').val(intro);
          $('#line_time_update').val(time);
          if(line.length < 1){
            $content = '<div class="route-list clearfix">' +
                '<input type="text" placeholder="纬度" value="" class="latitude">' +
                '<input type="text" placeholder="经度" value="" class="longitude">' +
                '<select class="viewname">' +
                  '<option view_id="000">请选择子景点</option>' +
                '</select>' +
                '<span class="addone"><i class="iconfont">&#xe603;</i></span>' +
            '</div>';
            $('#lines_list_update').append($content);
          }else{
            for(var i=0; i<line.length; i++){
              if(line[i].spots_id == 0){
                $content = '<div class="route-list clearfix">' +
                    '<input type="text" placeholder="纬度" value="' + line[i].latitude + '" class="latitude">' +
                    '<input type="text" placeholder="经度" value="' + line[i].longitude + '" class="longitude">' +
                    '<select class="viewname">' +
                      '<option view_id="0">请选择子景点</option>' +
                    '</select>' +
                    '<span class="addone"><i class="iconfont">&#xe603;</i></span>' +
                    '<span class="removeone"><i class="iconfont">&#xe60a;</i></span>' +
                '</div>';
              }else{
                $content = '<div class="route-list clearfix">' +
                    '<input type="text" placeholder="纬度" value="' + line[i].latitude + '" class="latitude">' +
                    '<input type="text" placeholder="经度" value="' + line[i].longitude + '" class="longitude">' +
                    '<select class="viewname">' +
                      '<option view_id="' + line[i].spots_id + '">' + line[i].name + '</option>' +
                    '</select>' +
                    '<span class="addone"><i class="iconfont">&#xe603;</i></span>' +
                    '<span class="removeone"><i class="iconfont">&#xe60a;</i></span>' +
                '</div>';
              }
              $('#lines_list_update').append($content);
              $('#lines_list_update .route-list').coordinateActive();
            }
          }
        }
      },
      error: function(){
        
      }
    });
  });
  
  //修改线路详细信息
  $('#line-list-main').delegate('#line_update','click',function(){
    var name = $('#line_name_update').val();
    var time = $('#line_time_update').val();
    var intro = $('#line_intro_update').val();
    var line_id = $('#line_name_update').attr('line_id');
    var line = "";
    var lines = "";
    $('#lines_list_update .route-list').each(function(){
        var lineLatitude = $(this).find('.latitude').val();
        var lineLongitude = $(this).find('.longitude').val();
        var lineId = $(this).find('.viewname option:selected').attr('view_id');
        lines += lineLatitude +"," + lineLongitude + "," + lineId + '|';
    });
    line = lines;
    line = line.substr(0,line.length-1);
    console.log(line)
    $.ajax({
      type : "GET",
      dataType: "json",
      async: true,
      cache: true,
      data: {
        id: line_id,
        name: name,
        description: intro,
        time: time,
        line: line,
        language: language
      },
      url: "http://115.29.179.17:8500/api/line/updateLine",
      jsonp: "callback",
      success: function(updateLines){
        alert('修改成功');
      },
      error: function(){
        alert('修改失败');
      }
    });
  });
  
  //线路坐标添加
  $('#lines_list').delegate('.route-list .addone','click',function(){
    $list = '<div class="route-list clearfix">' +
              '<input type="text" placeholder="纬度" value="" class="latitude">' +
              '<input type="text" placeholder="经度" value="" class="longitude">' +
              '<select class="viewname">' +
                '<option view_id="0">请选择子景点</option>' +
              '</select>' +
              '<span class="addone"><i class="iconfont">&#xe603;</i></span>' +
              '<span class="removeone"><i class="iconfont">&#xe60a;</i></span>' +
            '</div>';
    $('#lines_list').append($list);
    $('#lines_list .route-list').coordinateActive();
  });
  
  //线路详情添加坐标
  $('body').delegate('#lines_list_update .route-list .addone','click',function(){
    $list = '<div class="route-list clearfix">' + 
              '<input type="text" placeholder="纬度" value="" class="latitude">' +
              '<input type="text" placeholder="经度" value="" class="longitude">' +
              '<select class="viewname">' +
                '<option view_id="0">请选择子景点</option>' +
              '</select>' +
              '<span class="addone"><i class="iconfont">&#xe603;</i></span>' +
              '<span class="removeone"><i class="iconfont">&#xe60a;</i></span>' +
            '</div>';
    $('#lines_list_update').append($list);
    $('#lines_list_update .route-list').coordinateActive();
  });
  
  //创建子景点添加周围坐标
  $('#child_around_coordinates').delegate('.route-list .addone','click',function(){
    var html = around_coordinates.Html.format('','');
    $('#child_around_coordinates').append(html);
    $('#child_around_coordinates .route-list').coordinateActive();
  });
  
  //子景点详情里添加周围坐标
  $('#childview-list-main').delegate('#child_around_coordinates_update .addone','click',function(){
    var html = around_coordinates.Html.format('','');
    $('#child_around_coordinates_update').append(html);
    $('#child_around_coordinates_update .route-list').coordinateActive();
  });
  
  //移除坐标公用代码
  removeCoordinates();
  //添加坐标公用代码
  addCoordinates();
});