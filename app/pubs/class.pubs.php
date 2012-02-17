<?php 
defined('IN_TS') or die('Access Denied.');
class pubs extends tsApp{

	var $db;

	function pubs($dbhandle){
		$this->db = $dbhandle;
	}

}