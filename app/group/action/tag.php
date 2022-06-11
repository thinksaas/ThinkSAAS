<?php 
defined('IN_TS') or die('Access Denied.');

$name = urldecode(tsUrlCheck($_GET['id']));

//$name=mb_convert_encoding($name,'UTF-8', 'GB2312'); //针对IIS环境可能出现的问题请取消此行注释

$strTag = aac('tag')->getTagByName(t($name));

$strTag['tagname'] = htmlspecialchars($strTag['tagname']); 

$tagid = $strTag['tagid'];

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
$arrTag = $new['group']->findAll('tag',"`count_group`>0 and `isaudit`=0",'uptime desc',null,30);

$sitekey = $strTag['tagname'];
$title = $strTag['tagname'];

include template("tag");