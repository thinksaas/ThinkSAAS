<?php
defined('IN_TS') or die('Access Denied.');  

class weibo extends tsApp{
	
	//构造函数
	public function __construct($db){

        $tsAppDb = array();
		include 'app/weibo/config.php';
		//判断APP是否采用独立数据库
		if($tsAppDb){
			$db = new MySql($tsAppDb);
		}
	
		parent::__construct($db);
	}
	
	//获取一条微博 
	public function getOneWeibo($weiboid){
		
		$strWeibo = $this->find('weibo',array(
			'weiboid'=>$weiboid,
		));
		
		$strWeibo['user']=aac('user')->getOneUser($strWeibo['userid']);
		$strWeibo['title'] = tsTitle($strWeibo['title']);
		
		return $strWeibo;
		
	}
	

	
}