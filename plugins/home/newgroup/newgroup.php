<?php 
defined('IN_TS') or die('Access Denied.'); 

function newgroup(){
	$arrNewGroup = aac('group')->findAll('group',array(
		'isaudit'=>0,
	),'addtime desc','groupid,groupname',10);
	
	echo '<div class="panel panel-default">';
	echo '<div class="panel-heading">最新创建小组</div>';
	echo '<div class="panel-body commlist"><ul>';
	foreach($arrNewGroup as $key=>$item){
	
		echo '<li><a href="'.tsUrl('group','show',array('id'=>$item['groupid'])).'">'.$item['groupname'].'</a></li>';
	
	}
	echo '</ul></div>';
	echo '</div>';
	
}

addAction('home_index_right','newgroup');