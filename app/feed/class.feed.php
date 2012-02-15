<?php
defined('IN_TS') or die('Access Denied.');
class feed{

	var $db;

	public function __construct($dbhandle){
		$this->db = $dbhandle;
	}
	
	//添加feed
	function addFeed($userid,$template,$data){
		$userid = intval($userid);
		$data = addslashes($data);
		
		$this->db->create('feed',array(
			'userid'=>$userid,
			'template'=>$template,
			'data'=>$data,
			'addtime'=>time(),
		));
		
	}
	
	//析构函数
	public function __destruct(){
		
	}
	
}