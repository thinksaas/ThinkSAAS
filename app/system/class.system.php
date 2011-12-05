<?php
	/*
	 *模型：系统管理
	 */

	class system{

		var $db;

		function system($dbhandle){
			$this->db = $dbhandle;
		}

}