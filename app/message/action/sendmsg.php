<?php
defined('IN_TS') or die('Access Denied.');

$userid = aac('user')->isLogin();
$touserid = tsIntval($_POST['touserid']);
$content = tsTrim($_POST['content']);

$new['message']->sendmsg($userid,$touserid,$content);

echo '1';