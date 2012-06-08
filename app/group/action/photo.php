<?php 
/**
 * 图片贴瀑布流 By QiuJun 2012-03-27
 */
defined('IN_TS') or die('Access Denied.');

switch($ts){

	case "":
	
		$title = '图片贴';
		
		include template('photo');
	
		break;
		
	case "ajax":
	
		$page = isset($_GET['page']) ? $_GET['page'] : '1';
		
		$lstart = $page*8-8;
	
		$arrTopics = $new['group']->findAll('group_topics',"`photo`!=''",'addtime desc',null,$lstart.',8');
		
		foreach($arrTopics as $key=>$item){
			$arrTopic[] = $item;
			$arrTopic[$key]['user'] = aac('user')->getOneUser($item['userid']);
			$arrTopic[$key]['photoinfo'] = getimagesize(SITE_URL.tsXimg($item['photo'],'topic',200,'',$item['path']));
		}
		
		include template('photo_ajax');
		
		break;

}