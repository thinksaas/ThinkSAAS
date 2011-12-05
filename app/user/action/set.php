<?php
defined('IN_TS') or die('Access Denied.');

//设置用户信息

$userid = intval($TS_USER['user']['userid']);

if($userid == '0') header("Location: ".SITE_URL."index.php");

$strUser = $new['user']->getUserForEdit($userid);

if($userid != $strUser['userid']) header("Location: ".SITE_URL."index.php");


switch($ts){
	case "base":
	
		$strArea = aac('location')->getAreaForApp($strUser['areaid']);
		$title = '基本设置';
		include template("set_base");
		break;
		
	case "face":

		$title = '头像设置';
		include template("set_face");

		break;
	
	//设置密码
	case "pwd":
	
		$title = '密码修改';
		include template("set_pwd");

		break;
		
	//设置常居地 
	case "city":
		
		$strArea = aac('location')->getAreaForApp($strUser['areaid']);
	
		//调出省份数据
		$arrOne = $db->fetch_all_assoc("select * from ".dbprefix."area where referid='0'");
		
		$title = '常居地修改';
		include template("set_city");

		break;
	
	//个人标签
	case "tag":
	
		$arrTag = aac('tag')->getObjTagByObjid('user','userid',$userid);
	
		$title = '个人标签修改';
		include template("set_tag");
		break;
	//
	case "weibo":
	
		$o = new WeiboOAuth( WB_AKEY , WB_SKEY , $_SESSION['keys']['oauth_token'] , $_SESSION['keys']['oauth_token_secret']  );
		$last_key = $o->getAccessToken(  $_REQUEST['oauth_verifier'] ) ;

		$_SESSION['last_key'] = $last_key;
		
		$title = "微博同步";
		
		include template("set_weibo");
		
		break;
}