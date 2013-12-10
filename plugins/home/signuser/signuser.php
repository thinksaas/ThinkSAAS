<?php 
defined('IN_TS') or die('Access Denied.'); 

function signuser(){
	$arrUser = aac('user')->getHotUser(16);
	echo '<div class="bbox">';
	echo '<h2>最新签到用户</h2>';
	echo '<div class="bc facelist"><ul>';
	foreach($arrUser as $key=>$item){
		echo '<li><a href="'.tsUrl('user','space',array('id'=>$item['userid'])).'"><img src="'.$item['face'].'" alt="'.$item['username'].'" width="48" height="48" /></a><div><a href="'.tsUrl('user','space',array('id'=>$item['userid'])).'">'.$item['username'].'</a></div></li>';
	}
	echo '</ul></div><div class="clear"></div></div>';
}

addAction('home_index_left','signuser');