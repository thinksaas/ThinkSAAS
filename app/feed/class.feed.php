<?php
defined('IN_TS') or die('Access Denied.');
class feed extends tsApp{

	//构造函数
	public function __construct($db){
		parent::__construct($db);
	}
	
	//添加feed
	public function add($userid,$template,$data){
	
		$userid = intval($userid);
		
		if(is_array($data)){
			
			$data = serialize($data);
			
			$data = addslashes($data);

			$this->create('feed',array(
				'userid'=>$userid,
				'template'=>$template,
				'data'=>$data,
				'addtime'=>time(),
			));

		}
		
	}
	
	//析构函数
	public function __destruct(){
		
	}
	
}