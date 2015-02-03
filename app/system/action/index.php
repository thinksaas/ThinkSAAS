<?php
defined('IN_TS') or die('Access Denied.');
aac('system')->isLogin();

$title = '首页';

/*
 *包含模版
 */

include template("admincp");