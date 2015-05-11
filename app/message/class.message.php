<?php
defined('IN_TS') or die('Access Denied.');
 
class message extends tsApp{

	//构造函数
	public function __construct($db){
        $tsAppDb = array();
		include 'app/message/config.php';
		//判断APP是否采用独立数据库
		if($tsAppDb){
			$db = new MySql($tsAppDb);
		}
	
		parent::__construct($db);
	}
	
	//发送消息
	public function sendmsg($userid,$touserid,$content){
	
		$userid = intval($userid);
		
		$touserid = intval($touserid);
		
		$content = str_replace(SITE_URL,'[SITE_URL]',$content);
		
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