<?php
defined('IN_TS') or die('Access Denied.');

$userid = intval($_POST['userid']);
$touserid = intval($_POST['touserid']);
$content = t($_POST['content']);

$new['message']->sendmsg($userid,$touserid,$content);

echo '1';