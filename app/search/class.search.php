<?php 
defined('IN_TS') or die('Access Denied.');
class search extends tsApp{

	var $db;

	function search($dbhandle){
		$this->db = $dbhandle;
	}

}