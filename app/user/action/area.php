<?php 
defined('IN_TS') or die('Access Denied.');
switch($ts){
	case "two":
		$oneid = $_GET['oneid'];
		$arrArea = $db->fetch_all_assoc("select * from ".dbprefix."area where referid='$oneid'");
		
		if($arrArea){
			echo '<select id="twoid" name="twoid">';
			echo '<option value="0">请选择</option>';
			foreach($arrArea as $item){
				echo '<option value="'.$item['areaid'].'">'.$item['areaname'].'</option>';
			}
			echo "</select>";
		}else{
			echo '';
		}
		break;
		
	case "three":
		$twoid = $_GET['twoid'];
		$arrArea = $db->fetch_all_assoc("select * from ".dbprefix."area where referid='$twoid'");
		
		if($arrArea){
			echo '<select id="threeid" name="threeid">';
			echo '<option value="0">请选择</option>';
			foreach($arrArea as $item){
				echo '<option value="'.$item['areaid'].'">'.$item['areaname'].'</option>';
			}
			echo "</select>";
		}else{
			echo '';
		}
		break;
}