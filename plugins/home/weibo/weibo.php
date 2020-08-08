<?php 
defined('IN_TS') or die('Access Denied.'); 
//首页登录框
function weibo(){
	$arrWeibo = aac('weibo')->findAll('weibo',array(
	    'isaudit'=>0,
    ),'addtime desc',null,10);
	foreach($arrWeibo as $key=>$item){
		$arrWeibo[$key]['title'] = tsTitle($item['title']);
		$arrWeibo[$key]['user'] = aac('user')->getSimpleUser($item['userid']);
		$arrWeibo[$key]['photo'] = aac('weibo')->getWeiboPhoto($item['weiboid'],4);
	}
	
	include template('weibo','weibo');
}

function weibo_css(){

	echo '<link href="'.SITE_URL.'plugins/home/weibo/style.css" rel="stylesheet" type="text/css" />';

}
function weibo_js(){
	echo '<script src="'.SITE_URL.'plugins/home/weibo/weibo.js" type="text/javascript"></script>';
}

addAction('home_index_right','weibo');
addAction('pub_header_top','weibo_css');
addAction('pub_footer','weibo_js');