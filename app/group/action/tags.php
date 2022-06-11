<?php 
defined('IN_TS') or die('Access Denied.');

$page = tsIntval($_GET['page'],1);

$url = tsUrl('group','tags',array('page'=>''));

$lstart = $page*200-200;

$arrTag = $new['group']->findAll('tag',"`count_group`>'0' and `isaudit`=0",'uptime desc',null,$lstart.',200');

$tagNum = $new['group']->findCount('tag',"`count_group`>'0' and `isaudit`=0");

$pageUrl = pagination($tagNum, 200, $page, $url);

$title = '标签';
include template('tags');