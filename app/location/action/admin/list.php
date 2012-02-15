<?php 

switch($ts){
	case "one":
		$arrOne = $db->findAll("select * from ".dbprefix."area where referid='0'");
		
		include template("admin/list_one");
		break;
		
	case "two":
		$referid = $_GET['referid'];
		$arrTwo = $db->findAll("select * from ".dbprefix."area where referid='$referid'");
		
		include template("admin/list_two");
		break;
		
	case "three":
		$referid = $_GET['referid'];
		$arrThree = $db->findAll("select * from ".dbprefix."area where referid='$referid'");
		
		include template("admin/list_three");
		break;
}