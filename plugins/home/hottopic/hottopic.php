<?php 
defined('IN_TS') or die('Access Denied.'); 
function hottopic(){
	
	$arrHotTopics = aac('group')->getHotTopic(7);
	
	echo '<div class="bbox">';
	echo '<div class="btitle">热门话题</div>';
	echo '<div class="bc commlist">';
	echo '<ul>';
	foreach($arrHotTopics as $key=>$item){
		echo '<li><a href="'.tsUrl('group','topic',array('id'=>$item['topicid'])).'">'.cututf8(stripslashes($item['title']),0,20,false).'</a> ('.$item['count_view'].')</li>';
	}
	echo '</ul>';
	echo '</div>';
	echo '</div>';
	
}

addAction('home_index_right','hottopic');