<?php 
defined('IN_TS') or die('Access Denied.'); 

function recommendtopic(){

	$strData = fileRead('data/plugins_home_recommendtopic.php');
	if($strData==''){
		$strData = $GLOBALS['tsMySqlCache']->get('plugins_home_recommendtopic');
	}

	if($strData['isrecommend']==1){
		$where = array(
			'isrecommend'=>1,
			'isaudit'=>0
		);
	}else{
		$where = array(
			'isaudit'=>0
		);
	}
	
	//推荐帖子	
	$arrTopic = aac('group')->findAll('topic',$where,'istop desc,uptime desc','topicid,ptable,pkey,pid,pjson,userid,groupid,title,gaiyao,score,label,count_comment,count_view,iscommentshow,istop,uptime',35);
	
	foreach($arrTopic as $key=>$item){
		$arrTopic[$key]['title']=tsTitle($item['title']);
		$arrTopic[$key]['gaiyao']=tsTitle($item['gaiyao']);
		$arrTopic[$key]['user'] = aac('user')->getSimpleUser($item['userid']);
		$arrTopic[$key]['group'] = aac('group')->getOneGroup($item['groupid']);
		#应用扩展
		if($item['pjson']){
			$arrTopic[$key]['pjson'] = json_decode($item['pjson'],true);
		}
	}

	include template('recommendtopic','recommendtopic');
	
}

function recommendtopic_css(){

	echo '<link href="'.SITE_URL.'plugins/home/recommendtopic/style.css?v=201812251839" rel="stylesheet" type="text/css" />';

}

addAction('home_index_left','recommendtopic');
addAction('pub_header_top','recommendtopic_css');