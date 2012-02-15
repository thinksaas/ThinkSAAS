<?php
defined('IN_TS') or die('Access Denied.');
class apple{

	var $db;

	function apple($dbhandle){
		$this->db = $dbhandle;
	}
	
	//获取苹果机 
	function getApple($appleid){
		$strApple = $this->db->find("select * from ".dbprefix."apple where `appleid`='$appleid'");
		return $strApple;
	}
	
	//根据模型属性ID获取模型属性名称 
	function getVirtueName($virtueid){
		
		$strVirtue = $this->db->find("select * from ".dbprefix."apple_virtue where `virtueid`='$virtueid'");
		
		return $strVirtue['virtuename'];
		
	}
	
	
	//是否有苹果机 
	function isApple($appleid){
		$appleNum = $this->db->find("select count(*) from ".dbprefix."apple where `appleid`='$appleid'");
		
		if($appleNum['count(*)'] == 0){
			header("Location: ".SITE_URL);
			exit;
		}
		
	}
	
	//是否存在点评
	function isReview($reviewid){
		$reviewNum = $this->db->find("select count(*) from ".dbprefix."apple_review where `reviewid`='$reviewid'");
		
		if($reviewNum['count(*)'] == 0){
			header("Location: ".SITE_URL);
			exit;
		}
		
	}
	
}