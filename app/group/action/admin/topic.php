<?php 
defined('IN_TS') or die('Access Denied.');

switch($ts){
	
	case "list":

        $isrecommend = intval($_GET['isrecommend']);
        $istop = intval($_GET['istop']);

        $topicid = intval($_GET['topicid']);

        $title = urldecode($_GET['title']);
		
		$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
		$url = SITE_URL.'index.php?app=group&ac=admin&mg=topic&ts=list&page=';
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

        if($title){
            $where = "`title` like '%$title%'";
        }
		
		$arrTopic = $new['group']->findAll('group_topic',$where,'addtime desc',null,$lstart.',10');
		
		$topicNum = $new['group']->findCount('group_topic',$where);
		
		$pageUrl = pagination($topicNum, 10, $page, $url);

		include template("admin/topic_list");
		
		break;
	
	/**
	 * 删除帖子
	 */
	case "delete":
		$topicid = intval($_GET['topicid']);
		$strTopic = $new['group']->getOneTopic($topicid);
		$new['group']->deleteTopic($strTopic);
		qiMsg('删除成功');
		break;
	
	//帖子审核
	case "isaudit":
	
		$topicid = intval($_GET['topicid']);
		
		$strTopic = $new['group']->find('group_topic',array(
			'topicid'=>$topicid,
		));
		
		if($strTopic['isaudit']==0){
			$new['group']->update('group_topic',array(
				'topicid'=>$topicid,
			),array(
				'isaudit'=>1,
			));
		}
		
		if($strTopic['isaudit']==1){
			$new['group']->update('group_topic',array(
				'topicid'=>$topicid,
			),array(
				'isaudit'=>0,
			));
		}
		
		qiMsg('操作成功！');
	
		break;


    //推荐
    case "isrecommend":

        $topicid = intval($_GET['topicid']);

        $strTopic = $new['group']->find('group_topic',array(
            'topicid'=>$topicid,
        ));

        if($strTopic['isrecommend']==0){
            $new['group']->update('group_topic',array(
                'topicid'=>$topicid,
            ),array(
                'isrecommend'=>1,
            ));
        }

        if($strTopic['isrecommend']==1){
            $new['group']->update('group_topic',array(
                'topicid'=>$topicid,
            ),array(
                'isrecommend'=>0,
            ));
        }

        qiMsg('操作成功！');

        break;

    //置顶
    case "istop":

        $topicid = intval($_GET['topicid']);

        $strTopic = $new['group']->find('group_topic',array(
            'topicid'=>$topicid,
        ));

        if($strTopic['istop']==0){
            $new['group']->update('group_topic',array(
                'topicid'=>$topicid,
            ),array(
                'istop'=>1,
            ));
        }

        if($strTopic['istop']==1){
            $new['group']->update('group_topic',array(
                'topicid'=>$topicid,
            ),array(
                'istop'=>0,
            ));
        }

        qiMsg('操作成功！');

        break;


		
	//删除的帖子
	case "deletetopic":
	
		$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
		$url = SITE_URL.'index.php?app=group&ac=admin&mg=topic&ts=deletetopic&page=';
		$lstart = $page*10-10;
		
		$arrTopic = $new['group']->findAll('group_topic',array('isdelete'=>'1'),'addtime desc',null,$lstart.',10');
		
		$topicNum = $new['group']->findCount('group_topic',array(
			'isdelete'=>'1',
		));
		
		$pageUrl = pagination($topicNum, 10, $page, $url);

		include template("admin/topic_delete");
	
		break;
		
	//编辑的帖子
	case "edittopic":
	
		$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
		$url = SITE_URL.'index.php?app=group&ac=admin&mg=topic&ts=edittopic&page=';
		$lstart = $page*10-10;
		
		$arrTopic = $new['group']->findAll('group_topic_edit',null,'addtime desc',null,$lstart.',10');
		
		$topicNum = $new['group']->findCount('group_topic_edit');
		
		$pageUrl = pagination($topicNum, 10, $page, $url);

		include template("admin/topic_edit");
	
		break;
		
	//执行更新帖子
	case "update":
	
		$topicid = intval($_GET['topicid']);
		
		$strTopic = $new['group']->find('group_topic_edit',array(
			'topicid'=>$topicid,
		));
		
		$new['group']->update('group_topic',array(
			'topicid'=>$topicid,
		),array(
			'title'=>$strTopic['title'],
			'content'=>$strTopic['content'],
		));
		
		$new['group']->update('group_topic_edit',array(
			'topicid'=>$topicid,
		),array(
			'isupdate'=>1,
		));
		
		qiMsg('更新成功！');
	
		break;
		
	//查看单独某个修改的帖子
	case "editview":
		$topicid = intval($_GET['topicid']);
		
		$strTopic = $new['group']->find('group_topic_edit',array(
			'topicid'=>$topicid,
		));
		
		include template('admin/topic_edit_view');
		break;
		
}