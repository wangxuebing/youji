<?php /* Smarty version Smarty-3.0.8, created on 2015-01-22 17:54:46
         compiled from "/Users/wangxuebing/myworks/yitu-cms/cms/trunk/views/default/index/task.tpl" */ ?>
<?php /*%%SmartyHeaderCode:115284533454c0c8e6128420-42495670%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'b42453cf3505210699cdfef6cb1a644b565331a6' => 
    array (
      0 => '/Users/wangxuebing/myworks/yitu-cms/cms/trunk/views/default/index/task.tpl',
      1 => 1421920472,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '115284533454c0c8e6128420-42495670',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
<!DOCTYPE html>
<html lang="zh">
<head>
  <title>我的任务</title>
  <meta charset='utf-8' />
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
  <meta content="width=device-width,user-scalable=no" name="viewport">
  <meta name=“viewport” content=“width=device-width; initial-scale=1.0”>
  <link rel = "Shortcut Icon" href="/images/favicon.ico" /> 
  <link rel="stylesheet" href="/css/common.css" type="text/css" media="screen" title="no title" charset="utf-8">
  <link type="text/css" href="/css/date/jquery-ui-1.8.17.custom.css" rel="stylesheet" />
  <link type="text/css" href="/css/date/jquery-ui-timepicker-addon.css" rel="stylesheet" />
  <link rel="stylesheet" href="/css/tasks.css" type="text/css" media="screen" title="no title" charset="utf-8">
  <script type="text/javascript" src="/js/common/jquery.js"></script>
  <script type="text/javascript" src="/js/common/jquery-ui-1.10.3.min.js"></script>
  <script type="text/javascript" src="/js/common/jquery.cookie.js"></script>
  <script type="text/javascript" src="/js/common/select.js"></script>
  <script type="text/javascript" src="/js/userinfo_cookie.js"></script>
  <script type="text/javascript" src="/js/model/header.js"></script>
	<script type="text/javascript" src="/js/jquery-ui-timepicker-addon.js"></script>
  <script type="text/javascript" src="/js/jquery-ui-timepicker-zh-CN.js"></script>
  <script type="text/javascript" src="/js/tasks.js"></script>
</head>

<body>
  <?php $_template = new Smarty_Internal_Template('./default/layout/header.tpl', $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate(); $_template->rendered_content = null;?><?php unset($_template);?>
  <div class="tasks">
   <nav class="nav clearfix">
     <a href="/index" class="goback border-radius5">回到首页</a>
     <span>我的任务</span>
   </nav>
   <div class="search-warp clearfix">
     <div class="left">
       <div class="search-item clearfix">
         <div class="search-list">
           <span class="text">景点名称：</span>
           <input type="text" class="box"  maxlength="20" id="search_kw"/>
         </div>
         <div class="search-list">
           <span class="text">所在城市：</span>
           <div class="box">
             <select id="s_city" class="select" name="s_city" >
               <option area_id="000000">请选择市</option>
             </select>
           </div>
         </div>
         <div class="search-list">
           <span class="text">开始时间：</span>
           <input type="text" class="box ui_timepicker" id="search_stime"/>
         </div>
       </div>
       <div class="search-item clearfix">
         <div class="search-list">
           <span class="text">所在省份：</span>
           <div class="box">
             <select id="s_province" class="select" name="s_province">
               <option area_id="000000">请选择省</option>
             </select>
           </div>
         </div>
         <div class="search-list">
           <span class="text">状态：</span>
           <div class="box">
             <select class="select" id="search_status">
               <option status="9">请选择状态</option>
               <option status="0">未开始</option>
               <option status="1">进行中</option>
               <option status="2">已完成</option>
               <option status="3">中途停止</option>
             </select>
           </div>
         </div>
         <div class="search-list">
           <span class="text">结束时间：</span>
           <input type="text" class="box ui_timepicker" id="search_etime"/>
         </div>
       </div>
     </div>
     <div class="search-btn">
       <button class="search border-radius5" id="search_tasks">搜索</button>
     </div>
     <div class="right">
       <span class="create border-radius5" id="create">创建任务</span>
     </div>
   </div>
   <div class="list">
     <div class="listheader">
       <span class="view-id">景点ID</span>
       <span class="view-name">景点名称</span>
       <span class="view-province">所在省份</span>
       <span class="view-city">所在城市</span>
       <span class="view-star">开始时间</span>
       <span class="view-stop">结束时间</span>
       <span class="view-status">状态</span>
       <span class="view-active">操作</span>
     </div>
     <div class="items" id="taskslist">
       <ul></ul>
     </div>
   </div>
   <div class="pages" id="task-pages">
     <a id="page-prev"><i class="iconfont">&#xe602;</i></a>
     <a id="page-prev-more" style="display:none;"><i class="iconfont">&#xe60b;</i></a>
     <div class="task-pages" id="page-num"></div>
     <a id="page-next-more" style="display:none;"><i class="iconfont">&#xe60b;</i></a>
     <a id="page-next"><i class="iconfont">&#xe601;</i></a>
   </div>
  </div>
  <div class="dialog" id="create-dialog" style="display:none;">
    <p>景点名称</p>
    <input type="text" name="name" class="dialog-box" maxlength="20" id="spots_name">
    <p>开始时间</p>
    <input type="text" name="start" class="dialog-box ui_timepicker" id="stime">
    <p>结束时间</p>
    <input type="text" name="stop" class="dialog-box ui_timepicker" id="etime">
    <button type="buttom" class="submit-creat" id="create-tasks">创建任务</button>
    <div class="kwlist" id="kwlist">
      <ul></ul>
    </div>
  </div>
  <div class="opacity"></div>
</body>
</html>