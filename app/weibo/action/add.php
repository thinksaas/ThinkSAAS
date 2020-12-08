<?php
defined('IN_TS') or die('Access Denied.');

switch($ts){

    case "":
        $js = tsIntval($_GET['js']);

        $userid = aac('user')->isLogin(1);

        //判断用户是否存在
        if(aac('user')->isUser($userid)==false) getJson('不好意思，用户不存在！',$js);

        //判断发布者状态
        if(aac('user')->isPublisher()==false) getJson('不好意思，你还没有权限发布内容！',$js);

        //发布时间限制
        if(aac('system')->pubTime()==false) getJson('不好意思，当前时间不允许发布内容！',$js);

        $title = trim($_POST['title']);

        if($title == '') {
            getJson('内容不能为空',$js);
        }

        $isaudit = 0;

        if($GLOBALS['TS_USER']['isadmin']==0){
            //过滤内容开始
            $title = antiWord($title);
            //过滤内容结束
        }

        $weiboid = $new['weibo']->create('weibo',array(
            'userid'=>$userid,
            'title'=>$title,
            'isaudit'=>$isaudit,
            'addtime'=>date('Y-m-d H:i:s'),
        ));

        #绑定图片
        $new['weibo']->update('weibo_photo',array(
            'userid'=>$userid,
            'weiboid'=>0,
        ),array(
            'weiboid'=>$weiboid,
        ));

        $daytime = date('Y-m-d 00:00:01');
        $count_weibo = $new['weibo']->findCount('weibo',"`userid`='$userid' and `addtime`>'$daytime'");

        #每日前三条给积分
        if($count_weibo<4){
            aac('user') -> doScore($GLOBALS['TS_URL']['app'], $GLOBALS['TS_URL']['ac'], $GLOBALS['TS_URL']['ts']);
        }

        getJson('发布成功！',$js,2,tsurl('weibo','show',array('id'=>$weiboid)));

    break;

}