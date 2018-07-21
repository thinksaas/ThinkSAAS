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


	/* ThinkSAAS软件版权信息
	 * ThinkSAAS
	 * 请尊重ThinkSAAS版权信息，如需去除请购买ThinkSAAS商业授权
	 * 联系QQ:1078700473，微信:thinksaas
	 */
	'info' => array(
		'name' => 'ThinkSAAS',
		'url' => 'https://www.thinksaas.cn/',
		'email' => 'qiujun@thinksaas.cn',
		'qq' => '1078700473',
		'weixin' => 'thinksaas',
		'copyright' => 'ThinkSAAS',
		'copyurl' => 'http://www.thinksaas.cn/',
		'year' => '2012',#创立时间2012年
		'author' => '邱君',
	),
	
);
