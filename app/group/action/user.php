<?php 
defined('IN_TS') or die('Access Denied.');

switch($ts){
	
	case "":
	
		$userid = intval($_GET['id']);
		
		if($userid == '0') header("Location: ".SITE_URL."index.php");
		
		$strUser = aac('user')->getOneUser($userid);
		
		$strUser['rolename'] = aac('user')->getRole($strUser['count_score']);
		
		//我的小组
		$myGroup = $new['group']->findAll('group_users',array(
			'userid'=>$userid,
		));
		
		//我加入的小组数
		$myGroupNum = $new['group']->findCount('group_users',array(
			'userid'=>$userid,
		));
		
		if($myGroup != ''){
		
			//我加入的小组
			$myGroups = $new['group']->findAll('group_users',array(
				'userid'=>$userid,
			),'orderid','groupid,orderid');
			
			if(is_array($myGroups)){
				foreach($myGroups as $key=>$item){
					$arrMyGroup[] = $new['group']->getOneGroup($item['groupid'],$item['orderid']);
					$order.=$item['groupid'].',';
				}
				$oldordr=substr($order,0,-1);//老排序
			}
			
			//我加入的所有小组的话题
			if(is_array($myGroup)){
				foreach($myGroup as $item){
					$arrGroup[] = $item['groupid'];
				}
			}
			
			$strGroup = implode(',',$arrGroup);
			if($strGroup){
				$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	            $lstart = $page*20-20;
				$arrTopics = $db->fetch_all_assoc("select * from ".dbprefix."group_topics where groupid in ($strGroup) and isshow='0' order by uptime desc limit $lstart ,20");
				foreach($arrTopics as $key=>$item){
					$arrTopic[] = $item;
					$arrTopic[$key]['title'] = htmlspecialchars($item['title']);
					$arrTopic[$key]['user'] = aac('user')->getOneUser($item['userid']);
					$arrTopic[$key]['group'] = aac('group')->getOneGroup($item['groupid']);
				}
			}
		
		}
		
		$title = $strUser['username'];
		$pageKey = $strUser['username'].',小组';
		$pageDesc = $strUser['username'].'的小组，'.$strUser['username'].'关注的小组，'.$strUser['username'].'加入的小组，'.$strUser['username'].'的小组发帖';
		if($_POST && !isset($_POST['page'])){
			$neworder=explode(',',$_POST['id']);
			
			for($i=0;$i<count($neworder);$i++){
			$new['group']->update('group_users',array(
					'userid'=>$userid,'groupid'=>$neworder[$i]
				),array(
					'orderid'=>$i,
				));
			
			 }
			}else{
				if( isset($_POST['page'])){
					   include template("user_append");
					}else{
						include template("user");
						}
		    
		}
		break;
	
}