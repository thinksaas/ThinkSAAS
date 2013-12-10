<?php
defined('IN_TS') or die('Access Denied.');

switch($ts){
	
	case "list":
		
		$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
		$url = SITE_URL.'index.php?app=feed&ac=admin&mg=feed&ts=list&page=';
		$lstart = $page*20-20;
		
		$arrFeeds = $new['feed']->findAll('feed',null,'addtime desc',null,$lstart.',20');
		
		foreach($arrFeeds as $key=>$item){
			$data = json_decode($item['data'],true);
			if(is_array($data)){
				foreach($data as $key=>$itemTmp){
					$tmpkey = '{'.$key.'}';
					$tmpdata[$tmpkey] = urldecode($itemTmp);
				}
			}
			$arrFeed[] = array(
				'feedid'=>$item['feedid'],
				'userid'=>$item['userid'],
				'content' => strtr($item['template'],$tmpdata),
				'addtime' => $item['addtime'],
			);
		}
		
		$feedNum = $new['feed']->findCount('feed');
		
		$pageUrl = pagination($feedNum, 20, $page, $url);

		include template("admin/feed_list");
		
		break;
		
	case "delete":
		$feedid = intval($_GET['feedid']);
		$new['feed']->delete('feed',array(
			'feedid'=>$feedid,
		));
		
		qiMsg('删除成功！');
		
		break;

}