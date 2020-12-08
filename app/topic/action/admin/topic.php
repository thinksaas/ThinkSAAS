<?php 
defined('IN_TS') or die('Access Denied.');

switch($ts){
	
	case "list":

        $isrecommend = tsIntval($_GET['isrecommend']);
        $istop = tsIntval($_GET['istop']);

		$topicid = tsIntval($_GET['topicid']);

		$kw=urldecode(tsFilter($_GET['kw']));
		
		$page = isset($_GET['page']) ? tsIntval($_GET['page']) : 1;
		$url = SITE_URL.'index.php?app=topic&ac=admin&mg=topic&ts=list&page=';
		$lstart = $page*10-10;


        $where = null;

        if($isrecommend==1){
            $where = array(
                'isrecommend'=>1,
            );
        }

        if($istop==1){
            $where = array(
                'istop'=>1,
            );
        }


        if($topicid){
            $where = array(
                'topicid'=>$topicid,
            );
        }

        if($kw){
            $where = "`title` like '%$kw%'";
        }
		
		$arrTopic = $new['topic']->findAll('topic',$where,'addtime desc',null,$lstart.',10');
		
		$topicNum = $new['topic']->findCount('topic',$where);
		
		$pageUrl = pagination($topicNum, 10, $page, $url);

		include template("admin/topic_list");
		
		break;
	
	/**
	 * 删除帖子
	 */
	case "delete":
		$topicid = tsIntval($_GET['topicid']);
		$strTopic = $new['topic']->getOneTopic($topicid);

		#用户记录
		aac('pubs')->addLogs('topic','topicid',$topicid,$TS_USER['userid'],$strTopic['title'],$strTopic['content'],2);

		$new['topic']->deleteTopic($strTopic);
		qiMsg('删除成功');
		break;
	
	//帖子审核
	case "isaudit":
	
		$topicid = tsIntval($_GET['topicid']);
		
		$strTopic = $new['topic']->find('topic',array(
			'topicid'=>$topicid,
		));
		
		if($strTopic['isaudit']==0){
			$new['topic']->update('topic',array(
				'topicid'=>$topicid,
			),array(
				'isaudit'=>1,
			));
		}
		
		if($strTopic['isaudit']==1){
			$new['topic']->update('topic',array(
				'topicid'=>$topicid,
			),array(
				'isaudit'=>0,
			));
		}
		
		qiMsg('操作成功！');
	
		break;


    //推荐
    case "isrecommend":

        $topicid = tsIntval($_GET['topicid']);

        $strTopic = $new['topic']->find('topic',array(
            'topicid'=>$topicid,
        ));

        if($strTopic['isrecommend']==0){
            $new['topic']->update('topic',array(
                'topicid'=>$topicid,
            ),array(
                'isrecommend'=>1,
            ));
        }

        if($strTopic['isrecommend']==1){
            $new['topic']->update('topic',array(
                'topicid'=>$topicid,
            ),array(
                'isrecommend'=>0,
            ));
        }

        qiMsg('操作成功！');

        break;

    //置顶
    case "istop":

        $topicid = tsIntval($_GET['topicid']);

        $strTopic = $new['topic']->find('topic',array(
            'topicid'=>$topicid,
        ));

        if($strTopic['istop']==0){
            $new['topic']->update('topic',array(
                'topicid'=>$topicid,
            ),array(
                'istop'=>1,
            ));
        }

        if($strTopic['istop']==1){
            $new['topic']->update('topic',array(
                'topicid'=>$topicid,
            ),array(
                'istop'=>0,
            ));
        }

        qiMsg('操作成功！');

        break;


		
	//删除的帖子
	case "deletetopic":
	
		$page = isset($_GET['page']) ? tsIntval($_GET['page']) : 1;
		$url = SITE_URL.'index.php?app=topic&ac=admin&mg=topic&ts=deletetopic&page=';
		$lstart = $page*10-10;
		
		$arrTopic = $new['topic']->findAll('topic',array('isdelete'=>'1'),'addtime desc',null,$lstart.',10');
		
		$topicNum = $new['topic']->findCount('topic',array(
			'isdelete'=>'1',
		));
		
		$pageUrl = pagination($topicNum, 10, $page, $url);

		include template("admin/topic_delete");
	
		break;
		
	//编辑的帖子
	case "edittopic":
	
		$page = isset($_GET['page']) ? tsIntval($_GET['page']) : 1;
		$url = SITE_URL.'index.php?app=topic&ac=admin&mg=topic&ts=edittopic&page=';
		$lstart = $page*10-10;
		
		$arrTopic = $new['topic']->findAll('topic_edit',null,'addtime desc',null,$lstart.',10');
		
		$topicNum = $new['topic']->findCount('topic_edit');
		
		$pageUrl = pagination($topicNum, 10, $page, $url);

		include template("admin/topic_edit");
	
		break;
		
	//执行更新帖子
	case "update":
	
		$topicid = tsIntval($_GET['topicid']);
		
		$strTopic = $new['topic']->find('topic_edit',array(
			'topicid'=>$topicid,
		));
		
		$new['topic']->update('topic',array(
			'topicid'=>$topicid,
		),array(
			'title'=>$strTopic['title'],
			'content'=>$strTopic['content'],
		));
		
		$new['topic']->update('topic_edit',array(
			'topicid'=>$topicid,
		),array(
			'isupdate'=>1,
		));
		
		qiMsg('更新成功！');
	
		break;
		
	//查看单独某个修改的帖子
	case "editview":
		$topicid = tsIntval($_GET['topicid']);
		
		$strTopic = $new['topic']->find('topic_edit',array(
			'topicid'=>$topicid,
		));
		
		include template('admin/topic_edit_view');
		break;
		
}