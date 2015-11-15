<?php 
defined('IN_TS') or die('Access Denied.'); 
//首页幻灯片插件
function slide(){
	
	$arrSlide = aac('home')->findAll('slide',null,'addtime desc');
	
	include template('slide','slide');
}

addAction('home_index_left','slide');