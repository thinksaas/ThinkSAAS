<?php 
defined('IN_TS') or die('Access Denied.'); 
//首页幻灯片插件
function slide(){
	
	$arrSlide = aac('home')->findAll('slide',array(
        'typeid'=>0,
    ),'addtime desc');

    foreach($arrSlide as $key=>$item){
        if($GLOBALS['TS_SITE']['file_upload_type']==1){
            $arrSlide[$key]['photo_url'] = $GLOBALS['TS_SITE']['alioss_bucket_url'].'/uploadfile/slide/'.$item['photo'];
        }else{
            $arrSlide[$key]['photo_url'] = SITE_URL.'uploadfile/slide/'.$item['photo'];
        }
    }
	
	include template('slide','slide');
}

addAction('home_index_header','slide');