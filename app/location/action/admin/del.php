<?php 
$areaid = $_GET['areaid'];

$strArea = $db->once_fetch_assoc("select * from ".dbprefix."area where areaid='$areaid'");

if($strArea['referid'] == '0'){
	$arrTwo = $db->fetch_all_assoc("select * from ".dbprefix."area where referid='$areaid'");
	
	foreach($arrTwo as $item){
		//删除三级
		$db->query("delete from ".dbprefix."area where referid='".$item['areaid']."'");
	}
	
	//删除二级
	$db->query("delete from ".dbprefix."area where referid='$areaid'");
	//删除一级
	$db->query("delete from ".dbprefix."area where areaid='$areaid'");
	
	qiMsg("顶级区域及其下所有区域删除成功！",'点击返回顶级区域！',SITE_URL.'index.php?app=location&ac=admin&mg=list&ts=one');
	
}elseif($strArea['referid']>0){
	
	//这里可以不用指定是二级还是三级，只要循环就可以
	$arrArea = $db->fetch_all_assoc("select * from ".dbprefix."area where referid='$areaid'");
	
	foreach($arrArea as $item){
		//删除三级
		$db->query("delete from ".dbprefix."area where referid='".$item['areaid']."'");
	}
	
	//删除二级
	$db->query("delete from ".dbprefix."area where areaid='$areaid'");
	
	qiMsg("区域删除成功！",'点击返回顶级区域！',SITE_URL.'index.php?app=location&ac=admin&mg=list&ts=one');
	
}