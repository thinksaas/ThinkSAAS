<?php 
defined('IN_TS') or die('Access Denied.');

class photo extends tsApp{

	//构造函数
	public function __construct($db){
        $tsAppDb = array();
		include 'app/photo/config.php';
		//判断APP是否采用独立数据库
		if($tsAppDb){
			$db = new MySql($tsAppDb);
		}
	
		parent::__construct($db);
	}
	
	//getPhotoForApp
	function getPhotoForApp($photoid){
		$strPhoto = $this->db->once_fetch_assoc("select * from ".dbprefix."photo where photoid='$photoid'");
		return $strPhoto;
	}
	
	function getSamplePhoto($photoid){
		$strPhoto = $this->db->once_fetch_assoc("select path,photourl from ".dbprefix."photo where photoid='$photoid'");
		return $strPhoto;
	}
	
	//是否存在图片 
	public function isPhoto($photoid){
		$photoNum = $this->findCount('photo',array(
			'photoid'=>$photoid,
		));
		
		if($photoNum > 0){
			return true;
		}else{
			return false;
		}
		
	}

	/**
	 * 删除图片
	 *
	 * @param [type] $strPhoto
	 * @return void
	 */
	public function deletePhoto($strPhoto){
		#删除文件
        if($strPhoto['photourl']){
            if($GLOBALS['TS_SITE']['file_upload_type']==1){
                deleteAliOssFile('uploadfile/photo/'.$strPhoto['photourl']);
            }else{
                unlink('uploadfile/photo/'.$strPhoto['photourl']);
                tsDimg($strPhoto['photourl'],'photo','320','320',$strPhoto['path']);
            }
		}
		#删除记录
		$this->delete('photo',array(
			'photoid'=>$strPhoto['photoid'],
		));

		#删除评论
		$this->delete ( 'comment', array (
			'ptable'=>'photo',
			'pkey'=>'photoid',
			'pid'=>$strPhoto['photoid'],
		));

		return true;

	}
	
	//删除相册
	public function deletePhotoAlbum($albumid){
		
			$this->delete('photo_album',array(
				'albumid'=>$albumid,
			));
			
			$arrPhoto = $this->findAll('photo',array(
				'albumid'=>$albumid,
			));
			
			foreach($arrPhoto as $key=>$item){

				$this->deletePhoto($item);

			}
			
			$this->delete('photo',array(
				'albumid'=>$albumid,
			));
		
	}
	

}