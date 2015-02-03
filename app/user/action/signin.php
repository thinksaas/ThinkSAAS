<?php 
defined('IN_TS') or die('Access Denied.');

if($new['user']->signin()){
	echo 1;exit;
}else{
	echo 0;exit;
}