<?php
defined('IN_TS') or die('Access Denied.');
class location extends tsApp{

	//构造函数
	public function __construct($db){
		parent::__construct($db);
	}
	
	//通过连贯性找到三级区域
	function getAreaForApp($areaid){
		$strAreaThree = $this->db->once_fetch_assoc("select * from ".dbprefix."area where areaid='$areaid'");
		
		if($strAreaThree){
		
			if($strAreaThree['referid'] > 0){
				$strAreaTwo = $this->db->once_fetch_assoc("select * from ".dbprefix."area where areaid='".$strAreaThree['referid']."'");
				if($strAreaTwo['referid']>0){
					$strAreaOne = $this->db->once_fetch_assoc("select * from ".dbprefix."area where areaid='".$strAreaTwo['referid']."'");
					$strArea=array(
						'one'	=> array(
							'areaid' => $strAreaOne['areaid'],
							'areaname'	=> $strAreaOne['areaname'],
						),
						'two'	=> array(
							'areaid' => $strAreaTwo['areaid'],
							'areaname'	=> $strAreaTwo['areaname'],
						),
						'three'	=> array(
							'areaid' => $strAreaThree['areaid'],
							'areaname'	=> $strAreaThree['areaname'],
						)
					);
				}else{
					$strArea=array(
						'two'	=> array(
							'areaid' => $strAreaTwo['areaid'],
							'areaname'	=> $strAreaTwo['areaname'],
						),
						'three'	=> array(
							'areaid' => $strAreaThree['areaid'],
							'areaname'	=> $strAreaThree['areaname'],
						)
					);
				}
			}else{
				$strArea=array(
					'three'	=> array(
						'areaid' => $strAreaThree['areaid'],
						'areaname'	=> $strAreaThree['areaname'],
					)
				);
			}
		
		}else{
			$strArea=array(
				'three'	=> array(
				'areaid' => '0',
				'areaname'	=> '火星',
				)
			);
		}
		
		return $strArea;
		
	}
	
	//获取区域下的区域
	function getReferArea($areaid){
		$arrArea = $this->db->fetch_all_assoc("select * from ".dbprefix."area where referid='$areaid'");
		return $arrArea;
	}
	
	//
	function getOneArea($areaid){
		$strArea = $this->db->once_fetch_assoc("select * from ".dbprefix."area where areaid='$areaid'");
		if(!$strArea){
			$strArea = array(
				'areaid' => '0',
				'areaname'	=> '火星',
			);
		}
		
		return $strArea;
		
	}
	
	//获取首字母
	function getfirstchar($s0){   
			$fchar=ord($s0{0});   
			if($fchar>=ord("A") and $fchar<=ord("z") )return strtoupper($s0{0});   
			$s=mb_convert_encoding($s0,"gb2312", "UTF-8");   
			$asc=ord($s{0})*256+ord($s{1})-65536;   
			if($asc>=-20319 and $asc<=-20284)return "A";   
			if($asc>=-20283 and $asc<=-19776)return "B";   
			if($asc>=-19775 and $asc<=-19219)return "C";   
			if($asc>=-19218 and $asc<=-18711)return "D";   
			if($asc>=-18710 and $asc<=-18527)return "E";   
			if($asc>=-18526 and $asc<=-18240)return "F";   
			if($asc>=-18239 and $asc<=-17923)return "G";   
			if($asc>=-17922 and $asc<=-17418)return "H";                 
			if($asc>=-17417 and $asc<=-16475)return "J";                 
			if($asc>=-16474 and $asc<=-16213)return "K";                 
			if($asc>=-16212 and $asc<=-15641)return "L";                 
			if($asc>=-15640 and $asc<=-15166)return "M";                 
			if($asc>=-15165 and $asc<=-14923)return "N";                 
			if($asc>=-14922 and $asc<=-14915)return "O";                 
			if($asc>=-14914 and $asc<=-14631)return "P";                 
			if($asc>=-14630 and $asc<=-14150)return "Q";                 
			if($asc>=-14149 and $asc<=-14091)return "R";                 
			if($asc>=-14090 and $asc<=-13319)return "S";                 
			if($asc>=-13318 and $asc<=-12839)return "T";                 
			if($asc>=-12838 and $asc<=-12557)return "W";                 
			if($asc>=-12556 and $asc<=-11848)return "X";                 
			if($asc>=-11847 and $asc<=-11056)return "Y";                 
			if($asc>=-11055 and $asc<=-10247)return "Z";     
			return '';   
	}
	
	//按字母查询到区域列表 
	function getAreaByZm($zm){
		$arrArea = $this->db->fetch_all_assoc("select * from ".dbprefix."area where `zm`='$zm'");
		return $arrArea;
	}
	
	//获取区域下的所有区域
	function getAllArea($areaid){
		$strArea = $this->db->once_fetch_assoc("select referid from ".dbprefix."");
		$areaCount = $this->db->once_fetch_assoc("select count(areaid) from ".dbprefix."area where areaid='$areaid'");
		if($areaCount['count(areaid)'] > 0){
			
			$arrArea = $this->db->fetch_all_assoc("select areaid from ".dbprefix."area where referid='$areaid'");
			foreach($arrArea as $item){
				
			}
		}
	}
	
	
	//析构函数
	public function __destruct(){
		
	}
	
}
