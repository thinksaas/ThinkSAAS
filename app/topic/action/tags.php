<?php 
defined('IN_TS') or die('Access Denied.');

$page = tsIntval($_GET['page'],1);

$url = tsUrl('topic','tags',array('page'=>''));

$lstart = $page*200-200;

$arrTag = $new['topic']->findAll('tag',"`count_topic`>'0' and `isaudit`=0",'uptime desc',null,$lstart.',200');

$tagNum = $new['topic']->findCount('tag',"`count_topic`>'0' and `isaudit`=0");

$pageUrl = pagination($tagNum, 200, $page, $url);

$title = '标签';
include template('tags');