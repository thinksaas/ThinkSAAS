<?php 
defined('IN_TS') or die('Access Denied.');

class photo{

	var $db;

	function photo($dbhandle){
		$this->db = $dbhandle;
	}
	
	//getPhotoForApp
	function getPhotoForApp($photoid){
		$strPhoto = $this->db->find("select * from ".dbprefix."photo where photoid='$photoid'");
		return $strPhoto;
	}
	
	function getSamplePhoto($photoid){
		$strPhoto = $this->db->find("select path,photourl from ".dbprefix."photo where photoid='$photoid'");
		return $strPhoto;
	}

}