<?php 

switch($ts){
	case "one":
		
		include template("admin/add_one");
		break;
	
	case "one_do":
		
		$areaname = $_POST['areaname'];
		$zm = $new['location']->getfirstchar($areaname);
		
		$db->query("insert into ".dbprefix."area (`areaname`,`zm`) values ('$areaname','$zm')");
		
		qiMsg("顶级区域添加成功",'返回顶级区域列表',SITE_URL.'index.php?app=location&ac=admin&mg=list&ts=one');
		break;
		
	case "two":
		$referid = $_GET['referid'];
		$strArea = $db->once_fetch_assoc("select * from ".dbprefix."area where areaid='$referid'");
		
		include template("admin/add_two");
		
		break;
		
	case "two_do":
		
		$areaname = $_POST['areaname'];
		$referid = $_POST['referid'];
		
		$zm = $new['location']->getfirstchar($areaname);
		
		$db->query("insert into ".dbprefix."area (`areaname`,`zm`,`referid`) values ('$areaname','$zm','$referid')");
		
		qiMsg("二级区域添加成功",'返回二级区域列表',SITE_URL.'index.php?app=location&ac=admin&mg=list&ts=two&referid='.$referid);
		break;
		
	case "three":
		$referid = $_GET['referid'];
		$strArea = $db->once_fetch_assoc("select * from ".dbprefix."area where areaid='$referid'");
		
		include template("admin/add_three");
		
		break;
		
	case "three_do":
		
		$areaname = $_POST['areaname'];
		$referid = $_POST['referid'];
		
		$zm = $new['location']->getfirstchar($areaname);
		
		$db->query("insert into ".dbprefix."area (`areaname`,`zm`,`referid`) values ('$areaname','$zm','$referid')");
		
		qiMsg("三级区域添加成功",'返回三级区域列表',SITE_URL.'index.php?app=location&ac=admin&mg=list&ts=three&referid='.$referid);
		break;
}