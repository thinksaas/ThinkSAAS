<?php
defined('IN_TS') or die('Access Denied.'); 
require_once("API/qqConnectAPI.php");

$qc = new QC();

$access_token = $qc->qq_callback();
$openid = $qc->get_openid();

$qc = new QC($access_token,$openid);

//$qc->get_user_info();

////////////////////////////////////////////////////////////////////////

/*ThinkSAAS开始*/
if($openid){
	$strOpen = $new['pubs']->find('user_open',array(
		'sitename'=>'qq',
		'openid'=>$openid,
	));
	
	$new['pubs']->update('user_open',array(
		'sitename'=>'qq',
		'openid'=>$openid,
	),array(
		'access_token'=>$access_token,
		'uptime'=>time(),
	));

	if($strOpen['userid']){
		
		$userData = $new['pubs']->find('user_info',array(
			'userid'=>$strOpen['userid'],
		),'userid,username,path,face,isadmin,signin,uptime');
		
		//更新登录时间
		$new['pubs']->update('user_info',array(
			'userid'=>$strOpen['userid'],
		),array(
			'ip'=>getIp(),  //更新登录ip
			'uptime'=>time(),   //更新登录时间
		));
		
		$_SESSION['tsuser']	= $userData;
		header("Location: ".SITE_URL);
		exit;
		
	}else{
	
		//获取用户基本资料
		$arrUserInfo = $qc->get_user_info();
		//$arrWeiboInfo = $qc->get_info();
		//print_r($arrWeiboInfo);exit;
		
		/*	
		Array
		(
			[ret] => 0
			[msg] => 
			[nickname] => 我就是我
			[gender] => 男
			[figureurl] => http://qzapp.qlogo.cn/qzapp/205607/30CA6A53A2580AAD3299CB874EEED345/30
			[figureurl_1] => http://qzapp.qlogo.cn/qzapp/205607/30CA6A53A2580AAD3299CB874EEED345/50
			[figureurl_2] => http://qzapp.qlogo.cn/qzapp/205607/30CA6A53A2580AAD3299CB874EEED345/100
			[figureurl_qq_1] => http://q.qlogo.cn/qqapp/205607/30CA6A53A2580AAD3299CB874EEED345/40
			[figureurl_qq_2] => http://q.qlogo.cn/qqapp/205607/30CA6A53A2580AAD3299CB874EEED345/100
			[is_yellow_vip] => 0
			[vip] => 0
			[yellow_vip_level] => 0
			[level] => 0
			[is_yellow_year_vip] => 0
		)
		*/
/*		
Array ( [data] => Array ( [birth_day] => 1 [birth_month] => 5 [birth_year] => 2011 [city_code] => 8 [comp] => Array ( [0] => Array ( [begin_year] => 2011 [company_name] => 方正 [department_name] => [end_year] => 9999 [id] => 24047 ) [1] => Array ( [begin_year] => 2007 [company_name] => 网上车市 [department_name] => [end_year] => 2008 [id] => 24048 ) [2] => Array ( [begin_year] => 2008 [company_name] => 网际快车 [department_name] => [end_year] => 2009 [id] => 24049 ) [3] => Array ( [begin_year] => 2010 [company_name] => 旅人网 [department_name] => [end_year] => 2011 [id] => 24050 ) [4] => Array ( [begin_year] => 2012 [company_name] => ThinkSAAS [department_name] => [end_year] => 9999 [id] => 24051 ) ) [country_code] => 1 [edu] => Array ( [0] => Array ( [departmentid] => 0 [id] => 24037 [level] => 1 [schoolid] => 191242 [year] => 1992 ) [1] => Array ( [departmentid] => 0 [id] => 24038 [level] => 2 [schoolid] => 46619 [year] => 1997 ) [2] => Array ( [departmentid] => 0 [id] => 24039 [level] => 3 [schoolid] => 46608 [year] => 2000 ) [3] => Array ( [departmentid] => 0 [id] => 24040 [level] => 3 [schoolid] => 46605 [year] => 2000 ) [4] => Array ( [departmentid] => 10163 [id] => 24041 [level] => 4 [schoolid] => 10850 [year] => 2003 ) ) [email] => [exp] => 2496 [fansnum] => 470 [favnum] => 1 [head] => http://app.qlogo.cn/mbloghead/1da0643d8efdb7ff8322 [homecity_code] => 15 [homecountry_code] => 1 [homepage] => http://www.thinksaas.cn [homeprovince_code] => 41 [hometown_code] => [https_head] => https://app.qlogo.cn/mbloghead/1da0643d8efdb7ff8322 [idolnum] => 1156 [industry_code] => 2002 [introduction] => http://www.thinksaas.cn ThinkSAAS社区 [isent] => 0 [ismyblack] => 0 [ismyfans] => 0 [ismyidol] => 0 [isrealname] => 1 [isvip] => 0 [level] => 3 [location] => 中国 北京 海淀 [mutual_fans_num] => 358 [name] => thinksaas [nick] => ThinkSAAS [openid] => 1EA5183AF86C0D5A775649BF7CD3C4FE [province_code] => 11 [regtime] => 1276305946 [send_private_flag] => 2 [sex] => 1 [tag] => Array ( [0] => Array ( [id] => 9669241982788451947 [name] => ThinkSAAS ) ) [tweetinfo] => Array ( [0] => Array ( [city_code] => [country_code] => [emotiontype] => 0 [emotionurl] => [from] => ThinkSAAS [fromurl] => http://www.thinksaas.cn [geo] => [id] => 271831130356668 [image] => [latitude] => 0 [location] => 未知 [longitude] => 0 [music] => [origtext] => 如果每天用QQ登陆ThinkSAAS社区，我同样可以每天可以刷QQ微博哦。暴露一下我还存在这个世界。[ThinkSAAS社区]http://url.cn/IHTfGu [province_code] => [self] => 1 [status] => 0 [text] => 如果每天用QQ登陆ThinkSAAS社区，我同样可以每天可以刷QQ微博哦。暴露一下我还存在这个世界。[ThinkSAAS社区]http://url.cn/IHTfGu [timestamp] => 1376898494 [type] => 1 [video] => ) ) [tweetnum] => 68 [verifyinfo] => ) [errcode] => 0 [msg] => ok [ret] => 0 [seqid] => 5.91376905717E+18 )
*/


/*
Array ( [data] => Array ( [birth_day] => 0 [birth_month] => 0 [birth_year] => 0 [city_code] => [comp] => [country_code] => [edu] => [email] => [exp] => 0 [fansnum] => 0 [favnum] => 0 [head] => [homecity_code] => [homecountry_code] => [homepage] => [homeprovince_code] => [hometown_code] => [https_head] => [idolnum] => 0 [industry_code] => 0 [introduction] => [isent] => 0 [ismyblack] => 0 [ismyfans] => 0 [ismyidol] => 0 [isrealname] => 1 [isvip] => 0 [level] => 0 [location] => 未知 [mutual_fans_num] => 0 [name] => [nick] => [openid] => B98DC2BFAEE545D1D2DD0EF373199E3A [province_code] => [send_private_flag] => 0 [sex] => 0 [tag] => [tweetnum] => 0 [verifyinfo] => ) [errcode] => 0 [msg] => ok [ret] => 0 [seqid] => 5.913769637E+18 )
*/

		if($arrUserInfo['nickname']==''){
			tsNotice('登陆失败！请使用Email登陆');
		}
		
		$salt = md5(rand());
		
		$pwd = random(5,0);
		
		$userid = $new['pubs']->create('user',array(
			'pwd'=>md5($salt.$pwd),
			'salt'=>$salt,
			'email'=>$openid,
		));
		
		//插入ts_user_info
		$new['pubs']->create('user_info',array(
			'userid'			=> $userid,
			'username' 	=> $arrUserInfo['nickname'],
			'email'		=> $openid,
			'ip'			=> getIp(),
			'addtime'	=> time(),
			'uptime'	=> time(),
		));
		
		//插入ts_user_open
		$new['pubs']->create('user_open',array(
			'userid'=>$userid,
			'sitename'=>'qq',
			'openid' => $openid,
			'access_token'=>$access_token,
			'uptime'=>time(),
		));
		
		//更新用户头像
		if($arrUserInfo['figureurl_qq_2']){
			//1000个图片一个目录
			$menu2=intval($userid/1000);
			$menu1=intval($menu2/1000);
			$menu = $menu1.'/'.$menu2;
			$photo = $userid.'.jpg';
			
			$photos = $menu.'/'.$photo;
			
			$dir = 'uploadfile/user/'.$menu;
			
			$dfile = $dir.'/'.$photo;
			
			createFolders($dir);
			
			if(!is_file($dfile)){
				$img = file_get_contents($arrUserInfo['figureurl_qq_2']); 
				file_put_contents($dfile,$img); 
			};
			
			$new['pubs']->update('user_info',array(
				'userid'=>$userid,
			),array(
				'path'=>$menu,
				'face'=>$photos,
			));
			
		}
		
		//获取用户信息
		$userData = $new['pubs']->find('user_info',array(
			'userid'=>$userid,
		),'userid,username,path,face,isadmin,signin,uptime');
		
		
		//发送系统消息(恭喜注册成功)
		$msg_userid = '0';
		$msg_touserid = $userid;
		$msg_content = '亲爱的QQ用户 '.$username.' ：<br />您成功加入了 '
									.$TS_SITE['site_title'].'<br />在遵守本站的规定的同时，享受您的愉快之旅吧!';
		aac('message')->sendmsg($msg_userid,$msg_touserid,$msg_content);
		
		$_SESSION['tsuser']	= $userData;

		header("Location: ".SITE_URL);
		exit;

	}
}