<?php 
defined('IN_TS') or die('Access Denied.');
class search{

	var $db;

	function search($dbhandle){
		$this->db = $dbhandle;
	}

}