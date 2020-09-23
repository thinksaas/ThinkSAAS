<?php 
defined('IN_TS') or die('Access Denied.');

#最新话题
$page = tsIntval($_GET['page'],1);
$url = tsUrl ( 'topic', 'index', array ('page' => '') );
$lstart = $page * 20 - 20;

$arrTopic = $new['topic']->findAll('topic',array(
    'isaudit'=>0,
),'istop desc,uptime desc','topicid,ptable,pkey,pid,pjson,userid,groupid,title,gaiyao,score,label,count_comment,count_view,iscommentshow,istop,uptime',$lstart,20);
	
foreach($arrTopic as $key=>$item){
    $arrTopic[$key]['title']=tsTitle($item['title']);
    $arrTopic[$key]['gaiyao']=tsTitle($item['gaiyao']);
    $arrTopic[$key]['user'] = aac('user')->getSimpleUser($item['userid']);
    $arrTopic[$key]['group'] = aac('group')->getOneGroup($item['groupid']);
    #应用扩展
    if($item['pjson']){
        $arrTopic[$key]['pjson'] = json_decode($item['pjson'],true);
    }
}

$topicNum = $new ['topic']->findCount ( 'topic', array (
    'isaudit' => '0'
) );

$pageUrl = pagination ( $topicNum, 20, $page, $url );

#推荐话题
$arrRecommendTopic = $new['topic']->findAll('topic',array(
    'isrecommend'=>1,
    'isaudit'=>0,
),'topicid desc','topicid,title',10);

#热门话题
$arrHotTopic = $new['topic']->findAll('topic',array(
    'isaudit'=>0,
),'count_comment desc','topicid,title',10);

include template('index');