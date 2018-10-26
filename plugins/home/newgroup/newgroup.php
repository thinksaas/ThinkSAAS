<?php 
defined('IN_TS') or die('Access Denied.'); 

function newgroup(){
	$arrNewGroup = aac('group')->findAll('group',array(
		'isaudit'=>0,
	),'addtime desc','groupid,groupname',10);
	
	echo '<div class="card">';
	echo '<div class="card-header">最新创建小组</div>';
	echo '<div class="card-body"><div class="commlist"><ul>';
	foreach($arrNewGroup as $key=>$item){
	
		echo '<li><a href="'.tsUrl('group','show',array('id'=>$item['groupid'])).'">'.tsTitle($item['groupname']).'</a></li>';
	
	}
	echo '</ul></div></div>';
	echo '</div>';
	
}

addAction('home_index_right','newgroup');