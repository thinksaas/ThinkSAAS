<?php 
defined('IN_TS') or die('Access Denied.'); 

function newgroup(){
	$arrNewGroup = aac('group')->findAll('group',array(
		'isaudit'=>0,
	),'addtime desc','groupid,groupname',10);
	
	include template('newgroup','newgroup');
	
}

addAction('home_index_right','newgroup');