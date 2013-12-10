<?php
defined('IN_TS') or die('Access Denied.');



$title = $TS_SITE['base']['site_subtitle'];

if($TS_CF['mobile']) $sitemb = tsUrl('moblie');

include template("index");