/*
 * jQuery File Upload Plugin JS Example 8.9.1
 * https://github.com/blueimp/jQuery-File-Upload
 *
 * Copyright 2010, Sebastian Tschan
 * https://blueimp.net
 *
 * Licensed under the MIT license:
 * http://www.opensource.org/licenses/MIT
 */

/* global $, window */

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

//            alert(md);
//            alert(name);
//            alert(url);
        },
        fail: function(e, result) {
//            alert('failed');
        }
    });
});