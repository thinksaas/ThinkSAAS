<?php 
defined('IN_TS') or die('Access Denied.');

switch($ts){

	case "list":
		
		$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
		$url = SITE_URL.'index.php?app=article&ac=admin&mg=post&ts=list&page=';
		$lstart = $page*20-20;
		$arrArticle = $new['article']->findAll('article',null,'addtime desc',null,$lstart.',20');
		
		$articleNum = $new['article']->findCount('article');
		$pageUrl = pagination($articleNum, 20, $page, $url);
		
		include template('admin/post_list');
		break;
		
	//审核通过
	case "isaudit0":
		
		$articleid = intval($_GET['articleid']);
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
        aac('user') -> doScore($TS_URL['app'], $TS_URL['ac'], $TS_URL['ts'],$strArticle['userid'],$TS_URL['mg']);
		
		qiMsg('操作成功！');
		break;

    #审核不通过
    case "isaudit1":

        $articleid = intval($_GET['articleid']);
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
        aac('user') -> doScore($TS_URL['app'], $TS_URL['ac'], $TS_URL['ts'],$strArticle['userid'],$TS_URL['mg']);

        qiMsg('操作成功！');

        break;
		
	//删除 
	case "delete":
	
		$articleid = intval($_GET['articleid']);
		$strArticle = $new['article']->find('article',array(
			'articleid'=>$articleid,
		));
		
		if($strArticle['photo']){
			unlink('uploadfile/article/'.$strArticle['photo']);
		}
		
		$new['article']->delete('article',array(
			'articleid'=>$articleid,
		));
		
		$new['article']->delete('tag_article_index',array(
			'articleid'=>$articleid,
		));

        #处理积分
        aac('user') -> doScore($TS_URL['app'], $TS_URL['ac'], $TS_URL['ts'],$strArticle['userid'],$TS_URL['mg']);
		
		qiMsg('删除成功！');
	
		break;
	
	//推荐
	case "isrecommend":
		
		$articleid = intval($_GET['articleid']);
		$strArticle = $new['article']->find('article',array(
			'articleid'=>$articleid,
		));
		
		if($strArticle['isrecommend']==0){
			$new['article']->update('article',array(
				'articleid'=>$articleid,
			),array(
				'isrecommend'=>1,
			));
		}else{
			$new['article']->update('article',array(
				'articleid'=>$articleid,
			),array(
				'isrecommend'=>0,
			));
		}
		
		qiMsg('操作成功！');
		break;

}