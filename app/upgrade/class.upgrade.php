<?php 
defined('IN_TS') or die('Access Denied.');
class upgrade extends tsApp{

	//构造函数
	public function __construct($db){
	
		include 'config.php';
		//判断APP是否采用独立数据库
		if($tsAppDb){
			include 'sql/'.$tsAppDb['sql'].'.php';
			$db = new MySql($tsAppDb);
		}
	
		parent::__construct($db);
	}

}