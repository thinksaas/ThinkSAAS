<?php 
//$Id: theme.php 573 2011-11-24 17:05:42Z qiujun $

defined('IN_TS') or die('Access Denied.');

$title = '更换主题';

$arrTheme	= dirList('theme');

include template("theme");