<?php
defined('IN_TS') or die('Access Denied.');  

class redeem extends tsApp{
	
	//构造函数
	public function __construct($db){
        $tsAppDb = array();
		include 'app/redeem/config.php';
		//判断APP是否采用独立数据库
		if($tsAppDb){
			$db = new MySql($tsAppDb);
		}
	
		parent::__construct($db);
	}
	
}
