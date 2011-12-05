<?php 

switch($ts){
	case "one":
		$arrOne = $db->fetch_all_assoc("select * from ".dbprefix."area where referid='0'");
		
		include template("admin/list_one");
		break;
		
	case "two":
		$referid = $_GET['referid'];
		$arrTwo = $db->fetch_all_assoc("select * from ".dbprefix."area where referid='$referid'");
		
		include template("admin/list_two");
		break;
		
	case "three":
		$referid = $_GET['referid'];
		$arrThree = $db->fetch_all_assoc("select * from ".dbprefix."area where referid='$referid'");
		
		include template("admin/list_three");
		break;
}