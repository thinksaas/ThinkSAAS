<?php 
defined('IN_TS') or die('Access Denied.'); 
//首页幻灯片插件
function slide(){
	
	$arrSlide = aac('home')->findAll('slide',array(
        'typeid'=>0,
    ),'addtime desc');

    foreach($arrSlide as $key=>$item){
        $arrSlide[$key]['photo_url'] = SITE_URL.'uploadfile/slide/'.$item['photo'].'?v='.$item['addtime'];
    }
	
	include template('slide','slide');
}

addAction('home_index_header','slide');