<?php 
defined('IN_TS') or die('Access Denied.');

$attachid = intval($_GET['id']);

$strAttach = $new['attach']->find('attach',array(

	'attachid'=>$attachid,

));

$strAttach['album'] = $new['attach']->find('attach_album',array(
	'albumid'=>$strAttach['albumid'],
));

$strAttach['user'] = aac('user')->getOneUser($strAttach['userid']);

$userAttach = $new['attach']->findAll('attach',array(

	'userid'=>$strAttach['userid'],

));

$title = $strAttach['attachname'];

include template("show");