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
	

    /**
     * 	发送消息
     * @param $userid       发送者用户ID，0为系统消息
     * @param $touserid     接收消息的用户ID
     * @param $content      消息内容
     * @param string $tourl 消息对应的内容网址
     * @param string $extend 消息扩展
     */
    public function sendmsg($userid,$touserid,$content,$tourl='',$extend=''){
	
		$userid = intval($userid);
		$touserid = intval($touserid);
		$content = addslashes(trim($content));
		
		if($touserid && $content){
		
			$messageid = $this->create('message',array(
				'userid'		=> $userid,
				'touserid'	=> $touserid,
				'content'	=> $content,
                'tourl'=>$tourl,
                'extend'=>$extend,
				'addtime'	=> time(),
			));
			
		}
	}
	
	
}