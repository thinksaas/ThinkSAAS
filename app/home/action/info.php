<?php 
defined('IN_TS') or die('Access Denied.');

$keys = tsUrlCheck($_GET['key']);

$strInfo = $new['home']->find('home_info',array(
	'infokey'=>$keys,
));

$strInfo['content'] = nl2br(tsDecode($strInfo['content']));

$arrInfo = $new['home']->findAll('home_info');

$title = $strInfo['title'];
include template('info');