<?php 
$strInfo = $db->once_fetch_assoc("select * from ".dbprefix."home_info where `infokey`='$ac'");
$title = '隐私声明';
include template('privacy');