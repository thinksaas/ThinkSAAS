<?php 
defined('IN_TS') or die('Access Denied.');
//上传照片
$userid = intval($TS_USER['user']['userid']);
if($userid == 0) header("Location: ".SITE_URL."index.php");

$albumid = intval($_GET['albumid']);
if($albumid == 0) header("Location: ".SITE_URL."index.php");

$strAlbum = $db->once_fetch_assoc("select * from ".dbprefix."photo_album where albumid='$albumid'");

if($userid != $strAlbum['userid']) header("Location: ".SITE_URL."index.php?app=photo&ac=album&ts=user&userid=".$userid);

$addtime = time();

$title = '上传照片';
include template("upload");