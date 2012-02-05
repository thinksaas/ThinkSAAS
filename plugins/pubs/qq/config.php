<?php
defined('IN_TS') or die('Access Denied.');
define("QQDEBUG", false);
if (defined("QQDEBUG") && QQDEBUG)
{
    @ini_set("error_reporting", E_ALL);
    @ini_set("display_errors", TRUE);
}

include_once("session.php");

$arrQQ = include_once("data.php");

$_SESSION["appid"]    = $arrQQ['appid']; 


$_SESSION["appkey"]   = $arrQQ['appkey']; 


$_SESSION["callback"] = $arrQQ['siteurl']."index.php?app=pubs&ac=plugin&plugin=qq&in=get_access_token"; 