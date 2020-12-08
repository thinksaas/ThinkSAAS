<?php 
defined('IN_TS') or die('Access Denied.');

//用户是否登录
$userid = aac('user')->isLogin();

//普通用户不允许删除内容
if($TS_SITE['isallowdelete'] && $TS_USER ['isadmin'] == 0) tsNotice('系统不允许用户删除内容，请联系管理员删除！');
		
$topicid = tsIntval($_GET['topicid']);
$strTopic = $new['topic']->getOneTopic($topicid);
$strGroup = aac('group')->getOneGroup($strTopic['groupid']);
$strGroupUser = $new['topic']->find('group_user',array(
    'userid'=>$userid,
    'groupid'=>$groupid,
));

//系统管理员删除
if($TS_USER['isadmin'] == '1'){

    #用户记录
    aac('pubs')->addLogs('topic','topicid',$topicid,$userid,$strTopic['title'],$strTopic['content'],2);

    $new['topic']->deleteTopic($strTopic);
    tsNotice('帖子删除成功！','点击返回小组首页',tsUrl('group'));
}

//其他人员删除
if($userid == $strTopic['userid'] || $userid == $strGroup['userid'] || $strGroupUser['isadmin']=='1'){
    
    $new['topic']->update('topic',array(
        'topicid'=>$topicid,
    ),array(
        'isdelete'=>1,
    ));

    //处理积分
    aac('user')->doScore($GLOBALS['TS_URL']['app'],$GLOBALS['TS_URL']['ac'],$GLOBALS['TS_URL']['ts'],$strTopic['userid']);

    tsNotice('你的删除帖子申请已经提交！');
    
}