<?php 
defined('IN_TS') or die('Access Denied.');
switch($ts){
	case "":
		break;
		
	//打分 
	case "do":
		
		$userid = intval($TS_USER['user']['userid']);
		
		if($userid == 0){
			echo '0';
			exit;
		}
		
		$score = intval($_POST['score']);
		$appleid = intval($_POST['appleid']);
		
		if($score < 2 || $score > 10 || $appleid == 0){
			echo '1';
			exit;
		}
		
		$scoreNum = $db->once_fetch_assoc("select count(*) from ".dbprefix."apple_score where `appleid`='$appleid' and `userid`='$userid'");
		if($scoreNum['count(*)'] > 0){
			echo '2';exit;
		}
		
		$db->query("insert into ".dbprefix."apple_score (`appleid`,`userid`,`score`) values ('$appleid','$userid','$score')");
		
		echo '3';exit;
		
		break;
}