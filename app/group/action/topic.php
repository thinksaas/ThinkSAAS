<?php
defined('IN_TS') or die('Access Denied.');

$topicid = intval($_GET['id']);

$strTopic = $new['group']->find('group_topic',array(
    'topicid'=>$topicid,
));


if($strTopic==''){
    header("HTTP/1.1 404 Not Found");
    header("Status: 404 Not Found");
    $title = '404';
    include pubTemplate("404");
    exit;
}



//帖子审核 
if($strTopic['isaudit']==1 && $GLOBALS['TS_USER']['isadmin']==0){
    tsNotice('内容审核中......');
}


// 小组
$strGroup = $new['group']->getOneGroup($strTopic['groupid']);

// 判断会员是否加入该小组
$strGroupUser = '';
if(intval($TS_USER['userid'])){
    $strGroupUser = $new['group']->find('group_user',array(
        'userid'=>intval($TS_USER['userid']),
        'groupid'=>$strTopic['groupid'],
    ));
}


// 浏览方式
if ($strGroup['isopen'] == '1' && $strGroupUser == '') {
    $title = $strTopic['title'];
    include template("topic_isopen");exit;
}elseif($strGroup['isopen'] == '1' && $TS_APP['ispayjoin']==1 && $strGroupUser['endtime']!='0000-00-00' && $strGroupUser['endtime'] <date('Y-m-d')){
    $title = $strTopic['title'];
    include template("topic_xuqi");exit;
}



$strTopic['title'] = tsTitle($strTopic['title']);

$tpUrl = tpPage($strTopic['content'],'group','topic',array('id'=>$topicid));

$strTopic['content'] = tsDecode($strTopic['content'],$tp);

//判断是否评论后显示帖子内容
$isComment = $new['group']->findCount('group_topic_comment', array(
    'userid' => intval($TS_USER['userid']),
    'topicid' => $strTopic['topicid'],
));

if($strTopic['iscommentshow']==1 && $isComment==0 && $strTopic['userid']!=intval($TS_USER['userid'])){
    $strTopic['content'] = '<div class="alert alert-info">你需要回复后才可以浏览帖子内容！</div>';
}


//编辑的数据
if($strTopic['userid']==$TS_USER['userid']){

    if($strTopic['isdelete']=='1'){
        tsNotice('你的帖子删除中...');
    }

}

// 帖子分类
if ($strTopic['typeid'] != '0'){
    $strTopic['type'] = $new['group']->find('group_topic_type', array(
        'typeid' => $strTopic['typeid'],
    ));
}


$strTopic['content'] = @preg_replace("/\[@(.*)\:(.*)]/U","<a href='".tsUrl('user','space',array('id'=>'$2'))." ' rel=\"face\" uid=\"$2\"'>@$1</a>",$strTopic['content']);



//处理通过小程序或者客户端发的图片
$strTopic['photos'] = $new['group']->getTopicPhoto($topicid);




// 帖子标签
$strTopic['tags'] = aac('tag')->getObjTagByObjid('topic', 'topicid', $topicid);
$strTopic['user'] = aac('user')->getSimpleUser($strTopic['userid']);

//把标签作为关键词
if($strTopic['tags']){
    foreach($strTopic['tags'] as $key=>$item){
        $arrTag[] = $item['tagname'];
    }
    $sitekey = arr2str($arrTag);
}else{
    $sitekey = $strTopic['title'];
}
//标题
$title = $strTopic['title'].'_'.$strGroup['groupname'];


// 评论列表开始
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$url = tsUrl('group', 'topic', array('id' => $topicid, 'page' => ''));

$lstart = $page * 15-15;

$arrComment = $new['group']->findAll('group_topic_comment',array(
    'topicid'=>$topicid,
),'addtime desc',null,$lstart.',15');

foreach($arrComment as $key => $item)
{
    $arrTopicComment[] = $item;
    $arrTopicComment[$key]['l'] = (($page-1) * 15) + $key + 1;
    $arrTopicComment[$key]['user'] = aac('user')->getSimpleUser($item['userid']);

    $arrTopicComment[$key]['content'] = tsDecode($item['content']);

    $arrTopicComment[$key]['recomment'] = $new['group']->recomment($item['referid']);

    ####评论关联附件开始####
    if($TS_APP['istopicattach']){
        $arrTopicComment[$key]['attach'] = $new['group']->getCommentAttach($item['commentid']);
    }
    ####评论关联附件结束####

}

$commentNum = $new['group']->findCount('group_topic_comment',array(
    'topicid'=>$strTopic['topicid'],
));

$pageUrl = pagination($commentNum, 15, $page, $url);
// 评论列表结束


//7天内的热门帖子
$arrHotTopic = $new['group']->getHotTopic(7);

//推荐帖子
$arrRecommendTopic = $new['group']->getRecommendTopic();


//本组热门帖子
$arrGroupHotTopic = $new['group']->findAll('group_topic',array(
    'groupid'=>$strGroup['groupid'],
    'isaudit'=>0,
),'count_view desc','topicid,title',10);

// 最新帖子
$newTopic = $new['group']->findAll('group_topic',array(
    'isaudit'=>'0',
),'addtime desc','topicid,title',10);




####帖子关联附件APP开始####
if($TS_APP['istopicattach']){
    $arrAttach = $new['group']->getTopicAttach($strTopic['topicid']);
}
####帖子关联附件APP结束####


####帖子关联视频APP开始####
if($TS_APP['istopicvideo']){
    $arrVideo = $new['group']->getTopicVideo($strTopic['topicid']);
}
####帖子关联视频APP开始####


$sitedesc = cututf8(t($strTopic['content']),0,100);

include template('topic');

// 增加浏览次数

$new['group']->update('group_topic', array(
    'topicid' => $strTopic['topicid'],
), array(
    'count_view' => $strTopic['count_view'] + 1,
));