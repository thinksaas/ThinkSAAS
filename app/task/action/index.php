<?php
defined('IN_TS') or die('Access Denied.');


$userid = aac('user')->isLogin();

$arrTaskUser = $new['task']->findAll('task_user',array(
	'userid'=>$userid,
));



foreach($arrTaskUser as $key=>$item){
	$arrTask[$item['taskkey']] = $item['taskkey'];
}

$title = '任务';

include template("index");