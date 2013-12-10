<?php 
defined('IN_TS') or die('Access Denied.'); 

function newgroup(){
	$arrNewGroups = aac('group')->findAll('group',array(
		'isaudit'=>0,
	),'addtime desc',null,10);
	
	foreach($arrNewGroups as $key=>$item){
		$arrNewGroup[] = $item;
		$arrNewGroup[$key]['user'] = aac('user')->find('user_info',array(
			'userid'=>$item['userid'],
		),'userid,username');
	}
	
	echo '<div class="bbox">';
	echo '<h2>最新创建小组</h2>';
	echo '<div class="bc commlist"><ul>';
	foreach($arrNewGroup as $key=>$item){
	
		echo '<li><a href="'.tsUrl('group','show',array('id'=>$item['groupid'])).'">'.htmlspecialchars($item['groupname']).'</a> By <a href="'.tsUrl('user','space',array('id'=>$item['user']['userid'])).'">'.$item['user']['username'].'</a></li>';
	
	}
	echo '</ul></div>';
	echo '</div>';
	
}

addAction('home_index_right','newgroup');