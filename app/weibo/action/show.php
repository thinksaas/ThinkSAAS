<?php 
defined('IN_TS') or die('Access Denied.');
$weiboid = intval($_GET['id']);
$strWeibo = $new['weibo']->getOneWeibo($weiboid);
//comment 
$page = isset($_GET['page']) ? intval($_GET['page']) : '1';
$url = tsUrl('weibo','show',array('id'=>$weiboid,'page'=>''));
$lstart = $page*20-20;

$arrComments = $new['weibo']->findAll('weibo_comment',array(
	'weiboid'=>$weiboid,
),'addtime desc','commentid',$lstart.',20');

foreach($arrComments as $key=>$item){
	$arrComment[] = $new['weibo']->getOneComment($item['commentid']);
}

$commentNum = $new['weibo']->findCount('weibo_comment',array(
	'weiboid'=>$weiboid,
));

$pageUrl = pagination($commentNum, 20, $page, $url);

$title = '微博#'.$weiboid;

include template('show');