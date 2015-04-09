<?php /* Smarty version Smarty-3.0.8, created on 2015-01-19 14:36:09
         compiled from "/Users/wangxuebing/myworks/yitu-cms/cms/trunk/views/default/index/login.tpl" */ ?>
<?php /*%%SmartyHeaderCode:156700821154bca5d9b555f4-22811379%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '29fa26ddf843c7db477b82542113dfde77334141' => 
    array (
      0 => '/Users/wangxuebing/myworks/yitu-cms/cms/trunk/views/default/index/login.tpl',
      1 => 1421649366,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '156700821154bca5d9b555f4-22811379',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
<!DOCTYPE html>
<html lang="zh">
<head>
  <title>易途CMS后台登录</title>
  <meta charset='utf-8' />
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
  <meta content="width=device-width,user-scalable=no" name="viewport">
  <meta name=“viewport” content=“width=device-width; initial-scale=1.0”>
  <link rel = "Shortcut Icon" href="/images/favicon.ico" /> 
  <link rel="stylesheet" href="/css/common.css" type="text/css" media="screen" title="no title" charset="utf-8">
  <link rel="stylesheet" href="/css/login.css" type="text/css" media="screen" title="no title" charset="utf-8">
  <script type="text/javascript" src="/js/common/jquery.js"></script>
  <script type="text/javascript" src="/js/common/jquery.cookie.js"></script>
  <script type="text/javascript" src="/js/common/myplugin.js"></script>
  <script type="text/javascript" src="/js/userinfo_cookie.js"></script>
  <script type="text/javascript" src="/js/login.js"></script>
  <script type="text/javascript" src="/js/model/header.js"></script>
</head>

<body>
  <?php $_template = new Smarty_Internal_Template('./default/layout/header.tpl', $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate(); $_template->rendered_content = null;?><?php unset($_template);?>
  <center class="login">
    <header class="title">易途CMS后台登录</header>
    <div name="login-form" class="login-form" id="login-form">
      <div class="textbox"><span>账号：</span><input type="text" placeholder="账号" name="username" id="username" /></div>
      <div class="textbox"><span>密码：</span><input type="password" placeholder="密码" name="password" id="password" /></div>
      <div class="loginbutton"><div class="formerror"></div><button type="submit" class="loginbutton" id="loginbutton">登录</button></div>
    </div>
  </center>
</body>
</html>