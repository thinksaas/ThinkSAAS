<?php
class apple{

	var $db;

	function apple($dbhandle){
		$this->db = $dbhandle;
	}
	
	//根据模型属性ID获取模型属性名称 
	function getVirtueName($virtueid){
		
		$strVirtue = $this->db->once_fetch_assoc("select * from ".dbprefix."apple_virtue where `virtueid`='$virtueid'");
		
		return $strVirtue['virtuename'];
		
	}
	
}