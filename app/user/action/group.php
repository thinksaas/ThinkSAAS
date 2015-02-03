<?php 
defined('IN_TS') or die('Access Denied.');

include 'userinfo.php';

$arrGroupsList = $new['user']->findAll('group_user',array(
	'userid'=>$strUser['userid'],
),null,'groupid');


foreach($arrGroupsList as $key=>$item){
	$arrGroupList[] = aac('group')->getOneGroup($item['groupid']);
}


$title = $strUser['username'].'的小组';
include template('group');