<?php 
defined('IN_TS') or die('Access Denied.');

if($ac!='api'){
    $userid = aac('user')->isLogin();
    $strUser = aac('user')->getOneUser($userid); 
}