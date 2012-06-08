<?php 
//环境配置文件
return array(
	
	//Memcache配置
	'memcache'=>array(
		//'host' => '127.0.0.1',
		//'port' => 11211,
	),
	
	'hook'=>false, // 是否开启显示插件钩子
	
	'session'=>false,   //是否开启数据库存取session  暂时还有点问题预留
	
	//数据库配置
	'db'=>array(
		'sql'=>'mysql',
		'host'=>'localhost',
		'port'=>'3306',
		'user'=>'root',
		'pwd'=>'',
		'name'=>'thinksaas',
		'pre'=>'ts_',
	),
	
	//软件信息
	'info'=>array(
		'name'	=>	'ThinkSAAS',
		'version'	=>	'1.8',
		'url'		=>	'http://www.thinksaas.cn/',
		'email'	=>	'thinksaas@qq.com',
		'copyright' =>	'ThinkSAAS',
		'year'		=>	'2011 - 2012',
		'author'	=>	'QiuJun',
	),
	
);