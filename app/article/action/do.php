<?php 
defined('IN_TS') or die('Access Denied.');
switch($ts){
	case "isaudit":
		$articleid = $_GET['articleid'];
		$strArticle = $db->once_fetch_assoc("select * from ".dbprefix."article where `articleid`='$articleid'");
		
		if($strArticle['isaudit']==1){
			$db->query("update ".dbprefix."article set `isaudit`='0' where `articleid`='$articleid'");
		}else{
			$db->query("update ".dbprefix."article set `isaudit`='1' where `articleid`='$articleid'");
		}
		
		
		//发送通知
		//msg start
		$msg_userid = '0';
		$msg_touserid = $strArticle['userid'];
		$msg_content = '恭喜，你的文章：《'.$strArticle['title'].'》通过审核^_^ <br />'.SITE_URL.'index.php?app=article&ac=show&articleid='.$articleid;
		aac('message')->sendmsg($msg_userid,$msg_touserid,$msg_content);
		//msg end
		
		header("Location: ".SITE_URL.tsurl('article','show',array('articleid'=>$articleid)));
		
		break;
		
	//删除文章
	case "del_article":
		
		$userid = intval($TS_USER['user']['userid']);
		
		$articleid = intval($_GET['articleid']);
		
		$strArticle = $db->once_fetch_assoc("select userid from ".dbprefix."article where `articleid`='$articleid'");
		
		if(intval($TS_USER['user']['isadmin'])=='1' || $userid == $strArticle['userid']){
			$db->query("delete from ".dbprefix."article where `articleid`='$articleid'");
			$db->query("delete from ".dbprefix."article_comment where `articleid`='$articleid'");
			
			header("Location: ".SITE_URL.tsurl('article'));
			
		}else{
			qiMsg("非法操作！");
		}
		
		break;
	
}