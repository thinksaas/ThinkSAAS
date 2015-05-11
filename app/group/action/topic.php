<?php
defined('IN_TS') or die('Access Denied.');

$topicid = intval($_GET['id']);

$strTopic = $new['group']->find('group_topic',array(
	'topicid'=>$topicid,
));


if($strTopic==''){
	header("HTTP/1.1 404 Not Found");
	header("Status: 404 Not Found");
	$title = '404';
	include pubTemplate("404");
	exit;
}

//帖子审核 
if($strTopic['isaudit']==1){
	tsNotice('内容审核中......');
}


$strTopic['title'] = tsTitle($strTopic['title']);

$tpUrl = tpPage($strTopic['content'],'group','topic',array('id'=>$topicid));

$strTopic['content'] = tsDecode($strTopic['content'],$tp);

//判断是否评论后显示帖子内容
$isComment = $new['group']->findCount('group_topic_comment', array(
	'userid' => intval($TS_USER['userid']),
	'topicid' => $strTopic['topicid'],
)); 

if($strTopic['iscommentshow']==1 && $isComment==0 && $strTopic['userid']!=intval($TS_USER['userid'])){
	$strTopic['content'] = '你需要回复后才可以浏览帖子内容！';
}


//编辑的数据
if($strTopic['userid']==$TS_USER['userid']){

	if($strTopic['isdelete']=='1'){
		tsNotice('你的帖子删除中...');
	}

}

// 帖子分类
if ($strTopic['typeid'] != '0'){
	$strTopic['type'] = $new['group']->find('group_topic_type', array(
		'typeid' => $strTopic['typeid'],
	));
} 
// 小组
$strGroup = $new['group']->getOneGroup($strTopic['groupid']);

$strTopic['content'] = @preg_replace("/\[@(.*)\:(.*)]/U","<a href='".tsUrl('user','space',array('id'=>'$2'))." ' rel=\"face\" uid=\"$2\"'>@$1</a>",$strTopic['content']);



// 最新帖子
$newTopic = $new['group']->findAll('group_topic',array(
	'isaudit'=>'0',
),'addtime desc',null,10);
foreach($newTopic as $key=>$item){
	$newTopic[$key]['title'] = tsTitle($item['title']);
	$newTopic[$key]['content'] = tsDecode($item['content']);
}

// 浏览方式
if ($strGroup['isopen'] == '1' && $isGroupUser == '0')
{
	$title = $strTopic['title'];
	include template("topic_isopen");
}else{ 
	// 帖子标签
	$strTopic['tags'] = aac('tag')->getObjTagByObjid('topic', 'topicid', $topicid);
	$strTopic['user'] = aac('user')->getOneUser($strTopic['userid']);
	
	//把标签作为关键词
	if($strTopic['tags']){
		foreach($strTopic['tags'] as $key=>$item){
			$arrTag[] = $item['tagname'];
		}
		$sitekey = arr2str($arrTag);
	}else{
		$sitekey = $strTopic['title'];
	}
	//标题
	$title = $strTopic['title']; 
	
	
	// 评论列表开始
	$page = isset($_GET['page']) ? intval($_GET['page']) : 1; 
	$url = tsUrl('group', 'topic', array('id' => $topicid, 'page' => ''));

	$lstart = $page * 15-15;
	
	$arrComment = $new['group']->findAll('group_topic_comment',array(
		'topicid'=>$topicid,
	),'addtime asc',null,$lstart.',15');
	
	foreach($arrComment as $key => $item)
	{
		$arrTopicComment[] = $item;
		$arrTopicComment[$key]['l'] = (($page-1) * 15) + $key + 1;
		$arrTopicComment[$key]['user'] = aac('user')->getOneUser($item['userid']);

		$arrTopicComment[$key]['content'] = @preg_replace("/\[@(.*)\:(.*)]/U","<a href='".tsUrl('user','space',array('id'=>'$2'))." ' rel=\"face\" uid=\"$2\"'>@$1</a>",tsDecode($item['content']));	
		
		$arrTopicComment[$key]['recomment'] = $new['group']->recomment($item['referid']);
	}
	
	$commentNum = $new['group']->findCount('group_topic_comment',array(
		'topicid'=>$strTopic['topicid'],
	));

	$pageUrl = pagination($commentNum, 15, $page, $url); 
	// 评论列表结束
	
	
	// 判断会员是否加入该小组
	$strGroupUser = '';
	if(intval($TS_USER['userid'])){
		$strGroupUser = $new['group']->find('group_user',array(
			'userid'=>intval($TS_USER['userid']),
			'groupid'=>$strTopic['groupid'],
		));
	}
	
	//7天内的热门帖子
	$arrHotTopic = $new['group']->getHotTopic(7);
	
	//推荐帖子
	$arrRecommendTopic = $new['group']->getRecommendTopic();
	
	
	$sitedesc = cututf8(t($strTopic['content']),0,100);
	
	include template('topic'); 
	
	// 增加浏览次数
	$new['group']->update('group_topic', array(
		'topicid' => $strTopic['topicid'],
	), array(
		'count_view' => $strTopic['count_view'] + 1,
	));
}