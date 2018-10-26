<?php 
defined('IN_TS') or die('Access Denied.'); 
function hottopic(){
	
	$arrHotTopics = aac('group')->findAll('group_topic',array(
	    'isaudit'=>0,
    ),'addtime desc','topicid,title',10);
	
	echo '<div class="card">';
	echo '<div class="card-header">最新话题</div>';
	echo '<div class="card-body">';
	echo '<div class="commlist">';
	echo '<ul>';
	foreach($arrHotTopics as $key=>$item){
		echo '<li><a href="'.tsUrl('group','topic',array('id'=>$item['topicid'])).'">'.tsTitle($item['title']).'</a></li>';
	}
	echo '</ul>';
	echo '</div>';
	echo '</div>';
	echo '</div>';
	
}

addAction('home_index_right','hottopic');