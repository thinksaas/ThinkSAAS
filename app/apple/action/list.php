<?php 
defined('IN_TS') or die('Access Denied.');

$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$url = SITE_URL.tsurl('apple','list',array('page'=>''));
$lstart = $page*20-20;

$arrApples = $db->fetch_all_assoc("select * from ".dbprefix."apple order by addtime desc limit $lstart,20");

$appleNum = $db->once_fetch_assoc("select count(*) from ".dbprefix."apple");
$pageUrl = pagination($appleNum['count(*)'], 20, $page, $url);

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

$title = '列表';

include template('list');