<?php
defined('IN_TS') or die('Access Denied.');

//判断目录可写
$f_cache =  iswriteable('cache');
$f_data = iswriteable('data');
$f_plugins =  iswriteable('plugins');
$f_uploadfile =  iswriteable('uploadfile');
$f_tslogs =  iswriteable('tslogs');
$f_upgrade =  iswriteable('upgrade');

include 'install/html/index.html';