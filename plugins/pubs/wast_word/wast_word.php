<?php
defined('IN_TS') or die('Access Denied.');

function wast_word($title='',$content='',$tag=''){
	
	global $title;
	global $content;
	global $tag;
	
	$words = fileRead('plugins/pubs/wast_word/data.php');
	
	if($title != ''){
		//$title = preg_replace("/$words/i",'',$title);
		preg_match("/$words/i",$title, $matches);
		
		if(!empty($matches[0])){
			tsNotice('你在制造垃圾吗？');
		}
		
	}
	
	if($content != ''){		
		//$content = preg_replace("/$words/i",'',$content);
		preg_match("/$words/i",$content, $matches);
		if(!empty($matches[0])){
			tsNotice('你在制造垃圾吗？');
		}
		
	}
	
	if($tag != ''){
		//$tag = preg_replace("/$words/i",'',$tag);
		preg_match("/$words/i",$tag, $matches);
		if(!empty($matches[0])){
			tsNotice('你在制造垃圾吗？');
		}
	}
	
}

//小组发帖过滤
addAction('group_topic_add','wast_word');

//小组编辑帖子过滤
addAction('group_topic_edit','wast_word');

//小组评论过滤
addAction('group_comment_add','wast_word');

//补贴添加过滤
addAction('group_topic_after_add','wast_word');
//补贴编辑过滤
addAction('group_topic_after_edit','wast_word');