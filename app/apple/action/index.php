<?php 
defined('IN_TS') or die('Access Denied.');

//最新书籍 
$arrApples = $db->fetch_all_assoc("select * from ".dbprefix."apple order by addtime desc limit 6");

foreach($arrApples as $key=>$item){
	$arrApple[] = $item;
	$index = $db->fetch_all_assoc("select * from ".dbprefix."apple_index where `appleid`='".$item['appleid']."'");
	foreach($index as $mkey=>$mitem){
		$arrApple[$key]['model'][] = array(
			'virtuename'	=> $new['apple']->getVirtueName($mitem['virtueid']),
			'parameter'	=> $mitem['parameter'],
		);
	}
}


//最新点评
$arrReviews = $db->fetch_all_assoc("select * from ".dbprefix."apple_review order by addtime desc limit 6");
foreach($arrReviews as $key=>$item){
	$arrReview[] = $item;
	$arrReview[$key]['content'] = getsubstrutf8(t($item['content']),0,130);
	$arrReview[$key]['user'] = aac('user')->getSimpleUser($item['userid']);
	$arrReview[$key]['apple'] = $new['apple']->getApple($item['appleid']);
}

$title = '苹果机';

include template('index');