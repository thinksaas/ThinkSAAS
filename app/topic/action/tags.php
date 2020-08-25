<?php 
defined('IN_TS') or die('Access Denied.');
$page = isset($_GET['page']) ? $_GET['page'] : '1';

$url = tsUrl('topic','tags',array('page'=>''));

$lstart = $page*200-200;

$arrTag = $new['topic']->findAll('tag',"`count_topic`!=''",'uptime desc',null,$lstart.',200');

$tagNum = $new['topic']->findCount('tag',"`count_topic`!=''");

$pageUrl = pagination($tagNum, 200, $page, $url);

$title = '标签';
include template('tags');