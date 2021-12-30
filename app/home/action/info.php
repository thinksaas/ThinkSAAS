<?php 
defined('IN_TS') or die('Access Denied.');

$infoid = tsIntval($_GET['id']);

if($infoid==0){
	ts404();
}

$strInfo = $new['home']->find('home_info',array(
	'infoid'=>$infoid,
));

if($strInfo==''){
	ts404();
}

$strInfo['title'] = tsTitle($strInfo['title']);
$strInfo['content'] = nl2br(tsDecode($strInfo['content']));

$arrInfo = $new['home']->findAll('home_info',null,'orderid asc');

$title = $strInfo['title'];
include template('info');