<?php 
defined('IN_TS') or die('Access Denied.'); 

function tag(){

    $tag = fileRead('data/plugins_home_tag.php');
    if($tag==''){
        $tag = $GLOBALS['tsMySqlCache']->get('plugins_home_tag');
        if($tag == '') $tag='topic';
    }

    if($tag=='topic'){
        $where = "`count_topic`>'0'";
    }elseif($tag=='article'){
        $where = "`count_article`>'0'";
    }

	//最新标签
	$arrTag = aac('tag')->findAll('tag',$where,'uptime desc',null,30);
	
	foreach($arrTag as $key=>$item){
		$arrTag[$key]['tagname'] = tsTitle($item['tagname']);
	}
	
    include template('tag','tag');
	
}

addAction('home_index_left','tag');