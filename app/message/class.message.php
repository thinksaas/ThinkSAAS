<?php
defined('IN_TS') or die('Access Denied.');
 
class message{

	public $db;

	public function __construct($dbhandle){
		$this->db = $dbhandle;
	}
	
	//发送消息
	function sendmsg($useridd,$touseridd,$contentt){
	
		$userid = intval($useridd);
		$touserid = intval($touseridd);
		$content = addslashes(trim($contentt));
			
		if($touserid||$contect){

			$messageid = $this->db->create(dbprefix.'message',array(
				'userid'		=> $userid,
				'touserid'		=> $touserid,
				'content'		=> $content,
				'addtime'			=> time(),
			));
			
		}
}
	
}