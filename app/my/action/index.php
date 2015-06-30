<?php
defined('IN_TS') or die('Access Denied.');


//我的( 加入)小组
$arrGroupsList = $new['my']->findAll('group_user',array(
    'userid'=>$strUser['userid'],
),null,'groupid',12);

foreach($arrGroupsList as $key=>$item){
    $arrGroup[] = aac('group')->getOneGroup($item['groupid']);
}

//我的帖子
$arrTopic = $new['my']->findAll('group_topic',array(
    'userid'=>$strUser['userid'],
),'addtime desc',null,10);


//我的文章
$arrArticle = $new ['my']->findAll ( 'article', array (
    'userid' => $strUser['userid'],
), 'addtime desc', null, 10 );


//我的资料
$arrAttach = $new['my']->findAll('attach_album',array(
    'userid'=>$strUser['userid'],
),'addtime desc',null,8);



$title = '我的社区';
include template("index");