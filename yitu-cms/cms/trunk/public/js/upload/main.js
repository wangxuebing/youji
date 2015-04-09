$(function () {
    'use strict';

    // Initialize the jQuery File Upload widget:
    $('#fileupload').fileupload({
        // Uncomment the following to send cross-domain cookies:
        //xhrFields: {withCredentials: true},
        // url: '/api/index/uploadFile',
        url: 'http://115.29.179.17:8500/api/index/uploadFile',
        maxFileSize: 30000000, //30M
        acceptFileTypes: /(\.|\/)(gif|jpeg|png|wmv|mp3)$/i,
        send: function(e, result) {
 //           alert('before upload');
        },
        done: function(e, result) {
//            console.dir(result.result);
            var data = result.result.data;
            var md = data['md'];
            var name = data['name'];
            var url = data['url'];
            if(upload_type == 'changeimg'){
              uploadObj.empty();
              uploadObj.append('<div class="images newupload" md="' + md + '"><img src="' + url + '" width="235" /><span class="deleteimg"><i class="iconfont">&#xe607;</i></span></div>');
            }else if(upload_type == 'addimg'){
              uploadObj.append('<div class="images newupload" md="' + md + '"><img src="' + url + '" width="235" /><span class="deleteimg"><i class="iconfont">&#xe607;</i></span></div>');
            }else if(upload_type == 'addaudio'){
              uploadObj.append('<div class="addlist newupload" md="' + md + '"><span class="list-title">' + name + '</span><span class="remove-list"><i class="iconfont">&#xe604;</i></span></div>');
            }else{
              uploadObj.append('<div class="addlist newupload" md="' + md + '"><span class="list-title">' + name + '</span><span class="remove-list"><i class="iconfont">&#xe604;</i></span></div>');
            }
            $('.progress-striped[aria-valuenow="100"]').each(function(){
              $(this).parents('td').parents('.template-upload').remove();
            });
            if($('.table-striped .files tr').length < 1){
              $('#upload_box,.opacity').hide();
              $('#loadfiles').empty();
            }

//            alert(md);
//            alert(name);
           // alert(url);
        },
        fail: function(e, result) {
           alert('failed');
        }
    });
});