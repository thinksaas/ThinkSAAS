<?php
defined('IN_TS') or die('Access Denied.');
class attach{

	var $db;

	function attach($dbhandle){
		$this->db = $dbhandle;
	}
	
	//获取单个附件
	function getOneAttach($attachid){
	
		$isattach = $this->db->findCount('attach',array('attachid'=>$attachid));
		
		if($isattach > '0'){
			$strAttach = $this->db->find("select * from ".dbprefix."attach where attachid='$attachid'");
			
			$menu = substr($strAttach['userid'],0,1);
			
			$strAttach['attachurl'] = 'uploadfile/attach/'.$menu.'/'.$strAttach['attachurl'];
			
			$strAttach['isattach'] = '1';
			
			
		}else{
			$strAttach['isattach'] = '0';
		}
		
		return $strAttach;
		
	}
	
}