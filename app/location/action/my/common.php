<?php 
defined('IN_TS') or die('Access Denied.');

$userid = aac('user')->isLogin();

$strUser = aac('user')->getOneUser($userid); 