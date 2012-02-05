<?php 

switch($ts){
	
	case "about":
		
		$strInfo = $db->once_fetch_assoc("select * from ".dbprefix."home_info where `infokey`='about'");
		
		include template('admin/info_about');
		break;
		
	case "about_do":
		
		$infocontent = $_POST['infocontent'];
		$db->query("update ".dbprefix."home_info set `infocontent`='$infocontent' where `infokey`='about'");
		
		qiMsg("修改成功！");
		
		break;
		
	case "contact":
		$strInfo = $db->once_fetch_assoc("select * from ".dbprefix."home_info where `infokey`='contact'");
		include template('admin/info_contact');
		break;
		
	case "contact_do":
		
		$infocontent = $_POST['infocontent'];
		$db->query("update ".dbprefix."home_info set `infocontent`='$infocontent' where `infokey`='contact'");
		
		qiMsg("修改成功！");
		
		break;
		
	case "agreement":
		$strInfo = $db->once_fetch_assoc("select * from ".dbprefix."home_info where `infokey`='agreement'");
		include template('admin/info_agreement');
		break;
		
	case "agreement_do":
		
		$infocontent = $_POST['infocontent'];
		$db->query("update ".dbprefix."home_info set `infocontent`='$infocontent' where `infokey`='agreement'");
		
		qiMsg("修改成功！");
		
		break;
		
		
	case "privacy":
		$strInfo = $db->once_fetch_assoc("select * from ".dbprefix."home_info where `infokey`='privacy'");
		include template('admin/info_privacy');
		break;
		
	case "privacy_do":
		
		$infocontent = $_POST['infocontent'];
		$db->query("update ".dbprefix."home_info set `infocontent`='$infocontent' where `infokey`='privacy'");
		
		qiMsg("修改成功！");
		
		break;
	
}