<?php
defined('IN_TS') or die('Access Denied.');

//最多积分用户
$arrScoreUser = $new['user']->getScoreUser(10);

//关注最多的用户
$arrFollowUser = $new['user']->getFollowUser(10);

//活跃会员
$arrHotUser = $new['user']->getHotUser(10);

//最新会员
$arrNewUser = $new['user']->getNewUser(10);

$title = '用户';

include template('index');