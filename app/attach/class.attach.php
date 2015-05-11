<?php
defined('IN_TS') or die('Access Denied.');
class attach extends tsApp{

	//构造函数
	public function __construct($db){
        $tsAppDb = array();
		include 'app/attach/config.php';
		//判断APP是否采用独立数据库
		if($tsAppDb){
			$db = new MySql($tsAppDb);
		}
	
		parent::__construct($db);
	}
	
	//获取单个附件
	function getOneAttach($attachid){
		$isAttach = $this->findCount('attach',array(
			'attachid'=>$attachid,
		));
		if($isAttach > '0'){
			$strAttach = $this->find('attach',array(
				'attachid'=>$attachid,
			));
			if(is_file('uploadfile/attach/'.$strAttach['attachurl'])){
				$strAttach['isattach'] = '1';
			}else{
				$strAttach['isattach'] = '0';
			}
			
		}else{
			$strAttach['isattach'] = '0';
		}
		
		return $strAttach;
		
	}
	
	//是否存在资料库
	function isAlbum($albumid){
		$isAlbum = $this->findCount('attach_album',array(
			'albumid'=>$albumid,
		));
		
		if($isAlbum>0){
			return true;
		}else{
			return false;
		}
	}
	
	function getOneAlbum($albumid){
		$strAlbum = $this->find('attach_album',array(
			'albumid'=>$albumid,
		));
		return $strAlbum;
	}
	
	
}