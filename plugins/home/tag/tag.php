<?php 
defined('IN_TS') or die('Access Denied.'); 

function tag(){
	//最新标签
	$arrTag = aac('tag')->findAll('tag',"`count_topic`>'0'",'uptime desc',null,30);
	
	foreach($arrTag as $key=>$item){
		$arrTag[$key]['tagname'] = tsTitle($item['tagname']);
	}

	//echo '<div class="bbox">'.doAction('gobad','home_left_1').'</div>';
	
	echo '<div class="panel panel-default">';
	echo '<div class="panel-heading">热门标签<small><a href="'.tsUrl('group','tags').'">更多</a></small></div>';
	echo '<div class="panel-body tags">';
	foreach($arrTag as $key=>$item){
		echo '<a href="'.tsUrl('group','tag',array('id'=>urlencode($item['tagname']))).'">'.$item['tagname'].'</a>';
	}
	echo '</div></div>';
	
	//echo '<div class="bbox">'.doAction('gobad','home_left_2').'</div>';
	
}

addAction('home_index_left','tag');