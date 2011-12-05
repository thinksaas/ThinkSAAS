<?php 
	/*
	 *模型：邮件
	 *class.photo.php
	 *By QINIAO
	 */
	 
	class photo{

		var $db;

		function photo($dbhandle){
			$this->db = $dbhandle;
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