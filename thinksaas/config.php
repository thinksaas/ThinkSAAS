<?php
//环境配置文件
return array(

	//Memcache配置
	'memcache' => array(
		//'host' => '127.0.0.1',
		//'port' => 11211,
	),

	//是否开启debug，正式环境下请关闭
	'debug' => true,

	//是否开启显示插件钩子
	'hook' => false,

	//是否开启数据库存取session  暂时还有点问题预留
	'session' => false,

	//存储session文件在cache/sessions/目录下，如果IIS环境出现登陆后无法退出请将sessionpath前注释去掉，启用自定义session存储目录
	//'sessionpath'=>'sessions',

	//是否开启系统日志记录功能，日志存放在根目录下tslogs目录下
	'logs' => false,

	//是否支持app二级域名访问，比如小组group支持group.thinksaas.cn域名访问
	//不开启请留空数组，开启写域名，比如thinksaas.cn
	'subdomain' => array(
		//'domain'=>'thinkpaas.com', //域名
		//'app'=>array('group','user'), //开启子域的APP
	),

	//APP独立域名支持
	'appdomain' => array(//'photo'=>'www.thinkphotos.com', //www.thinkphotos.com
	),

	//缓存图片和附件等支持其他域名访问，首先请将需要绑定的域名解析到程序目录
	//'fileurl'=>'',
	'fileurl' => array(
		//'url'=>'cache.thinksaas.cn',
		//'dir'=>array('cache','uploadfile','public'),
	),

	/* 软件信息
	 * 奇鸟软件（北京）有限公司
	 * 请尊重ThinkSAAS版权信息，如需去除请购买ThinkSAAS商业授权
	 * 联系QQ:1078700473
	 */
	'info' => array(
		'name' => 'ThinkSAAS', 
		'version' => '2.4',
		'url' => 'http://www.thinksaas.cn/', 
		'email' => 'jun.qiu@thinksaas.cn', 
		'powered' => 'Powered by ThinkSAAS', 
		'copyright' => '奇鸟软件（北京）有限公司', 
		'copyurl' => 'http://www.qiniao.com/', 
		'year' => '2011 - 2015', 
		'author' => '邱君',
	),
	
);
