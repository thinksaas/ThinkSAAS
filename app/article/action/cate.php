<?php 
defined('IN_TS') or die('Access Denied.');
$cateid = intval($_GET['cateid']);
$strCate = $new['article']->getOneCate($cateid);

//列表 
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$url = SITE_URL.tsurl('article','cate',array('cateid'=>$cateid,'page'=>''));
$lstart = $page*10-10;

$arrArticles = $db->fetch_all_assoc("select * from ".dbprefix."article where `cateid`='$cateid' and `isaudit`='0' order by addtime desc limit $lstart, 10");

$articleNum = $db->once_fetch_assoc("select count(*) from ".dbprefix."article where `cateid`='$cateid'");

$pageUrl = pagination($articleNum['count(*)'], 10, $page, $url);

foreach($arrArticles as $key=>$item){
	$arrArticle[] = $item;
	$arrArticle[$key]['photo'] = $new['article']->getOnePhoto($item['content']);
	$arrArticle[$key]['content'] = getsubstrutf8(t($item['content']),0,200);
	$arrArticle[$key]['user'] = aac('user')->getUserForApp($item['userid']);
}

//分类 
$arrCate = $new['article']->getArrCate();

$title = $strCate['catename'];

include template('cate');