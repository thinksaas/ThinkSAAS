<?php
defined('IN_TS') or die('Access Denied.');
class tag extends tsApp{
	
	//构造函数
	public function __construct($db){
        $tsAppDb = array();
		include 'app/tag/config.php';
		//判断APP是否采用独立数据库
		if($tsAppDb){
			$db = new MySql($tsAppDb);
		}
	
		parent::__construct($db);
	}
	
	//添加多个标签 
	function addTag($objname,$idname,$objid,$tags){

		$isaudit = 0;

		$strOption = getAppOptions('tag');

		if($strOption && $strOption['isaudit']==1){
			$isaudit = 1;
		}
	
		$objname = tsUrlCheck($objname);
		$idname = tsUrlCheck($idname);
		$objid = tsIntval($objid);
	
		if($objname != '' && $idname != '' && $objid!=0 && $tags!=''){
			$tags = str_replace ( '，', ',', $tags );
			$arrTag = explode(',',$tags);
			foreach($arrTag as $item){
				$tagname = t($item);
				if(strlen($tagname) < '32' && $tagname != ''){
					$uptime = time();
					
					$tagcount = $this->findCount('tag',array(
						'tagname'=>$tagname,
					));
					
					if($tagcount == '0'){
						
						$tagid = $this->create('tag',array(
							'tagname'=>$tagname,
							'isaudit'=>$isaudit,
							'uptime'=>$uptime,
						));
						
						$tagIndexCount = $this->findCount('tag_'.$objname.'_index',array(
							$idname=>$objid,
							'tagid'=>$tagid,
						));
						
						if($tagIndexCount == '0'){
							
							$this->create("tag_".$objname."_index",array(
								$idname=>$objid,
								'tagid'=>$tagid,
							));
							
						}
						
						$tagIdCount = $this->findCount("tag_".$objname."_index",array(
							'tagid'=>$tagid,
						));
						
						$count_obj = "count_".$objname;
						
						$this->update('tag',array(
							'tagid'=>$tagid,
						),array(
							$count_obj=>$tagIdCount,
						));
						
					}else{

						$tagData = $this->find('tag',array(
							'tagname'=>$tagname,
						));
						
						$tagIndexCount = $this->findCount("tag_".$objname."_index",array(
							$idname=>$objid,
							'tagid'=>$tagData['tagid'],
						));
						
						if($tagIndexCount == '0'){
							
							$this->create("tag_".$objname."_index",array(
							
								$idname=>$objid,
								'tagid'=>$tagData['tagid'],
							
							));
							
						}
						
						$tagIdCount = $this->findCount("tag_".$objname."_index",array(
							'tagid'=>$tagData['tagid'],
						));
						
						$count_obj = "count_".$objname;
						
						$this->update('tag',array(
							'tagid'=>$tagData['tagid'],
						),array(
							$count_obj=>$tagIdCount,
							'uptime'=>$uptime,
						));
						
					}
					
				}
			}
		}
	}
	
	//通过topic获取tag
	function getObjTagByObjid($objname,$idname,$objid){
	
		$arrTagIndex = $this->findAll("tag_".$objname."_index",array(
			$idname=>$objid,
		));

		$arrTag = array();

		if($arrTagIndex){
			foreach($arrTagIndex as $item){
				$arrTagId[] = $item['tagid'];
			}
	
			$tagids = arr2str($arrTagId);
	
			$arrTag = $this->findAll('tag',"`tagid` in ($tagids) and `isaudit`=0");	
		}
		
		return $arrTag;
		
	}
	
	//通过tagid获得tagname
	function getOneTag($tagid){
		
		$strTag = $this->find('tag',array(
			'tagid'=>$tagid,
		));

		if($strTag=='') ts404();

		if($strTag['isaduit']==1) tsNotice('标签审核中...');
		
		return $strTag;
	}
	
	//通过tagname获取tag
	function getTagByName($tagname){
		$strTag = $this->find('tag',array(
			'tagname'=>$tagname,
		));
		if($strTag=='') ts404();
		if($strTag['isaudit']==1) tsNotice('标签审核中...');
		return $strTag;
	}
	
	//统计标签
	function countObjTag($objname,$tagid){
		$countObj = $this->findCount("tag_".$objname."_index",array(
			'tagid'=>$tagid,
		));
		$this->update('tag',array(
			'tagid'=>$tagid,
		),array(
			'count_'.$objname=>$countObj,
		));
		
	}
	
	//删除项目ID所有标签
	function delIndextag($objname,$idname,$objid){
		$this->delete("tag_".$objname."_index",array(
			$idname=>$objid,
		));
		return true;
	}
	
	//tag是否存在
	public function isTag($tagname){
		$countTag = $this->findCount('tag',array(
			'tagname'=>$tagname,
		));
		if($countTag>0){
			return true;
		}else{
			return false;
		}
	}
	
	
}