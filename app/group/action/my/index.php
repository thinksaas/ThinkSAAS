<?php 
defined ( 'IN_TS' ) or die ( 'Access Denied.' );

//加入的小组
$arrGroupsList = $new['group']->findAll('group_user',array(
	'userid'=>$strUser['userid'],
),null,'groupid');


foreach($arrGroupsList as $key=>$item){
	$arrGroup[] = aac('group')->getOneGroup($item['groupid']);
}

//创建的小组
$arrCreateGroup = $new['group']->findAll('group',array(
    'userid'=>$strUser['userid'],
));

foreach($arrCreateGroup as $key=>$item){
    $arrCreateGroup[$key]['groupname'] = tsTitle($item['groupname']);
    if($item['photo']){
        $arrCreateGroup[$key]['photo'] = tsXimg($item['photo'],'group',120,120,$item['path'],1);
    }else{
        $arrCreateGroup[$key]['photo'] = SITE_URL.'public/images/group.jpg';
    }
}


$title = '我加入的小组';
include template('my/index');