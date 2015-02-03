<?php 
defined ( 'IN_TS' ) or die ( 'Access Denied.' );


$arrGroup = $new['group']->findAll('group',array(
	'userid'=>$strUser['userid'],
));

foreach($arrGroup as $key=>$item){
	if($item['photo']){
		$arrGroup[$key]['photo'] = tsXimg($item['photo'],'group',120,120,$item['path'],1);
	}else{
		$arrGroup[$key]['photo'] = SITE_URL.'public/images/group.jpg';
	}
}


$title = '我创建的小组';
include template('my/created');