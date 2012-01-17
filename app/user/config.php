<?php
defined('IN_TS') or die('Access Denied.');
require_once THINKDATA."/config.inc.php";

//options
$TS_APP['options']['appname'] = L::config_appname;
$TS_APP['options']['appdesc'] = L::config_appdesc;

//skins
$skin = 'default';