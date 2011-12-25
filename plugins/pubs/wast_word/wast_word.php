<?php
defined('IN_TS') or die('Access Denied.');

function wast_word($title='',$content='',$tag=''){
	
	global $title;
	global $content;
	global $tag;
	
	$words = fileRead('data.php','plugins','pubs','wast_word');
	
	if($title != ''){
		$title = preg_replace("/$words/i",'',$title);
	}
	
	if($content != ''){		
		$content = preg_replace("/$words/i",'',$content);
	}
	
	if($tag != ''){
		$tag = preg_replace("/$words/i",'',$tag);
	}
	
}

//小组发帖过滤
addAction('group_topic_add','wast_word');
//小组评论过滤
addAction('group_comment_add','wast_word');