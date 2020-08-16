<?php 
defined('IN_TS') or die('Access Denied.');
//用户是否登录
$userid = aac('user')->isLogin($js,$userkey);
switch($ts){

	/**
	 * 添加评论
     * index.php?app=pubs&ac=comment&ts=do&js=1
     * post
     * @userkey
     * 
     * @ptable
     * @pkey
     * @pid
     * 
     * @referid
     * @touserid
     * 
     * @content
     * @ispublic
	 */
	case "do":
		
		$authcode = strtolower($_POST['authcode']);
		
		if ($TS_SITE ['isauthcode'] && $authcode) {
			if ($authcode != $_SESSION ['verify']) {
				getJson ( "验证码输入有误，请重新输入！" ,$js,0);
			}
		}
        
        $ptable = trim($_POST['ptable']);
        if(!preg_match("/^[a-z_]*$/i", $ptable)){
            getJson('非法操作！',$js);
        }

        $pkey = trim($_POST['pkey']);
        if(!preg_match("/^[a-z_]*$/i", $pkey)){
            getJson('非法操作！',$js);
        }

        $pid = tsIntval($_POST['pid']);


        $referid = tsIntval($_POST['referid']);
        $touserid = tsIntval($_POST['touserid']);


		$content	= tsClean($_POST['content'],$js);
		$content2	= emptyText($_POST['content']);//测试空内容
        $ispublic = tsIntval($_POST['ispublic']);

		//过滤内容开始
		if($TS_USER['isadmin']==0){
			$content = antiWord($content);
		}
        //过滤内容结束

		if($ptable=='' || $pkey=='' || $pid=='' || $content2=='' || $content==''){
			getJson('没有任何内容是不允许你通过滴^_^',$js);
		}else{
			$commentid = $new['pubs']->create('comment',array(
                'ptable'=>$ptable,
                'pkey'=>$pkey,
                'pid'=>$pid,

                'referid'=>$referid,
                'userid'=>$userid,
                'touserid'=>$touserid,
                
				'content'	=> $content,
                'ispublic'=>$ispublic,
				'addtime'=> time(),
			));
			
			//统计评论数
			$count_comment = $new['pubs']->findCount('comment',array(
                'ptable'=>$ptable,
                'pkey'=>$pkey,
                'pid'=>$pid,
			));
			
			//更新项目最后回应时间和评论数			
			$new['pubs']->update($ptable,array(
				$pkey=>$pid,
			),array(
				'count_comment'=>$count_comment,
				'uptime'=>time(),
            ));
            
            #更新ptable评论数
            aac('pubs')->upPtableComment($ptable,$pkey,$pid,$count_comment);
			
			//发送系统消息(通知楼主有人回复他的帖子啦)			
			$strProject = $new['pubs']->find($ptable,array(
                $pkey=>$pid,
            ));

            if($referid){

                $strComment = $new['pubs']->find('comment',array(
                    'commentid'=>$referid,
                ));
    
                //创建消息
                if($strComment['userid'] != $userid){
                    $msg_userid = $userid;
                    $msg_touserid = $strComment['userid'];
                    $msg_content = '回复了你：'.t($content);
                    $msg_tourl = getProjectUrl($ptable,$pid);
                    $msg_extend = json_encode(array(
                        'ptable'=>$ptable,
                        'pkey'=>$pkey,
                        'pid'=>$pid,
                    ));
                    aac('message')->sendmsg($msg_userid,$msg_touserid,$msg_content,$msg_tourl,$msg_extend);
                }
    
                #上级评论用户加分
                aac ( 'user' )->doScore ( $TS_URL['app'], $TS_URL['ac'], $TS_URL['ts'],$strComment['userid']);
    
    
            }else{
    
                //创建消息
                if($strProject['userid'] != $userid){
                    $msg_userid = $userid;
                    $msg_touserid = $strProject['userid'];
                    $msg_content = '评论了你发布的：《'.$strProject['title'].'》';
                    $msg_tourl = getProjectUrl($ptable,$pid);
                    $msg_extend = json_encode(array(
                        'ptable'=>$ptable,
                        'pkey'=>$pkey,
                        'pid'=>$pid,
                    ));
                    aac('message')->sendmsg($msg_userid,$msg_touserid,$msg_content,$msg_tourl,$msg_extend);
                }
    
            }

            $daytime = strtotime(date('Y-m-d 00:00:01'));
            $count_comment = $new['pubs']->findCount('comment',"`userid`='$userid' and `addtime`>'$daytime'");

            #每日前1条给积分
            if($count_comment<2){
                aac('user') -> doScore($GLOBALS['TS_URL']['app'], $GLOBALS['TS_URL']['ac'], $GLOBALS['TS_URL']['ts']);
            }

			getJson('评论成功',$js,2,getProjectUrl($ptable,$pid));

		}	
	
		break;


		
	//删除评论
	case "delete":
		
		$commentid = tsIntval($_GET['commentid']);
		
		$strComment = $new['pubs']->find('comment',array(
			'commentid'=>$commentid,
		));
        
        $ptable = $strComment['ptable'];
        $pkey = $strComment['pkey'];
        $pid = $strComment['pid'];
		
		if($TS_USER['isadmin']==1 || $strComment['userid']==$userid){
			
			$new['pubs']->delComment($ptable,$pkey,$pid,$commentid);

            //处理积分
            aac('user')->doScore($GLOBALS['TS_URL']['app'], $GLOBALS['TS_URL']['ac'], $GLOBALS['TS_URL']['ts'],$strComment['userid']);
			
        }
        

		
		//跳转回到详情页
		header("Location: ".getProjectUrl($ptable,$pid));
		
		break;
}