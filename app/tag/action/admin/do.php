<?php 
defined('IN_TS') or die('Access Denied.');
switch ($ts){
	
	//禁用-启用
	case "isenable":
		$tagid = intval($_GET['tagid']);
		$isenable = intval($_GET['isenable']);
		
		$db->query("update ".dbprefix."tag set `isenable`='$isenable' where tagid = '$tagid'");
		
		qiMsg("设置成功！");
		
		break;
		
	//删除
	case "del":
		$tagid = intval($_GET['tagid']);
		
		$new['tag']->delete('tag',array('tagid'=>$tagid));
		$new['tag']->delete('tag_article_index',array('tagid'=>$tagid));
		$new['tag']->delete('tag_bang_index',array('tagid'=>$tagid));
		$new['tag']->delete('tag_group_index',array('tagid'=>$tagid));
		$new['tag']->delete('tag_photo_index',array('tagid'=>$tagid));
		$new['tag']->delete('tag_study_index',array('tagid'=>$tagid));
		$new['tag']->delete('tag_topic_index',array('tagid'=>$tagid));
		$new['tag']->delete('tag_user_index',array('tagid'=>$tagid));
		
		qiMsg("删除成功！");
		
		break;
		
	//优化标签
	case "opt":
		$tagid = intval($_GET['tagid']);
		$strTag = $new['tag']->getOneTag($tagid);
		
		$tagname = t($strTag['tagname']);
		
		$tagNum = $db->once_fetch_assoc("select count(*) from ".dbprefix."tag where `tagname`='$tagname'");
		
		if($tagNum['count(*)']==0){
			$db->query("update ".dbprefix."tag set `tagname`='$tagname' where `tagid`='$tagid'");
		}elseif($tagNum['count(*)']==1){
			
		}else{
			$arrTags = $db->fetch_all_assoc("select * from ".dbprefix."tag where `tagname`='$tagname'");
			foreach($arrTags as $item){
				$tagids = $item['tagid'];
				//先更新索引
				$db->query("update ".dbpreifx."tag_topic_index set `tagid`='$tagid' where `tagid`='$tagids'");
				$db->query("update ".dbpreifx."tag_article_index set `tagid`='$tagid' where `tagid`='$tagids'");
				$db->query("update ".dbpreifx."tag_user_index set `tagid`='$tagid' where `tagid`='$tagids'");
				
				//再进行删除
				$db->query("delete from ".dbprefix."tag where `tagid`='$tagids'");
				
				//最后更新tag
				$db->query("update ".dbprefix."tag set `tagname`='$tagname' where `tagid`='$tagid'");
			}
		}
		
		qiMsg("优化成功！");
		
		break;
}