<?php 
defined('IN_TS') or die('Access Denied.');
class article{

	var $db;
	
	function article($dbhandle){
		$this->db = $dbhandle;
	}
	
	//获取所有分类
	function getArrCate(){
		$arrCate = $this->db->fetch_all_assoc("select * from ".dbprefix."article_cate");
		return $arrCate;
	}

	//获取一个分类 
	function getOneCate($cateid){
		$strCate = $this->db->once_fetch_assoc("select * from ".dbprefix."article_cate where `cateid`='$cateid'");
		return $strCate;
	}
	
	//获取文章的第一张图片
	function getOnePhoto($content){
			preg_match_all('/\[(photo)=(\d+)\]/is', $content, $photo);
			$photoid = $photo[2][0];
			$strPhoto = aac('photo')->getSamplePhoto($photoid);
			return $strPhoto;
	}
	
}
