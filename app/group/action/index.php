<?php
defined('IN_TS') or die('Access Denied.');

//所有小组
$page = isset($_GET['page']) ? $_GET['page'] : '1';
$url = SITE_URL.tsUrl('group','index',array('page'=>''));
$lstart = $page*20-20;
if(isset($_GET['cat']) && $_GET['cat']>0){
$sql="cateid='".intval($_GET['cat'])."'";	
}
$arrGroups = $new['group']->findAll('group',$sql,'isrecommend desc,addtime asc','groupid',$lstart.',20');
$catGroups = $db->fetch_all_assoc("select * from ".dbprefix."group_cates order by cateid ASC");
foreach($catGroups as $key=>$val){
	$list[] =  $val;
}
foreach($arrGroups as $key=>$item){
	$arrData[] = $new['group']->getOneGroup($item['groupid']);
}
foreach($arrData as $key=>$item){
	$arrRecommendGroup[] =  $item;
	$arrRecommendGroup[$key]['groupdesc'] = getsubstrutf8(t($item['groupdesc']),0,35);
}

$groupNum = $new['group']->findCount('group');

$pageUrl = pagination($groupNum, 20, $page, $url);

//我加入的小组
$myGroup = array();
if($TS_USER['user']['userid']){
	$myGroups = $new['group']->findAll('group_users',array(
		'userid'=>$TS_USER['user']['userid'],
	),null,'groupid');	
	foreach($myGroups as $item){
		$myGroup[]=$item['groupid'];
	}
}

//最新10个小组
$arrNewGroup = $new['group']->getNewGroup('10');

//热门帖子
$arrTopics = $new['group']->findAll('group_topics',null,'count_comment desc','groupid,topicid,title,count_comment',10);
foreach($arrTopics as $key=>$item){
	$arrTopic[] = $item;
	$arrTopic[$key]['group'] = $new['group']->getOneGroup($item['groupid']);
}

$title = '小组';
if($_POST['bac_d']=='html'){
include template('group_ajax');
}else if(!$arrGroups && $_POST['bac_d']=='more'){
print_r($arrGroups);
}else if($arrGroups && $_POST['bac_d']=='more'){
include template("group_ajax_more");
}else{
include template("index");
}
