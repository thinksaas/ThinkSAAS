<?php 
defined('IN_TS') or die('Access Denied.');

switch($ts){

	case "list":
		
		$page = tsIntval($_GET['page'],1);
		$url = SITE_URL.'index.php?app=article&ac=admin&mg=article&ts=list&page=';
		$lstart = $page*20-20;
		$arrArticle = $new['article']->findAll('article',null,'addtime desc',null,$lstart.',20');
		
		$articleNum = $new['article']->findCount('article');
		$pageUrl = pagination($articleNum, 20, $page, $url);
		
		include template('admin/article_list');
		break;
		
	//审核通过
	case "isaudit0":
		
		$articleid = tsIntval($_GET['articleid']);
		$strArticle = $new['article']->find('article',array(
			'articleid'=>$articleid,
		));

        $new['article']->update('article',array(
            'articleid'=>$articleid,
        ),array(
            'isaudit'=>0,
        ));

        #发送系统消息
        $msg_userid = '0';
        $msg_touserid = $strArticle['userid'];
        $msg_content = '你发布的文章审核通过，快去看看吧^_^ ';
        $msg_url = tsUrl('article','show',array('id'=>$articleid));
        aac('message')->sendmsg($msg_userid,$msg_touserid,$msg_content,$msg_url);

        #处理积分
        aac('user') -> doScore($TS_URL['app'], $TS_URL['ac'],$TS_URL['mg'],$TS_URL['api'],$TS_URL['ts'],$strArticle['userid']);
		
		qiMsg('操作成功！');
		break;

    #审核不通过
    case "isaudit1":

        $articleid = tsIntval($_GET['articleid']);
        $strArticle = $new['article']->find('article',array(
            'articleid'=>$articleid,
        ));

        $new['article']->update('article',array(
            'articleid'=>$articleid,
        ),array(
            'isaudit'=>1,
        ));

        #发送系统消息
        $msg_userid = '0';
        $msg_touserid = $strArticle['userid'];
        $msg_content = '你发布的文章审核未通过，快去看看吧^_^ ';
        $msg_url = tsUrl('article','show',array('id'=>$articleid));
        aac('message')->sendmsg($msg_userid,$msg_touserid,$msg_content,$msg_url);

        #处理积分
        aac('user') -> doScore($TS_URL['app'], $TS_URL['ac'],$TS_URL['mg'],$TS_URL['api'],$TS_URL['ts'],$strArticle['userid']);

        qiMsg('操作成功！');

        break;
		
	//删除 
	case "delete":
	
		$articleid = tsIntval($_GET['articleid']);
		$strArticle = $new['article']->find('article',array(
			'articleid'=>$articleid,
		));
		
		$new['article']->deleteArticle($strArticle);

        #处理积分
		aac('user') -> doScore($TS_URL['app'], $TS_URL['ac'],$TS_URL['mg'],$TS_URL['api'],$TS_URL['ts'],$strArticle['userid']);
		
		#用户记录
		aac('pubs')->addLogs('article','articleid',$articleid,$TS_USER['userid'],$strArticle['title'],$strArticle['content'],2);
		
		qiMsg('删除成功！');
	
		break;


	//删除用户内容
	case "deleteuser":
	
		$userid = tsIntval($_GET['userid']);
        
        $new['article']->delete('article',array(
            'userid'=>$userid,    
        ));
        
        $new['article']->delete('article_content',array(
            'userid'=>$userid,    
        ));
		
		qiMsg('删除成功！');
	
		break;
	
	//推荐
	case "isrecommend":
		
		$articleid = tsIntval($_GET['articleid']);
		$strArticle = $new['article']->find('article',array(
			'articleid'=>$articleid,
		));
		
		if($strArticle['isrecommend']==0){
			$isrecommend = 1;
		}else{
			$isrecommend = 0;
		}

		$new['article']->update('article',array(
			'articleid'=>$articleid,
		),array(
			'isrecommend'=>$isrecommend,
		));

		#更新项目推荐
		aac('pubs')->upPtableRecommend('article','articleid',$articleid,$isrecommend);
		
		qiMsg('操作成功！');
		break;

	//置顶
	case "istop":
		
		$articleid = tsIntval($_GET['articleid']);
		$strArticle = $new['article']->find('article',array(
			'articleid'=>$articleid,
		));
		
		if($strArticle['istop']==0){
			$istop = 1;
		}else{
			$istop = 0;
		}

		$new['article']->update('article',array(
			'articleid'=>$articleid,
		),array(
			'istop'=>$istop,
		));
		
		qiMsg('操作成功！');
		break;

}