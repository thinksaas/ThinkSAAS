<?php
defined('IN_TS') or die('Access Denied.');
//require_once("config.php");

require_once("API/qqConnectAPI.php");

$qc = new QC();
$qc->qq_login();