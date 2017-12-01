<?php 
defined('IN_TS') or die('Access Denied.'); 

function signuser(){
	$arrUser = aac('user')->getHotUser(20);
    include template('signuser','signuser');
}

addAction('home_index_left','signuser');