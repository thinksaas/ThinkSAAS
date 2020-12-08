<?php
defined('IN_TS') or die('Access Denied.');

$topicid = tsIntval($_GET['id']);

$strTopic = $new['topic']->find('topic',array(
    'topicid'=>$topicid,
));


if($strTopic==''){
    header("HTTP/1.1 404 Not Found");
    header("Status: 404 Not Found");
    $title = '404';
    include pubTemplate("404");
    exit;
}

#永久性跳转到其他项目
if($strTopic['ptable'] && $strTopic['pid']){
    Header("HTTP/1.1 301 Moved Permanently");
    header('Location: '.getProjectUrl($strTopic['ptable'],$strTopic['pid']));
    exit();
}

//帖子审核 
if($strTopic['isaudit']==1 && $GLOBALS['TS_USER']['isadmin']==0){
    tsNotice('内容审核中......');
}


//小组信息
if($strTopic['groupid']){
    $strGroup = aac('group')->getOneGroup($strTopic['groupid']);
    // 判断会员是否加入该小组
    $strGroupUser = '';
    if(tsIntval($TS_USER['userid'])){
        $strGroupUser = $new['topic']->find('group_user',array(
            'userid'=>tsIntval($TS_USER['userid']),
            'groupid'=>$strTopic['groupid'],
        ));
    }
}

// 浏览方式
if ($strGroup['isopen'] == '1' && $strGroupUser == '') {
    $title = $strTopic['title'];
    include template("topic_isopen");exit;
}elseif($strGroup['isopen'] == '1' && $strGroupUser && $TS_APP['ispayjoin']==1 && $strGroupUser['endtime']!='0000-00-00' && $strGroupUser['endtime']!='1970-01-01' && $strGroupUser['endtime'] <date('Y-m-d')){
    $title = $strTopic['title'];
    include template("topic_xuqi");exit;
}

$strTopic['title'] = tsTitle($strTopic['title']);

$tpUrl = tpPage($strTopic['content'],'topic','show',array('id'=>$topicid));

$strTopic['content'] = tsDecode($strTopic['content'],$tp);

//判断是否评论后显示帖子内容
$isComment = $new['topic']->findCount('comment', array(
    'ptable'=>'topic',
    'pkey'=>'topicid',
    'pid' => $strTopic['topicid'],
    'userid' => tsIntval($TS_USER['userid']),
));

if($strTopic['iscommentshow']==1 && $isComment==0 && $strTopic['userid']!=tsIntval($TS_USER['userid'])){
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
    $strTopic['type'] = $new['topic']->find('topic_type', array(
        'typeid' => $strTopic['typeid'],
    ));
}


$strTopic['content'] = @preg_replace("/\[@(.*)\:(.*)]/U","<a href='".tsUrl('user','space',array('id'=>'$2'))." ' rel=\"face\" uid=\"$2\"'>@$1</a>",$strTopic['content']);



//处理通过小程序或者客户端发的图片
$strTopic['photos'] = $new['topic']->getTopicPhoto($topicid);



#应用扩展
$strProject = $new['topic']->getProject($strTopic['ptable'],$strTopic['pkey'],$strTopic['pid']);
$strTopic['video'] = $strProject['video'];



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
$title = $strTopic['title'];


// 评论列表开始
$page = tsIntval($_GET['page'],1);
$url = tsUrl('topic', 'show', array('id' => $topicid, 'page' => ''));
$lstart = $page * 15-15;
$arrComment = aac('pubs')->getCommentList('topic','topicid',$strTopic['topicid'],$page,$lstart,$strTopic['userid']);
$commentNum = aac('pubs')->getCommentNum('topic','topicid',$strTopic['topicid']);
$pageUrl = pagination($commentNum, 15, $page, $url);
// 评论列表结束


//7天内的热门帖子
$arrHotTopic = $new['topic']->getHotTopic(7);

//推荐帖子
$arrRecommendTopic = $new['topic']->getRecommendTopic();


//本组热门帖子
$arrGroupHotTopic = $new['topic']->findAll('topic',array(
    'groupid'=>$strGroup['groupid'],
    'isaudit'=>0,
),'count_view desc','topicid,title',10);

// 最新帖子
$newTopic = $new['topic']->findAll('topic',array(
    'isaudit'=>'0',
),'addtime desc','topicid,title',10);



//判断用户可阅读帖子：0可读1不可读
$isread = 0;
if($strTopic['score']>0) $isread = 1;
if($TS_USER['userid'] && $strTopic['userid']==$TS_USER['userid']) $isread=0;
if($TS_USER['userid'] && $strTopic['userid']!=$TS_USER['userid'] && $strTopic['score']>0){
    $isTopicUser = $new['topic']->findCount('topic_user',array(
        'topicid'=>$topicid,
        'userid'=>$TS_USER['userid'],
    ));
    if($isTopicUser>0) $isread=0;
}
if($TS_USER['isadmin']==1) $isread=0;




$sitedesc = cututf8(t($strTopic['content']),0,100);

$content = $strTopic['content'];
#钩子
doAction('topic',$content);

include template('show');

// 增加浏览次数

$new['topic']->update('topic', array(
    'topicid' => $strTopic['topicid'],
), array(
    'count_view' => $strTopic['count_view'] + 1,
));