<?php /* Smarty version Smarty-3.0.8, created on 2015-01-22 14:45:03
         compiled from "/Users/wangxuebing/myworks/yitu-cms/cms/trunk/views/./default/layout/upload.tpl" */ ?>
<?php /*%%SmartyHeaderCode:103781045554c09c6fa3c7f6-70596425%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '3dd4e893ed0b8da1656b9df99456f32c2647d5f9' => 
    array (
      0 => '/Users/wangxuebing/myworks/yitu-cms/cms/trunk/views/./default/layout/upload.tpl',
      1 => 1421909047,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '103781045554c09c6fa3c7f6-70596425',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
<h1>文件上传</h1>
<form id="fileupload" action="/api/index/uploadFile" method="POST" enctype="multipart/form-data">
  <input type="hidden" name="type" value="" id="files_type"><!--取值image,video,audio,panorama-->
  <input type="hidden" name="spots_type" value="1" id="upload_spots_type"><!--大景点为1，子景点为2-->
  <input type="hidden" name="spots_id" value="" id="spots_id"><!--填入大景点或者子景点的id-->
  <div class="row fileupload-buttonbar">
    <div class="col-lg-7">
        <span class="btn btn-success fileinput-button">
            <span>选择文件</span>
            <input type="file" name="files[]" multiple>
        </span>
        <button type="submit" class="btn btn-primary start">
            <span>开始上传</span>
        </button>
        <button type="reset" class="btn btn-warning cancel">
            <span>取消上传</span>
        </button>
        <button type="button" class="btn btn-danger delete">
            <span>删除全部</span>
        </button>
        <input type="checkbox" class="toggle">
        <span class="fileupload-process"></span>
    </div>
    <div class="col-lg-5 fileupload-progress fade">
        <div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100">
            <div class="progress-bar progress-bar-success" style="width:0%;"></div>
        </div>
        <div class="progress-extended">&nbsp;</div>
    </div>
  </div>
  <div class="upload_list_box">
    <table role="presentation" class="table table-striped">
      <tbody class="files"></tbody>
    </table>
  </div>
</form>
<script id="template-upload" type="text/x-tmpl">
{% for (var i=0, file; file=o.files[i]; i++) { %}
  <tr class="template-upload fade">
    <td>
        <span class="preview"></span>
    </td>
    <td>
        <p class="name">{%=file.name%}</p>
        <strong class="error text-danger"></strong>
    </td>
    <td>
        <p class="size">Processing...</p>
        <div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0"><div class="progress-bar progress-bar-success" style="width:0%;"></div></div>
    </td>
    <td>
        {% if (!i && !o.options.autoUpload) { %}
            <button class="btn btn-primary start" disabled>
                <span>开始</span>
            </button>
        {% } %}
        {% if (!i) { %}
            <button class="btn btn-warning cancel">
                <span>取消</span>
            </button>
        {% } %}
        <button class="btn btn-warning delete">
            <span>移除</span>
        </button>
    </td>
  </tr>
{% } %}
</script>
<script id="template-download" type="text/x-tmpl">
{% for (var i=0, file; file=o.files[i]; i++) { %}
    <tr class="template-download fade">
        <td>
            <span class="preview">
                {% if (file.thumbnailUrl) { %}
                    <a href="{%=file.url%}" title="{%=file.name%}" download="{%=file.name%}" data-gallery><img src="{%=file.thumbnailUrl%}"></a>
                {% } %}
            </span>
        </td>
        <td>
            <p class="name">
                {% if (file.url) { %}
                    <a href="{%=file.url%}" title="{%=file.name%}" download="{%=file.name%}" {%=file.thumbnailUrl?'data-gallery':''%}>{%=file.name%}</a>
                {% } else { %}
                    <span>{%=file.name%}</span>
                {% } %}
            </p>
            {% if (file.error) { %}
                <div><span class="label label-danger">Error</span> {%=file.error%}</div>
            {% } %}
        </td>
        <td>
            <span class="size">{%=o.formatFileSize(file.size)%}</span>
        </td>
        <td>
            {% if (file.deleteUrl) { %}
                <button class="btn btn-danger delete" data-type="{%=file.deleteType%}" data-url="{%=file.deleteUrl%}"{% if (file.deleteWithCredentials) { %} data-xhr-fields='{"withCredentials":true}'{% } %}>
                    <i class="glyphicon glyphicon-trash"></i>
                    <span>Delete</span>
                </button>
                <input type="checkbox" name="delete" value="1" class="toggle">
            {% } else { %}
                <button class="btn btn-warning cancel">
                    <i class="glyphicon glyphicon-ban-circle"></i>
                    <span>Cancel</span>
                </button>
            {% } %}
        </td>
    </tr>
{% } %}
</script>