<?php 
defined('IN_TS') or die('Access Denied.'); 

function tag(){
	//最新标签
	$arrTag = aac('tag')->findAll('tag',null,'uptime desc',null,30);

	echo '<div class="bbox">';
	echo '<h2>热门标签<div class="right"><a href="'.tsUrl('group','tags').'">更多</a></div></h2>';
	echo '<div class="bc tags">';
	foreach($arrTag as $key=>$item){
		echo '<a href="'.tsUrl('group','tag',array('id'=>urlencode($item['tagname']))).'">'.$item['tagname'].'</a>';
	}
	echo '</div><div class="clear"></div></div>';
	
}

addAction('home_index_left','tag');