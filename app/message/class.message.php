<?php
/*
 *模型：message
 *class.message.php
 *By QiuJun
 */
 
class message{

	var $db;

	function message($dbhandle){
		$this->db = $dbhandle;
	}
	
	//发送消息
	function sendmsg($useridd,$touseridd,$contentt){
			$userid = intval($useridd);
			$touserid = intval($touseridd);
			$content = trim($contentt);
  if($touserid||$contect){
		$arrData = array(
			'userid'		=> $userid,
			'touserid'		=> $touserid,
			'content'		=> $content,
			'addtime'			=> time(),
		);
		$messageid = $this->db->insertArr($arrData,dbprefix.'message');
		
	}
}
	
}