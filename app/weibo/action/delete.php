<?php
defined('IN_TS') or die('Access Denied.');

$userid = aac('user')->isLogin();

$weiboid = intval($_GET['weiboid']);

$strWeibo = $new['weibo']->find('weibo',array(
    'weiboid'=>$weiboid,
));

if($userid == $strWeibo['userid'] || $GLOBALS['TS_USER']['isadmin']==1){

    $new['weibo']->delete('weibo',array(
        'weiboid'=>$weiboid,
    ));
    
    //删除图片
    if($strWeibo['photo']){
        unlink('uploadfile/weibo/'.$strWeibo['photo']);
    }
    
    #删除评论ts_comment
    aac('pubs')->delComment('weibo','weiboid',$strWeibo['weiboid']);

    #删除点赞ts_love
    aac('pubs')->delLove('weibo','weiboid',$strWeibo['weiboid']);
    
    tsNotice('删除成功！','点击返回唠叨首页',tsUrl('weibo'));
    
}else{
    tsNotice('非法操作！');
}