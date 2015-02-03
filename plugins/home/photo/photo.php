<?php 
defined('IN_TS') or die('Access Denied.'); 
//相册
function photo(){
	
	$arrAlbum = aac('photo')->findAll('photo_album',array(
		'isrecommend'=>1,
	),'addtime desc',null,12);
	foreach($arrAlbum as $key=>$item){
		$arrAlbum[$key]['albumname']=tsTitle($item['albumname']);
	}
	
	include template('photo','photo');
}

function photo_css(){

	echo '<link href="'.SITE_URL.'plugins/home/photo/style.css" rel="stylesheet" type="text/css" />';

}

addAction('home_index_footer','photo');
addAction('pub_header_top','photo_css');