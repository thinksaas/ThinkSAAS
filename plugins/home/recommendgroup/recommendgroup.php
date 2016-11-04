<?php 
defined('IN_TS') or die('Access Denied.'); 
//推荐小组
function recommendgroup(){
	$arrRecommendGroup = aac('group')->getRecommendGroup('12');
    include template('recommendgroup','recommendgroup');
}
function recommendgroup_css(){
	echo '<link href="'.SITE_URL.'plugins/home/recommendgroup/style.css" rel="stylesheet" type="text/css" />';
}
addAction('home_index_left','recommendgroup');
addAction('pub_header_top','recommendgroup_css');