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
	

	
}