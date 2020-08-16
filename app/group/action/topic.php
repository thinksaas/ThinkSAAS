<?php
defined('IN_TS') or die('Access Denied.');
$topicid = tsIntval($_GET['id']);
header('HTTP/1.1 301 Moved Permanently');
header('Location: '.tsUrl('topic','show',array('id'=>$topicid)));