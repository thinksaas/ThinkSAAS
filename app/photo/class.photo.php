<?php 
defined('IN_TS') or die('Access Denied.');

class photo extends tsApp{

	//构造函数
	public function __construct($db){
		parent::__construct($db);
	}
	
	//getPhotoForApp
	function getPhotoForApp($photoid){
		$strPhoto = $this->db->once_fetch_assoc("select * from ".dbprefix."photo where photoid='$photoid'");
		return $strPhoto;
	}
	
	function getSamplePhoto($photoid){
		$strPhoto = $this->db->once_fetch_assoc("select path,photourl from ".dbprefix."photo where photoid='$photoid'");
		return $strPhoto;
	}

}