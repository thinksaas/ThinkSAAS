<?php
defined('IN_TS') or die('Access Denied.');

class system extends tsApp{

	var $db;

	function system($dbhandle){
		$this->db = $dbhandle;
	}

}