<?php
defined('IN_TS') or die('Access Denied.');

$userid = $_POST['userid'];
$touserid = $_POST['touserid'];
$content = $_POST['content'];

$new['message']->sendmsg($userid,$touserid,$content);

echo '1';