<?php 
defined('IN_TS') or die('Access Denied.');

if($TS_SITE['site_urltype']==1){
	$name = urldecode(tsUrlCheck(urlencode($_GET['id'])));
}else{
	$name = urldecode(tsUrlCheck($_GET['id']));
}

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