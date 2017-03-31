<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/3/5
 * Time: 18:23
 */
defined('IN_TS') or die('Access Denied.');

$userid = aac('user')->isLogin();

$attachid = intval ( $_GET ['attachid'] );

$strAttach = $new ['attach']->find ( 'attach', array (
    'attachid' => $attachid
) );

if (! is_file ( 'uploadfile/attach/' . $strAttach ['attachurl'] )) {
    tsNotice ( '文件已经不存在！' );
}

$new ['attach']->update ( 'attach', array (
    'attachid' => $strAttach ['attachid']
), array (
    'count_down' => $strAttach ['count_down'] + 1
) );

// 对积分进行处理
aac('user') -> doScore($TS_URL['app'], $TS_URL['ac'], $TS_URL['ts']);

header ( "Location: " . SITE_URL . 'uploadfile/attach/' . $strAttach ['attachurl'] );