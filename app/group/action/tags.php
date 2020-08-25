<?php 
defined('IN_TS') or die('Access Denied.');
$page = isset($_GET['page']) ? $_GET['page'] : '1';

$url = tsUrl('group','tags',array('page'=>''));

$lstart = $page*200-200;

$arrTag = $new['group']->findAll('tag',"`count_group`!=''",'uptime desc',null,$lstart.',200');

$tagNum = $new['group']->findCount('tag',"`count_group`!=''");

$pageUrl = pagination($tagNum, 200, $page, $url);

$title = '标签';
include template('tags');