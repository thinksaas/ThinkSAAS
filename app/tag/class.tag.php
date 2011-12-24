<?php
defined('IN_TS') or die('Access Denied.');
class tag{

	var $db;

	function tag($dbhandle){
		$this->db = $dbhandle;
	}
	
	//添加多个标签 
	function addTag($objname,$idname,$objid,$tags){
	
		if($objname != '' && $idname != '' && $objid!='' && $tags!=''){
			$tags = str_replace ( '，', ',', $tags );
			$arrTag = explode(',',$tags);
			foreach($arrTag as $item){
				$tagname = t($item);
				if(strlen($tagname) < '32' && $tagname != ''){
					$uptime = time();
					$tagcount = $this->db->once_num_rows("select * from ".dbprefix."tag where tagname='".$tagname."'");
					
					if($tagcount == '0'){
						$this->db->query("INSERT INTO ".dbprefix."tag (`tagname`,`uptime`) VALUES ('".$tagname."','".$uptime."')");
						$tagid = $this->db->insert_id();
						
						$tagIndexCount = $this->db->once_num_rows("select * from ".dbprefix."tag_".$objname."_index where ".$idname."='".$objid."' and tagid='".$tagid."'");
						if($tagIndexCount == '0'){
							$this->db->query("INSERT INTO ".dbprefix."tag_".$objname."_index (`".$idname."`,`tagid`) VALUES ('".$objid."','".$tagid."')");
						}
						$tagIdCount = $this->db->once_num_rows("select * from ".dbprefix."tag_".$objname."_index where tagid='".$tagid."'");
						$this->db->query("update ".dbprefix."tag set `count_".$objname."`='".$tagIdCount."',`uptime`='".$uptime."' where tagid='".$tagid."'");
					}else{
						$tagData = $this->db->once_fetch_assoc("select * from ".dbprefix."tag where tagname='".$tagname."'");
						
						$tagIndexCount = $this->db->once_num_rows("select * from ".dbprefix."tag_".$objname."_index where ".$idname."='".$objid."' and tagid='".$tagData['tagid']."'");
						if($tagIndexCount == '0'){
							$this->db->query("INSERT INTO ".dbprefix."tag_".$objname."_index (`".$idname."`,`tagid`) VALUES ('".$objid."','".$tagData['tagid']."')");
						}
						$tagIdCount = $this->db->once_num_rows("select * from ".dbprefix."tag_".$objname."_index where tagid='".$tagData['tagid']."'");
						$this->db->query("update ".dbprefix."tag set `count_".$objname."`='".$tagIdCount."',`uptime`='".$uptime."' where tagid='".$tagData['tagid']."'");
					}
					
				}
			}
		}
	}
	
	//通过topic获取tag
	function getObjTagByObjid($objname,$idname,$objid){
		$arrTagIndex = $this->db->fetch_all_assoc("select * from ".dbprefix."tag_".$objname."_index where ".$idname."='$objid'");
		
		if(is_array($arrTagIndex)){
		foreach($arrTagIndex as $item){
			$arrTag[] = $this->getOneTag($item['tagid']);
		}
		}
		
		return $arrTag;
		
	}
	
	//通过tagid获得tagname
	function getOneTag($tagid){
		$tagData = $this->db->once_fetch_assoc("select * from ".dbprefix."tag where tagid='$tagid'");
		
		return $tagData;
	}
	
	//通过tagname获取tagid
	function getTagId($tagname){
		$strTag = $this->db->once_fetch_assoc("select tagid from ".dbprefix."tag where `tagname`='$tagname'");
		
		return $strTag['tagid'];
	}
	
}