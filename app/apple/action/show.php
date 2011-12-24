<?php

//苹果机 
$appleid = $_GET['appleid'];

$strApple = $db->once_fetch_assoc("select * from ".dbprefix."apple where `appleid`='$appleid'");

$index = $db->fetch_all_assoc("select * from ".dbprefix."apple_index where `appleid`='$appleid'");
foreach($index as $key=>$item){
	$strApple['model'][] = array(
		'virtuename'	=> $new['apple']->getVirtueName($item['virtueid']),
		'parameter'	=> $item['parameter'],
	);
}

//print_r($strApple);

//计算打分人数和平均分
$userNum = $db->once_fetch_assoc("select count(*) from ".dbprefix."apple_score where `appleid`='$appleid'");
$userNumCount = $userNum['count(*)'];

$score = $db->once_fetch_assoc("select sum(score) from ".dbprefix."apple_score where `appleid`='$appleid'");

//总分
$allScore = $score['sum(score)'];

//平均分
$average = $allScore/$userNumCount;

//计算当前用户的评分 
if(intval($TS_USER['user']['userid'])>0){
	$userid = intval($TS_USER['user']['userid']);
	$userScore = $db->once_fetch_assoc("select count(*) from ".dbprefix."apple_score where `appleid`='$appleid' and `userid`='$userid'");
	$userScoreCount = $userScore['count(*)'];
	if($userScoreCount > 0){
		$strScore = $db->once_fetch_assoc("select * from ".dbprefix."apple_score where `appleid`='$appleid' and `userid`='$userid'");
	}
}

//获取点评列表
$arrReviews= $db->fetch_all_assoc("select * from ".dbprefix."apple_review where `appleid`='$appleid' limit 5");
foreach($arrReviews as $key=>$item){
	$arrReview[] = $item;
	$arrReview[$key]['user'] = aac('user')->getSimpleUser($item['userid']);
	$arrReview[$key]['content'] = getsubstrutf8($item['content'],0,100);
}
//计算点评数 
$reviewNum = $db->once_fetch_assoc("select count(*) from ".dbprefix."apple_review where `appleid`='$appleid'");

$reviewNumCount = $reviewNum['count(*)'];

$title = $strApple['title'];

include template('show');