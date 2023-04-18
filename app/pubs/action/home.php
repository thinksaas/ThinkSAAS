<?php
defined('IN_TS') or die('Access Denied.');

if($TS_USER){
    header('Location: '.SITE_URL);
    exit();
}

$title = $TS_SITE['site_subtitle'];
include template('home');