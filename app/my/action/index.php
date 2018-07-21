<?php
defined('IN_TS') or die('Access Denied.');


//我的( 加入)小组
$arrGroupsList = $new['my']->findAll('group_user',array(
    'userid'=>$strUser['userid'],
),null,'groupid',12);

foreach($arrGroupsList as $key=>$item){
    $strGroup = aac('group')->getOneGroup($item['groupid']);
    if($strGroup){
        $arrGroup[] = $strGroup;
    }else{
        $new['my']->delete('group_user',array(
            'userid'=>$strUser['userid'],
            'groupid'=>$item['groupid'],
        ));
    }
}

#加入的小组数
$joinGroupNum = $new['my']->findCount('group_user',array(
    'userid'=>$strUser['userid'],
));

//我的帖子
$arrTopic = $new['my']->findAll('group_topic',array(
    'userid'=>$strUser['userid'],
),'addtime desc',null,10);


//我的文章
$arrArticle = $new ['my']->findAll ( 'article', array (
    'userid' => $strUser['userid'],
), 'addtime desc', null, 10 );



$title = '我的社区';
include template("index");