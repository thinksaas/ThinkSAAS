<?php
defined('IN_TS') or die('Access Denied.');

//小组首页

$groupid = tsIntval($_GET['id']);
$typeid = tsIntval($_GET['typeid']);
$isshow = tsIntval($_GET['isshow']);

//小组信息
$strGroup = $new['group']->getOneGroup($groupid);

if($strGroup['groupid'] == '') {
	header("HTTP/1.1 404 Not Found");
	header("Status: 404 Not Found");
	$title = '404';
	include pubTemplate("404");
	exit;
}

if($strGroup['isaudit'] == 1) {
	tsNotice('小组审核中...');
}

$title = $strGroup['groupname'];

//小组帖子分类
$arrTopicTypes = $new['group']->findAll('topic_type',array(
	'groupid'=>$groupid,
));

if(is_array($arrTopicTypes)){
	foreach($arrTopicTypes as $item){
		$arrTopicType[$item['typeid']] = $item;
	}
}

//组长信息
$strLeader = aac('user')->getSimpleUser($strGroup['userid']);

//判断会员是否加入该小组
$isGroupUser = '';
if(tsIntval($TS_USER['userid'])){
	$strUser = aac('user')->getSimpleUser(tsIntval($TS_USER['userid']));
	$isGroupUser = $new['group']->find('group_user',array(
		'userid'=>tsIntval($TS_USER['userid']),
		'groupid'=>$groupid,
	));
}

//小组是否需要审核
if($strGroup['isaudit']=='1'){
	//推荐小组
	$arrRecommendGroup = $new['group']->getRecommendGroup('7');
	include template("group_isaudit");
	
}else{

	$page = tsIntval($_GET['page'],1);
	
	$lstart = $page*30-30;

	if($typeid > 0){
		$andType = " and `typeid`='$typeid'";
		$url = tsUrl('group','show',array('id'=>$groupid,'typeid'=>$typeid,'page'=>''));
	}else{
		$andType = '';
		$url = tsUrl('group','show',array('id'=>$groupid,'page'=>''));
	}
	
	$arrTopics = $new['group']->findAll('topic',"`groupid`='$groupid' ".$andType." and `isaudit`='0'",'istop desc,uptime desc',null,$lstart.',30');
	
	if( is_array($arrTopics)){
		foreach($arrTopics as $key=>$item){
			$arrTopic[] = $item;
			$arrTopic[$key]['title'] = tsTitle($item['title']);
			$arrTopic[$key]['gaiyao'] = tsTitle($item['gaiyao']);
			$arrTopic[$key]['typename'] = $arrTopicType[$item['typeid']]['typename'];
			$arrTopic[$key]['user'] = aac('user')->getSimpleUser($item['userid']);
			$arrTopic[$key]['group'] = aac('group')->getOneGroup($item['groupid']);
		}
	}
	
	$topicNum = $new['group']->findCount('topic',"`groupid`='$groupid' ".$andType);
	
	$pageUrl = pagination($topicNum, 30, $page, $url);
	
	
	//是否小组会员
	$groupUser = $new['group']->findAll('group_user',array(
		'groupid'=>$groupid,
	),'addtime desc',null,8);
	
	if(is_array($groupUser)){
		foreach($groupUser as $item){
			$strUser = aac('user')->getSimpleUser($item['userid']);
			if($strUser){
				$arrGroupUser[] = $strUser;
			}else{
				$new['group']->delete('group_user',array(
					'userid'=>$item['userid'],
					'groupid'=>$groupid,
				));
			}
		}
	}

	//小组管理员
    $arrGroupAdmin = $new['group']->findAll('group_user',array(
        'groupid'=>$groupid,
        'isadmin'=>1,
    ));
    $arrGroupAdminUser = array();
    if($arrGroupAdmin){
        foreach($arrGroupAdmin as $key=>$item){
            $arrGroupUserId[] = $item['userid'];
        }
        $groupUserIds = arr2str($arrGroupUserId);
        $arrGroupAdminUser = $new['group']->findAll('user_info',"`userid` in ($groupUserIds)",'addtime desc','userid,username');
    }
	
	//标签
	$strGroup ['tags'] = aac ( 'tag' )->getObjTagByObjid ( 'group', 'groupid', $strGroup ['groupid'] );
	
	
	
	if($page > 1){
		$title = $strGroup['groupname'].' - 第'.$page.'页';
	}

	//把标签作为关键词
	if($strGroup['tags']){
		foreach($strGroup['tags'] as $key=>$item){
			$arrTag[] = $item['tagname'];
		}
		$sitekey = $strGroup['groupname'].','.arr2str($arrTag);
	}else{
		$sitekey = $strGroup['groupname'];
	}

	
	$sitedesc = tsCutContent($strGroup['groupdesc'],50);

	if($TS_CF['mobile']) $sitemb = tsUrl('moblie','group',array('ts'=>'show','groupid'=>$strGroup['groupid']));
		
	include template("show");

}