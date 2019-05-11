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
	
	echo '<div class="card">';
	echo '<div class="card-header">热门标签<small class="float-right">';
    if($tag=='topic'){
        echo '<a class="text-black-50" href="'.tsUrl('group','tags').'">更多</a>';
    }elseif($tag=='article'){
        echo '<a class="text-black-50" href="'.tsUrl('article','tags').'">更多</a>';
    }
	echo '</small></div>';
	echo '<div class="card-body">';
	foreach($arrTag as $key=>$item){
	    if($tag=='topic'){
            echo '<a class="badge badge-secondary mr-2 fw300" href="'.tsUrl('group','tag',array('id'=>urlencode($item['tagname']))).'">'.$item['tagname'].'</a>';
        }elseif($tag=='article'){
            echo '<a class="badge badge-secondary mr-2 fw300" href="'.tsUrl('article','tag',array('id'=>urlencode($item['tagname']))).'">'.$item['tagname'].'</a>';
        }

	}
	echo '</div></div>';
	
}

addAction('home_index_left','tag');