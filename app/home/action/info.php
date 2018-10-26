<?php 
defined('IN_TS') or die('Access Denied.');

$infoid = intval($_GET['id']);

$strInfo = $new['home']->find('home_info',array(
	'infoid'=>$infoid,
));
$strInfo['title'] = tsTitle($strInfo['title']);
$strInfo['content'] = nl2br(tsDecode($strInfo['content']));

$arrInfo = $new['home']->findAll('home_info',null,'orderid asc');

$title = $strInfo['title'];
include template('info');