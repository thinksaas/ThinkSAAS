<?php 
defined('IN_TS') or die('Access Denied.'); 
function topic(){
	
	$arrTopic = aac('home')->findAll('topic',array(
	    'isaudit'=>0,
    ),'addtime desc','topicid,title',10);
	
	include template('topic','topic');

}

addAction('home_index_right','topic');