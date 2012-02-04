<?php
defined('IN_TS') or die('Access Denied.');

class user{

	private $db;

	public function user($dbhandle){
		$this->db = $dbhandle;
	}
	
	//获取用户的信息
	public function getOneUser($userid){
		
		$strUser = $this->db->once_fetch_assoc("select * from ".dbprefix."user_info where `userid`='$userid'");
		
		//头像
		if($strUser['face'] != ''){
			$strUser['bigface'] = SITE_URL.miniimg($strUser['face'],'user',120,120,$strUser['path']);
			$strUser['face'] = SITE_URL.miniimg($strUser['face'],'user',48,48,$strUser['path'],1);
			
		}else{
			$strUser['bigface'] = SITE_URL.'public/images/user_large.jpg';
			$strUser['face']	= SITE_URL.'public/images/user_normal.jpg';
		}
		
		return $strUser;
	}			
}