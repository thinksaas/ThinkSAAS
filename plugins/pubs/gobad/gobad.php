<?php
defined('IN_TS') or die('Access Denied.');
//统计代码
function gobad_html(){
	$code = fileRead('data.php','plugins','pubs','gobad');
	
	$code = stripcslashes($code);
	
	echo $code;
}

//home首页右侧底部
addAction('home_index_right_footer','gobad_html');

//动态首页右侧底部
addAction('feed_index_right_footer','gobad_html');

//小组帖子页右侧底部
addAction('group_topic_right_footer','gobad_html');

//小组页右侧底部
addAction('group_group_right_footer','gobad_html');

//相册图片页右侧底部
addAction('photo_show_right_footer','gobad_html');

//苹果机首页右侧底部
addAction('apple_index_right_footer','gobad_html');

//苹果机内容页右侧底部
addAction('apple_show_right_footer','gobad_html');

//苹果机点评内容页右侧底部
addAction('apple_review_right_footer','gobad_html');

//电台首页右侧底部
addAction('radio_index_right_footer','gobad_html');