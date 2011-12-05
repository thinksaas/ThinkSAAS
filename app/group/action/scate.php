<?php 
defined('IN_TS') or die('Access Denied.');
switch($ts){
	case "two":
		$oneid = $_GET['oneid'];
		$arrCate = $db->fetch_all_assoc("select * from ".dbprefix."group_cates where catereferid='$oneid'");
		
		if($arrCate){
			echo '<select id="twoid" name="twoid">';
			echo '<option value="0">请选择</option>';
			foreach($arrCate as $item){
				echo '<option value="'.$item['cateid'].'">'.$item['catename'].'</option>';
			}
			echo "</select>";
		}else{
			echo '';
		}
		break;
		
	case "three":
		$twoid = $_GET['twoid'];
		$arrCate = $db->fetch_all_assoc("select * from ".dbprefix."group_cates where catereferid='$twoid'");
		
		if($arrCate){
			echo '<select id="threeid" name="threeid">';
			echo '<option value="0">请选择</option>';
			foreach($arrCate as $item){
				echo '<option value="'.$item['cateid'].'">'.$item['catename'].'</option>';
			}
			echo "</select>";
		}else{
			echo '';
		}
		break;
}