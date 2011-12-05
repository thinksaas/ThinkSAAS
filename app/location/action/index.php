<?php
defined('IN_TS') or die('Access Denied.');

$userid = intval($TS_USER['user']['userid']);

if($userid == '0'){

/* $arrZm = $db->fetch_all_assoc("SELECT zm FROM ".dbprefix."area GROUP BY zm");
foreach($arrZm as $key=>$item){
	$arrArea[$item['zm']] = $new['location']->getAreaByZm($item['zm']);
	
}

$title = '同城';
include template("index"); */

	$areaid = '0';
	
}else{
	
	$areaid = intval($TS_USER['user']['areaid']);
	
}

header("Location: ".SITE_URL.tsurl('location','area',array('areaid'=>$areaid)));