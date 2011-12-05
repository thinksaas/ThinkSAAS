<?php 
switch($ts){
	case "":
		$areaid = $_GET['areaid'];
		$strArea = $db->once_fetch_assoc("select * from ".dbprefix."area where areaid='$areaid'");
		include template("admin/edit");
		break;
	case "do":
		$areaid = $_POST['areaid'];
		$areaname = $_POST['areaname'];
		$zm = $new['location']->getfirstchar($areaname);
		$db->query("update ".dbprefix."area set `areaname`='$areaname',`zm`='$zm' where `areaid`='$areaid'");
		
		qiMsg("区域名称修改成功！");
		
		break;
}