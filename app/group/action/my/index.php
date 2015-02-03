<?php 
defined ( 'IN_TS' ) or die ( 'Access Denied.' );


$arrGroupsList = $new['group']->findAll('group_user',array(
	'userid'=>$strUser['userid'],
),null,'groupid');


foreach($arrGroupsList as $key=>$item){
	$arrGroup[] = aac('group')->getOneGroup($item['groupid']);
}


$title = '我加入的小组';
include template('my/index');