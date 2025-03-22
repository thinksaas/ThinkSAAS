<?php
//环境配置文件
return array(

    //redis配置
    'redis'=>array(
        'tcp'=>'tcp://127.0.0.1:6379',
        'host'=>'127.0.0.1',
        'port'=>'6379',
    ),

	//Memcache配置
	'memcache' => array(
		//'host' => '127.0.0.1',
		//'port' => 11211,
	),

	//是否开启debug，正式环境下请关闭
	'debug' => true,

	//是否开启显示插件钩子
	'hook' => false,

	//session存取方式，默认位本地存储，支持redis存储
	'session' => '',

	//对象-关系映射(数据库操作，查询构造器，模型等。php环境低于7.1切勿开启)
	'orm'=>false,

	//存储session文件在cache/sessions/目录下，如果IIS环境出现登陆后无法退出请将sessionpath前注释去掉，启用自定义session存储目录
	//'sessionpath'=>'sessions',

	//是否开启系统日志记录功能，日志存放在根目录下tslogs目录下
	'logs' => false,

    //是否开启mysql慢sql语句记录功能，日志存放在根目录下tslogs目录下
    'slowsqllogs'=>0,   //默认为0为不开启，例如：写0.5为执行时间大于0.5秒的，写1为执行时间大于1秒的

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


	//feed模板数据
	'feed'=>array(
		'user_register'=>'',
		'user_follow'=>'',

		'group_create'=>'',
		'group_topic_add'=>'',
		'group_topic_comment'=>'',

		'weibo_add'=>'',
		'weibo_comment'=>'',

		'article_add'=>'',
		'article_comment'=>'',
	),

    //域名锁定，防止网站被恶意缓存仿站。例如我的网址是http://www.thinksaas.cn/,只要输入www.thinksaas.cn即可
    'urllock'=>'',

	//系统信息【请勿修改】
	//系统名称，系统网址，系统邮箱，系统QQ，微信号，版权信息，版权网址，创立时间，作者
	'info' => array(
		'name' => 'ThinkSAAS',
		'url' => 'https://www.thinksaas.cn/',
		'email' => 'qiniao@thinksaas.cn',
		'qq' => '1078700473',
		'weixin' => 'thinksaas',
		'copyright' => 'ThinkSAAS',
		'copyurl' => 'http://www.thinksaas.cn/',
		'year' => '2012',#创立时间2012年
		'author' => 'qiniao',
	),
	
);
