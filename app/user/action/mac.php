<?php 
defined('IN_TS') or die('Access Denied.');

$mac = trim($_POST['mac']);

if($mac){
	$ismac = $new['user']->findCount('anti_mac',array(
		'mac'=>$mac,
	));
	if($ismac==0){
		$new['user']->create('anti_mac',array(
			'mac'=>$mac,
			'addtime'=>date('Y-m-d H:i:s')
		));
	}
}