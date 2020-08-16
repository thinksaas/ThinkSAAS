<?php 
defined('IN_TS') or die('Access Denied.');

$name = urldecode(tsFilter($_GET['id']));

//$name=mb_convert_encoding($name,'UTF-8', 'GB2312'); //针对IIS环境可能出现的问题请取消此行注释

$tagid = aac('tag')->getTagId(t($name));

if($tagid==0){
	header("HTTP/1.1 404 Not Found");
	header("Status: 404 Not Found");
	$title = '404';
	include pubTemplate("404");
	exit;
}

$strTag = $new['group']->find('tag',array(
	'tagid'=>$tagid,
));

$strTag['tagname'] = htmlspecialchars($strTag['tagname']); 


//小组
$arrGroupTagId = $new['group']->findAll('tag_group_index',array(
	'tagid'=>$tagid,
));
if($arrGroupTagId){
	foreach($arrGroupTagId as $key=>$item){
		$arrGroup[] = $new['group']->getOneGroup($item['groupid']);
	}
}

//热门tag
$arrTag = $new['group']->findAll('tag',"`count_group`!=''",'uptime desc',null,30);

$sitekey = $strTag['tagname'];
$title = $strTag['tagname'];

include template("tag");