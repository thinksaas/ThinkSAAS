<?php 
defined('IN_TS') or die('Access Denied.');
$hl = $_GET['hl'];

setcookie("ts_lang", $hl, time()+3600*30,'/');

header("Location: ".SITE_URL.'index.php');