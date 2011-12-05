<?php
defined('IN_TS') or die('Access Denied.');
//生成本地缓存文件
//省
$arrProvince = $db->fetch_all_assoc("select provinceid,provincename from ".dbprefix."app_location_province");
AppCacheWrite($arrProvince,'location','province.php');

//市

foreach($arrProvince as $item){
	$arrCity[$item['provinceid']] = $db->fetch_all_assoc("select cityid,cityname from ".dbprefix."app_location_city where isshow='0' and provinceid='".$item['provinceid']."'");
}
AppCacheWrite($arrCity,'location','city.php');

//区县
$arrCitys = $db->fetch_all_assoc("select cityid from ".dbprefix."app_location_city where isshow='0'");

foreach($arrCitys as $item){
	$arrArea[$item['cityid']] = $db->fetch_all_assoc("select areaid,areaname from ".dbprefix."app_location_area where isshow='0' and cityid='".$item['cityid']."'");
}
AppCacheWrite($arrArea,'location','area.php');

echo 'OK';