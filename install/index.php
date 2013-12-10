<?php
defined('IN_TS') or die('Access Denied.'); 
/*
 *ThinkSAAS 安装程序
 * @copyright (c) 2010-3000 ThinkSAAS All Rights Reserved
 * @code by QiuJun
 * @Email:thinksaas@qq.com
 */

//安装文件的IMG，CSS文件
$skins	= 'data/install/skins/';

//进入正题
$title = 'ThinkSAAS安装程序';

require_once 'action/'.$install.'.php';