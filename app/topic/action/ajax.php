<?php 
defined('IN_TS') or die('Access Denied.');

switch($ts){

	//帖子审核
	case "topicaudit":
	
		$topicid = tsIntval($_POST['topicid']);
		
		$strTopic = $new['topic']->find('topic',array(
			'topicid'=>$topicid,
		));
		
		if($strTopic['isaudit']==0){
			$new['topic']->update('topic',array(
				'topicid'=>$topicid,
			),array(
				'isaudit'=>1,
			));
			
			echo 0;exit;
			
		}
		
		if($strTopic['isaudit']==1){
			$new['topic']->update('topic',array(
				'topicid'=>$topicid,
			),array(
				'isaudit'=>0,
			));
			
			echo 1;exit;
			
		}
		
    break;

    //帖子推荐
	case "isrecommend":

		$userid = aac('user')->isLogin();
	
		$js = tsIntval($_GET['js']);
		
		$topicid = tsIntval($_POST['topicid']);
		
		if($TS_USER['isadmin']==1 && $topicid){
		
			$strTopic = $new['topic']->find('topic',array(
				'topicid'=>$topicid,
			));
			
			if($strTopic['isrecommend']==1){
				$new['topic']->update('topic',array(
					'topicid'=>$topicid,
				),array(
					'isrecommend'=>0,
				));
				
				getJson('取消推荐成功！',$js);
				
			}
			
			if($strTopic['isrecommend']==0){
				$new['topic']->update('topic',array(
					'topicid'=>$topicid,
				),array(
					'isrecommend'=>1,
				));
				
				getJson('推荐成功！',$js);
				
			}
			
		
		}else{
		
			getJson('非法操作',$js);
		
		}
		
	break;


    /**
     * 帖子加标注
     */
    case "book":

        $userid = aac('user')->isLogin();
        $topicid = tsIntval($_POST['topicid']);
        $book = trim($_POST['book']);

        //if($topicid==0 || $book==''){
        if($topicid==0){
            echo 0;exit;
		}

		$strTopic = $new['topic']->find('topic',array(
			'topicid'=>$topicid,
		),'userid');

		if($strTopic['userid']!=$userid && $TS_USER['isadmin']==0){
			echo 0;exit;
		}

        if($TS_USER['isadmin']==1){
            $new['topic']->update('topic',array(
                'topicid'=>$topicid,
            ),array(
                'label'=>$book,
            ));
        }else{
            $new['topic']->update('topic',array(
                'topicid'=>$topicid,
                'userid'=>$userid,
            ),array(
                'label'=>$book,
            ));
        }

        echo 1;exit;

	break;
	

	//置顶帖子
	case "topic_istop":

		$userid = aac('user')->isLogin();
			
		$topicid = tsIntval($_GET['topicid']);

		$strTopic = $new['topic']->find('topic',array(
			'topicid'=>$topicid,
		));
		
		$istop = $strTopic['istop'];
		
		$istop == 0 ? $istop = 1 : $istop = 0;
		
		$strGroup = $new['topic']->find('group',array(
			'groupid'=>$strTopic['groupid'],
		));
		
		if($userid==$strGroup['userid'] || $TS_USER['isadmin']==1){
			$new['topic']->update('topic',array(
				'topicid'=>$topicid,
			),array(
				'istop'=>$istop,
			));
			
			
			tsNotice("操作成功！");
			
			
		}else{
			tsNotice("非法操作！");
		}

	break;




		
	//精华帖子 
	case "isposts":

		$userid = aac('user')->isLogin();

		$topicid = tsIntval($_GET['topicid']);
		
		if($userid == 0 || $topicid == 0) tsNotice("非法操作"); 
		
		$strTopic = $db->once_fetch_assoc("select userid,groupid,title,isposts from ".dbprefix."topic where topicid='$topicid'");
		
		$strGroup = $db->once_fetch_assoc("select userid from ".dbprefix."group where groupid='".$strTopic['groupid']."'");
		
		if($userid == $strGroup['userid'] || tsIntval($TS_USER['isadmin']) == 1){
			if($strTopic['isposts']==0){
				$db->query("update ".dbprefix."topic set `isposts`='1' where `topicid`='$topicid'");
				
				//msg start
				$msg_userid = '0';
				$msg_touserid = $strTopic['userid'];
				$msg_content = '恭喜，你的帖子：《'.$strTopic['title'].'》被评为精华帖啦^_^ ';
				$msg_tourl = tsUrl('topic','show',array('id'=>$topicid));
				aac('message')->sendmsg($msg_userid,$msg_touserid,$msg_content,$msg_tourl);
				//msg end
				
			}else{
				$db->query("update ".dbprefix."topic set `isposts`='0' where `topicid`='$topicid'");
			}
			
			tsNotice("操作成功！");
		}else{
			tsNotice("非法操作！");
		}
	
	break;
        
}