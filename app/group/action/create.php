<?php

//创建小组

defined('IN_TS') or die('Access Denied.');

$arrOne = $db->findAll("select * from ".dbprefix."group_cates where catereferid='0'");

$title = '创建小组';
include template("create");