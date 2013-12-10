<?php
defined('IN_TS') or die('Access Denied.');
 
class message extends tsApp{

	//构造函数
	public function __construct($db){
		parent::__construct($db);
	}
	
	//发送消息
	public function sendmsg($userid,$touserid,$content){
	
		$userid = intval($userid);
		
		$touserid = intval($touserid);
		
		$content = addslashes(trim($content));
		
		if($touserid && $content){
		
			$messageid = $this->create('message',array(
				'userid'		=> $userid,
				'touserid'		=> $touserid,
				'content'		=> $content,
				'addtime'			=> time(),
			));
			
		}
	}
	
	
}