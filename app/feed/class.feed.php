<?php
class feed{

	var $db;

	function feed($dbhandle){
		$this->db = $dbhandle;
	}
	
	//添加feed
	function addFeed($userid,$template,$data){
		$this->db->query("insert into ".dbprefix."feed (`userid`,`template`,`data`,`addtime`) values ('$userid','$template','$data','".time()."')");
	}
	
}