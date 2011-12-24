<?php
defined('IN_TS') or die('Access Denied.'); 
$userid = intval($TS_USER['user']['userid']);

if($userid == 0){
	header("Location: ".SITE_URL.tsurl('user','login'));
	exit;
}

switch($ts){
	case "":
		
		$articleid = intval($_GET['articleid']);
		
		$cateid = intval($_GET['cateid']);
		
		$strArticle = $db->once_fetch_assoc("select * from ".dbprefix."article where `articleid`='$articleid'");
		
		$arrCate = $new['article']->getArrCate();
		
		$title = '修改文章';
		include template('edit');
		break;
		
	case "do":
		
		$articleid = $_POST['articleid'];
		$cateid = $_POST['cateid'];
		$title	= htmlspecialchars(trim($_POST['title']));
		$content = trim($_POST['content']);
		$addtime = time();
		
		if($title=='' || $content=='') qiMsg("标题和内容都不能为空！");
		
		$isphoto = 0;
		$isattach = 0;
		
		//判断是否有图片和附件
		preg_match_all('/\[(photo)=(\d+)\]/is', $content, $photo);
		if($photo[2]){
			$isphoto = '1';
		}
		//判断附件
		preg_match_all('/\[(attach)=(\d+)\]/is', $content, $attach);
		if($attach[2]){
			$isattach = '1';
		}
		
		$db->query("update ".dbprefix."article set `cateid`='$cateid',`title`='$title',`content`='$content',`isphoto`='$isphoto',`isattach`='$isattach' where `articleid`='$articleid'");
		
		header("Location: ".SITE_URL.tsurl('article','show',array('articleid'=>$articleid)));
		
		break;
}