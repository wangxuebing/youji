<?php
return array (
    //模板目录
    'viewPath' => APP_DIR . "/views/", 
    //protected根目录 
    'basePath' => APP_DIR . '/protected/', 
    //应用名
    'name' => 'management', 
    //运行时目录，主要用于模板的编译
	'runtimePath' => APP_DIR . '/runtime/',
    //预先import的命名空间
    'import' => array (
        'application.lib.models.*',  //orm model
        'application.lib.services.*',  //service layer
        'application.lib.classes.*',  //other lib class
    ),
    //模块配置，各部分的默认值为：module=default,controller=index,action=index
    'modules' => array (
	    'default',
	    'api',
        'gii'=>array(
            'class'=>'system.gii.GiiModule',
            'password'=>'1',
            // If removed, Gii defaults to localhost only. Edit carefully to taste.
            'ipFilters'=>array('*'),
            'generatorPaths' => array(
                'bootstrap.gii'
            ),
        ),
    ), 
    //组建配置
    'components' => array (
        'bootstrap' => array(
            'class' => 'ext.bootstrap.components.Bootstrap',
        ),
        //URLRewrite组件，根据需要进行配置
        'urlManager' => array (
            'urlFormat' => 'path',
            'showScriptName' => false,
            'rules' => array (
                'gii'=>'gii/default/index',
                'gii/<controller:\w+>'=>'gii/<controller>',
                'gii/<controller:\w+>/<action:\w+>'=>'gii/<controller>/<action>',

                '' => 'default/index/index', 
                '<module:\w+>/<controller:\w+>/<action:\w+>' => '<module>/<controller>/<action>', 
                '<controller:\w+>/<action:\w+>' => 'default/<controller>/<action>', 
                '<action:\w+>' => 'default/index/<action>' 
            ),
           'baseUrl' => 'http://yitu.com' 
        ), 
        //模板渲染组件，这里统一采用smarty引擎
        'viewRenderer' => array (
            //支持smarty引擎的插件
            'class' => 'CommonSmartyView',
            //模板后缀名
            'fileExtension' => '.tpl',
            //这里为Smarty支持的属性
            'config' => array (
                'left_delimiter' => "<{",
                'right_delimiter' => "}>",
                'template_dir' => APP_DIR . "/views/",
                'debugging' => false
            )
        ),
        //正常的log组件
        'log' => array (
            'class' => 'CLogRouter', 
            'routes' => array (
                array (
                    'class' => 'CFileLogRoute', 
                    'levels' => 'info,trace,debug', 
					'logPath' => APP_DIR . '/logs/',
                    'logFile' => 'yii_cms_access.log', 
                    'maxFileSize' => 2097152 
                ), 
                array (
                    'class' => 'CFileLogRoute', 
                    'levels' => 'error,warning', 
					'logPath' => APP_DIR . '/logs/',
                    'logFile' => 'yii_cms_error.log', 
                    'maxFileSize' => 2097152 
                ),
            ) 
        ), 
        'c' =>array(
            'class' => 'CommonConfig',
            'interface_base_url' => 'http://115.29.179.17/',
            'base_url' => 'http://115.29.179.17:8500/',
            'user_cache_expire' => '86400',
            'storage_path' => '/data1/storage_test',
            'cache_server' => array(
                array("ip"=>"127.0.0.1","port"=>"11211"),
            ),
            'data_cache_server' => array(
                array("ip"=>"127.0.0.1","port"=>"11211"),
            ),
            'generate_download_url' => 'http://115.29.179.17/api/spots/spotsDetailDownload',
            'resource_map_type' => array(1=>'image', 2=>'video', 3=>'audio', 4=>'doc', 5=>'compress', 7=>'panorama'),
            'spots' => require(dirname(__FILE__) . '/spots.php'),
        ),
        //数据库组件
        'db'=>array(
            'class'=>'CommonDbConnection',
            'charset'=>'utf8',
            'enableProfiling'=>false,
            'schemaCachingDuration'=>3600,
            'servers'=>array(
                array(
                    'connectionString'=>'mysql:host=115.29.179.17;dbname=yitu_test',
                    'emulatePrepare'=>true,
                    'username'=>'root',
                    'password'=>'123456',
                ),
                array(
                    'connectionString'=>'mysql:host=115.29.179.17;dbname=yitu_test',
                    'emulatePrepare'=>true,
                    'username'=>'root',
                    'password'=>'123456',
                ),
            ),
        ),
        'db_cms'=>array(
            'class'=>'CommonDbConnection',
            'charset'=>'utf8',
            'enableProfiling'=>false,
            'schemaCachingDuration'=>3600,
            'servers'=>array(
                array(
                    'connectionString'=>'mysql:host=115.29.179.17;dbname=cms',
                    'emulatePrepare'=>true,
                    'username'=>'root',
                    'password'=>'123456',
                ),
                array(
                    'connectionString'=>'mysql:host=115.29.179.17;dbname=cms',
                    'emulatePrepare'=>true,
                    'username'=>'root',
                    'password'=>'123456',
                ),
            ),
        ),
        'db_resource'=>array(
            'class'=>'CommonDbConnection',
            'charset'=>'utf8',
            'enableProfiling'=>false,
            'schemaCachingDuration'=>3600,
            'servers'=>array(
                array(
                    'connectionString'=>'mysql:host=localhost;dbname=resource_test',
                    'emulatePrepare'=>true,
                    'username'=>'root',
                    'password'=>'123456',
                ),
                array(
                    'connectionString'=>'mysql:host=localhost;dbname=resource_test',
                    'emulatePrepare'=>true,
                    'username'=>'root',
                    'password'=>'123456',
                ),
            ),
        ),
        'db_system'=>array(
            'class'=>'CommonDbConnection',
            'charset'=>'utf8',
            'enableProfiling'=>false,
            'schemaCachingDuration'=>3600,
            'servers'=>array(
                array(
                    'connectionString'=>'mysql:host=localhost;dbname=system',
                    'emulatePrepare'=>true,
                    'username'=>'root',
                    'password'=>'123456',
                ),
                array(
                    'connectionString'=>'mysql:host=localhost;dbname=system',
                    'emulatePrepare'=>true,
                    'username'=>'root',
                    'password'=>'123456',
                ),
            ),
        ),
    ), 
    //预先加载log组建
    'preload' => array (
        'log',
    ) 
); 
