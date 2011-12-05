<?php 
//$Id: theme.php 66 2011-09-25 09:21:28Z qiujun $

defined('IN_TS') or die('Access Denied.');

$title = '更换主题';

$arrTheme	= dirList('theme');

include template("theme");