<?php
defined ( 'IN_TS' ) or die ( 'Access Denied.' );

$userid = aac ( 'user' )->isLogin ();

$arrGroupUser = $new['topic']->findAll('group_user',array(
    'userid'=>$userid,
));

if($arrGroupUser==''){
    tsNotice('请加入小组后再发帖！','点击去加入小组',tsUrl('group'));
}

foreach($arrGroupUser as $key=>$item){
    $arrGroup[$key] = aac('group')->getOneGroup($item['groupid']);
}

$title = '选择发帖小组';

include template('group');