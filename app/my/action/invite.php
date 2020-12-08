<?php 
defined('IN_TS') or die('Access Denied.');

#邀请的用户
$arrInviteUser = $new['my']->findAll('user_info',array(
    'fuserid'=>$userid,
),'addtime desc','userid,username');

$title = '我的邀请';
include template('invite');