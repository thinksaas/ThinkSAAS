<?php 
defined('IN_TS') or die('Access Denied.');
class upgrade extends tsApp{

	var $db;

	function upgrade($dbhandle){
		$this->db = $dbhandle;
	}

}