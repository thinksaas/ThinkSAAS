<?php
defined('IN_TS') or die('Access Denied.');

$userid = aac('user')->isLogin();

$weiboid = tsIntval($_GET['weiboid']);

$strWeibo = $new['weibo']->find('weibo',array(
    'weiboid'=>$weiboid,
));

if($userid == $strWeibo['userid'] || $GLOBALS['TS_USER']['isadmin']==1){

    $new['weibo']->deleteWeibo($weiboid);
    
    tsNotice('删除成功！','点击返回唠叨首页',tsUrl('weibo'));
    
}else{
    tsNotice('非法操作！');
}