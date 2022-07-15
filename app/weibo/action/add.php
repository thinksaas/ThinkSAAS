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

        if ($TS_APP['allowpost'] == 0 && $TS_USER['isadmin'] == 0) {
			getJson('应用设置不允许会员发布唠叨！',$js);
		}

        $title = tsTrim($_POST['title']);


        //匿名用户
		$isniming = tsIntval($_POST['isniming']);
		if($TS_SITE['isniming']==1 && $isniming==1) $userid = aac('user')->getNimingId();


        if($title == '') {
            getJson('内容不能为空',$js);
        }

        //1审核后显示0不审核
        $isaudit = 0;
		if ($TS_APP['isaudit'] == 1 && $TS_USER['isadmin']==0) $isaudit = 1;

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
            aac('user') -> doScore($TS_URL['app'], $TS_URL['ac'],$TS_URL['mg'],$TS_URL['api'], $TS_URL['ts']);
        }

        #用户记录
		aac('pubs')->addLogs('weibo','weiboid',$weiboid,$userid,$title,$title,0);

        getJson('发布成功！',$js,2,tsurl('weibo','show',array('id'=>$weiboid)));

    break;

}