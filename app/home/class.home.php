<?php
defined('IN_TS') or die('Access Denied.');  
class home{

	var $db;

	function home($dbhandle){
		$this->db = $dbhandle;
	}

}
