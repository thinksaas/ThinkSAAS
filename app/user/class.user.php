<?php
defined('IN_TS') or die('Access Denied.');

class user extends tsApp{

	//构造函数
	public function __construct($db){
		parent::__construct($db);
	}
	
	//获取最新会员
	function getNewUser($num){
		$arrNewUserId = $this->db->fetch_all_assoc("select userid from ".dbprefix."user_info order by addtime desc limit $num");
		foreach($arrNewUserId as $item){
			$arrNewUser[] = $this->getOneUser($item['userid']);
		}
		return $arrNewUser;
	}
	
	//获取活跃会员
	function getHotUser($num){
		$arrNewUserId = $this->db->fetch_all_assoc("select userid from ".dbprefix."user_info order by uptime desc limit $num");
		foreach($arrNewUserId as $item){
			$arrHotUser[] = $this->getOneUser($item['userid']);
		}
		return $arrHotUser;
	}
	
	//获取一个用户的信息
	function getOneUser($userid){
	
		$strUser = $this->find('user_info',array(
			'userid'=>$userid,
		));
		
		//头像
		if($strUser['face']){
			$strUser['face_120'] = SITE_URL.tsXimg($strUser['face'],'user',120,120,$strUser['path']);
			$strUser['face_32'] = SITE_URL.tsXimg($strUser['face'],'user',32,32,$strUser['path'],1);
			$strUser['face'] = SITE_URL.tsXimg($strUser['face'],'user',48,48,$strUser['path'],1);
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
		
		$isUser = $this->findCount('user',array('userid'=>$userid));
		
		if($isUser == 0){
			return false;
		}else{
			return true;
		}
	}
	
	//是否登录 
	public function isLogin(){
	
		$userid = intval($_SESSION['tsuser']['userid']);
		
		if($userid>0){
			if($this->isUser($userid)){
				return $userid;
			}else{
				header("Location: ".SITE_URL.tsUrl('user','login',array('ts'=>'out')));
				exit;
			}
		}else{
			header("Location: ".SITE_URL.tsUrl('user','login',array('ts'=>'out')));
		}
	}
	
	public function getOneArea($areaid){
	
		$strArea = $this->find('area',array('areaid'=>$areaid));
		return $strArea;
	
	}
	
	//析构函数
	public function __destruct(){
		
	}
					
}