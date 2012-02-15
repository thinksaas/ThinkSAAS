<?php
defined('IN_TS') or die('Access Denied.');

class user{

	public $db;

	public function __construct($dbhandle){
		$this->db = $dbhandle;
	}
	
	//获取最新会员
	function getNewUser($num){
		$arrNewUserId = $this->db->findAll("select userid from ".dbprefix."user_info order by addtime desc limit $num");
		foreach($arrNewUserId as $item){
			$arrNewUser[] = $this->getOneUser($item['userid']);
		}
		return $arrNewUser;
	}
	
	//获取活跃会员
	function getHotUser($num){
		$arrNewUserId = $this->db->findAll("select userid from ".dbprefix."user_info order by uptime desc limit $num");
		foreach($arrNewUserId as $item){
			$arrHotUser[] = $this->getOneUser($item['userid']);
		}
		return $arrHotUser;
	}
	 
	function getAllUser($page = 1, $prePageNum){
		$start_limit = !empty($page) ? ($page - 1) * $prePageNum : 0;
		$limit = $prePageNum ? "LIMIT $start_limit, $prePageNum" : '';
		$users	= $this->db->findAll("select * from ".dbprefix."user order by userid desc $limit");
		if($users){
		foreach($users as $item){
			$arrUser[]	= $this->getOneUser($item['userid']);	
		}}
		
		return $arrUser;
	}
	
	//
	 
	function getUserNum($virtue, $setvirtue){
		$where = 'where '.$virtue.'='.$setvirtue.'';
		$res = "SELECT * FROM ".dbprefix."user $where";
		$userNum = $this->db->findCount($res);
		return $userNum;
	}
	
	//获取一个用户的信息
	function getOneUser($userid){
	
		$strUser = $this->db->find("select * from ".dbprefix."user_info where userid='$userid'");
		
		//头像
		if($strUser['face']){
			$strUser['face_120'] = SITE_URL.miniimg($strUser['face'],'user',120,120,$strUser['path']);
			$strUser['face_32'] = SITE_URL.miniimg($strUser['face'],'user',32,32,$strUser['path'],1);
			$strUser['face'] = SITE_URL.miniimg($strUser['face'],'user',48,48,$strUser['path'],1);
		}else{
			$strUser['face_120'] = SITE_URL.'public/images/user_large.jpg';
			$strUser['face_32'] = SITE_URL.'public/images/user_normal.jpg';
			$strUser['face']	= SITE_URL.'public/images/user_normal.jpg';
		}
		
		//地区
		if($strUser['areaid'] > 0){
		
			$strUser['area'] = $this->getOneArea($strUser['areaid']);
		}else{
			$strUser['area'] = array(
				'areaid'	=> '0',
				'areaname' => '火星',
			);
		}
		
		//签名
		$pattern='/(http:\/\/|https:\/\/|ftp:\/\/)([\w:\/\.\?=&-_]+)/is';

		$strUser['signed'] = hview(preg_replace($pattern, '<a rel="nofollow" target="_blank" href="\1\2">\1\2</a>', $strUser['signed']));
		
		return $strUser;
	}
	
	//用户是否存在
	public function isUser($userid){
		$isUser = $this->db->find("select count(*) from ".dbprefix."user where `userid`='$userid'");
		if($isUser['count(userid)'] == 0){
			return false;
		}else{
			return true;
		}
	}
	
	public function getOneArea($areaid){
		$strArea = $this->db->find("select * from ".dbprefix."area where `areaid`='$areaid'");
		return $strArea;
	}
	
	//析构函数
	public function __destruct(){
		
	}
					
}