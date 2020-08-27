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

	/**
     * 获取微博图片
     */
    public function getWeiboPhoto($weiboid,$num=null){
        $arrPhotos = $this->findAll('weibo_photo',array(
            'weiboid'=>$weiboid,
        ),'orderid asc',null,$num);
        foreach($arrPhotos as $key=>$item){
            if($num){
                $arrPhoto[$key] = tsXimg($item['photo'],'weibo/photo','200','200',$item['path'],1);
            }else{
                $arrPhoto[$key] = tsXimg($item['photo'],'weibo/photo','640','',$item['path']);
            }
        }
        return $arrPhoto;
	}

	/**
	 * 删除单个关联的图片
	 *
	 * @param [type] $strPhoto
	 * @return void
	 */
	public function deletePhoto($strPhoto){
		if($strPhoto['photo']){
            if($GLOBALS['TS_SITE']['file_upload_type']==1){
                deleteAliOssFile('uploadfile/weibo/photo/'.$strPhoto['photo']);
            }else{
                unlink('uploadfile/weibo/photo/'.$strPhoto['photo']);
                tsDimg($strPhoto['photo'],'weibo/photo','320','320',$strPhoto['path']);
            }
		}

		$this->delete('weibo_photo',array(
			'photoid'=>$strPhoto['photoid'],
		));

		return true;

	}
	
	/**
	 * 删除微博
	 *
	 * @param [type] $strWeibo
	 * @return void
	 */
	public function deleteWeibo($weiboid){
		#删除图片
		$arrPhoto = $this->findAll('weibo_photo',array(
			'weiboid'=>$weiboid,
		));

		foreach($arrPhoto as $key=>$item){
			$this->deletePhoto($item);
		}
		
		#删除记录
		$this->delete('weibo',array(
			'weiboid'=>$weiboid,
		));
		
		#删除评论ts_comment
		aac('pubs')->delComment('weibo','weiboid',$weiboid);
	
		#删除点赞ts_love
		aac('pubs')->delLove('weibo','weiboid',$weiboid);

		return true;
	}
	

	
}