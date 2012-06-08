<?php
defined('IN_TS') or die('Access Denied.');
//统计代码
function gobad_html($s,$w){

	$code = fileRead('plugins/pubs/gobad/data.php');
	echo stripslashes($code[$w]);
}

/*300广告位*/
//home首页右侧底部
addAction('home_index_right_footer','gobad_html');

//动态首页右侧底部
addAction('feed_index_right_footer','gobad_html');

//小组帖子页右侧底部
addAction('group_topic_right_footer','gobad_html');

//小组首页
addAction('group_index_right_footer','gobad_html');

//帖子内容底部
addAction('topic_footer','gobad_html');

//小组页右侧底部
addAction('group_group_right_footer','gobad_html');

//小组用户页右侧底部
addAction('group_my_right_footer','gobad_html');

//用户user
addAction('user_space_right_footer','gobad_html');

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