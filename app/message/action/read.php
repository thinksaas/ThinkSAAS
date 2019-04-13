<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/4/8
 * Time: 9:42
 */
defined('IN_TS') or die('Access Denied.');
$touserid= aac('user')->isLogin();
//isread设为已读
$new['message']->update('message',array(
    'touserid'=>$touserid,
    'isread'=>0,
),array(
    'isread'=>1,
));

tsNotice('操作成功！');