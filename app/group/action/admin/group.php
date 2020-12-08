<?php
defined('IN_TS') or die('Access Denied.');

switch($ts){

	//小组列表
	case "list":
		$page = isset($_GET['page']) ? tsIntval($_GET['page']) : 1;
		$url = SITE_URL.'index.php?app=group&ac=admin&mg=group&ts=list&page=';
		$lstart = $page*10-10;
		$arrGroup = $db->fetch_all_assoc("select * from ".dbprefix."group order by addtime desc limit $lstart,10");
		$groupNum = $db->once_num_rows("select * from ".dbprefix."group");
		if(is_array($arrGroup)){
			foreach($arrGroup as $key=>$item){
				$arrAllGroup[] = $item;
				$arrAllGroup[$key]['groupdesc'] = cututf8($item['groupdesc'],0,40);
			}
		}
		$pageUrl = pagination($groupNum, 10, $page, $url);

		include template("admin/group_list");
		
		break;


    //推荐的小组
    case "recommend":

        $arrGroup = $new['group']->findAll('group',array(
            'isrecommend'=>1,
        ),'orderid asc','groupid,orderid,groupname,isrecommend');


        include template("admin/group_recommend");

        break;


    case "orderid":

        $arrGroupid = $_POST['groupid'];
        $arrOrderid = $_POST['orderid'];

        foreach($arrGroupid as $key=>$item){
            $new['group']->update('group',array(
                'groupid'=>tsIntval($item)
            ),array(
                'orderid'=>tsIntval($arrOrderid[$key])
            ));
        }

        qiMsg('修改成功！');

        break;

	
	//小组编辑
	case "edit":
		$groupid = tsIntval($_GET['groupid']);
		$arrGroup = $db->once_fetch_assoc("select * from ".dbprefix."group where groupid='$groupid'");
		include template("admin/group_edit");
		break;
	
	//小组编辑执行
	case "editdo":
		$groupid = tsIntval($_POST['groupid']);
		
		$new['group']->update('group',array(
			'groupid'=>$groupid,
		),array(
			'userid'			=> tsIntval($_POST['userid']),
		));
		
		qiMsg("小组信息修改成功！");
		break;
	
	//小组删除
	case "del":
		$groupid = tsIntval($_GET['groupid']);
		
		if($groupid == 1){
			qiMsg("默认小组不能删除！");
		}
		
		$topicNum = $db->once_fetch_assoc("select count(*) from ".dbprefix."topic where `groupid`='$groupid'");
		
		if($topicNum['count(*)'] > 0){
			qiMsg("本小组还有帖子，不允许删除。");
		}

		$strGroup = $new['group']->find('group',array(
			'groupid'=>$groupid,
		));
		
		$new['group']->deleteGroup($strGroup);
		
		qiMsg("小组删除成功！");
		
		break;
		
	//审核小组 
	case "isaudit":

		$groupid = tsIntval($_GET['groupid']);

		$strGroup = $db->once_fetch_assoc("select groupid,userid,groupname,isaudit from ".dbprefix."group where groupid='$groupid'");

        if($strGroup['isaudit']){

            $db->query("update ".dbprefix."group set `isaudit`='0' where groupid='$groupid'");

            //发送系统消息(审核通过)
            $msg_userid = '0';
            $msg_touserid = $strGroup['userid'];
            $msg_content = '恭喜你，你申请的小组《'.$strGroup['groupname'].'》审核通过！快去看看吧';
            $msg_tourl = tsUrl('group','show',array('id'=>$groupid));
            aac('message')->sendmsg($msg_userid,$msg_touserid,$msg_content,$msg_tourl);

        }else{

            $db->query("update ".dbprefix."group set `isaudit`='1' where groupid='$groupid'");

        }
		

		
		qiMsg("操作成功！");
		
		break;
	
	//推荐小组 
	case "isrecommend":
		$groupid = tsIntval($_GET['groupid']);
		
		$strGroup = $db->once_fetch_assoc("select groupid,userid,groupname,isrecommend from ".dbprefix."group where groupid='$groupid'");
		
		if($strGroup['isrecommend'] == 0){
			$db->query("update ".dbprefix."group set `isrecommend`='1' where groupid='$groupid'");
			
			//发送系统消息(审核通过)
			$msg_userid = '0';
			$msg_touserid = $strGroup['userid'];
			$msg_content = '恭喜你，你的小组《'.$strGroup['groupname'].'》被推荐啦！快去看看吧';
            $msg_tourl = tsUrl('group','show',array('id'=>$groupid));
			aac('message')->sendmsg($msg_userid,$msg_touserid,$msg_content,$msg_tourl);
			
		}else{
			
			$db->query("update ".dbprefix."group set `isrecommend`='0' where groupid='$groupid'");
			
		}
		
		qiMsg("操作成功！");
		
		break;
}