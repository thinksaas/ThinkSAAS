<?php
defined('IN_TS') or die('Access Denied.');  

class weibo extends tsApp{
	
	//构造函数
	public function __construct($db){
		parent::__construct($db);
	}
	
	//获取一条微博 
	public function getOneWeibo($weiboid){
		
		$strWeibo = $this->find('weibo',array(
			'weiboid'=>$weiboid,
		));
		
		$strWeibo['user']=aac('user')->getOneUser($strWeibo['userid']);
		
		return $strWeibo;
		
	}
	
	//获取一条评论 
	public function getOneComment($commentid){
		
		$strComment = $this->find('weibo_comment',array(
			'commentid'=>$commentid,
		));
		
		$strComment['content'] = htmlspecialchars($strComment['content']);
		
		$strComment['user']=aac('user')->getOneUser($strComment['userid']);
		
		return $strComment;
		
	}
	
}