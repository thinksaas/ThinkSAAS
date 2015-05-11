<?php 
defined('IN_TS') or die('Access Denied.');



$page = isset($_GET['page']) ? intval($_GET['page']) : 1;

$url = 'index.php?app=tag&ac=admin&mg=list&page=';

$sLimit = $page*10-10;

$arrTags = $db->fetch_all_assoc("select * from ".dbprefix."tag order by uptime desc limit $sLimit,10");

$tagNum = $db->once_num_rows("select * from ".dbprefix."tag");

$pageUrl = pagination($tagNum, 10, $page, $url);

include template("admin/list");