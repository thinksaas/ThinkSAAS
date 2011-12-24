<?php 
defined('IN_TS') or die('Access Denied.');
class upgrade{

	var $db;

	function upgrade($dbhandle){
		$this->db = $dbhandle;
	}

}