<?php
defined('IN_TS') or die('Access Denied.');

aac('user')->logout();
//header('Location: '.tsUrl('user','login'));
header('Location: '.SITE_URL);
exit;