<?php
defined('IN_TS') or die('Access Denied.');

$groupuserid = intval($TS_USER['userid']);

if($groupuserid==0){
	echo 0;exit;
}

$groupid = intval($_POST['groupid']);
$userid = intval($_POST['userid']);

$strGroup = $new['group']->find('group',array(
	'groupid'=>$groupid,
));

if($strGroup['userid']!=$groupuserid){

	echo 1;exit;
	
}

$new['group']->delete('group_user',array(
	'userid'=>$userid,
	'groupid'=>$groupid,
));

$new['group']->update('group',array(
    'groupid'=>$groupid,
),array(
    'count_user'=>$strGroup['count_user']-1,
));

echo 2;exit;