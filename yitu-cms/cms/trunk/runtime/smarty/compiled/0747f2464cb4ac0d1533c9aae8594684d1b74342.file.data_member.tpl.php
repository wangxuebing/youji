<?php /* Smarty version Smarty-3.0.8, created on 2015-03-05 16:14:57
         compiled from "/Users/wangxuebing/myworks/yitu-cms/cms/trunk/views/default/index/data_member.tpl" */ ?>
<?php /*%%SmartyHeaderCode:107445598554f81081a47a81-71794528%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '0747f2464cb4ac0d1533c9aae8594684d1b74342' => 
    array (
      0 => '/Users/wangxuebing/myworks/yitu-cms/cms/trunk/views/default/index/data_member.tpl',
      1 => 1425543294,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '107445598554f81081a47a81-71794528',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
<!DOCTYPE html>
<html lang="zh">
<head>
  <title>编辑景点</title>
  <meta charset='utf-8' />
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
  <meta content="width=device-width,user-scalable=no" name="viewport">
  <meta name=“viewport” content=“width=device-width; initial-scale=1.0”>
  <link rel = "Shortcut Icon" href="/images/favicon.ico" /> 
  <link rel="stylesheet" href="/css/common.css" type="text/css" media="screen" title="no title" charset="utf-8">
  <link rel="stylesheet" href="/css/editor.css" type="text/css" media="screen" title="no title" charset="utf-8">
  <link rel="stylesheet" href="/css/review.css" type="text/css" media="screen" title="no title" charset="utf-8">
  <link rel="stylesheet" href="/css/upload/upload.css" type="text/css" media="screen" title="no title" charset="utf-8">
  <script type="text/javascript" src="/js/common/jquery.js"></script>
  <script type="text/javascript" src="/js/common/jquery-ui-1.10.3.min.js"></script>
  <script type="text/javascript" src="/js/common/jquery.cookie.js"></script>  
  <script type="text/javascript" src="/js/common/myplugin.js"></script>
  <script type="text/javascript" src="/js/common/select.js"></script>
  <script type="text/javascript" src="/js/userinfo_cookie.js"></script>
  <script type="text/javascript" src="/js/model/header.js"></script>
  <script type="text/javascript" src="/js/model/editor.js"></script>
  <script type="text/javascript" src="/js/editor.js"></script>
  <script type="text/javascript" src="/js/upload/upload.js"></script>
  <script type="text/javascript" src="/js/model/review.js"></script>
  <script type="text/javascript" src="/js/review.js"></script>
</head>

<body>
  <?php $_template = new Smarty_Internal_Template('./default/layout/header.tpl', $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate(); $_template->rendered_content = null;?><?php unset($_template);?>
  <div class="editor">
    <nav class="nav clearfix">
      <a href="/task" class="goback border-radius5">我的任务</a>
      <span>录入景点详情</span>
    </nav>
    <div class="content clearfix">
      <div class="menu">
        <div class="menulist active" id="baseinfo">基本信息</div>
        <div class="menulist normal" id="childview">子景点<i class="iconfont">&#xe603;</i></div>
        <div class="secend-menu" id="childview-list">
          <ul id="sortable"></ul>
        </div>
        <div class="menulist normal" id="appendix">附录<i class="iconfont">&#xe603;</i></div>
        <div class="secend-menu" id="appendix-list">
          <ul id="sortable2"></ul>
        </div>
        <div class="menulist normal" id="route">徒步路线<i class="iconfont">&#xe603;</i></div>
        <div class="secend-menu" id="line-list"></div>
      </div>
      <div class="tab">
        <div class="tablist clearfix" id="baseinfo-main">
          <h2 class="title">基本信息</h2>
          <div class="base-info">
            <div class="info">
              <div class="info-list">
                <p>景点名称</p>
                <input type="text" id="spot-name" disabled="disabled">
                <p><span>注：景点名称不可修改</span></p>
              </div>
            </div>
            <div class="info">
              <div class="info-list">
                <p>景点拼音</p>
                <input type="text" maxlength="50" id="spot-pinyin">
                <p><span>注：景点拼音只能填写英文字母，下划线，空格和横线，最多50个字符，请切换到英文输入法进行输入</span></p>
              </div>
            </div>
            <div class="info">
             <div class="info-list">
                <p>电话</p>
                <input type="text" maxlength="20" id="spot-telephone">
                <p><span>注：电话只能填写数字和横线，最多20个字符</span></p>
              </div>
            </div>
            <div class="info">
              <div class="info-list">
                <p>地址</p>
                <input type="text" maxlength="500" id="spot-address">
                <p><span>注：必填，最多500个字</span></p>
              </div>
            </div>
            <div class="info">
              <p>票价</p>
              <textarea onkeyup="this.value = this.value.slice(0, 500)" id="spot-ticket-price"></textarea>
              <p><span>注：必填，最多500个字</span></p>
            </div>
            <div class="info">
              <p>开放时间</p>
              <textarea onkeyup="this.value = this.value.slice(0, 500)" id="spot-open-time"></textarea>
              <p><span>注：必填，最多500个字</span></p>
            </div>
            <div class="info">
              <p>所在地区</p>
              <div class="selectbox">
                <div class="select">
                  <select id="s_province" name="s_province">
                    <option>请选择省</option>
                  </select> 
                </div>
                <div class="select">
                  <select id="s_city" name="s_city" >
                    <option>请选择市</option>
                  </select>
                </div>
                <div class="select">
                  <select id="s_county" name="s_county">
                    <option>请选择区/县</option>
                  </select>
                </div>
              </div>
            </div>
            <div class="info">
              <p>中心坐标</p>
              <div class="route-list clearfix">
                <input type="text" placeholder="纬度" value="" class="latitude" id="latitude">
                <input type="text" placeholder="经度" value="" class="longitude" id="longitude">
              </div>
            </div>
            <div class="info">
              <p>周围坐标</p>
              <div id="around_coordinates" class="coordinates"></div>
            </div>
            <div class="info">
              <p>封面图</p>
              <div class="upload-box clearfix">
                <button id="upload_cover">上传图片</button>
              </div>
              <div class="imglist clearfix" id="face"></div>
            </div>
            <div class="info">
              <p>子详情页通用图<span>（宽高比为，图片宽度最小为1280px）</span></p>
              <div class="upload-box clearfix">
                <button id="upload_content_face">上传图片</button>
              </div>
              <div class="imglist clearfix" id="content_face"></div>
            </div>
            <div class="info">
              <p>景点图片</p>
              <div class="upload-box clearfix">
                <button id="upload_images">上传图片</button>
              </div>
              <div class="imglist clearfix" id="spots_images"></div>
            </div>
            <div class="info">
              <p>景点全景图</p>
              <div class="upload-box clearfix">
                <button id="upload_panorama">上传全景图</button>
              </div>
              <div class="imglist clearfix" id="panorama_images"></div>
            </div>
            <div class="info">
              <p>景点视频</p>
              <div class="upload-box clearfix">
                <button id="upload_video">上传视频</button>
              </div>
              <div class="video-list" id="video_list"></div>
            </div>
            <div class="info">
              <p>景点语音</p>
              <div class="upload-box clearfix">
                <button id="upload_audio">上传语音</button>
              </div>
              <div class="video-list" id="audio_list"></div>
            </div>
            <div class="info">
              <button class="recovery" id="baseinfo_update">保存</button>
            </div>
          </div>
        </div>
        <div class="tablist clearfix" id="childview-main" style="display:none;">
          <h2 class="title">子景点</h2>
          <div class="info top15">
            <div class="info-main">
              <p>请输入子景点名称</p>
              <div class="viewnamebox clearfix">
                <input type="text" class="inputbox" id="childview-name">
                <select class="viewfind" id="subspots">
                  <option type_id="0">请选择子景点类型</option>
                </select>
              </div>
            </div>
          </div>
          <div class="info">
            <p>中心坐标</p>
            <div class="route-list clearfix">
              <input type="text" placeholder="纬度" value="" class="latitude" id="child-latitude">
              <input type="text" placeholder="经度" value="" class="longitude" id="child-longitude">
            </div>
          </div>
          <div class="info">
            <p>周围坐标</p>
            <div id="child_around_coordinates" class="coordinates">
              <div class="route-list clearfix">
                <input type="text" placeholder="纬度" value="" class="latitude">
                <input type="text" placeholder="经度" value="" class="longitude">
                <span class="addone"><i class="iconfont">&#xe603;</i></span>
                <span class="removeone" style="display:none;"><i class="iconfont">&#xe60a;</i></span>
              </div>
            </div>
          </div>
          <div class="info">
            <p>标题</p>
            <input type="text" class="inputbox" id="childview-title">
          </div>
          <div class="info">
            <p>选择语音</p>
            <div class="upload-box clearfix">
              <button class="keep add-sounds" id="childview-sounds">插入语音</button>
            </div>
            <div class="audio-list clearfix" id="childview-sounds_list"></div>
          </div>
          <div class="info">
            <p>插入子详情页首图</p>
            <div class="upload-box clearfix">
              <button class="keep add-face" id="childview-face">插入图片</button>
            </div>
            <div class="imglist clearfix" id="childview_face_list"></div>
          </div>
          <div class="info">
            <div class="add-info">
              <div class="button-box">
                <div class="add-source">
                  <button class="add-images"><i class="iconfont">&#xe603;</i>插入图片</button>
                  <div class="add-way">
                    <span id="childview-upload-image">从本地上传</span>
                    <span id="childview-box-image">从图库选择</span>
                  </div>
                </div>
                <div class="add-source">
                  <button class="add-images"><i class="iconfont">&#xe603;</i>插入全景图</button>
                  <div class="add-way">
                    <span id="childview-upload-panorama">从本地上传</span>
                    <span id="childview-box-panorama">从图库选择</span>
                  </div>
                </div>
                <button id="add_news"><i class="iconfont">&#xe603;</i>插入趣闻</button>
                <button class="add-text" id="childview-text"><i class="iconfont">&#xe603;</i>插入文字</button>
              </div>
              <div class="addinfo-main" id="childview-box">
                <div class="childview-info viewlist clearfix" import_flag="0" id="childview-content"></div>
              </div>
            </div>
          </div>
          <div class="info">
            <button class="keep" id="childview-create">保存</button>
          </div>
        </div>
        <div class="tablist clearfix" id="appendix-main" style="display:none;">
          <h2 class="title">添加附录</h2>
          <div class="info top15">
            <div class="info-main">
              <p>请输入附录名称</p>
              <div class="viewnamebox clearfix">
                <input type="text" class="inputbox" id="appendix_title">
                <select class="viewfind" id="appendix_kind">
                  <option>请选择附录类型</option>
                </select>
              </div>
            </div>
          </div>
          <div class="info">
            <p>插入子详情页首图</p>
            <div class="upload-box clearfix">
              <button class="keep add-face" id="appendix-face">插入图片</button>
            </div>
            <div class="imglist clearfix" id="appendix_face_list"></div>
          </div>
          <div class="info">
            <div class="add-info">
              <div class="button-box">
                <div class="add-source">
                  <button class="add-images"><i class="iconfont">&#xe603;</i>插入图片</button>
                  <div class="add-way">
                    <span id="appendix-upload">从本地上传</span>
                    <span id="appendix-box-image">从图库选择</span>
                  </div>
                </div>
                <button class="add-text" id="appendix-text"><i class="iconfont">&#xe603;</i>插入文字</button>
              </div>
              <div class="addinfo-main" id="appendix-box"></div>
            </div>
          </div>
          <div class="info">
            <button class="keep" id="appendix_create">保存</button>
          </div>
        </div>
        <div class="tablist clearfix" id="route-main" style="display:none;">
          <h2 class="title">徒步路线</h2>
          <div class="info top15">
            <p>线路名称</p>
            <input type="text" class="introbox" id="line_name" maxlength="30">
          </div>
          <div class="info">
            <p>线路说明</p>
            <textarea onkeyup="this.value = this.value.slice(0, 500)" id="line_intro"></textarea>
          </div>
          <div class="info">
            <p>游览时间</p>
            <input type="text" class="introbox" id="line_time" maxlength="10">
          </div>
          <div class="info">
            <p>路线坐标</p>
            <div id="lines_list" class="coordinates">
              <div class="route-list clearfix">
                <input type="text" placeholder="纬度" value="" class="latitude">
                <input type="text" placeholder="经度" value="" class="longitude">
                <select class="viewname">
                  <option view_id="0">请选择子景点</option>
                </select>
                <span class="addone"><i class="iconfont">&#xe603;</i></span>
                <span class="removeone" style="display:none;"><i class="iconfont">&#xe60a;</i></span>
              </div>
            </div>
          </div>
          <div class="info top15">
            <button class="keep" id="line_create">保存</button>
          </div>
        </div>
        <div class="tablist clearfix" id="appendix-list-main" style="display:none;"></div>
        <div class="tablist clearfix" id="childview-list-main" style="display:none;"></div>
        <div class="tablist clearfix" id="line-list-main" style="display:none;"></div>
      </div>
    </div>
  </div>
  <div class="opacity"></div>
  <div class="dialog" id="source-dialog" style="display:none;">
    <div class="dialog-main" id="dialog-main"></div>
    <div class="dialog-footer">
      <div class="imgnum"></div>
      <div class="dialog-button">
        <button class="queding">确定</button>
        <button class="close_dialog">取消</button>
      </div>
    </div>
  </div>
  <div class="review-box"></div>
  <div id="upload_box" class="upload_box">
    <span class="hide_upload iconfont">&#xe60d;</span>
    <span class="close_upload iconfont">&#xe600;</span>
    <?php $_template = new Smarty_Internal_Template('./default/layout/upload.tpl', $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate(); $_template->rendered_content = null;?><?php unset($_template);?>
    <div id="loadfiles"></div>
  </div>
  <div class="uoload_status">
    <div class="status_main">
      <span class="status_text"></span>
      <span class="show_upload iconfont">&#xe60e;</span>
      <span class="close_upload iconfont">&#xe600;</span>
    </div>
  </div>
</body>
</html>