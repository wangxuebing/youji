<?php /* Smarty version Smarty-3.0.8, created on 2014-12-28 17:55:20
         compiled from "/Users/wangxuebing/myworks/yitu-cms/cms/trunk/views/default/index/index.tpl" */ ?>
<?php /*%%SmartyHeaderCode:445900315549fd38870bc74-17741835%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '49b2c1efecf556a2e1355081c43de4b67a92bf7a' => 
    array (
      0 => '/Users/wangxuebing/myworks/yitu-cms/cms/trunk/views/default/index/index.tpl',
      1 => 1419751500,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '445900315549fd38870bc74-17741835',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
<!DOCTYPE html>
<html lang="zh">
<head>
  <title>首页</title>
  <meta charset='utf-8' />
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
  <meta content="width=device-width,user-scalable=no" name="viewport">
  <meta name=“viewport” content=“width=device-width; initial-scale=1.0”>
  <link rel = "Shortcut Icon" href="/images/favicon.ico" /> 
  <link rel="stylesheet" href="/css/common.css" type="text/css" media="screen" title="no title" charset="utf-8">
  <link rel="stylesheet" href="/css/index.css" type="text/css" media="screen" title="no title" charset="utf-8">
  <script type="text/javascript" src="/js/common/jquery.js"></script>
  <script type="text/javascript" src="/js/common/jquery.cookie.js"></script>
  <script type="text/javascript" src="/js/userinfo_cookie.js"></script>
  <script type="text/javascript" src="/js/model/header.js"></script>
  <script type="text/javascript" src="/js/index.js"></script>
</head>

<body>
  <?php $_template = new Smarty_Internal_Template('./default/layout/header.tpl', $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate(); $_template->rendered_content = null;?><?php unset($_template);?>
  <div class="welcome">
    <p class="text"><span class="welcome-username">username</span>,欢迎使用易途CMS后台。</p>
    <div class="entrance">
      <a href="/task">数据录入</a>
    </div>
  </div>
</body>
</html>