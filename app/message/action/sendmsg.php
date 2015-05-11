<?php
defined('IN_TS') or die('Access Denied.');

$userid = aac('user')->isLogin();
$touserid = intval($_POST['touserid']);
$content = t($_POST['content']);

$new['message']->sendmsg($userid,$touserid,$content);

echo '1';