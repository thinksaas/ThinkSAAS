<?php
defined('IN_TS') or die('Access Denied.');

//echo $_SERVER ['SCRIPT_NAME'];
//echo $_SERVER ['REQUEST_URI'];

//echo $app;
//echo $ac;

$title = $TS_SITE['base']['site_subtitle'];

if($TS_CF['mobile']) $sitemb = tsUrl('moblie');

include template("index");