<?php
defined('IN_TS') or die('Access Denied.');
//苹果机 
$appleid = $_GET['appleid'];

$strApple = $db->find("select * from ".dbprefix."apple where `appleid`='$appleid'");

$index = $db->findAll("select * from ".dbprefix."apple_index where `appleid`='$appleid'");
foreach($index as $key=>$item){
	$strApple['model'][] = array(
		'virtuename'	=> $new['apple']->getVirtueName($item['virtueid']),
		'parameter'	=> $item['parameter'],
	);
}

//print_r($strApple);

//计算打分人数和平均分
$userNum = $db->find("select count(*) from ".dbprefix."apple_score where `appleid`='$appleid'");
$userNumCount = $userNum['count(*)'];

$score = $db->find("select sum(score) from ".dbprefix."apple_score where `appleid`='$appleid'");

//总分
$allScore = $score['sum(score)'];

//平均分
$average = round($allScore/$userNumCount,2);

//计算当前用户的评分 
if(intval($TS_USER['user']['userid'])>0){
	$userid = intval($TS_USER['user']['userid']);
	$userScore = $db->find("select count(*) from ".dbprefix."apple_score where `appleid`='$appleid' and `userid`='$userid'");
	$userScoreCount = $userScore['count(*)'];
	if($userScoreCount > 0){
		$strScore = $db->find("select * from ".dbprefix."apple_score where `appleid`='$appleid' and `userid`='$userid'");
	}
}

//获取点评列表
$arrReviews= $db->findAll("select * from ".dbprefix."apple_review where `appleid`='$appleid' limit 5");
foreach($arrReviews as $key=>$item){
	$arrReview[] = $item;
	$arrReview[$key]['user'] = aac('user')->getOneUser($item['userid']);
	$arrReview[$key]['content'] = getsubstrutf8($item['content'],0,100);
}
//计算点评数 
$reviewNum = $db->find("select count(*) from ".dbprefix."apple_review where `appleid`='$appleid'");

$reviewNumCount = $reviewNum['count(*)'];

$title = $strApple['title'];

include template('show');