<?php
ini_set('date.timezone','Asia/Shanghai');

ini_set("display_errors", 'on');
error_reporting(E_ALL);


define ( "APP_DIR", dirname ( dirname ( __FILE__ ) ) );
//定义www目录，如/var/gs/www
define ( "WWW_DIR",  dirname ( APP_DIR ) );
//文件缓存目录
define ("FILE_DIR", APP_DIR . '/static/');
//debug开关
defined ( 'YII_DEBUG' ) or define ( 'YII_DEBUG', true );
//加载yii框架
require_once (WWW_DIR . '/thirdlib/yii/framework/yii.php');
//设置新命名空间
Yii::setPathOfAlias ( 'third', WWW_DIR . "/thirdlib" );
//导入命名空间
Yii::import ( "third.*" );
Yii::import ( "third.yiiext.*" );
Yii::import ( "third.util.*" );
Yii::import ( "third.storage.*" );
Yii::import ( "third.ActiveRecord.*" );
//run app
Yii::createWebApplication ( APP_DIR . "/protected/config/main.php" )->run ();
