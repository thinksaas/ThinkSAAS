<?php
defined('IN_TS') or die('Access Denied.');
aac('system')->isLogin();
$arrArticle = $new['news']->findAll('');