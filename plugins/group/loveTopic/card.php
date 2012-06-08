<?php 
defined('IN_TS') or die('Access Denied.');
// 插件编辑
switch ($ts)
{
	case "show":
		$userid = intval($_POST['uid']);
		$qianming = $db->once_fetch_assoc("select signed,username,face from " . dbprefix . "user_info where userid='$userid'");
		if ($qianming['face'] == '')
		{
			$face = SITE_URL . '/public/images/user_normal.jpg';
		}
		else
		{
			$face = SITE_URL . tsXimg($qianming['face'], 'user', '48', '48', $qianming['path'], '1');
		}

		$count_group = $db->once_num_rows("select * from " . dbprefix . "group_users where userid='$userid'");
		$count_post = $db->once_num_rows("select * from " . dbprefix . "group_topics where userid='$userid'");
		$count_follow = $db->once_num_rows("select * from " . dbprefix . "user_follow  where userid='$userid'");
		$tags_d = $db->fetch_all_assoc("select m.*,mf.* from ".dbprefix."tag m LEFT JOIN ".dbprefix."tag_user_index mf on mf.tagid=m.tagid where mf.userid='$userid' limit 5");
		$tags.='Tag:';
		
foreach($tags_d as $key=>$item){
	
	
$tags.=$item['tagname'];
$tags.="\t";

}

       
		$data= array('face' => $face,
			'count_group' => $count_group,
			'count_post' => $count_post,
			'count_follow' => $count_follow,
			'qianming' => $qianming['signed'],
			'uname' => $qianming['username'],
            'tags'=>$tags,
			);
		
		echo json_encode($data);
		break;
}
